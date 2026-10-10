<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Ajax extends AJAX_Controller
{

    public function __construct()
    {
        parent::__construct();


    }


    public function payment_update_status(){

        if(!GroupAccess::isGranted('booking',GRP_MANAGE_BOOKING)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }


        $booking_id = intval(RequestInput::post('booking_id'));
        $payment_status_id = RequestInput::post('payment_status');
        $transactionId = RequestInput::post('transactionId');

        $result = $this->mBookingModel->update_payment_status($booking_id,$payment_status_id, $transactionId);

        echo json_encode($result);
    }

    public function change_booking_status(){

        if(!GroupAccess::isGranted('booking',GRP_MANAGE_BOOKING)){
            echo json_encode(array(Tags::SUCCESS=>0,Tags::ERRORS=>array(
                "error"  => Translate::sprint(Messages::PERMISSION_LIMITED)
            )));
            exit();
        }

        $booking_id = RequestInput::post('booking_id');
        $status_id = RequestInput::post('status');
        $message = RequestInput::post('message');

        $result = $this->mBookingModel->change_booking_status($booking_id, $status_id, $message);
        echo json_encode($result);
    }

    public function cancelBookingClient(){

        $booking_id = intval(RequestInput::post('booking_id'));
        $status_id = -1;
        $client_id = SessionManager::getData("id_user");
        $message = Translate::sprintf("Client: Order Canceled #%s",array($booking_id));
        $this->mBookingModel->change_booking_status($booking_id, $status_id, $message,$client_id);

        echo json_encode(array(Tags::SUCCESS=>1));
    }

    public function sendMessageBookingClient(){

        $booking_id = intval(RequestInput::post('booking_id'));
        $booking = $this->mBookingModel->getBooking($booking_id);

        if($booking == NULL){
            echo json_encode(array(Tags::SUCCESS=>0));return;
        }

        $userData = $this->mUserModel->getUserData($booking['store_id']);

        if($userData == NULL){
            echo json_encode(array(Tags::SUCCESS=>0));return;
        }


        echo json_encode(array(Tags::SUCCESS=>1,Tags::RESULT=>admin_url("messenger/messages?u=".$userData['hash_id'])));
    }


    public function getServicesByStore(){
        $store_id = intval(RequestInput::post('store_id'));
        if ($store_id <= 0) {
            echo json_encode(array(Tags::SUCCESS => 0, Tags::RESULT => array()));
            return;
        }

        $services = $this->db->where('store_id', $store_id)
            ->where('hidden', 0)
            ->where('value >', 0)
            ->get('service')
            ->result_array();

        if (empty($services)) {
            $services = $this->db->where('store_id', $store_id)
                ->where('hidden', 0)
                ->get('service')
                ->result_array();
        }

        $data = array();
        foreach ($services as $srv) {
            $data[] = array(
                'id' => $srv['id'],
                'name' => $srv['label'] ? $srv['label'] : $srv['name'],
                'value' => floatval($srv['value'])
            );
        }

        echo json_encode(array(Tags::SUCCESS => 1, Tags::RESULT => $data));
    }


    public function createBookingSelf(){
        $user_id = SessionManager::getData("id_user");
        if ($user_id <= 0) {
            echo json_encode(array(Tags::SUCCESS => 0, Tags::ERRORS => array("error" => _lang("User not logged in"))));
            return;
        }

        $store_id = intval(RequestInput::post('store_id'));
        $service_id = intval(RequestInput::post('service_id'));
        $qty = intval(RequestInput::post('qty')) ?: 1;
        $note = RequestInput::post('note');
        $payment_status = RequestInput::post('payment_status') ?: 'cod';
        $booking_date = RequestInput::post('booking_date') ?: date("Y-m-d H:i:s");

        if ($store_id <= 0 || $service_id <= 0) {
            echo json_encode(array(Tags::SUCCESS => 0, Tags::ERRORS => array("error" => _lang("Please select store and service"))));
            return;
        }

        $service = $this->db->where('id', $service_id)->get('service')->row_array();
        if (!$service) {
            echo json_encode(array(Tags::SUCCESS => 0, Tags::ERRORS => array("error" => _lang("Service not found"))));
            return;
        }

        $store = $this->db->where('id_store', $store_id)->get('store')->row_array();
        if (!$store) {
            echo json_encode(array(Tags::SUCCESS => 0, Tags::ERRORS => array("error" => _lang("Store not found"))));
            return;
        }

        $unit_price = floatval($service['value']);
        $total_amount = $unit_price * $qty;

        $cart = array(
            "0" => array(
                "module" => "service",
                "module_id" => $service_id,
                "qty" => $qty,
                "amount" => $unit_price
            )
        );

        $cf_data = array(
            "note" => $note,
            "booking_date" => $booking_date,
            "service_name" => $service['label'] ? $service['label'] : $service['name']
        );

        $date = date("Y-m-d H:i:s");
        $booking_data = array(
            "store_id" => $store_id,
            "user_id" => $user_id,
            "amount" => $total_amount,
            "status" => 0, // Pending / New
            "payment_status" => $payment_status,
            "booking_type" => "service",
            "cart" => json_encode($cart, JSON_FORCE_OBJECT),
            "cf_data" => json_encode($cf_data),
            "created_at" => $date,
            "updated_at" => $date
        );

        $this->db->insert("booking", $booking_data);
        $booking_id = $this->db->insert_id();

        if ($booking_id > 0) {
            echo json_encode(array(Tags::SUCCESS => 1, Tags::RESULT => $booking_id));
        } else {
            echo json_encode(array(Tags::SUCCESS => 0, Tags::ERRORS => array("error" => _lang("Failed to create booking"))));
        }
    }


}

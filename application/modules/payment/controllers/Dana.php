<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require_once(APPPATH . 'modules/payment/libraries/dana-id/DANA.php'); // require paypal files

class Dana extends MAIN_Controller {

    public $_api_context;

    function  __construct()
    {
        parent::__construct();
     }



     public function charge(){

         $this->load->library("payment/DANA");


         $auth = $this->auth();
         if($auth[Tags::SUCCESS]==0){
             echo json_encode($auth);
             die();
         }

         $url = \DANA\Config::SANDBOX_API_URL."/v1.0/payment-gateway/payment.htm";

         $data = [
             "partnerReferenceNo" => "14054Q0727520211128121824991",
             "customerNumber" => "081234567891",
             "otp" => "888888",
             "amount" => [
                 "currency" => "IDR",
                 "value" => "50000.00"
             ],
             "additionalInfo" => [
                 "extensionInfo" => [
                     "postId" => "Q07275",
                     "storeId" => "14054",
                     "phoneNumber" => "081234567890"
                 ]
             ]
         ];

         // Generate a dynamic timestamp
         $timestamp = MyDateUtils::convert(date('Y-m-d\TH:i:sP'),"UTC","Asia/Jakarta","Y-m-d\TH:i:sP"); // Example format: 2022-03-22T14:45:43+07:00

         try {
             $signature = $this->generate_signature(\DANA\Config::$clientSecret, $timestamp, \DANA\Config::$privateKey);
         } catch (Exception $e) {
             return array(Tags::SUCCESS=>0,Tags::ERROR=>'Credentials invalid');
         }


         $headers = [
             "Content-Type: application/json",
             "Authorization: Bearer ".$auth['token'],
             "X-TIMESTAMP: ".$timestamp,
             "X-SIGNATURE: ".$signature,
             "ORIGIN: ".site_url(),
             "X-PARTNER-ID: ".\DANA\Config::$clientId, //client id
             "X-EXTERNAL-ID: ".time(), //order id
             "CHANNEL-ID: 95221"
         ];

         $ch = curl_init($url);

         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

         $response = curl_exec($ch);
         curl_close($ch);

         echo "<br><br>Header====><br><br>";
         echo json_encode($headers);

         echo "<br><br>RequestBody====><br><br>";
         echo json_encode($data);

         echo "<br><br>Response====><br><br>";
         echo $response;

     }


     public function auth(){

         $url = \DANA\Config::SANDBOX_API_URL."/v1.0/access-token/b2b.htm";

         $data = [
             "grantType" => "client_credentials",
             "additionalInfo" => []
         ];


         // Replace with your actual clientId and private key
         $clientId = \DANA\Config::$clientId;
         $clientKey = \DANA\Config::$clientSecret;
         $privateKey = \DANA\Config::$privateKey;
         $publicKey = \DANA\Config::$publicKey;

        // Generate a dynamic timestamp
         $timestamp = MyDateUtils::convert(date('Y-m-d\TH:i:sP'),"UTC","Asia/Jakarta","Y-m-d\TH:i:sP"); // Example format: 2022-03-22T14:45:43+07:00


         try {
             $signature = $this->generate_signature($clientId, $timestamp, $privateKey);

         } catch (Exception $e) {
            return array(Tags::SUCCESS=>0,Tags::ERROR=>'Credentials invalid');
         }


        // Create the payload

         $headers = [
             "Content-Type: application/json",
             "X-TIMESTAMP: ".$timestamp,
             "X-CLIENT-KEY: ".\DANA\Config::$clientId,
             "X-SIGNATURE: ".$signature,
             "CHANNEL-ID: 95221"
         ];


         $ch = curl_init($url);

         curl_setopt($ch, CURLOPT_POST, 1);
         curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
         curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
         curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

         $response = curl_exec($ch);
         curl_close($ch);

         $response = json_decode($response,JSON_OBJECT_AS_ARRAY);

         if($response['responseCode']==2007300){
             return array(
                 Tags::SUCCESS=>1,
                 'token'=>$response['accessToken'],
                 'signature'=>$signature,
                 'timestamp'=>$timestamp,
             );
         }

         return array(Tags::SUCCESS=>0,Tags::ERROR=>'Credentials invalid');
     }


    function generate_signature($client_key, $timestamp, $private_key) {
        // Step 1: Compose the string to sign
        $string_to_sign = $client_key . "|" . $timestamp;

        // Step 2: Load the private key
        $private_key_id = openssl_pkey_get_private($private_key);

        if (!$private_key_id) {
            throw new Exception('Private key not found or invalid');
        }

        // Step 3: Generate the signature using SHA-256 with RSA-2048 encryption
        openssl_sign($string_to_sign, $signature, $private_key_id, OPENSSL_ALGO_SHA256);

        // Step 4: Encode the result to base64
        $signature_base64 = base64_encode($signature);

        // Note: No need to free the key, PHP will handle it automatically

        return $signature_base64;
    }

}
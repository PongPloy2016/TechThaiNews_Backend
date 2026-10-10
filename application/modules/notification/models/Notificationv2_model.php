<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class NotificationV2_model extends CI_Model {




    // (iOS) Private key's passphrase.
    private static $passphrase = 'joashp';
    // (Windows Phone 8) The name of our push channel.
    private static $channelName = "beta";

    // Change the above three vriables as per your app.
    public function __construct() {
        parent::__construct();
    }



    function getAccessToken() {


        if(!file_exists(ConfigManager::getValue('SERVICE_ACCOUNT_FILE'))){
            return null;
        }

        $file = file_get_contents(ConfigManager::getValue('SERVICE_ACCOUNT_FILE'));
        $serviceAccount = json_decode($file, true);

        $tokenUrl = 'https://oauth2.googleapis.com/token';
        $jwt = $this->generateJWT($serviceAccount);
        $headers = array(
            'Content-Type: application/x-www-form-urlencoded'
        );
        $postFields = 'grant_type=urn:ietf:params:oauth:grant-type:jwt-bearer&assertion=' . $jwt;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $tokenUrl);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postFields);

        $result = curl_exec($ch);
        if ($result === FALSE) {
            die('Curl failed: ' . curl_error($ch));
        }
        curl_close($ch);
        $response = json_decode($result, true);

        return $response['access_token'];
    }


    function generateJWT($serviceAccount) {

        $header = json_encode(['alg' => 'RS256', 'typ' => 'JWT']);
        $iat = time();
        $exp = $iat + 3600;
        $claimSet = json_encode([
            'iss' => $serviceAccount['client_email'],
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging',
            'aud' => 'https://oauth2.googleapis.com/token',
            'exp' => $exp,
            'iat' => $iat
        ]);

        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlClaimSet = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($claimSet));

        $signature = '';
        $key = openssl_pkey_get_private($serviceAccount['private_key']);
        if (!$key) {
            die('Invalid private key');
        }

        $dataToSign = $base64UrlHeader . '.' . $base64UrlClaimSet;
        openssl_sign($dataToSign, $signature, $key, OPENSSL_ALGO_SHA256);

        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));

        return $base64UrlHeader . '.' . $base64UrlClaimSet . '.' . $base64UrlSignature;
    }

    /**
     * @param string $platform
     * @param array $params
     */
    public function send_notification($platform="android", $params=array(),$notification=FALSE) {


        // include config

        $errors = array();
        extract($params);

        $accessToken = $this->getAccessToken();

        if($accessToken==null)
            return;


        if(!isset($regIds) OR $regIds==""){
            $errors['registerId'] = Translate::sprint(Messages::REGISTER_ID_ERRORS);
        }

        if ($platform=="android"){

            if (isset($body) and isset($regIds)){
                $result = $this->android($body,$regIds,$accessToken);
                return $result;
            }

        }else if ($platform=="ios"){

            if (isset($body) and isset($regIds)){
                $result = $this->fbSend($platform,$body,$regIds,$accessToken);
                return $result;
            }

        }else{

            if (isset($body) and isset($regIds)){
                $result = $this->android($body,$regIds,$accessToken);
                return $result;
            }

        }


        return array();
    }


    // Sends Push notification for Android users
    public function android($data, $reg_id,$tokenAccess) {

        $url = 'https://fcm.googleapis.com/v1/projects/'.ConfigManager::getValue('FIREBASE_PROJECT_ID').'/messages:send';

        $headers = array(
            'Authorization: Bearer ' .$tokenAccess,
            'Content-Type: application/json'
        );

        $fields = array(
            'registration_ids' => array($reg_id),
            'data' => $data,
            "priority" => "high",
            "mutable_content" => true,
        );



        foreach ($data as $k => $val){
            if(is_array($val))
                $data[$k] = json_encode($val);
            else
                $data[$k] = ($val);
        }


        $message = array(
            'message' => array(
                'token' => $reg_id,
                'data' => $data,
                'android' => array(
                    'priority' => 'high',
                ),
                'apns' => array(
                    'payload' => array(
                        'aps' => array(
                            'mutable-content' => 1,
                        ),
                    ),
                ),
            ),
        );


        $result = $this->useCurl($url, $headers, json_encode($message));
    
        return $result;
    }



    // Sends Push notification for Android users
    public function fbSend($platform,$data, $reg_id,$tokenAccess) {

        $url = 'https://fcm.googleapis.com/v1/projects/'.ConfigManager::getValue('FIREBASE_PROJECT_ID').'/messages:send';

        $headers = array(
            'Authorization: Bearer ' .$tokenAccess,
            'Content-Type: application/json'
        );

        if ($platform == 'android') {

            foreach ($data as $k => $val){
                $data[$k] = json_encode($val);
            }

            $fields = [
                "message" => [
                    "to" => $reg_id,
                    "data" => $data,
                    "priority" => 10,
                    "content_available" => true,
                ]
            ];

        }else if ($platform == 'ios') {

            $bodyForeground = array(
                'title' => ConfigManager::getValue("APP_NAME"),
                'text' => $data,
                'sound' => 'default',
                'badge' => '0',
                "alert" => ""
            );

            $bodyBackground = array(
                'title' => ConfigManager::getValue("APP_NAME"),
                'text' => Translate::sprint("You have new notification"),
                'sound' => 'default',
                'badge' => 1,
                "alert" => "default",
            );

            $fields = array(
                'message' => array(
                    'token' => $reg_id,
                    'notification' => $bodyBackground, // For background notifications
                    'data' => $bodyForeground, // For foreground notifications
                    'android' => array(
                        'priority' => 'high',
                    ),
                    'apns' => array(
                        'headers' => array(
                            'apns-priority' => '10',
                        ),
                        'payload' => array(
                            'aps' => array(
                                'alert' => array(
                                    'title' => $bodyBackground['title'],
                                    'body' => $bodyBackground['text'],
                                ),
                                'sound' => $bodyBackground['sound'],
                                'badge' => $bodyBackground['badge'],
                                "content-available" => 1,
                            ),
                        ),
                    ),
                ),
            );

        }else{

            $body = array(
                'title' =>"hi every one! using any other platform" ,
                'text' => $data,
                'sound' => 'default',
                'badge' => '1'
            );

            $fields = array(
                "to" => $reg_id,
                "data" => $body,
                //"priority" => 10
            );

        }


        $result = $this->useCurl($url, $headers, json_encode($fields));
        return $result;

    }


    // Sends Push notification for Android users
    public function sendCustomNotification($platform,$title,$message, $reg_id) {

        $url = 'https://fcm.googleapis.com/fcm/send';

        $headers = array(
            'Authorization: key=' .ConfigManager::getValue("FCM_KEY"),
            'Content-Type: application/json'
        );

        if ($platform == 'android') {

            //use this body when the app running in background
            $body_background = array(
                'title' => $title ,
                'text' => $message,
                'sound' => '',
                'badge' => 1,
                "alert" => "default",
            );

            $fields = array(
                "to" => $reg_id,
                "notification" => $body_background,
                "priority" => 10,
                "content_available" => true,
            );

        }else if ($platform == 'ios') {

            //use this body when the app running in background
            $body_background = array(
                'title' => $title ,
                'text' => $message,
                'sound' => '',
                'badge' => 1,
                "alert" => "default",
            );

            $fields = array(
                "to" => $reg_id,
                "notification" => $body_background,
                "content_available" => true,
                "priority" => "high",
            );

        }


        $result = $this->useCurl($url, $headers, json_encode($fields));
        return $result;

    }

    // Sends Push's toast notification for Windows Phone 8 users
    public function WP($data, $uri) {
        $delay = 2;
        $msg =  "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
            "<wp:Notification xmlns:wp=\"WPNotification\">" .
            "<wp:Toast>" .
            "<wp:Text1>".htmlspecialchars($data['mtitle'])."</wp:Text1>" .
            "<wp:Text2>".htmlspecialchars($data['mdesc'])."</wp:Text2>" .
            "</wp:Toast>" .
            "</wp:Notification>";

        $sendedheaders =  array(
            'Content-Type: text/xml',
            'Accept: application/*',
            'X-WindowsPhone-Target: toast',
            "X-NotificationClass: $delay"
        );

        $response = $this->useCurl($uri, $sendedheaders, $msg);

        $result = array();
        foreach(explode("\n", $response) as $line) {
            $tab = explode(":", $line, 2);
            if (count($tab) == 2)
                $result[$tab[0]] = trim($tab[1]);
        }

        return $result;
    }

    // Sends Push notification for iOS users
    public function iOS($data, $devicetoken) {
        $deviceToken = $devicetoken;
        $ctx = stream_context_create();
        // ck.pem is your certificate file
        stream_context_set_option($ctx, 'ssl', 'local_cert', 'ck.pem');
        stream_context_set_option($ctx, 'ssl', 'passphrase', self::$passphrase);
        // Open a connection to the APNS server
        $fp = stream_socket_client(
            'ssl://gateway.sandbox.push.apple.com:2195', $err,
            $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);
        if (!$fp)
            exit("Failed to connect: $err $errstr" . PHP_EOL);
        // Create the payload body
        $body['aps'] = array(
            'alert' => array(
                'title' => "HiHi",
                'body' => $data,
            ),
            'sound' => 'default'
        );
        // Encode the payload as JSON
        $payload = json_encode($body);
        // Build the binary notification
        $msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
        // Send it to the server
        $result = fwrite($fp, $msg, strlen($msg));

        // Close the connection to the server
        fclose($fp);
        if (!$result)
            return 'Message not delivered' . PHP_EOL;
        else
            return 'Message successfully delivered' . PHP_EOL;
    }

    // Curl
    private function useCurl($url, $headers, $fields = null) {
        // Open connection
        $ch = curl_init();
        if ($url) {

            // Set the url, number of POST vars, POST data
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            // Disabling SSL Certificate support temporarly
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            if ($fields) {
                curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
            }

            // Execute post
            $result = curl_exec($ch);
            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }

            // Close connection
            curl_close($ch);

            return $result;
        }
    }






    
  
}


<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class GCMServer {

    private $url = null;
    private $api_key = null;

    public function __construct($config) {
        $this->url = $config['url'];
        $this->api_key = $config['api_key'];
    }

    public function send($id, $message) {
        $fields = array(
            'registration_ids' => array($id),
            'data' => $message,
        );

        $headers = array(
            'Authorization: key=' . $this->api_key,
            'Content-Type: application/json'
        );
        // Open connection
        $ch = curl_init();

        // Set the url, number of POST vars, POST data
        curl_setopt($ch, CURLOPT_URL, $this->url);

        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Disabling SSL Certificate support temporarly
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));

        // Execute post
        $result = curl_exec($ch);
        //print_r($result);
            //echo ('Curl failed: ' . curl_error($ch));
        if ($result === FALSE) {
            echo ('Curl failed: ' . curl_error($ch));
            return false;
        }

        // Close connection
        curl_close($ch);
        return $result;
    }

}

/* End of file FileUploader.php */
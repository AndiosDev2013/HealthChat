<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require(APPPATH . '/libraries/REST_Controller.php');

class API extends REST_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        
    }
    
    public function _remap($method, $arguments) {
        $this->__call_rest_api($method, strtolower($this->request->method) == 'post');
    }

    private function __call_rest_api($name, $post_flag = false) {
        if (empty($name)) {
            return;
        }
        if (empty($name) || !file_exists(APPPATH . 'controllers/api/' . $name . '.php')) {
            $this->response(array('status' => ERROR, 'msg' => 'Invalid API. Look at the api.html.'), 404);
            return;
        }

        require_once 'api/' . $name . '.php';
        $rp = new $name();
        $rp->__set_controller($this);
        $rp->__set_post_flag($post_flag);
        if (!$rp->__check_login()) {
            $this->response(array('status' => ERROR, 'msg' => 'User token is required.'));
            return;
        }
        $api = $this->uri->segment(3);
        if (empty($api) || !method_exists($rp, $api)) {
            $this->response(array('status' => ERROR, 'msg' => 'Invalid Method. Look at the api.html.'), 404);
            return;
        }
        
        $rp->__init();
        $rp->$api();
    }

    function __mail($email, $subject, $msg) {

        if (1) {
            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://smtp.zoho.com',
                'smtp_port' => 465,
                'smtp_user' => ('support@healthchatapp.com'),
                'smtp_pass' => ('Healthchat@123'),
                'mailtype' => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE
            );
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");
            $this->email->from('HealthChat Support Team <support@healthchatapp.com>'); // change it to yours
            $this->email->to($email); // change it to yours
        } else {
            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'email-smtp.us-west-2.amazonaws.com',
                'mail.smtp.starttls.enable' => 'true',
                'mail.smtp.auth' => 'true',
                'smtp_port' => 587,
                'smtp_user' => 'AKIAIJZ2VL7W4UP5RPUA',
                'smtp_pass' => 'AmKPdNsQWsI10yYA4P0mCyGHwciG07dYD5rfU4NYEuJa',
                'mailtype' => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE
            );
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");
            $this->email->from('.com'); // change it to yours
            $this->email->to($email); // change it to yours
        }
        $this->email->subject($subject);
        $this->email->message($msg);
        if ($this->email->send()) {
            return true;
        } else {
            //show_error($this->email->print_debugger());
            return false;
        }
    }

}

?>

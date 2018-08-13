<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require 'Controller.php';

class Welcome extends Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $exist_super_admin = $this->____load_model('UserModel')->exist_super_admin();
        if ($exist_super_admin > 0) {
            if ($this->session->userdata('user')->is_admin)
            //$this->__view('welcome');
                redirect(MODULE_NAME_TOPIC);
            else if ($this->session->userdata('user')->reset)
                redirect(MODULE_NAME_MEMBER . '/profile');
            else
                redirect(MODULE_NAME_TOPIC);
        }
        else {
            redirect('member/registor');
        }
    }

    public function __check_login() {
        return true;
    }

}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
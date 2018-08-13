<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require 'Controller.php';

class Member extends Controller {

    public function __construct() {
        parent::__construct();
        $this->__load_model('UserModel');
        $this->__select_module_name(MODULE_NAME_MEMBER);
    }

    public function index() {
        $this->__view($this->__module_name . '/login');
    }

    public function __check_login() {
        return TRUE;
    }

    public function login() {

        $this->form_validation->set_rules('email', 'E-mail Address', 'trim|required');
        $this->form_validation->set_rules('digest', 'Password', 'trim|required|callback_password_check[' . $this->input->post('email') . ']');

        if ($this->form_validation->run() == TRUE) {
            $this->__redirect('');
        } else {
            if ('POST' == $_SERVER['REQUEST_METHOD'])
                $this->__error(validation_errors());
            $this->__view($this->__module_name . '/login', array('email' => $this->session->userdata('email')));
        }
    }

    function password_check($password, $email) {
        $info = $this->__model->get_by_name_or_email($email);
        if (sizeof($info) <= 0) {
            $this->form_validation->set_message('password_check', 'Invalid e-mail address. Please try again.');
            return FALSE;
        }

        if (strcmp($password, $info->password) != 0) {
            $this->form_validation->set_message('password_check', 'The password is incorrect.');
            return FALSE;
        }

        $user = new stdClass();

        $user->uid = $info->uid;
        $display_name = '';
        $name = $info->name;
        $email = $info->email;
        if ($name != null && !empty($name)) {
            $display_name = $name;
        }
        if ($email != null && !empty($email)) {
            if (!empty($display_name))
                $display_name .= '(' . $email . ')';
            else
                $display_name = $email;
        }
        if (!empty($display_name))
            $user->display_name = $display_name;
        $user->name = $name;
        $user->email = $email;
        $level = $info->level;
        $user->level = $level;
        $user->is_super_admin = $level == USER_TYPE_SUPER_ADMIN;
        $user->is_admin = $level == USER_TYPE_SUPER_ADMIN || $level == USER_TYPE_ADMIN;
        $user->is_owner = $level == USER_TYPE_SHOP_OWNER;
        $user->is_guest = $level == USER_TYPE_GUEST;
        $user->suspended = $info->active != 1;
        $user->reset = $info->reset == 1;
        $user->longitude = $info->longitude;
        $user->latitude = $info->latitude;

        $user->token = $info->token;
        if (empty($user->token)) {
            $user->token = md5($info->name . $info->email . $info->pwd);
            $this->__model->save(array('uid' => $user->uid, 'token' => $user->token));
        }

        $this->session->set_userdata('user', $user);

        return TRUE;
    }

    function _mail_new_pass($email, $newpass) {
        return $this->__mail($email,' Password reset', 'Your new password is ' . $newpass);
    }

    public function email_check($str) {
        if (!$this->__model->get_by_email($str)) {
            $this->form_validation->set_message('email_check', 'Invalid e-mail address');
            return FALSE;
        }

        return TRUE;
    }
    
    public function reset_password() {
        $this->form_validation->set_rules('email', 'E-mail Address', 'trim|required|callback_email_check');
        if ($this->form_validation->run() == TRUE) {
            $info = array();
            $email = $this->input->post('email');
            $user = $this->__model->get_by_email($email);
            if ($user) {
                $newpwd = $this->__model->gen_new_pass();
                $user->pwd = md5($newpwd);
                if ($this->__model->change_password($user->uid, $user->pwd, 1)) {
                    $ret = $this->_mail_new_pass($email, $newpwd);
                    $this->load->view($this->__module_name . '/congratulations', array('pass' => $newpwd));
                    return;
                }
            }
        }

        $this->load->view($this->__module_name . '/reset_password');
    }

    /*
    public function signup() {
        $this->form_validation->set_rules('email', 'E-mail Address', 'trim|required|callback_email_check');
        if ($this->form_validation->run() == TRUE) {
            $info = array();
            $info['email'] = $this->input->post('email');
            $newpwd = $this->gen_new_pass();
            $info['pwd'] = md5($newpwd);
            $info['level'] = USER_TYPE_SHOP_OWNER;
            if ($this->__model->save($info)) {
                //$this->_mail_new_pass($info['email'], $newpwd);
                $this->load->view($this->__module_name . '/congratulations', array('pass' => $newpwd));
                return;
            }
        }

        $this->load->view($this->__module_name . '/signup');
    }
    */

    public function registor() {
        $this->form_validation->set_rules('username', 'User Name', 'trim|required');
        $this->form_validation->set_rules('digest', 'Password', 'trim|required');

        $this->name = $this->input->post('username');
        $this->pwd = $this->input->post('digest');

        if ($this->form_validation->run() == TRUE) {
            $info = array('name' => $this->name,
                'gender' => 0,
                'birthday' => '',
                'email' => '',
                'password' => $this->pwd,
                'phone' => '',
                'level' => 1);

            if ($this->__model->save($info))
                $this->__redirect('welcome');
        } else {
            if ('POST' == $_SERVER['REQUEST_METHOD'])
                $this->__error(validation_errors());
            $this->__view($this->__module_name . '/registor');
        }
    }

    public function logout() {
        $this->session->unset_userdata('user');

        $this->__redirect($this->__module_name . '/login');
    }

    public function profile() {
        if (!parent::__check_login())
            exit;

        $this->__select_admin_type(MODULE_TYPE_PROFILE);
        if ('POST' == $_SERVER['REQUEST_METHOD']) {
            $user = $this->session->userdata('user');
            $user_info = $this->__model->__entity($user->uid);
            if (sizeof($user_info) > 0) {
                if (strcmp($user_info->password, $this->input->post('digest1')) == 0) {
                    if ($this->__model->change_password($user->uid, $this->input->post('digest2')))
                        $this->__success('Password has been changed successfully.', false);
                    else
                        $this->__error('Unknown error.');
                }
                else
                    $this->__error('Current password is not correct.');
            }
        }

        $this->__view($this->__module_name . '/profile');
    }

    public function recover_password() {
        $token = $this->uri->segment(3);
        $password = $this->uri->segment(4);
        $user = $this->__model->get_by_token($token);
        if ($user) {
            if (empty($password)) {
                $this->__error('Password cannot be blank');
            } else {
                $this->__model->change_password($user->uid, $password);
                $this->__success('Your new password is active now.');
            }
        } else {
            $this->__error('Invalid user.');
        }

        $this->__view($this->__module_name . '/recover_password');
    }
}

/* End of file meber.php */
/* Location: ./application/controllers/member.php */
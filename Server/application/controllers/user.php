<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require 'Controller.php';

class User extends Controller {

    public function __construct() {
        parent::__construct();
		if (!$this->session->userdata('user')->is_super_admin) {
            redirect('member/login');
			exit;
		}
        $this->__select_module(MODULE_TYPE_USER);
        $this->__select_module_name(MODULE_NAME_USER);
        $this->__load_model('UserModel');
    }

    function _mail_new_pass($email, $newpass) {
        return $this->__mail($email, 'Registration from appappapp.co', $this->load->view('user/congratulation', array('password' => $newpass), true));
    }

    public function save() {
        $this->form_validation->set_rules('email', 'E-mail Address', 'trim|required|callback_email_check');
        if ($this->form_validation->run() == TRUE) {
            $info = array();
            $info['email'] = $this->input->post('email');
            $newpwd = $this->__model->gen_new_pass();
            $info['pwd'] = md5($newpwd);
            $info['level'] = USER_TYPE_SHOP_OWNER;
            $info['reg_date'] = date('Y-m-d H:i:s', now());
            if ($this->__model->save($info)) {
                $this->_mail_new_pass($info['email'], $newpwd);
                redirect($this->__module_name);
                return;
            }
        }

        $this->__view($this->__module_name . '/register');
    }

    public function activate() {
        $cid = (int) $this->uri->segment(3);

        if ($cid <= 0) {
            redirect($this->__module_name);
            return;
        }

        $user = $this->__model->entity($cid);
        if ($user)
            $this->__model->save(array('uid' => $cid, 'active' => 1));
        redirect($this->__module_name);
    }

    public function suspense() {
        $cid = (int) $this->uri->segment(3);

        if ($cid <= 0) {
            redirect($this->__module_name);
            return;
        }

        $user = $this->__model->entity($cid);
        if ($user)
            $this->__model->save(array('uid' => $cid, 'active' => 0));
        redirect($this->__module_name);
    }

    public function email_check($str) {
        if ($this->__model->get_by_email($str)) {
            $this->form_validation->set_message('email_check', 'E-mail address is duplicated. If you have already signed up, please log in, or sign up with another e-mail address.');
            return FALSE;
        }

        return TRUE;
    }

    /*
      public function save() {
      $user_id = (int) $this->input->post('user_id');
      $user = $this->__model->get_by_name($this->input->post('name'));
      if ($user && $user->uid != $user_id) {
      echo 'duplicate name';
      return;
      }
      if ($this->input->post('email') != '') {
      $user = $this->__model->get_by_email($this->input->post('email'));
      if ($user && $user->uid != $user_id) {
      echo 'duplicate email';
      return;
      }
      }
      $res_id = 0;
      $origin = $_FILES['avatar']['name'];
      if (!empty($origin)) {
      $this->File_utils_model->create_dir(getcwd() . UPLOAD_FOLDER_PATH);
      $setting = array('save_path' => UPLOAD_FOLDER_PATH,
      'max_file_size_in_bytes' => 0,
      'extension_whitelist' => array('jpg', 'png', 'gif')
      );
      $this->load->library('My_upload', $setting);
      if ($this->my_upload->do_upload('avatar')) {
      $user = $this->__model->get_customer_info_by_id($user_id);
      if (sizeof($user) > 0) {
      $resource = $this->__model->get_resource_by_id($user->res_id);

      if (sizeof($resource) > 0) {
      $path = getcwd() . $resource->path . $resource->file_name;
      $this->File_utils_model->remove($path);
      $this->__model->update_resource_info($resource->rid, $origin, $this->my_upload->file_name, UPLOAD_FOLDER_PATH);
      } else {
      $this->__model->add_resource_info($origin, $this->my_upload->file_name, UPLOAD_FOLDER_PATH);
      $res_id = $this->__model->get_max_resource_id();
      }
      }
      } else {
      echo $this->my_upload->error;
      }
      }
      $info = array('uid' => $user_id, 'name' => $this->input->post('name'),
      'gendor' => $this->input->post('gendor'),
      'birth' => $this->input->post('birth'),
      'email' => $this->input->post('email'),
      'pwd' => $this->input->post('digest'),
      'phone' => $this->input->post('phone'),
      'level' => $this->input->post('level'),
      'fullName' => $this->input->post('fullName'),
      'interests' => $this->input->post('interests'),
      'occupation' => $this->input->post('occupation'),
      'res_id' => $res_id
      );

      $active = $this->input->post('active');
      if ($active != null)
      $info['active'] = $active;

      $this->__model->save($info);
      redirect('user');
      }
     * 
     */

    public function delete() {
        $this->__delete_with_resource();
    }

}

/* End of file meber.php */
/* Location: ./application/controllers/member.php */
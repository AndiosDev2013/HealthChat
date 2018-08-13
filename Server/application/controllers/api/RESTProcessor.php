<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class RESTProcessor {

    protected $__controller = null;
    protected $__model;
    protected $__post_flag = false;
    protected $__user = null;

    public function __load_model($model_name) {
        $this->__model = $this->____load_model($model_name);
        $this->__model->__set_rest_flag(true);
    }

    protected function ____load_model($model_name) {
        if (isset($this->__controller->$model_name))
            return $this->__controller->$model_name;
        $this->__controller->load->model($model_name);
        $model = $this->__controller->$model_name;
        $model->__set_user($this->__user);
        return $model;
    }

    public function __set_post_flag($post) {
        $this->__post_flag = $post;
    }

    public function __is_post() {
        return $this->__post_flag;
    }

    public function __init() {
        
    }

    public function __set_controller($controller) {
        $this->__controller = $controller;
    }

    public function __check_login() {
        $token = $this->__params('token');
        if (!$token)
            return false;

        $info = $this->____load_model('UserModel')->get_by_token($token);

        if ($info) {
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
            $user->health_topic_array = $info->health_topic_array;
            $user->diagnosed_with_array = $info->diagnosed_with_array;
            $this->__user = $user;
            if ($this->__model)
                $this->__model->__set_user($this->__user);
            return true;
        }

        return false;
    }

    public function response($data = null, $status = SUCCESS, $msg = null, $http_status = 200) {
        $params = array();
        $params['status'] = $status;
        if ($msg)
            $params['msg'] = $msg;
        if ($data && sizeof($data) > 0) {
            foreach ($data as $key => $value)
                $params[$key] = $value;
        }
        $this->__controller->response($params, $http_status);
    }

    public function success($data = null, $msg = null) {
        $this->response($data, SUCCESS, $msg);
    }
    public function error($msg, $code = ERROR_CODE_UNKOWN, $http_status = 200, $data = null) {
        if ($data == null) {
            $data = array();
        }
        $data['error_code'] = $code;
        $this->response($data, ERROR, $msg, $http_status);
    }

    public function __get_params($param) {
        return $this->__controller->get($param);
    }

    public function __post_params($param) {
        return $this->__controller->post($param);
    }

    public function __params($param) {
        if ($param == 'id')
            return $api = $this->__controller->uri->segment(4);
        if ($this->__is_post())
            return $this->__post_params($param);
        else
            return $this->__get_params($param);
    }

    public function __save_resource($resource_field_name, $res_id = 0) {
        $origin = time() . '.jpg';
        if (empty($origin)) {
            return $res_id;
        }

        $this->__controller->load->model('FileModel');
        if ($file_name = $this->__controller->FileModel->do_upload($resource_field_name, getcwd() . UPLOAD_FOLDER_PATH)) {
            $this->__controller->load->model('ResourceModel');
            if ($res_id > 0) {
                $resource = $this->__controller->ResourceModel->__entity($res_id);
                if (sizeof($resource) > 0) {
                    $path = getcwd() . $resource->path . $resource->file_name;
                    $this->__controller->FileModel->remove($path);
                }
                else {
                    $res_id = 0;
                }
            }

            $resource = array();
            $resource['rid'] = $res_id;
            $resource['origin_name'] = $origin;
            $resource['file_name'] = $file_name;
            $resource['path'] = UPLOAD_FOLDER_PATH;
            return $this->__controller->ResourceModel->save($resource);
        }

        return $res_id;
    }

    public function __remove_resource($res_id) {
        $this->__controller->load->model('FileModel');
        $this->__controller->load->model('ResourceModel');
        $resource = $this->__controller->ResourceModel->__entity($res_id);
        if (sizeof($resource) > 0) {
            $path = getcwd() . $resource->path . $resource->file_name;
            $this->__controller->FileModel->remove($path);
            $this->__controller->ResourceModel->delete($res_id);
        }
    }
    
    public function set_active_time() {
        if ($this->__user) {
            $userModel = $this->____load_model('UserModel');
            $userModel->save(array('uid' => $this->__user->uid, 'last_active_time' => date('Y-m-d H:i:s', now())));
        }
    }

}

?>

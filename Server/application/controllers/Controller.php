<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Controller extends CI_Controller {

    protected $__mtype = MODULE_TYPE_HOME;
    protected $__admin_type = MODULE_TYPE_HOME;
    protected $__model;
    protected $__pagination_link = null;
    protected $__entity = null;
    protected $__entities = null;
    protected $__total_count = 0;
    protected $__module_name = null;
    protected $__more_params = null;
    protected $__msg = null;

    public function __construct() {
        parent::__construct();
        $this->form_validation->set_error_delimiters('', '');
        if (!$this->__check_login())
            exit;
    }

    public function __check_login() {
        $user = $this->session->userdata('user');

        if ($user == null) {
            $this->__redirect('member/login');
            return FALSE;
        }

        return TRUE;
    }

    public function __params($sub_params = null) {
        $params = array();
        $params['mtype'] = $this->__mtype;
        $params['admin_type'] = $this->__admin_type;
        $params['module_name'] = $this->__module_name;

        $params['user'] = $this->session->userdata('user');

        $params['msg'] = $this->__msg ? $this->__msg : $this->session->flashdata('msg');
        //print_r($params);exit;

        if ($this->__pagination_link !== null)
            $params['pagination'] = $this->__pagination_link;
        if ($this->__entity != null)
            $params['entity'] = $this->__entity;
        if ($this->__entities != null) {
            $params['entities'] = $this->__entities;
            $params['total_count'] = $this->__total_count;
        }

        if ($sub_params) {
            foreach ($sub_params as $key => $value) {
                $params[$key] = $value;
            }
        }

        $more_params = $this->__more_params;
        if ($more_params) {
            foreach ($more_params as $key => $value) {
                $params[$key] = $value;
            }
        }

        return $params;
    }

    public function __set_more_params($params) {
        $this->__more_params = $params;
    }

    public function __add_params($name, $value = null) {
        if (is_array($name)) {
            foreach ($name as $key => $value)
                $this->__more_params[$key] = $value;
        } else {
            $this->__more_params[$name] = $value;
        }
    }

    public function __view($view, $params = null) {
        $this->____view($view, $this->__params($params));
    }

    public function ____view($view, $params = null) {
        $this->load->view('common/header.php', $params);
        $this->load->view($view, $params);
        $this->load->view('common/footer.php', $params);
    }

    public function __select_module($mtype) {
        $this->__mtype = $mtype;
    }

    public function __select_admin_type($admin_type) {
        $this->__admin_type = $admin_type;
    }

    public function __select_module_name($name) {
        $this->__module_name = $name;
    }

    public function __load_model($model_name) {
        $this->__model = $this->____load_model($model_name);
    }

    protected function ____load_model($model_name) {
        if (isset($this->$model_name))
            return $this->$model_name;
        $this->load->model($model_name);
        $this->$model_name->__set_user($this->session->userdata('user'));
        return $this->$model_name;
    }

    public function __set_entity($entity) {
        $this->__entity = $entity;
    }

    public function __set_entities($entities) {
        $this->__entities = $entities;
    }

    public function __paginate($base_url, $total_count, $params = null) {
        $config['base_url'] = $base_url;
        $config['total_rows'] = $total_count;
        $this->__total_count = $total_count;
        $config['per_page'] = PER_PAGE;
        $config['uri_segment'] = 3;

        $config['full_tag_open'] = '<ul>';
        $config['full_tag_close'] = '</ul>';
        $config['first_link'] = '<<';
        $config['first_tag_open'] = '<li>';
        $config['first_tag_close'] = '</li>';
        $config['last_link'] = '>>';
        $config['last_tag_open'] = '<li>';
        $config['last_tag_close'] = '</li>';
        $config['prev_link'] = '<';
        $config['prev_tag_open'] = '<li>';
        $config['prev_tag_close'] = '</li>';
        $config['next_link'] = '>';
        $config['next_tag_open'] = '<li>';
        $config['next_tag_close'] = '</li>';
        $config['cur_tag_open'] = "<li><a class='active'>";
        $config['cur_tag_close'] = '</a></li>';
        $config['num_tag_open'] = '<li>';
        $config['num_tag_close'] = '</li>';

        if ($params != null)
            $config['suffix'] = '?' . $params;
        $this->pagination->initialize($config);

        $this->__pagination_link = $this->pagination->create_links();
    }

    public function __entity_resource_id($entity_id, $resource_id_name = 'res_id') {
        $res_id = 0;
        if ($entity_id > 0) {
            $entity = $this->__model->__entity($entity_id);
            if (sizeof($entity) > 0)
                $res_id = (int) $entity->$resource_id_name;
        }

        return $res_id;
    }

    public function __save_resource($resource_field_name, $res_id = 0) {
        $origin = $_FILES[$resource_field_name]['name'];
        if (empty($origin)) {
            return $res_id;
        }

        $this->load->model('FileModel');
        $this->FileModel->create_dir(getcwd() . UPLOAD_FOLDER_PATH);
        $setting = array('save_path' => UPLOAD_FOLDER_PATH,
            'max_file_size_in_bytes' => 0,
            'extension_whitelist' => array('jpg', 'png', 'gif')
        );

        $this->load->library('FileUploader', $setting);
        if ($this->fileuploader->do_upload($resource_field_name)) {
            $this->load->model('ResourceModel');
            if ($res_id > 0) {
                $resource = $this->ResourceModel->__entity($res_id);
                if (sizeof($resource) > 0) {
                    $path = getcwd() . $resource->path . $resource->file_name;
                    $this->FileModel->remove($path);
                }
                else
                    $res_id = 0;
            }

            $resource = array();
            $resource['rid'] = $res_id;
            $resource['origin_name'] = $origin;
            $resource['file_name'] = $this->fileuploader->file_name;
            $resource['path'] = UPLOAD_FOLDER_PATH;
            return $this->ResourceModel->save($resource);
        }
        else
            echo $this->fileuploader->error;

        return $res_id;
    }

    public function __remove_resource($res_id) {
        $this->load->model('FileModel');
        $this->load->model('ResourceModel');
        $resource = $this->ResourceModel->__entity($res_id);
        if (sizeof($resource) > 0) {
            $path = getcwd() . $resource->path . $resource->file_name;
            $this->FileModel->remove($path);
            $this->ResourceModel->delete($res_id);
            return true;
        }
        else
            return false;
    }

    public function index() {
        $this->__paginate($this->config->site_url() . '/' . $this->__module_name . '/index/', $this->__model->__total_count());
        $this->__set_entities($this->__model->search(PER_PAGE, 0, $this->uri->segment(3)));
        $this->__view($this->__module_name . '/list');
    }

    public function view() {
        $cid = (int) $this->uri->segment(3);

        if ($cid <= 0) {
            $this->__redirect($this->__module_name);
            return;
        }

        $this->__set_entity($this->__model->entity($cid));
        if ($this->__entity)
            $this->__view($this->__module_name . '/view');
        else
            $this->__redirect($this->__module_name);
    }

    public function register() {
        $this->__view($this->__module_name . '/register');
    }

    public function edit() {
        $cid = (int) $this->uri->segment(3);

        if ($cid <= 0) {
            $this->__redirect($this->__module_name);
            return;
        }

        $this->__set_entity($this->__model->entity($cid));
        if ($this->__entity)
            $this->__view($this->__module_name . '/edit');
        else
            $this->__redirect($this->__module_name);
    }

    function __mail($email, $subject, $msg) {

        if (1) {
            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'ssl://smtp.gmail.com',
                'smtp_port' => 465,
                'smtp_user' => ('.com'),
                'smtp_pass' => (''),
                'mailtype' => 'html',
                'charset' => 'iso-8859-1',
                'wordwrap' => TRUE
            );
            $this->load->library('email', $config);
            $this->email->set_newline("\r\n");
            $this->email->from('.com'); // change it to yours
            $this->email->to($email); // change it to yours
            /*
              $config = Array(
              'protocol' => 'smtp',
              'smtp_host' => 'smtp.live.com',
              'smtp_port' => 587,
              'smtp_user' => ('.com'),
              'smtp_pass' => (''),
              'mailtype' => 'html',
              'charset' => 'iso-8859-1',
              'wordwrap' => TRUE
              );
              $this->load->library('email', $config);
              $this->email->set_smtp_crypto('tls');
              $this->email->set_newline("\r\n");
              $this->email->from('.com'); // change it to yours
              $this->email->to($email); // change it to yours
             */
        } else {
            $config = Array(
                'protocol' => 'smtp',
                'smtp_host' => 'relay-hosting.secureserver.net',
                'smtp_port' => 25,
                'smtp_user' => '.co',
                'smtp_pass' => '',
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
            show_error($this->email->print_debugger());
            return false;
        }
    }

    public function delete() {
        $sid = (int) $this->uri->segment(3);
        if ($sid <= 0) {
            $this->__redirect($this->__module_name);
            return;
        }

        $this->__model->delete($sid);

        $this->__redirect($this->__module_name);
    }

    public function __delete_with_resource() {
        $sid = (int) $this->uri->segment(3);
        if ($sid <= 0) {
            $this->__redirect($this->__module_name);
            return;
        }

        if (($res_id = $this->__entity_resource_id($sid)) > 0)
            $this->__remove_resource($res_id);

        $this->__model->delete($sid);

        $this->__redirect($this->__module_name);
    }

    public function __success($data) {
        $this->__message($data, MSG_TYPE_SUCCESS);
    }

    public function __error($data) {
        $this->__message($data, MSG_TYPE_ERROR);
    }

    public function __message($data, $status = MSG_TYPE_SUCCESS) {
        $msg = new stdClass();
        $msg->status = $status;
        $msg->data = $data;
        $this->__msg = $msg;
    }

    public function __redirect($uri = '', $method = 'location', $http_response_code = 302) {
        if ($this->__msg)
            $this->session->set_flashdata('msg', $this->__msg);
        redirect($uri, $method, $http_response_code);
    }

}

?>

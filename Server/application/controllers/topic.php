<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
require 'Controller.php';

class Topic extends Controller {

    public function __construct() {
        parent::__construct();
        $this->__select_module(MODULE_TYPE_TOPIC);
        $this->__select_module_name(MODULE_NAME_TOPIC);
        $this->__load_model('TopicModel');
    }

}

/* End of file topic.php */
/* Location: ./application/controllers/topic.php */
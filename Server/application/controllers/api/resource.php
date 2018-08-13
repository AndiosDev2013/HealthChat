<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'RESTProcessor.php';

class Resource extends RESTProcessor {

    public function __init() {
        parent::__init();
    }
    
    public function __check_login() {
        return true;
    }

    public function upload() {
        $rid = $this->__save_resource('file');
        if ($rid > 0) {
            $this->response(array('rid' => $rid), 'Upload succed.');
        } else
            $this->error ('Upload failed of unkown errors');
    }
}

?>

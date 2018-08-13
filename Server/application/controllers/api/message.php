<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'RESTProcessor.php';

class Message extends RESTProcessor {

    public function __init() {
        parent::__init();
        $this->__load_model('MessageModel');
    }

    public function send() {
        $message = $this->__params('message');
        $receiver_id = (int) $this->__params('receiver_id');
        $receiverModel = $this->____load_model('UserModel');
        if (!($receiver = $receiverModel->__entity($receiver_id))) {
            $this->error('Invalid Receiver Id');
            return;
        }
        $mid = $this->__model->save(array('message' => $message, 'user_id' => $this->__user->uid, 'receiver_id' => $receiver_id));
        $message = $this->__model->entity($mid);
        if ($mid > 0) {
            $this->set_active_time();
            if (!$this->getGCMServer()->send($receiver->device_token, $message)) {
                $this->error('GCM Error!!!');
            } else {
                $this->success(array('mid' => $mid, 'message' => $message), 'A new message has been sent successfully.');
            }
        } else
            $this->error('Sending failed.');
    }

    public function test() {
        $message = $this->__params('message');
        $receiver_id = (int) $this->__params('receiver_id');
        $receiverModel = $this->____load_model('UserModel');
        if (!($receiver = $receiverModel->__entity($receiver_id))) {
            $this->error('Invalid Receiver Id');
            return;
        }
        echo $this->getGCMServer()->send($receiver->device_token, $message);
    }

    public function connected() {
        $receiver_id = (int) $this->__params('receiver_id');
        $receiverModel = $this->____load_model('UserModel');
        if (!$receiverModel->__entity($receiver_id)) {
            $this->error('Invalid Receiver Id');
            return;
        }

        $this->success(array('mid' => $this->__model->connected($this->__user->uid, $receiver_id)));
    }

    public function search() {
        $offset = $this->__params('offset');
        $limit = $this->__params('limit');
        $receiver_id = (int) $this->__params('receiver_id');
        $this->response(array('message_list' => $this->__model->search($limit, $offset, array('user_id' => $this->__user->uid, 'receiver_id' => $receiver_id))));
    }

    public function getGCMServer() {
        if (!isset($this->__controller->gcm)) {
            $this->__controller->load->config('gcm');
            $config = array(
                'url' => $this->__controller->config->item('url'),
                'api_key' => $this->__controller->config->item('api_key')
            );
            $this->__controller->load->library('GCMServer', $config, 'gcm');
        }
        return $this->__controller->gcm;
    }

}

?>

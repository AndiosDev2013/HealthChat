<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'RESTProcessor.php';

class Topic extends RESTProcessor {

    public function __init() {
        parent::__init();
        $this->__load_model('TopicModel');
    }

    public function register() {
        $title = $this->__params('title');
        $desc = $this->__params('desc');
        if ($this->__model->get_by_title($title)) {
            $this->error('The title is already in use.', ERROR_CODE_TITLE_ALREADY_REGISTERED);
            return;
        }
        $tid = $this->__model->save(array('title' => $title, 'desc' => $desc, 'user_id' => $this->__user->uid));
        if ($tid > 0) {
            $this->set_active_time();
            $this->success(array('tid' => $tid), 'A new topic has been registered successfully.');
        } else
            $this->error('Registration failed.');
    }

    public function search() {
        $offset = $this->__params('offset');
        $limit = $this->__params('limit');
        $this->response(array('topic_list' => $this->__model->search($limit, $offset, array('type' => $this->__params('type')))));
    }

    public function like() {
        $topic_id = $this->__params('topic_id');
        if ($topic_id <= 0 || !$this->__model->__entity($topic_id)) {
            $this->error('Invalid topic id');
            return;
        }

        $likeModel = $this->____load_model('TopicLikeModel');
        $like = $likeModel->get_by_topic($topic_id);
        if ($like) {
            $this->success(array('lid' => $like->lid));
            return;
        }

        $user_id = $this->__user->uid;
        $ret = $likeModel->save(array('topic_id' => $topic_id, 'user_id' => $user_id));
        if ($ret > 0) {
            $this->__model->__save(array('tid' => $topic_id, 'like_count' => $likeModel->__total_count(array('topic_id' => $topic_id))));
            $this->set_active_time();
            $this->success(array('lid' => $ret));
        } else
            $this->error('Unkown error');
    }

    public function unlike() {
        $like_id = $this->__params('lid');
        $likeModel = $this->____load_model('TopicLikeModel');
        if ($like_id <= 0 || !($like = $likeModel->__entity($like_id))) {
            $this->error('Never liked.');
            return;
        }

        $ret = $likeModel->delete($like->lid);
        if ($ret) {
            $this->__model->__save(array('tid' => $like->topic_id, 'like_count' => $likeModel->__total_count(array('topic_id' => $like->topic_id))));
            $this->set_active_time();
            $this->success(array('lid' => $like->lid));
        } else
            $this->error('Unkown error');
    }

    public function flag() {
        $topic_id = $this->__params('topic_id');
        if ($topic_id <= 0 || !$this->__model->__entity($topic_id)) {
            $this->error('Invalid topic id');
            return;
        }

        $flagModel = $this->____load_model('TopicFlagModel');
        $flag = $flagModel->get_by_topic($topic_id);
        if ($flag) {
            $this->success(array('fid' => $flag->fid));
            return;
        }

        $user_id = $this->__user->uid;
        $ret = $flagModel->save(array('topic_id' => $topic_id, 'user_id' => $user_id));
        if ($ret > 0) {
            $this->__model->__save(array('tid' => $topic_id, 'flag_count' => $flagModel->__total_count(array('topic_id' => $topic_id))));
            $this->set_active_time();
            $this->success(array('fid' => $ret));
        } else
            $this->error('Unkown error');
    }

    public function unflag() {
        $flag_id = $this->__params('fid');
        $flagModel = $this->____load_model('TopicFlagModel');
        if ($flag_id <= 0 || !($flag = $flagModel->__entity($flag_id))) {
            $this->error('Never flagged.');
            return;
        }

        $ret = $flagModel->delete($flag->fid);
        if ($ret) {
            $this->__model->__save(array('tid' => $flag->topic_id, 'flag_count' => $flagModel->__total_count(array('topic_id' => $flag->topic_id))));
            $this->set_active_time();
            $this->success(array('fid' => $flag->fid));
        } else
            $this->error('Unkown error');
    }

}

?>

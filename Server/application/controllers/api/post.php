<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'RESTProcessor.php';

class Post extends RESTProcessor {

    public function __init() {
        parent::__init();
        $this->__load_model('PostModel');
    }

    public function register() {
        $title = $this->__params('title');
        $topic_id = (int) $this->__params('topic_id');
        $topicModel = $this->____load_model('TopicModel');
        if (!$topicModel->__entity($topic_id)) {
            $this->error('Invalid Topic Id');
            return;
        }
        $res_id = $this->__save_resource('picture');
        $pid = $this->__model->save(array('title' => $title, 'topic_id' => $topic_id, 'user_id' => $this->__user->uid, 'res_id' => $res_id));
        if ($pid > 0) {
            $this->set_active_time();
            $topicModel->__save(array('tid' => $topic_id, 'post_count' => $this->__model->__total_count(array('topic_id' => $topic_id))));
            $post = $this->__model->entity($pid);
            $this->success(array('pid' => $pid, 'picture_url' => $post->picture_url), 'A new post has been registered successfully.');
        } else
            $this->error('Registration failed.');
    }

    public function search() {
        $offset = $this->__params('offset');
        $limit = $this->__params('limit');
        $this->response(array('post_list' => $this->__model->search($limit, $offset, array('topic_id' => $this->__params('topic_id')))));
    }

    public function get() {
        $pid = $this->__params('post_id');
        $post = $this->__model->entity($pid);
        if ($post) {
            $this->response(array('post' => $post));
        } else {
            $this->error('Invalid Post Id');
        }
    }

    public function update() {
        $pid = $this->__params('post_id');
        if (!($post = $this->__model->entity($pid))) {
            $this->error('Invalid Post Id');
            return;
        }

        if ($post->user_id != $this->__user->uid) {
            $this->error('No permission to update this post.');
        }

        $title = $this->__params('title');
        $res_id = $this->__save_resource('picture', $post->res_id);
        if ($this->__model->save(array('title' => $title, 'pid' => $pid, 'res_id' => $res_id))) {
            $this->set_active_time();
            $post = $this->__model->entity($pid);
            $this->success(array('pid' => $pid, 'picture_url' => $post->picture_url), 'The post has been updated successfully.');
        } else
            $this->error('Update failed.');
    }

    public function delete() {
        $pid = $this->__params('post_id');
        if ($pid <= 0 || !($post = $this->__model->__entity($pid))) {
            $this->error('Invalid Post Id.');
            return;
        }

        if ($post->user_id != $this->__user->uid) {
            $this->error('No permission to delete this post.');
        }

        if ($this->__model->delete($pid)) {
            $this->__remove_resource($post->res_id);
            $this->____load_model('CommentModel')->delete_by_post($pid);
            $topicModel = $this->____load_model('TopicModel');
            $topicModel->__save(array('tid' => $post->topic_id, 'post_count' => $this->__model->__total_count(array('topic_id' => $post->topic_id))));
            $this->set_active_time();
            $this->success(array('pid' => $pid));
        } else {
            $this->error('Unknow error while deleting post.');
        }
    }

    public function like() {
        $post_id = $this->__params('post_id');
        if ($post_id <= 0 || !($post = $this->__model->__entity($post_id))) {
            $this->error('Invalid post id');
            return;
        }

        $likeModel = $this->____load_model('PostLikeModel');
        $like = $likeModel->get_by_post($post_id);
        if ($like) {
            $this->success(array('lid' => $like->lid, 'post_id' => $post_id, 'like_count' => $post->like_count));
            return;
        }

        $user_id = $this->__user->uid;
        $ret = $likeModel->save(array('post_id' => $post_id, 'user_id' => $user_id));
        if ($ret > 0) {
            $like_count = $likeModel->__total_count(array('post_id' => $post_id));
            $this->__model->__save(array('pid' => $post_id, 'like_count' => $like_count));
            $this->set_active_time();
            $this->success(array('lid' => $ret, 'post_id' => $post_id, 'like_count' => $like_count));
        } else
            $this->error('Unkown error');
    }

    public function unlike() {
        $like_id = $this->__params('lid');
        $likeModel = $this->____load_model('PostLikeModel');
        if ($like_id <= 0 || !($like = $likeModel->__entity($like_id))) {
            $this->error('Never liked.');
            return;
        }

        $ret = $likeModel->delete($like->lid);
        if ($ret) {
            $like_count = $likeModel->__total_count(array('post_id' => $like->post_id));
            $this->__model->__save(array('pid' => $like->post_id, 'like_count' => $like_count));
            $this->set_active_time();
            $this->success(array('lid' => $like->lid, 'post_id' => $like->post_id, 'like_count' => $like_count));
        } else
            $this->error('Unkown error');
    }

    public function likelist() {
        $offset = $this->__params('offset');
        $limit = $this->__params('limit');
        $post_id = $this->__params('post_id');
        if ($post_id <= 0 || !($post = $this->__model->__entity($post_id))) {
            $this->error('Invalid post id');
            return;
        }

        $likeModel = $this->____load_model('PostLikeModel');
        $this->success(array('like_list' => $likeModel->search($limit, $offset, array('post_id' => $post_id))));
    }

    public function flag() {
        $post_id = $this->__params('post_id');
        if ($post_id <= 0 || !$this->__model->__entity($post_id)) {
            $this->error('Invalid post id');
            return;
        }

        $flagModel = $this->____load_model('PostFlagModel');
        $flag = $flagModel->get_by_post($post_id);
        if ($flag) {
            $this->success(array('fid' => $flag->fid, 'post_id' => $post_id));
            return;
        }

        $user_id = $this->__user->uid;
        $ret = $flagModel->save(array('post_id' => $post_id, 'user_id' => $user_id));
        if ($ret > 0) {
            $this->__model->__save(array('pid' => $post_id, 'flag_count' => $flagModel->__total_count(array('post_id' => $post_id))));
            $this->set_active_time();
            $this->success(array('fid' => $ret, 'post_id' => $post_id));
        } else
            $this->error('Unkown error');
    }

    public function unflag() {
        $flag_id = $this->__params('fid');
        $flagModel = $this->____load_model('PostFlagModel');
        if ($flag_id <= 0 || !($flag = $flagModel->__entity($flag_id))) {
            $this->error('Never flagged.');
            return;
        }

        $ret = $flagModel->delete($flag->fid);
        if ($ret) {
            $this->__model->__save(array('pid' => $flag->post_id, 'flag_count' => $flagModel->__total_count(array('post_id' => $flag->post_id))));
            $this->set_active_time();
            $this->success(array('fid' => $flag->fid, 'post_id' => $flag->post_id));
        } else
            $this->error('Unkown error');
    }

    public function share() {
        $post_id = $this->__params('post_id');
        if ($post_id <= 0 || !($post = $this->__model->__entity($post_id))) {
            $this->error('Invalid post id');
            return;
        }

        $shareModel = $this->____load_model('PostShareModel');
        $share = $shareModel->get_by_post($post_id);
        $sid = 0;
        if ($share) {
            $sid = $share->sid;
        }

        $user_id = $this->__user->uid;
        $shared = $this->__params('shared');
        $ret = $shareModel->save(array('sid' => $sid, 'post_id' => $post_id, 'user_id' => $user_id, 'shared' => $shared));
        if ($ret > 0) {
            $this->set_active_time();
            $this->success(array('sid' => $ret, 'post_id' => $post_id, 'shared' => $shared));
        } else
            $this->error('Unkown error');
    }

}

?>

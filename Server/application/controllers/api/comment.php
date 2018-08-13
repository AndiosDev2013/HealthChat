<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'RESTProcessor.php';

class Comment extends RESTProcessor {

    public function __init() {
        parent::__init();
        $this->__load_model('CommentModel');
    }

    public function register() {
        $title = $this->__params('title');
        $post_id = (int) $this->__params('post_id');
        $postModel = $this->____load_model('PostModel');
        if (!$postModel->__entity($post_id)) {
            $this->error('Invalid Post Id');
            return;
        }
        $cid = $this->__model->save(array('title' => $title, 'user_id' => $this->__user->uid, 'post_id' => $post_id));
        if ($cid > 0) {
            $this->set_active_time();
            $postModel->__save(array('pid' => $post_id, 'comment_count' => $this->__model->__total_count(array('post_id' => $post_id))));
            $this->success(array('cid' => $cid), 'A new comment has been registered successfully.');
        } else
            $this->error('Registration failed.');
    }

    public function search() {
        $offset = $this->__params('offset');
        $limit = $this->__params('limit');
        $this->response(array('comment_list' => $this->__model->search($limit, $offset, array('post_id' => $this->__params('post_id')))));
    }

    public function update() {
        $cid = $this->__params('comment_id');
        if (!($comment = $this->__model->entity($cid))) {
            $this->error('Invalid Comment Id');
            return;
        }

        if ($comment->user_id != $this->__user->uid) {
            $this->error('No permission to update this comment.');
        }
        
        $title = $this->__params('title');
        if ($this->__model->save(array('title' => $title, 'cid' => $cid))) {
            $this->set_active_time();
            $this->success(array('cid' => $cid), 'The comment has been updated successfully.');
        } else
            $this->error('Update failed.');
    }

    public function delete() {
        $cid = $this->__params('comment_id');
        if ($cid <= 0 || !($comment = $this->__model->__entity($cid))) {
            $this->error('Invalid Comment Id.');
            return;
        }
        
        if ($comment->user_id != $this->__user->uid) {
            $this->error('No permission to delete this comment.');
        }

        if ($this->__model->delete($cid)) {
            $this->set_active_time();
            $postModel = $this->____load_model('PostModel');
            $postModel->__save(array('pid' => $comment->post_id, 'comment_count' => $this->__model->__total_count(array('post_id' => $comment->post_id))));
            $this->success(array('cid' => $cid));
        } else {
            $this->error('Unknow error while deleting comment.');
        }
    }

    public function like() {
        $comment_id = $this->__params('comment_id');
        if ($comment_id <= 0 || !$this->__model->__entity($comment_id)) {
            $this->error('Invalid comment id');
            return;
        }

        $likeModel = $this->____load_model('CommentLikeModel');
        $like = $likeModel->get_by_comment($comment_id);
        if ($like) {
            $this->success(array('lid' => $like->lid, 'comment_id' => $comment_id));
            return;
        }

        $user_id = $this->__user->uid;
        $ret = $likeModel->save(array('comment_id' => $comment_id, 'user_id' => $user_id));
        if ($ret > 0) {
            $this->__model->__save(array('cid' => $comment_id, 'like_count' => $likeModel->__total_count(array('comment_id' => $comment_id))));
            $this->set_active_time();
            $this->success(array('lid' => $ret, 'comment_id' => $comment_id));
        } else
            $this->error('Unkown error');
    }

    public function unlike() {
        $like_id = $this->__params('lid');
        $likeModel = $this->____load_model('CommentLikeModel');
        if ($like_id <= 0 || !($like = $likeModel->__entity($like_id))) {
            $this->error('Never liked.');
            return;
        }

        $ret = $likeModel->delete($like->lid);
        if ($ret) {
            $this->__model->__save(array('cid' => $like->comment_id, 'like_count' => $likeModel->__total_count(array('comment_id' => $like->comment_id))));
            $this->set_active_time();
            $this->success(array('lid' => $like->lid, 'comment_id' => $like->comment_id));
        } else
            $this->error('Unkown error');
    }

    public function flag() {
        $comment_id = $this->__params('comment_id');
        if ($comment_id <= 0 || !$this->__model->__entity($comment_id)) {
            $this->error('Invalid comment id');
            return;
        }

        $flagModel = $this->____load_model('CommentFlagModel');
        $flag = $flagModel->get_by_comment($comment_id);
        if ($flag) {
            $this->success(array('fid' => $flag->fid, 'comment_id' => $comment_id));
            return;
        }

        $user_id = $this->__user->uid;
        $ret = $flagModel->save(array('comment_id' => $comment_id, 'user_id' => $user_id));
        if ($ret > 0) {
            $this->__model->__save(array('cid' => $comment_id, 'flag_count' => $flagModel->__total_count(array('comment_id' => $comment_id))));
            $this->set_active_time();
            $this->success(array('fid' => $ret, 'comment_id' => $comment_id));
        } else
            $this->error('Unkown error');
    }

    public function unflag() {
        $flag_id = $this->__params('fid');
        $flagModel = $this->____load_model('CommentFlagModel');
        if ($flag_id <= 0 || !($flag = $flagModel->__entity($flag_id))) {
            $this->error('Never flagged.');
            return;
        }

        $ret = $flagModel->delete($flag->fid);
        if ($ret) {
            $this->__model->__save(array('cid' => $flag->comment_id, 'flag_count' => $flagModel->__total_count(array('comment_id' => $flag->comment_id))));
            $this->set_active_time();
            $this->success(array('fid' => $flag->fid, 'comment_id' => $flag->comment_id));
        } else
            $this->error('Unkown error');
    }

}

?>

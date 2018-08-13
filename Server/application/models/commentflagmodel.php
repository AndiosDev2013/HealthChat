<?php

require_once 'Model.php';

class CommentFlagModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_COMMENT_FLAG, 'fid');
    }

    public function __set_filter($params) {
        if (isset($params['comment_id']) && $params['comment_id'] > 0)
            $this->db->where('comment_id', $params['comment_id']);
    }

    public function get_by_comment($comment_id) {
        $this->db->where('comment_id', $comment_id);
        $this->db->where('user_id', $this->__user->uid);
        return $this->__valid_entity($this->db->get($this->__entity_name)->row());
    }
}

?>

<?php

require_once 'Model.php';

class PostShareModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_POST_SHARE, 'sid');
    }

    public function __set_filter($params) {
        if (isset($params['post_id']) && $params['post_id'] > 0)
            $this->db->where('post_id', $params['post_id']);
    }

    public function get_by_post($post_id) {
        $this->db->where('post_id', $post_id);
        $this->db->where('user_id', $this->__user->uid);
        return $this->__valid_entity($this->db->get($this->__entity_name)->row());
    }
}

?>

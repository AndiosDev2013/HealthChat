<?php

require_once 'Model.php';

class TopicLikeModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_TOPIC_LIKE, 'lid');
    }

    public function __set_filter($params) {
        if (isset($params['topic_id']) && $params['topic_id'] > 0)
            $this->db->where('topic_id', $params['topic_id']);
    }

    public function get_by_topic($topic_id) {
        $this->db->where('topic_id', $topic_id);
        $this->db->where('user_id', $this->__user->uid);
        return $this->__valid_entity($this->db->get($this->__entity_name)->row());
    }
}

?>

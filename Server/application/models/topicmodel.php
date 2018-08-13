<?php

require_once 'Model.php';

class TopicModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_TOPIC, 'tid');
        $this->__set_use_created_time(true);
    }

    public function get_by_title($title) {
        $this->db->where('title', $title);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function __set_filter($params) {
        $this->db->select('l.lid');
        $this->db->join(ENTITY_NAME_TOPIC_LIKE . ' l', $this->__entity_name . '.tid=l.topic_id and l.user_id=' . $this->__user->uid, 'LEFT');
        $this->db->select('f.fid');
        $this->db->join(ENTITY_NAME_TOPIC_FLAG . ' f', $this->__entity_name . '.tid=f.topic_id and f.user_id=' . $this->__user->uid, 'LEFT');

        $type = (int) $params['type'];
        if ($type == 0) {
            $this->db->order_by($this->__entity_name . '.created_time', 'DESC');
        } else if ($type == 1) {
            $health_topic_array = $this->__user->health_topic_array;
            if (!empty($health_topic_array)) {
                $health_topic_array = str_replace(';', ',', $health_topic_array);
                $this->db->where($this->__entity_name . ".tid in (" . $health_topic_array . ")");
            } else {
                $this->db->where($this->__entity_name . ".tid in (false)");
            }
            $this->db->order_by($this->__entity_name . '.created_time', 'DESC');
        } else if ($type == 2) {
            $this->db->order_by($this->__entity_name . ".post_count", "DESC");
        } else if ($type == 3) {
            $this->db->where('l.lid >', 0);
            $this->db->order_by($this->__entity_name . '.created_time', 'DESC');
        }
    }

}

?>

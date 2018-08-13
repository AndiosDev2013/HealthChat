<?php

require_once 'Model.php';

class PostModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_POST, 'pid');
        $this->__set_use_created_time(true);
        $this->__set_use_resource(true);
    }
    
    public function entity($id) {
        $this->db->select('(SELECT cid FROM ' . ENTITY_NAME_COMMENT .' c WHERE c.user_id=' . $this->__user->uid . ' AND c.post_id=pid LIMIT 1) cid');
        return parent::entity($id);
    }

    public function __set_filter($params) {
        $this->db->select('t.title topic_title');
        $this->db->join(ENTITY_NAME_TOPIC . ' t', $this->__entity_name . '.topic_id=t.tid', 'LEFT');
        $this->db->select('l.lid');
        $this->db->join(ENTITY_NAME_POST_LIKE . ' l', $this->__entity_name . '.pid=l.post_id and l.user_id=' . $this->__user->uid, 'LEFT');
        $this->db->select('f.fid');
        $this->db->join(ENTITY_NAME_POST_FLAG . ' f', $this->__entity_name . '.pid=f.post_id and f.user_id=' . $this->__user->uid, 'LEFT');
        $this->db->select('s.shared');
        $this->db->join(ENTITY_NAME_POST_SHARE. ' s', $this->__entity_name . '.pid=s.post_id and s.user_id=' . $this->__user->uid, 'LEFT');
        $this->db->select('(SELECT cid FROM ' . ENTITY_NAME_COMMENT .' c WHERE c.user_id=' . $this->__user->uid . ' AND c.post_id=pid LIMIT 1) cid');
        $this->db->select("u.full_name user_full_name, u.social_picture_url user_social_picture_url, concat('" . $this->config->base_url() . "', user_res.path, user_res.file_name) as user_picture_url, gender user_gender", false);
        $this->db->join(ENTITY_NAME_USER . ' u', $this->__entity_name . '.user_id=u.uid', 'LEFT');
        $this->db->join(ENTITY_NAME_RESOURCE . ' user_res', 'u.res_id = ' . 'user_res.rid', 'LEFT');

        $topic_id = (int) $params['topic_id'];
        if ($topic_id > 0) {
            $this->db->where('topic_id', $topic_id);
        }
        $this->db->order_by($this->__entity_name . '.created_time', 'DESC');
    }

}

?>

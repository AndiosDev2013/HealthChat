<?php

require_once 'Model.php';

class CommentModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_COMMENT, 'cid');
        $this->__set_use_created_time(true);
    }

    public function get_by_title($title) {
        $this->db->where('title', $title);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function __set_filter($params) {
        $this->db->select('l.lid');
        $this->db->join(ENTITY_NAME_COMMENT_LIKE . ' l', $this->__entity_name . '.cid=l.comment_id and l.user_id=' . $this->__user->uid, 'LEFT');
        $this->db->select('f.fid');
        $this->db->join(ENTITY_NAME_COMMENT_FLAG . ' f', $this->__entity_name . '.cid=f.comment_id and f.user_id=' . $this->__user->uid, 'LEFT');
        $this->db->select("u.full_name user_full_name, u.social_picture_url user_social_picture_url, concat('" . $this->config->base_url() . "', user_res.path, user_res.file_name) as user_picture_url, gender user_gender", false);
        $this->db->join(ENTITY_NAME_USER . ' u', $this->__entity_name . '.user_id=u.uid', 'LEFT');
        $this->db->join(ENTITY_NAME_RESOURCE . ' user_res', 'u.res_id = ' . 'user_res.rid', 'LEFT');

        $post_id = (int) $params['post_id'];
        if ($post_id > 0) {
            $this->db->where('post_id', $post_id);
        }
        
        $this->db->order_by($this->__entity_name . '.created_time', 'DESC');
    }

    public function delete_by_post($post_id) {
        return $this->db->delete($this->__entity_name, array('post_id' => $post_id));
    }
}

?>

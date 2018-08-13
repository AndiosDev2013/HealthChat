<?php

require_once 'Model.php';

class PostLikeModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_POST_LIKE, 'lid');
    }

    public function __set_filter($params) {
        //$this->__show_query = true;
        if (isset($params['post_id']) && $params['post_id'] > 0) {
            $this->db->select("u.full_name user_full_name, u.birthday user_birthday, u.gender user_gender, u.social_picture_url user_social_picture_url, concat('" . $this->config->base_url() . "', user_res.path, user_res.file_name) as user_picture_url", false);
            $this->db->join(ENTITY_NAME_USER . ' u', $this->__entity_name . '.user_id=u.uid', 'LEFT');
            $this->db->join(ENTITY_NAME_RESOURCE . ' user_res', 'u.res_id = ' . 'user_res.rid', 'LEFT');
            $this->db->where('post_id', $params['post_id']);
        }
    }

    public function get_by_post($post_id) {
        $this->db->where('post_id', $post_id);
        $this->db->where('user_id', $this->__user->uid);
        return $this->__valid_entity($this->db->get($this->__entity_name)->row());
    }

}

?>

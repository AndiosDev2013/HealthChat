<?php

require_once 'Model.php';

class MessageModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_MESSAGE, 'mid');
        $this->__set_use_created_time(true);
    }

    public function get_by_title($title) {
        $this->db->where('title', $title);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function entity($id) {
        $this->db->select("u.full_name user_full_name", false);
        $this->db->join(ENTITY_NAME_USER . ' u', $this->__entity_name . '.user_id=u.uid', 'LEFT');

        return parent::entity($id);
    }

    public function __set_filter($params) {
        /*
          $this->db->select("u.full_name user_full_name, u.social_picture_url user_social_picture_url, concat('" . $this->config->base_url() . "', user_res.path, user_res.file_name) as user_picture_url, gender user_gender", false);
          $this->db->join(ENTITY_NAME_USER . ' u', $this->__entity_name . '.user_id=u.uid', 'LEFT');
          $this->db->join(ENTITY_NAME_RESOURCE . ' user_res', 'u.res_id = ' . 'user_res.rid', 'LEFT');

          $post_id = (int) $params['post_id'];
          if ($post_id > 0) {
          $this->db->where('post_id', $post_id);
          }
         * 
         */

        $user_id = (int) $params['user_id'];
        $receiver_id = (int) $params['receiver_id'];
        if ($receiver_id > 0) {
            $arr = array($user_id, $receiver_id);
            $this->db->where_in('user_id', $arr);
            $this->db->where_in('receiver_id', $arr);
            $this->db->order_by($this->__entity_name . '.created_time', 'DESC');
        } else {
            $this->db->or_where(array('user_id' => $user_id, 'receiver_id' => $user_id));
            $this->db->join("(select max(mid) maxid from messages group by (if(user_id=$user_id, receiver_id, user_id))) m", $this->__entity_name . '.mid=m.maxid', 'INNER');
            $this->db->order_by($this->__entity_name . '.created_time', 'DESC');
        }
    }

    public function connected($user_id, $member_id) {
        $arr = array($user_id, $member_id);
        $this->db->where_in('user_id', $arr);
        $this->db->where_in('receiver_id', $arr);
        $this->db->limit(1, 0);
        $query = $this->db->get($this->__entity_name);
        $row = $query->row();
        if ($row && sizeof($row) > 0) {
            return $row->mid;
        } else {
            return 0;
        }
    }

}

?>

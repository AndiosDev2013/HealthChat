<?php

require_once 'Model.php';

class UserModel extends Model {

    public function __construct() {
        parent::__construct();
        $this->__config(ENTITY_NAME_USER, 'uid');
        $this->__set_use_resource(true);
        $this->__set_use_resource(true);
    }

    public function entity($id) {
        $time = gmmktime() - 3 * 60;
        $time = gmdate('Y-m-d H:i:s', $time);
        $this->db->select("IF(online_time > '$time', 1, 0) online", false);
        return parent::entity($id);
    }

    public function get($id) {
        $this->db->select('l.lid');
        $this->db->join(ENTITY_NAME_MEMBER_LIKE . ' l', $this->__entity_name . '.uid=l.member_id and l.user_id=' . $this->__user->uid, 'LEFT');
        return $this->entity($id);
    }

    public function __set_filter($params) {
        $time = gmmktime() - 3 * 60;
        $time = gmdate('Y-m-d H:i:s', $time);
        $this->db->select("IF(online_time > '$time', 1, 0) online", false);
        $this->db->where('level >=', USER_TYPE_ADMIN);
        $this->db->select('l.lid');
        $this->db->join(ENTITY_NAME_MEMBER_LIKE . ' l', $this->__entity_name . '.uid=l.member_id and l.user_id=' . $this->__user->uid, 'LEFT');
        $type = (int) $params['type'];
        if ($type == 1) {
            $diagnosed_with_array = array();
            if (!empty($this->__user->diagnosed_with_array)) {
                $diagnosed_with_array = explode(';', $this->__user->diagnosed_with_array);
            }
            if (count($diagnosed_with_array) > 0) {
                $field = "";
                $filter = "";
                foreach ($diagnosed_with_array as $diag) {
                    $diag = trim($diag);
                    if (!empty($field)) {
                        $field .= '+';
                        $filter .= ' OR ';
                    }
                    $field .= "(concat(';'," . $this->__entity_name . ".diagnosed_with_array,';') like '%;$diag;%')";
                    $filter .= "(concat(';'," . $this->__entity_name . ".diagnosed_with_array,';') like '%;$diag;%')";
                }
                $this->db->select('(' . $field . ') suggested_count', false);
                $this->db->order_by('suggested_count', 'DESC');
                $this->db->where('(' . $filter . ')');
                //$this->__show_query = true;
            } else {
                $this->db->where(false, null, false);
            }
        } else if ($type == 2) {
            $this->db->order_by('last_active_time', 'DESC');
        } else if ($type == 3) {
            $this->db->where('l.lid >', 0);
        }
    }

    public function search($limit = 0, $offset = 0, $params = null) {
        if ($this->__user)
            $this->db->where($this->__entity_name . '.uid <>', $this->__user->uid);
        return parent::search($limit, $offset, $params);
    }

    public function get_by_name_or_email($name_or_email) {
        $this->db->or_where(array('name' => $name_or_email, 'email' => $name_or_email));
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function get_by_name($name) {
        $this->db->where('name', $name);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function get_by_email($email) {
        $this->db->where('email', $email);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function get_by_token($token) {
        $this->db->where('token', $token);
        $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function exist_super_admin() {
        $this->db->where('level', 1);
        $query = $this->db->get($this->__entity_name);
        return $query->num_rows();
    }

    function change_password($uid, $pwd, $reset = 0) {
        if ($this->db->update($this->__entity_name, array('password' => $pwd, 'reset' => $reset), array($this->__pk_name => $uid)))
            return true;
        return false;
    }

    public function __save($entity) {
        $id = isset($entity[$this->__pk_name]) ? $entity[$this->__pk_name] : 0;
        if ($id <= 0 && $this->__is_rest())
            $entity['token'] = $this->generate_token($entity);
        return parent::__save($entity);
    }

    private function generate_token($entity) {
        $seed = '';
        if (isset($entity['name']))
            $seed .= $entity['name'];
        if (isset($entity['email']))
            $seed .= $entity['email'];
        if (isset($entity['pwd']))
            $seed .= $entity['pwd'];

        return md5($seed);
    }

    function gen_new_pass() {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        return substr(str_shuffle($chars), 0, 8);
    }

    public function online($uid) {
        $time = gmdate('Y-m-d H:i:s');
        $this->save(array('uid' => $uid, 'online_time' => $time));
    }

}

?>

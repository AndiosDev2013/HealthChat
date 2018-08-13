<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Model extends CI_Model {

    protected $__entity_name;
    protected $__pk_name;
    protected $__user = null;
    protected $__rest_flag = false;
    protected $__show_query = false;
    protected $__use_created_time = false;
    protected $__use_resource = false;

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function __config($entity_name, $pk_name) {
        $this->__entity_name = $entity_name;
        $this->__pk_name = $pk_name;
    }

    public function __set_entity_name($name) {
        $this->__entity_name = $name;
    }

    public function __set_pk_name($name) {
        $this->__pk_name = $name;
    }

    public function __set_user($user) {
        $this->__user = $user;
    }

    public function __set_rest_flag($rest) {
        $this->__rest_flag = $rest;
    }

    public function __is_rest() {
        return $this->__rest_flag;
    }

    public function __set_use_created_time($use) {
        $this->__use_created_time = $use;
    }

    public function __set_use_resource($use) {
        $this->__use_resource = $use;
    }

    function __total_count($params = null) {
        $this->__set_filter($params);
        $query = $this->db->get($this->__entity_name);
        return $query->num_rows();
    }

    public function __search($limit = 0, $offset = 0, $params = null) {
        $this->db->select($this->__entity_name . ".*");
        $this->__set_filter($params);
        if ($limit > 0) {
            $this->db->limit($limit, $offset);
        }

        if ($this->__show_query) {
            $this->db->save_queries = true;
            $query = $this->db->get($this->__entity_name);
            print_r($this->db->queries);
        }
        else
            $query = $this->db->get($this->__entity_name);

        return $query->result();
    }

    public function search($limit = 0, $offset = 0, $params = null) {
        if ($this->__use_resource) {
            return $this->__search_with_resource($limit, $offset, $params);
        }
        return $this->__search($limit, $offset, $params);
    }

    public function __search_with_resource($limit = 0, $offset = 0, $params = null) {
        
        $this->db->select("concat('" . $this->config->base_url() . "', res.path, res.file_name) as picture_url", false);
        $this->db->join(ENTITY_NAME_RESOURCE . ' res', $this->__entity_name . '.res_id = ' . 'res.rid', 'LEFT');
        return $this->__search($limit, $offset, $params);
    }

    public function __set_filter($params) {
        
    }

    public function __entity($id) {
        $this->db->select($this->__entity_name . ".*");
        $this->db->where($this->__pk_name, $id);
        if ($this->__show_query) {
            $this->db->save_queries = true;
            $query = $this->db->get($this->__entity_name);
            print_r($this->db->queries);
        }
        else
            $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function entity($id) {
        if ($this->__use_resource) {
            return $this->__entity_with_resource ($id);
        }
        return $this->__entity($id);
    }

    public function __entity_with_resource($id) {
        $this->db->select($this->__entity_name . ".*, concat('" . $this->config->base_url() . "', res.path, res.file_name) as picture_url", false);
        $this->db->where($this->__pk_name, $id);
        $this->db->join(ENTITY_NAME_RESOURCE . ' res', $this->__entity_name . '.res_id = ' . 'res.rid', 'LEFT');
        if ($this->__show_query) {
            $this->db->save_queries = true;
            $query = $this->db->get($this->__entity_name);
            print_r($this->db->queries);
        }
        else
            $query = $this->db->get($this->__entity_name);
        return $this->__valid_entity($query->row());
    }

    public function __valid_entity($entity) {
        if ($entity && sizeof($entity) > 0)
            return $entity;
        else
            return null;
    }

    public function __save($entity) {
        $id = isset($entity[$this->__pk_name]) ? (int) $entity[$this->__pk_name] : 0;
        $ret = FALSE;
        if ($id > 0)
            $ret = $this->db->update($this->__entity_name, $entity, array($this->__pk_name => $id));
        else {
            if ($this->__use_created_time) {
                $entity['created_time'] = gmdate('Y-m-d H:i:s');
            }
            $ret = $this->db->insert($this->__entity_name, $entity);
        }
        return $ret == TRUE ? ($id <= 0 ? $this->db->insert_id() : $id) : 0;
    }

    public function save($entity) {
        return $this->__save($entity);
    }

    public function __delete($id) {
        return $this->db->delete($this->__entity_name, array($this->__pk_name => $id));
    }

    public function delete($id) {
        return $this->__delete($id);
    }

}

?>

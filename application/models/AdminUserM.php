<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of AdminUser
 *
 * @author Vincent
 */
class AdminUserM extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->load->database();
    }

    function u_insert($arr) {
        $this->db->insert('user', $arr);
    }

    function u_update($id, $arr) {
        $this->db->where('uid', $id);
        $this->db->update('user', $arr);
    }

    function u_del($id) {
        $this->db->where('uid', $id);
        $this->db->delete('user');
    }

    function u_select($name) {
        $this->db->where('uname', $name);
        $this->db->select('*');
        $query = $this->db->get('admin_user');
        return $query->result();
    }

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_model extends CI_Model {

    public function count_customers() {
        return $this->db->count_all('pelanggan');
    }

    public function count_vehicles() {
        return $this->db->count_all('kendaraan');
    }

    public function count_services() {
        return $this->db->count_all('transaksi');
    }

    public function get_non_admin_users() {
        return $this->db->where('role_id !=', 1)
                        ->get('users')
                        ->result();
    }

    public function insert_manager($user_payload) {
        return $this->db->insert('users', $user_payload);
    }

    public function update_user_password($id_user, $hashed_password) {
        $this->db->where('id', $id_user); 
        return $this->db->update('users', ['password' => $hashed_password]);
    }
}

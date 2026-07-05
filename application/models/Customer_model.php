<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customer_model extends CI_Model {

    public function get_profile_by_user_id($user_id) {
        return $this->db->get_where('pelanggan', ['user_id' => $user_id])->row();
    }

    public function save_vehicle($data) {
        return $this->db->insert('kendaraan', $data);
    }

    public function fetch_my_garage($id_pelanggan) {
        return $this->db->get_where('kendaraan', ['id_pelanggan' => $id_pelanggan])->result();
    }

    public function save_reservation($data) {
        return $this->db->insert('transaksi', $data);
    }

    public function get_customer_service_history($id_pelanggan) {
        return $this->db->select('transaksi.*, kendaraan.brand_kendaraan, kendaraan.model_kendaraan')
                        ->from('transaksi')
                        ->join('kendaraan', 'transaksi.no_plat = kendaraan.no_plat')
                        ->where('kendaraan.id_pelanggan', $id_pelanggan)
                        ->order_by('transaksi.id_transaksi', 'DESC')
                        ->get()->result();
    }
    
    
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model
{

    public function insert_customer_user($user_payload)
    {
        $this->db->insert('users', $user_payload);
        return $this->db->insert_id(); 
    }

    public function get_user_by_email($email)
    {
        return $this->db->get_where('users', ['email' => $email])->row();
    }

    public function register_user($user_payload)
    {
        $this->db->insert('users', $user_payload);
        return $this->db->insert_id();
    }

    public function update_password($user_id, $new_hashed_password)
    {
        $this->db->where('id', $user_id);
        return $this->db->update('users', ['password' => $new_hashed_password]);
    }

    public function get_profile_by_user_id($user_id)
    {
        return $this->db->get_where('pelanggan', ['user_id' => $user_id])->row();
    }

    public function fetch_my_garage($id_pelanggan)
    {
        return $this->db->get_where('kendaraan', ['id_pelanggan' => $id_pelanggan])->result();
    }

    public function get_customer_service_history($id_pelanggan)
    {
        return $this->db->select('transaksi.*, mekanik.nama_mekanik, kendaraan.brand_kendaraan, kendaraan.model_kendaraan')
                        ->from('transaksi')
                        ->join('kendaraan', 'transaksi.no_plat = kendaraan.no_plat', 'left')
                        ->join('mekanik', 'transaksi.id_mekanik = mekanik.id_mekanik', 'left')
                        ->where('kendaraan.id_pelanggan', $id_pelanggan)
                        ->order_by('transaksi.id_transaksi', 'DESC')
                        ->get()->result();
    }

    public function save_vehicle($data)
    {
        return $this->db->insert('kendaraan', $data);
    }

    public function count_available_mechanics()
    {
        return $this->db->where('status_ketersediaan', 'Tersedia')
                        ->count_all_results('mekanik');
    }

    public function get_available_mechanics()
    {
        return $this->db->get_where('mekanik', ['status_ketersediaan' => 'Tersedia'])->result();
    }

    public function count_mechanic_jobs_today($id_mekanik, $today)
    {
        return $this->db->where('id_mekanik', $id_mekanik)
                        ->where('tanggal_servis', $today)
                        ->where_in('status_servis', ['Initial', 'Inspeksi Awal', 'Pengerjaan'])
                        ->count_all_results('transaksi');
    }

    public function save_reservation($data)
    {
        return $this->db->insert('transaksi', $data);
    }

    public function get_transaction_by_id($id_transaksi)
    {
        return $this->db->get_where('transaksi', ['id_transaksi' => $id_transaksi])->row();
    }

    public function update_payment_proof($id_transaksi, $proof_text)
    {
        $this->db->where('id_transaksi', $id_transaksi);
        return $this->db->update('transaksi', [
            'bukti_pembayaran' => $proof_text,
            'status_pembayaran' => 'Menunggu Verifikasi'
        ]);
    }

    public function insert_customer_profile($data)
    {
        return $this->db->insert('pelanggan', $data);
    }
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager_model extends CI_Model
{



    public function insert_mechanic_roster($user_payload, $nama_mekanik, $keahlian)
    {
        $this->db->trans_start();


        $this->db->insert('users', $user_payload);
        $new_worker_user_id = $this->db->insert_id();


        $mekanik_payload = [
            'user_id' => $new_worker_user_id,
            'nama_mekanik' => $nama_mekanik,
            'keahlian' => $keahlian,
            'status_ketersediaan' => 'Tersedia'
        ];
        $this->db->insert('mekanik', $mekanik_payload);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }

  public function get_all_transactions()
    {
       
        return $this->db->select('transaksi.*, pelanggan.nama_pelanggan, pelanggan.alamat, kendaraan.brand_kendaraan, kendaraan.model_kendaraan, mekanik.nama_mekanik')
                        ->from('transaksi')
                        ->join('kendaraan', 'transaksi.no_plat = kendaraan.no_plat', 'left')
                        ->join('pelanggan', 'kendaraan.id_pelanggan = pelanggan.id_pelanggan', 'left')
                        ->join('mekanik', 'transaksi.id_mekanik = mekanik.id_mekanik', 'left')
                        ->order_by('transaksi.id_transaksi', 'DESC')
                        ->get()->result();
    }




    public function get_all_spareparts()
    {
        return $this->db->get('suku_cadang')->result();
    }


    public function insert_sparepart($data)
    {
        return $this->db->insert('suku_cadang', $data);
    }


    public function get_part_by_id($id_part)
    {
        return $this->db->get_where('suku_cadang', ['id_part' => $id_part])->row();
    }


    public function delete_part($id_part)
    {
        return $this->db->where('id_part', $id_part)->delete('suku_cadang');
    }


    public function update_part($id_part, $data)
    {
        return $this->db->where('id_part', $id_part)->update('suku_cadang', $data);
    }
    public function get_pending_payments()
    {
        return $this->db->select('transaksi.*, pelanggan.nama_pelanggan, kendaraan.brand_kendaraan, kendaraan.model_kendaraan')
            ->from('transaksi')
            ->join('kendaraan', 'transaksi.no_plat = kendaraan.no_plat', 'left')
            ->join('pelanggan', 'kendaraan.id_pelanggan = pelanggan.id_pelanggan', 'left')
            ->where('transaksi.status_pembayaran', 'Menunggu Verifikasi')
            ->get()->result();
    }

    public function update_payment_status($id_transaksi, $status)
    {
        $this->db->where('id_transaksi', $id_transaksi);
        return $this->db->update('transaksi', ['status_pembayaran' => $status]);
    }
    public function delete_part_with_relations($id_part)
    {
        $this->db->trans_start();
        $this->db->where('id_part', $id_part)->delete('detail_suku_cadang');
        $this->db->where('id_part', $id_part)->delete('suku_cadang');
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    

    public function update_mechanic($id_mekanik, $data)
    {
        return $this->db->where('id_mekanik', $id_mekanik)->update('mekanik', $data);
    }




    public function get_all_mechanics()
    {
        return $this->db->get('mekanik')->result();
    }

    public function get_mechanic_by_id($id_mekanik)
    {
        return $this->db->get_where('mekanik', ['id_mekanik' => $id_mekanik])->row();
    }

    public function delete_mechanic($id_mekanik)
    {
        return $this->db->where('id_mekanik', $id_mekanik)->delete('mekanik');
    }

    
}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mechanic_model extends CI_Model {

    public function get_mechanic_profile_by_user($user_id) {
        return $this->db->get_where('mekanik', ['user_id' => $user_id])->row();
    }

    public function fetch_assigned_workshop_schedule($id_mekanik) {
        $this->db->select('transaksi.*, kendaraan.brand_kendaraan, kendaraan.model_kendaraan, pelanggan.nama_pelanggan');
        $this->db->from('transaksi');
        $this->db->join('kendaraan', 'transaksi.no_plat = kendaraan.no_plat', 'left');
        $this->db->join('pelanggan', 'kendaraan.id_pelanggan = pelanggan.id_pelanggan', 'left');
        $this->db->where('transaksi.id_mekanik', $id_mekanik);
        $this->db->order_by('transaksi.tanggal_servis', 'ASC');
        return $this->db->get()->result();
    }

    public function fetch_assigned_workshop_schedule_today($id_mekanik, $today) {
        $this->db->select('transaksi.*, kendaraan.brand_kendaraan, kendaraan.model_kendaraan, pelanggan.nama_pelanggan');
        $this->db->from('transaksi');
        $this->db->join('kendaraan', 'transaksi.no_plat = kendaraan.no_plat', 'left');
        $this->db->join('pelanggan', 'kendaraan.id_pelanggan = pelanggan.id_pelanggan', 'left');
        $this->db->where('transaksi.id_mekanik', $id_mekanik);
        $this->db->where('transaksi.tanggal_servis', $today);
        $this->db->order_by('transaksi.tanggal_servis', 'ASC');
        return $this->db->get()->result();
    }
    public function get_sparepart_by_name($nama_part)
    {
        return $this->db->get_where('suku_cadang', ['nama_part' => $nama_part])->row();
    }

    public function reschedule_delayed_job($id_transaksi) {
        $job = $this->db->get_where('transaksi', ['id_transaksi' => $id_transaksi])->row();
        if ($job) {
            $new_date = date('Y-m-d', strtotime($job->tanggal_servis . ' +1 day'));
            $this->db->where('id_transaksi', $id_transaksi)->update('transaksi', [
                'tanggal_servis' => $new_date,
                'status_servis'  => 'Initial', 
                'detail_progres' => 'Perbaikan di tunda'
            ]);
        }
    }

    public function get_all_spareparts() {
        return $this->db->get('suku_cadang')->result();
    }

    public function get_transaction_by_id($id_transaksi) {
        return $this->db->get_where('transaksi', ['id_transaksi' => $id_transaksi])->row();
    }

    public function get_sparepart_by_id($id_part) {
        return $this->db->get_where('suku_cadang', ['id_part' => $id_part])->row();
    }

    public function update_repair_progress($id, $status_servis, $detail_progres) {
        $this->db->where('id_transaksi', $id)->update('transaksi', [
            'status_servis' => $status_servis,
            'detail_progres' => $detail_progres
        ]);
    }

    public function update_mechanic_availability($id_mekanik, $status) {
        $this->db->where('id_mekanik', $id_mekanik)->update('mekanik', ['status_ketersediaan' => $status]);
    }

     public function log_sparepart_usage($id_transaksi, $id_part, $qty, $current_stok, $cost_addition) {
        $this->db->trans_start();

        $this->db->insert('detail_suku_cadang', [
            'id_transaksi'     => $id_transaksi,
            'id_part'          => $id_part,
            'jumlah_digunakan' => $qty
        ]);

        $hitung_stok_baru = (int)$current_stok - (int)$qty;
        $this->db->where('id_part', $id_part)->update('suku_cadang', ['stok' => $hitung_stok_baru]);

        $current_tx = $this->db->select('total_biaya')->get_where('transaksi', ['id_transaksi' => $id_transaksi])->row();
        $biaya_lama = $current_tx ? (int)$current_tx->total_biaya : 0;

        $biaya_baru = $biaya_lama + (int)$cost_addition;

        $this->db->where('id_transaksi', $id_transaksi)->update('transaksi', ['total_biaya' => $biaya_baru]);

        $this->db->trans_complete();
        return $this->db->trans_status();
    }
    public function process_delay_assignment($id_transaksi, $job, $target_mechanic_id) {
        $this->db->trans_start();
        $this->reschedule_delayed_job($id_transaksi);
        $this->db->where('id_transaksi', $id_transaksi)->update('transaksi', [
            'status_servis' => 'Tertunda',
            'detail_progres' => 'Perbaikan ditunda oleh mekanik ke jadwal berikutnya.'
        ]);

        if (!empty($target_mechanic_id)) {
            if ($job && empty($job->id_mekanik)) {
                $this->db->where('id_transaksi', $id_transaksi)->update('transaksi', ['id_mekanik' => $target_mechanic_id]);
            }
            $this->db->where('id_mekanik', $target_mechanic_id)->update('mekanik', ['status_ketersediaan' => 'Tersedia']);
        }
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function undo_repair_status($id_transaksi) {
        $this->db->where('id_transaksi', $id_transaksi)->update('transaksi', [
            'status_servis' => 'Pengerjaan',
            'detail_progres' => 'Status perbaikan diubah'
        ]);
    }
}

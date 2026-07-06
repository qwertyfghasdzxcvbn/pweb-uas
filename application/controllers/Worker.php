<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Worker extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 2) {
            redirect('auth/process_login');
        }
        $this->load->library('form_validation');
        $this->load->model('Mechanic_model');
    }

    public function dashboard()
    {
        $user_id = $this->session->userdata('user_id');
        $profile = $this->Mechanic_model->get_mechanic_profile_by_user($user_id);

        if (!$profile) {
            show_error('Profile data tidak ditemukan.');
        }

        $data['title'] = 'Dashboard Mekanik';
        $data['profile'] = $profile;
        $data['parts'] = $this->Mechanic_model->get_all_spareparts();

        $view_filter = $this->input->get('view', TRUE);
        $today = date('Y-m-d');

        if ($view_filter === 'all') {
            $data['schedules'] = $this->Mechanic_model->fetch_assigned_workshop_schedule($profile->id_mekanik);
            $data['current_view'] = 'all';
        } else {
            $data['schedules'] = $this->Mechanic_model->fetch_assigned_workshop_schedule_today($profile->id_mekanik, $today);
            $data['current_view'] = 'today';
        }

        $data['subview'] = 'pages/Mechanic/dashboard';
        $this->load->view('layout/main', $data);
    }

    public function update_repair_progress()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_transaksi', 'Ref ID', 'required|numeric');
        $this->form_validation->set_rules('status_servis', 'Work Status Metric', 'required|trim');
        $this->form_validation->set_rules('detail_progres', 'Operational Notes', 'required|trim');

        if ($this->form_validation->run() == TRUE) {
            $id = $this->input->post('id_transaksi');
            $status_servis = $this->input->post('status_servis', TRUE);
            $detail_progres = $this->input->post('detail_progres', TRUE);

            $this->Mechanic_model->update_repair_progress($id, $status_servis, $detail_progres);
            $job = $this->Mechanic_model->get_transaction_by_id($id);

            if ($job && !empty($job->id_mekanik)) {
                if ($status_servis === 'Pengerjaan') {
                    $this->Mechanic_model->update_mechanic_availability($job->id_mekanik, 'Tidak Tersedia');
                } elseif ($status_servis === 'Selesai' || $status_servis === 'Dibatalkan') {
                    $this->Mechanic_model->update_mechanic_availability($job->id_mekanik, 'Tersedia');
                }
            }

            $this->session->set_flashdata('success', 'Status perbaikan diperbarui');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui status operasional.');
        }
        redirect('Worker/dashboard');
    }

             public function log_used_sparepart() 
    {
        $id_transaksi = $this->input->post('id_transaksi');
        $nama_part    = $this->input->post('nama_part_display', TRUE); 
        $qty          = $this->input->post('qty', TRUE);

        if (empty($qty) || $qty < 1) {
            $this->session->set_flashdata('error', 'Jumlah suku cadang yang digunakan minimal harus 1 unit!');
            redirect('Worker/dashboard');
        }

        if (empty($nama_part)) {
            $this->session->set_flashdata('error', 'Silahkan pilih komponen terlebih dahulu!');
            redirect('Worker/dashboard');
        }

        $part = $this->Mechanic_model->get_sparepart_by_name($nama_part);

        if ($part) {
            if ($qty > $part->stok) {
                $this->session->set_flashdata('error', '⚠️ Gagal: Jumlah input (' . $qty . ') melebihi sisa stok gudang yang tersedia (' . $part->stok . ' unit)!');
                redirect('Worker/dashboard');
            }

            if ($part->stok >= $qty) {
                
                // KUNCI SAKTI: Ubah nilai decimal database langsung menjadi Integer bulat (Rp 200.000,00 -> Rp 200.000) [▲]
                $harga_bulat = (int)$part->harga_jual;
                
                // Lakukan perkalian murni antar angka bulat [▲]
                $cost_addition = $harga_bulat * (int)$qty;
                
                $this->Mechanic_model->log_sparepart_usage($id_transaksi, $part->id_part, $qty, $part->stok, $cost_addition);
                $this->session->set_flashdata('success', 'Penggunaan sparepart berhasil di rekam');
            } else {
                $this->session->set_flashdata('error', 'Inventory stok sudah habis!');
            }
        } else {
            $this->session->set_flashdata('error', 'Komponen tidak ditemukan! Pastikan Anda memilih dari daftar.');
        }
        redirect('Worker/dashboard');
    }


    public function delay_assignment($id_transaksi)
    {
        if (!empty($id_transaksi) && is_numeric($id_transaksi)) {
            $active_user_id = $this->session->userdata('user_id');
            $current_mechanic = $this->Mechanic_model->get_mechanic_profile_by_user($active_user_id);
            $job = $this->Mechanic_model->get_transaction_by_id($id_transaksi);

            $target_mechanic_id = null;
            if ($job && !empty($job->id_mekanik)) {
                $target_mechanic_id = $job->id_mekanik;
            } elseif ($current_mechanic) {
                $target_mechanic_id = $current_mechanic->id_mekanik;
            }

            $status = $this->Mechanic_model->process_delay_assignment($id_transaksi, $job, $target_mechanic_id);

            if ($status === FALSE) {
                $this->session->set_flashdata('error', 'Gagal memproses sinkronisasi penundaan.');
            } else {
                $this->session->set_flashdata('success', 'Perbaikan berhasil ditunda. Status ketersediaan mekanik kembali Tersedia!');
            }
        } else {
            $this->session->set_flashdata('error', 'ID Transaksi tidak valid.');
        }
        redirect('Worker/dashboard');
    }

    public function undo_repair_status($id_transaksi)
    {
        if (!empty($id_transaksi) && is_numeric($id_transaksi)) {
            $this->Mechanic_model->undo_repair_status($id_transaksi);
            $job = $this->Mechanic_model->get_transaction_by_id($id_transaksi);

            if ($job && !empty($job->id_mekanik)) {
                $this->Mechanic_model->update_mechanic_availability($job->id_mekanik, 'Tidak Tersedia');
            }

            $this->session->set_flashdata('success', 'Perbaikan di mulai kembali.');
        } else {
            $this->session->set_flashdata('error', 'Invalid reference');
        }
        redirect('Worker/dashboard');
    }
}

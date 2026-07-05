<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 3) {
            redirect('auth/process_login');
        }
        $this->load->library('form_validation');
        $this->load->model('User_model');
    }

    public function dashboard()
    {
        $user_id = $this->session->userdata('user_id');
        $profile = $this->User_model->get_profile_by_user_id($user_id);

        if (!$profile) {
            redirect('User/complete_profile');
        }

        $data['title'] = 'Customer Dashboard';
        $data['vehicles'] = $this->User_model->fetch_my_garage($profile->id_pelanggan);
        $data['services'] = $this->User_model->get_customer_service_history($profile->id_pelanggan);

        $data['subview'] = 'pages/user/dashboard';
        $this->load->view('layout/main', $data);
    }

    public function add_vehicle_process()
    {
        $this->form_validation->set_rules('no_plat', 'Plate Number', 'required|is_unique[kendaraan.no_plat]|trim');
        $this->form_validation->set_rules('brand', 'Brand', 'required|trim');
        $this->form_validation->set_rules('model', 'Model', 'required|trim');
        $this->form_validation->set_rules('year', 'Year', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Add New Vehicle';
            $data['subview'] = 'pages/user/vehicle';
            $this->load->view('layout/main', $data);
        } else {
            $user_id = $this->session->userdata('user_id');
            $profile = $this->User_model->get_profile_by_user_id($user_id);

            $payload = [
                'no_plat' => $this->input->post('no_plat', TRUE),
                'id_pelanggan' => $profile->id_pelanggan,
                'brand_kendaraan' => $this->input->post('brand', TRUE),
                'model_kendaraan' => $this->input->post('model', TRUE),
                'tahun_produksi' => $this->input->post('year', TRUE)
            ];
            $this->User_model->save_vehicle($payload);
            $this->session->set_flashdata('success', 'Vehicle saved successfully!');
            redirect('User/dashboard');
        }
    }

    public function book_service_process()
    {
        $user_id = $this->session->userdata('user_id');
        $profile = $this->User_model->get_profile_by_user_id($user_id);

        $this->form_validation->set_rules('no_plat', 'Vehicle', 'required');
        $this->form_validation->set_rules('tanggal_servis', 'Date', 'required');
        $this->form_validation->set_rules('keluhan', 'Complaint', 'required|trim');

        $input_date = $this->input->post('tanggal_servis');
        $today = date('Y-m-d');

        if ($this->form_validation->run() == TRUE && $input_date !== $today) {
            $this->session->set_flashdata('error', 'Tanggal yang anda pilih adalah hari kemarin!');
            redirect('User/dashboard');
        }

        $available_mechanics_count = $this->User_model->count_available_mechanics();

        if ($available_mechanics_count == 0) {
            $this->session->set_flashdata('error', 'Bengkel Penuh!');
            redirect('User/dashboard');
        }

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Book Appointment';
            $data['vehicles'] = $this->User_model->fetch_my_garage($profile->id_pelanggan);
            $data['subview'] = 'pages/user/reserve';
            $this->load->view('layout/main', $data);
        } else {
            $today = date('Y-m-d');
            $mechanics_list = $this->User_model->get_available_mechanics();
            $assigned_id = null;

            foreach ($mechanics_list as $mechanic) {
                $jobs_today_count = $this->User_model->count_mechanic_jobs_today($mechanic->id_mekanik, $today);

                if ($jobs_today_count == 0) {
                    $assigned_id = $mechanic->id_mekanik;
                    break;
                }
            }

            if ($assigned_id === null) {
                $this->session->set_flashdata('error', 'Bengkel Penuh!');
                redirect('User/dashboard');
            }

            $payload = [
                'no_plat' => $this->input->post('no_plat', TRUE),
                'id_mekanik' => $assigned_id,
                'tanggal_servis' => $today,
                'keluhan_awal' => $this->input->post('keluhan', TRUE),
                'status_servis' => 'Initial',
                'status_pembayaran' => 'Belum Lunas'
            ];

            $this->User_model->save_reservation($payload);
            $this->session->set_flashdata('success', 'Appointment successfully scheduled and allocated!');
            redirect('User/dashboard');
        }
    }

    public function complete_profile()
    {
        $data['title'] = 'Complete Customer Profile Metadata Records';
        $data['subview'] = 'pages/user/profile';
        $this->load->view('layout/main', $data);
    }

    public function submit_payment_proof()
    {
        $this->load->library('form_validation');
        $this->form_validation->set_rules('id_transaksi', 'Transaction ID', 'required|numeric');
        $this->form_validation->set_rules('proof_ref', 'Payment Reference', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Masukkan lagi nomor transaksi dari bank anda.');
            redirect('User/dashboard');
        } else {
            $id_transaksi = $this->input->post('id_transaksi');
            $transaksi = $this->User_model->get_transaction_by_id($id_transaksi);

            if (!$transaksi || $transaksi->status_servis !== 'Selesai') {
                $this->session->set_flashdata('error', 'Anda bisa membayar di saat perbaikan sudah selesai');
                redirect('User/dashboard');
            }

            $proof_text = $this->input->post('proof_ref', TRUE);
            $this->User_model->update_payment_proof($id_transaksi, $proof_text);

            $this->session->set_flashdata('success', 'Bukti pembayaran terkirim, silahkan tunggu untuk di verifikasi');
            redirect('User/dashboard');
        }
    }

    public function complete_profile_action()
    {
        $this->form_validation->set_rules('no_telepon', 'Phone Number', 'required|numeric|min_length[9]');
        $this->form_validation->set_rules('alamat', 'Home Address', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->complete_profile();
        } else {
            $user_id = $this->session->userdata('user_id');
            $profile_payload = [
                'user_id' => $user_id,
                'no_telepon' => $this->input->post('no_telepon', TRUE),
                'alamat' => $this->input->post('alamat', TRUE)
            ];

            $this->User_model->insert_customer_profile($profile_payload);
            $this->session->set_flashdata('success', 'Profil dimasukkan, selamat datang');
            redirect('User/dashboard');
        }
    }
}

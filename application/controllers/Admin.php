<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        // Agar hanya user bertipe admin yang bisa akses
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
            redirect('auth/process_login');
        }
        // Load the new specialized model
        $this->load->model('Manager_model');
    }
    // Dashboard
    public function dashboard() {
        $data['title'] = 'Dashboard Admin';
        
        // General metrics tallies for the admin panel cards
        $data['total_customers'] = $this->db->count_all('pelanggan');
        $data['total_vehicles']  = $this->db->count_all('kendaraan');
        $data['total_services']  = $this->db->count_all('transaksi');

        $data['subview'] = 'pages/admin/dashboard';
        $this->load->view('layout/main', $data);
    }

    // Tambah manager baru
    public function create_manager_process() {
        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|is_unique[users.email]|trim');
        $this->form_validation->set_rules('password', 'Login Password', 'required|min_length[6]');

        if ($this->form_validation->run() == FALSE) {
            $this->dashboard(); 
        } else {
            $manager_payload = [
                'name'     => $this->input->post('name', TRUE),
                'email'    => $this->input->post('email', TRUE),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role_id'  => 4 // Role id manager
            ];

            $this->Manager_model->insert_manager($manager_payload);
            $this->session->set_flashdata('success', 'Manager baru ditambahkan');
            redirect('admin/dashboard');
        }
    }
}

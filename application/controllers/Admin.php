<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
            redirect('auth/process_login');
        }
        $this->load->library('form_validation');
        $this->load->model('Admin_model');
    }

    public function dashboard() {
        $data['title'] = 'Dashboard Admin';
        
        $data['total_customers'] = $this->Admin_model->count_customers();
        $data['total_vehicles']  = $this->Admin_model->count_vehicles();
        $data['total_services']  = $this->Admin_model->count_services();
        $data['all_users']       = $this->Admin_model->get_non_admin_users();
        $data['current_admin_id'] = $this->session->userdata('user_id');

        $data['subview'] = 'pages/admin/dashboard';
        $this->load->view('layout/main', $data);
    }

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
                'role_id'  => 4 
            ];

            $this->Admin_model->insert_manager($manager_payload);
            $this->session->set_flashdata('success', 'Manager baru ditambahkan');
            redirect('Admin/dashboard');
        }
    }

    public function change_user_password_process() {
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 1) {
            $this->session->set_flashdata('error', 'Akses Ditolak');
            redirect('auth/process_login');
        }

        $this->form_validation->set_rules('id_user', 'Target User ID', 'required|numeric');
        $this->form_validation->set_rules('new_password', 'New Password', 'required|min_length[6]|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Minimal 6 Karakter');
            redirect('Admin/dashboard');
        } else {
            $id_user = $this->input->post('id_user');
            $raw_password = $this->input->post('new_password');
            $hashed_password = password_hash($raw_password, PASSWORD_BCRYPT);
            
            $this->Admin_model->update_user_password($id_user, $hashed_password);

            $current_logged_in_admin_id = $this->session->userdata('user_id');
            if ($id_user == $current_logged_in_admin_id) {
                $this->session->set_flashdata('success', 'Password admin diganti');
            } else {
                $this->session->set_flashdata('success', 'Password Pengguna Diganti');
            }

            redirect('Admin/dashboard');
        }
    }
}

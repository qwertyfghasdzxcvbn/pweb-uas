<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    // Fungsi Login
    public function process_login() {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Login'; 
            $data['subview'] = 'pages/auth/login';
            $this->load->view('layout/main', $data);
        } else {
            $email = $this->input->post('email', TRUE);
            $password = $this->input->post('password', TRUE);
            
            // Mencari email 
            $user = $this->User_model->get_user_by_email($email);

            if ($user && password_verify($password, $user->password)) {
                $session_payload = [
                    'user_id'   => $user->id,
                    'role_id'   => $user->role_id,
                    'name'      => $user->name,
                    'logged_in' => TRUE
                ];

                // Jika role id adalah 3 atau user maka preload data profile mereka
                if ($user->role_id == 3) {
                    $this->load->model('Customer_model');
                    $profile = $this->Customer_model->get_profile_by_user_id($user->id);
                    if ($profile) { $session_payload['id_pelanggan'] = $profile->id_pelanggan; }
                }

                $this->session->set_userdata($session_payload);
                $this->_redirect_by_role($user->role_id);
            } else {
                $this->session->set_flashdata('error', 'Email atau Password Salah!');
                redirect('auth/process_login');
            }
        }
    }

    // Registrasi Pelanggan
    public function process_registration() {
        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[3]');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Create Customer Account'; 
            $data['subview'] = 'pages/auth/register';
            $this->load->view('layout/main', $data);
        } else {
            // Jike validasi sukses maka mulai proses penambahan data ke database
            $user_payload = [
                'name'     => $this->input->post('name', TRUE),
                'email'    => $this->input->post('email', TRUE),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role_id'  => 3 // Agar semua user yang dibuat tetap bertipe pelanggan
            ];

           
            $this->db->insert('users', $user_payload);
            $new_user_id = $this->db->insert_id();

            // Add langsung ke session database agar sang pengguna dapat langsung menambahkan profil mereka
            $session_payload = [
                'user_id'   => $new_user_id,
                'role_id'   => 3,
                'name'      => $user_payload['name'],
                'logged_in' => TRUE
            ];
            $this->session->set_userdata($session_payload);

            // Redirect ke dashboard dan langsung memulai proses tambahan profil
            $this->session->set_flashdata('success', 'Registrasi Akun Berhasil');
            redirect('user/dashboard');
        }
    }

    // Traffic manager
    private function _redirect_by_role($role_id) {
        if ($role_id == 1)      { redirect('admin/dashboard'); }
        elseif ($role_id == 2)  { redirect('worker/dashboard'); }
        elseif ($role_id == 3)  { redirect('user/dashboard'); }
        elseif ($role_id == 4)  { redirect('manager/dashboard'); }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/process_login');
    }
}

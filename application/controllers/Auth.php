<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('form_validation');
        $this->load->model('User_model');
    }

    public function process_login() {
          $this->form_validation->set_error_delimiters(
            '<p class="mt-1.5 text-[11px] font-normal text-rose-600 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded shadow-3xs block text-left leading-normal w-full box-border">', 
            '</p>'
        );
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Login'; 
            $data['subview'] = 'pages/auth/login';
            $this->load->view('layout/main', $data);
        } else {
            $email = $this->input->post('email', TRUE);
            $password = $this->input->post('password', TRUE);
            
            $user = $this->User_model->get_user_by_email($email);

            if ($user && password_verify($password, $user->password)) {
                $session_payload = [
                    'user_id'   => $user->id,
                    'role_id'   => $user->role_id,
                    'name'      => $user->name,
                    'logged_in' => TRUE
                ];

                if ($user->role_id == 3) {
                    $this->load->model('Customer_model');
                    $profile = $this->Customer_model->get_profile_by_user_id($user->id);
                    if ($profile) { 
                        $session_payload['id_pelanggan'] = $profile->id_pelanggan; 
                    }
                }

                $this->session->set_userdata($session_payload);
                $this->_redirect_by_role($user->role_id);
            } else {
                $this->session->set_flashdata('error', 'Email atau Password Salah!');
                redirect('Auth/process_login');
            }
        }
    }

    public function process_registration() {
        $this->form_validation->set_error_delimiters(
            '<p class="mt-1.5 text-[11px] text-rose-600 bg-rose-50 border border-rose-200 px-2.5 py-1 rounded shadow-3xs block text-left leading-normal w-full box-border">', 
            '</p>'
        );

        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|is_unique[users.email]|trim');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Create Customer Account'; 
            $data['subview'] = 'pages/auth/register';
            $this->load->view('layout/main', $data);
        } else {
            $user_payload = [
                'name'     => $this->input->post('name', TRUE),
                'email'    => $this->input->post('email', TRUE),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role_id'  => 3 
            ];

            $new_user_id = $this->User_model->insert_customer_user($user_payload);

            $session_payload = [
                'user_id'   => $new_user_id,
                'role_id'   => 3,
                'name'      => $user_payload['name'],
                'logged_in' => TRUE
            ];
            $this->session->set_userdata($session_payload);

            $this->session->set_flashdata('success', 'Registrasi Akun Berhasil');
            redirect('User/dashboard');
        }
    }

    private function _redirect_by_role($role_id) {
        if ($role_id == 1)      { redirect('Admin/dashboard'); }
        elseif ($role_id == 2)  { redirect('Worker/dashboard'); }
        elseif ($role_id == 3)  { redirect('User/dashboard'); }
        elseif ($role_id == 4)  { redirect('Manager/dashboard'); }
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('Auth/process_login');
    }
}

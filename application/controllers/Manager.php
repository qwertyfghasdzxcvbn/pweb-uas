<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manager extends CI_Controller
{

       public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role_id') != 4) {
            redirect('auth/process_login');
        }
        $this->load->library('form_validation');
        $this->load->model('Manager_model');
    }

    public function dashboard()
    {
        $data['title'] = 'Dashboard Manager';

        $data['parts'] = $this->Manager_model->get_all_spareparts();
        $data['mechanics'] = $this->Manager_model->get_all_mechanics();
        $data['pending_payments'] = $this->Manager_model->get_pending_payments();

        $data['subview'] = 'pages/manager/dashboard';
        $this->load->view('layout/main', $data);
    }

    public function verify_payment_action($id_transaksi)
    {
        if (empty($id_transaksi) || !is_numeric($id_transaksi)) {
            redirect('Manager/dashboard');
        }

        $this->Manager_model->update_payment_status($id_transaksi, 'Lunas');

        $this->session->set_flashdata('success', 'Pembayaran Berhasil Diverifikasi');
        redirect('Manager/dashboard');
    }

        public function create_mechanic_process()
    {
        $this->form_validation->set_error_delimiters('<p class="mt-1 text-[11px] font-bold text-rose-600 bg-rose-50 border border-rose-100 px-2 py-1 rounded shadow-2xs">', '</p>');
        $this->form_validation->set_rules('name', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('email', 'Email Address', 'required|valid_email|is_unique[users.email]|trim');
        $this->form_validation->set_rules('password', 'Login Password', 'required|min_length[6]');
        $this->form_validation->set_rules('keahlian', 'Technical Specialty Field', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->dashboard();
        } else {
            $user_payload = [
                'name' => $this->input->post('name', TRUE),
                'email' => $this->input->post('email', TRUE),
                'password' => password_hash($this->input->post('password'), PASSWORD_BCRYPT),
                'role_id' => 2
            ];
            $nama_mechanic = $user_payload['name'];
            $keahlian = $this->input->post('keahlian', TRUE);

            $this->Manager_model->insert_mechanic_roster($user_payload, $nama_mechanic, $keahlian);
            $this->session->set_flashdata('success', 'Mekanik Ditambahkan');
            redirect('Manager/dashboard');
        }
    }

    public function add_part_process() {
        $this->form_validation->set_rules('nama_part', 'Part Name', 'required|trim');
        $this->form_validation->set_rules('stok', 'Stock Level', 'required|numeric');
        $this->form_validation->set_rules('harga_jual', 'Selling Price', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = 'Replenish Inventory Line';
            $data['subview'] = 'pages/manager/add_part'; 
            $this->load->view('layout/main', $data);
        } else {
            $part_payload = [
                'nama_part'  => $this->input->post('nama_part', TRUE),
                'stok'       => $this->input->post('stok', TRUE),
                'harga_jual' => $this->input->post('harga_jual', TRUE)
            ];

            $this->Manager_model->insert_sparepart($part_payload);
            $this->session->set_flashdata('success', 'Sparepart di tambahkan');
            redirect('Manager/dashboard');
        }
    }

    public function delete_part_action($id_part)
    {
        if (!empty($id_part) && is_numeric($id_part)) {
            $this->Manager_model->delete_part_with_relations($id_part);
            $this->session->set_flashdata('success', 'Spare part berhasil di hapus');
        }
        redirect('Manager/dashboard');
    }

    public function update_part_view($id_part)
    {
        if (empty($id_part) || !is_numeric($id_part)) {
            redirect('Manager/dashboard');
        }

        $data['title'] = 'Modify Spare Part Inventory Record';
        $data['part'] = $this->Manager_model->get_part_by_id($id_part);

        if (!$data['part']) {
            $this->session->set_flashdata('error', 'Sparepart tidak ada.');
            redirect('Manager/dashboard');
        }

        $data['is_edit'] = TRUE;
        $data['subview'] = 'pages/manager/add_part';
        $this->load->view('layout/main', $data);
    }

    public function update_part_process()
    {
        $id_part = $this->input->post('id_part');

        $this->form_validation->set_rules('id_part', 'ID Ref', 'required|numeric');
        $this->form_validation->set_rules('nama_part', 'Spare Part Name', 'required|trim');
        $this->form_validation->set_rules('stok', 'Stock Level Count', 'required|numeric');
        $this->form_validation->set_rules('harga_jual', 'Selling Price Unit', 'required|numeric');

        if ($this->form_validation->run() == FALSE) {
            $this->update_part_view($id_part);
        } else {
            $update_payload = [
                'nama_part' => $this->input->post('nama_part', TRUE),
                'stok' => $this->input->post('stok', TRUE),
                'harga_jual' => $this->input->post('harga_jual', TRUE)
            ];

            $this->Manager_model->update_part($id_part, $update_payload);
            $this->session->set_flashdata('success', 'Sparepart berhasil di update');
            redirect('Manager/dashboard');
        }
    }

    public function update_mechanic_view($id_mekanik)
    {
        if (empty($id_mekanik) || !is_numeric($id_mekanik)) {
            redirect('Manager/dashboard');
        }

        $data['title'] = 'Modify Mechanic Profile Metadata';
        $data['worker'] = $this->Manager_model->get_mechanic_by_id($id_mekanik);

        if (!$data['worker']) {
            $this->session->set_flashdata('error', 'Mekanik tidak ada');
            redirect('Manager/dashboard');
        }

        $data['subview'] = 'pages/manager/update_mechanic';
        $this->load->view('layout/main', $data);
    }

    public function update_mechanic_process()
    {
        $id_mekanik = $this->input->post('id_mekanik');

        $this->form_validation->set_rules('id_mekanik', 'ID Ref', 'required|numeric');
        $this->form_validation->set_rules('nama_mekanik', 'Full Name', 'required|trim');
        $this->form_validation->set_rules('keahlian', 'Technical Specialty Field', 'required|trim');
        $this->form_validation->set_rules('status_ketersediaan', 'Availability State', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $this->update_mechanic_view($id_mekanik);
        } else {
            $update_payload = [
                'nama_mekanik' => $this->input->post('nama_mekanik', TRUE),
                'keahlian' => $this->input->post('keahlian', TRUE),
                'status_ketersediaan' => $this->input->post('status_ketersediaan', TRUE)
            ];

            $this->Manager_model->update_mechanic($id_mekanik, $update_payload);
            $this->session->set_flashdata('success', 'Data mekanik berhasil diubah');
            redirect('Manager/dashboard');
        }
    }

}

<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function index()
    {
        if ($this->session->userdata('logged_in')) {
            
         
            $role_id = $this->session->userdata('role_id');

          
            if ($role_id == 1)      { redirect('admin/dashboard'); }
            elseif ($role_id == 2)  { redirect('worker/dashboard'); }
            elseif ($role_id == 3)  { redirect('user/dashboard'); }
            elseif ($role_id == 4)  { redirect('manager/dashboard'); }
            
        } else {

            redirect('auth/process_login');
        }
    }
}

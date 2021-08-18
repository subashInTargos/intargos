<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller
{
    public function check_login()
	{
		$this->form_validation->set_rules('login_username', 'Username', 'trim|required');
        // $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('login_password', 'Pasword', 'trim|required');

		if($this->form_validation->run())
		{
			$logged_user = $this->authenticate_model->login($this->input->post('login_username'), $this->input->post('login_password'));
	        if($logged_user)
	        {
				$logged_user['avatar'] = rand(1,10); // For Avatar icon
				$this->session->set_userdata('user_session', $logged_user);
				
				$output['message'] = 'Login Successfull.';
				$output['title'] = 'Welcome';
			}
	        else
	        {
				$output['error'] = true;
				$output['title'] = 'Sorry';
				$output['message'] = 'Invalid username or password.';
			}
			// echo json_encode($output);
		}
		else
		{
			$output['error'] = true;
			$output['title'] = 'Error';
			$output['message'] = 'Username or password cannot be blank.';
		}
		echo json_encode($output);
    }
    
    public function logout()
    {
		$this->session->unset_userdata('user_session');
        $this->session->sess_destroy();
		redirect('/');
	}

}
?>
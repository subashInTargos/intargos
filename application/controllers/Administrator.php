<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Administrator extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
		
	public function index()
	{
		if($this->session->has_userdata('user_session'))
			redirect('dashboard');
		else
			$this->load->view('login');
	}

	public function dashboard()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('dashboard');
		else
			redirect('/');
	}

	public function admin_roles()
	{
		if($this->session->has_userdata('user_session'))
			// if($this->searchdata_model->haspermission(session[role_id]) OR hascustompermit(session[userid]))
				$this->load->view('admin_roles');
			// else
			// 	redirect('error_page')
		else
			redirect('/');
	}

	public function admin_modules()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('admin_modules');
		else
			redirect('/');
	}

	public function admin_users()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('admin_users');
		else
			redirect('/');
	}

	public function manage_permissions()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('permissions');
		else
			redirect('/');
	}

	public function master_cod_cycle()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('master_cod_cycle');
		else
			redirect('/');
	}


	public function master_billing_cycle()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('master_billing_cycle');
		else
			redirect('/');
	}

	public function master_transit_partners()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('master_transit_partners');
		else
			redirect('/');
	}

	public function awb_generation()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('awb_generation');
		else
			redirect('/');
	}

	public function master_pincodes()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('master_pincodes');
		else
			redirect('/');
	}

	public function master_pinservices()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('master_pinservices');
		else
			redirect('/');
	}


	public function master_weightslab()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('master_weightslab');
		else
			redirect('/');
	}

	public function master_zones()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('master_zones');
		else
			redirect('/');
	}


	public function users_registration()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('users_registration');
		else
			redirect('/');
	}

	public function users_update()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('users_update');
		else
			redirect('/');
	}

	public function complete_registration()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('complete_registration');
		else
			redirect('/');
	}

	public function users_manage()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('users_manage');
		else
			redirect('/');
	}

	public function users_ratechart()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('users_ratechart');
		else
			redirect('/');
	}

	public function users_courierpriority()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('users_courierpriority');
		else
			redirect('/');
	}

	public function users_weightslab()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('users_weightslab');
		else
			redirect('/');
	}

	public function add_balance()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('add_balance');
		else
			redirect('/');
	}

	public function change_status()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('change_status');
		else
			redirect('/');
	}
	
	public function weight_update()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('weight_update');
		else
			redirect('/');
	}

	public function generate_invoice()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('generate_invoice');
		else
			redirect('/');
	}

	public function view_invoice()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('view_invoice');
		else
			redirect('/');
	}

	public function add_payment()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('add_payment');
		else
			redirect('/');
	}

	public function invoice()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('invoice');
		else
			redirect('/');
	}
	
	public function generate_cod()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('generate_cod');
		else
			redirect('/');
	}

	public function view_cods()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('view_cods');
		else
			redirect('/');
	}

	public function register_address()
	{
		if($this->session->has_userdata('user_session'))
		{
			// print_r($this->input->post());

			if(!empty($this->input->post()))
			{
				$this->load->model('Warehousemanagement_model','warehouse');

				$tracking_data = array(
					'activity_type' => "bulk_register_address",
					'log_data' => json_encode($this->input->post()),
					'admin_id' => $this->session->userdata['user_session']['admin_username'],
				);
				$warehouse_data = $this->warehouse->BulkRegisterwarehouse($this->input->post('accountid'));
				if($warehouse_data['status']=='success' && $this->insertions_model->activity_logs($tracking_data))
				{
					$output['title'] = 'Congrats';
					$output['message'] = $warehouse_data['reg_add'].' warehouse registered out of '.$warehouse_data['tot_add'];
				}
				else
				{
					$output['error'] = true;
					$output['title'] = 'Error';
					$output['message'] = 'Some Error occurred, Try again.';
				}
			}
			else
			{
				$output['error'] = true;
                $output['title'] = 'Error';
                $output['message'] = 'Invalid account id, please try again';
			}
            echo json_encode($output);
		}
		else
			redirect('/');
	}
}
?>
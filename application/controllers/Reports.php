<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reports extends CI_Controller
{
	public function __construct()
    {
      parent::__construct();
      if(!$this->session->has_userdata('user_session'))
		exit();
    }

	public function shipments()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('reports_shipments');
		else
			redirect('/');
    }

  public function mis()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('reports_mis');
		else
			redirect('/');
    }

	public function failedshipments()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('reports_failedshipments');
		else
			redirect('/');
    }

	public function viewbalance()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('reports_balance');
		else
			redirect('/');
    }

	public function allpayments()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('reports_payments');
		else
			redirect('/');
    }

	public function alltransactions()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('reports_alltransactions');
		else
			redirect('/');
    }

	public function userledger()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('reports_userledger');
		else
			redirect('/');
    }

	public function error_401()
	{
		if($this->session->has_userdata('user_session'))
			$this->load->view('error_401');
		else
			redirect('/');
	}




	
}
?>
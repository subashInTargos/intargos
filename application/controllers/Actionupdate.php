<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Actionupdate extends CI_Controller
{
    public function administrator_roles()
    {
        $this->form_validation->set_rules('role_name', 'Role', 'required|trim|min_length[3]|edit_unique[administrator_roles.role_name.admin_role_id.role_status]');
        $this->form_validation->set_rules('role_description', 'Description', 'trim');

        if($this->form_validation->run() == TRUE)
        {
            $form_data = array(
                'role_name' => $this->input->post('role_name'),
                'role_description' => $this->input->post('role_description'),
                'updated_by' => $this->session->userdata['user_session']['admin_username'],                
            );

            $tracking_data = array(
                'activity_type' => "update_admin_role",
                'log_data' => json_encode($this->input->post()),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );       
            
            if($this->updations_model->updt_admin_role($form_data,$this->input->post('cid')) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'Role Updated Successfully.';
            }
            else
            {
                $output['error'] = true;
                $output['title'] = 'Error';
                $output['message'] = 'Some Error occurred, Try again.';
            }
            echo json_encode($output);
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = validation_errors();
            echo json_encode($output);
        }
    }

    public function administrator_modules()
    {
        $this->form_validation->set_rules('module_parent', 'Parent', 'required|trim');
        $this->form_validation->set_rules('module_name', 'Module Name', 'required|trim|edit_unique_no_condition[administrator_modules.module_name.admin_module_id]');
        $this->form_validation->set_rules('module_route', 'Route', 'required|trim|edit_unique_no_condition[administrator_modules.module_route.admin_module_id]');
        $this->form_validation->set_rules('module_description', 'Description', 'trim');

        if($this->form_validation->run() == TRUE)
        {
            $form_data = array(
                'parent_menu' => $this->input->post('module_parent'),
                'module_name' => $this->input->post('module_name'),
                'module_route' => $this->input->post('module_route'),
                'module_description' => $this->input->post('module_description'),
                'updated_by' => $this->session->userdata['user_session']['admin_username'],               
            );

            $tracking_data = array(
                'activity_type' => "update_admin_module",
                'log_data' => json_encode($this->input->post()),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );       
            
            if($this->updations_model->updt_admin_module($form_data,$this->input->post('cid')) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'Module Updated Successfully.';
            }
            else
            {
                $output['error'] = true;
                $output['title'] = 'Error';
                $output['message'] = 'Some Error occurred, Try again.';
            }
            echo json_encode($output);
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = validation_errors();
            echo json_encode($output);
        }
    }

    public function administrator_users()
    {
        $this->form_validation->set_rules('admin_name', 'Fullname', 'required|trim|min_length[3]|edit_unique_no_condition[admin_users.admin_name.admin_uid]');

        $this->form_validation->set_rules('admin_phone', 'Mobile number', 'required|trim|min_length[10]|max_length[10]|edit_unique_no_condition[admin_users.admin_phone.admin_uid]');

        $this->form_validation->set_rules('admin_email', 'Email', 'required|trim|valid_email|edit_unique_no_condition[admin_users.admin_email.admin_uid]');

        $this->form_validation->set_rules('admin_username', 'Username', 'required|trim|edit_unique_no_condition[admin_users.admin_username.admin_uid]');
        $this->form_validation->set_rules('admin_password', 'Password', 'required|trim');
        $this->form_validation->set_rules('admin_role', 'Role', 'required|trim');



        if($this->form_validation->run() == TRUE)
        {
            $form_data = array(
                'admin_name' => $this->input->post('admin_name'),
                'admin_phone' => $this->input->post('admin_phone'),
                'admin_email' => $this->input->post('admin_email'),
                'admin_username' => $this->input->post('admin_username'),
                'admin_password' => $this->input->post('admin_password'),
                'admin_role' => $this->input->post('admin_role'),
                'updated_by' => $this->session->userdata['user_session']['admin_username'],

            );

            $tracking_data = array(
                'activity_type' => "update_admin_user",
                'log_data' => json_encode($this->input->post()),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );       
            
            if($this->updations_model->updt_admin_user($form_data,$this->input->post('cid')) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'Sub-Admin Updated Successfully.';
            }
            else
            {
                $output['error'] = true;
                $output['title'] = 'Error';
                $output['message'] = 'Some Error occurred, Try again.';
            }
            echo json_encode($output);
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = validation_errors();
            echo json_encode($output);
        }
    }

    public function master_billingcycles()
    {
        $this->form_validation->set_rules('billingcycle_title', 'Title', 'required|trim|edit_unique_no_condition[master_billing_cycle.billing_cycle_title.billing_cycle_id]');
        $this->form_validation->set_rules('billingcycle_dates', 'Dates', 'required|trim');

        if($this->form_validation->run() == TRUE)
        {
            $form_data = array(
                'billing_cycle_title' => $this->input->post('billingcycle_title'),
                'billing_cycle_dates' => $this->input->post('billingcycle_dates'),
                'updated_by' => $this->session->userdata['user_session']['admin_username'],               
            );

            $tracking_data = array(
                'activity_type' => "update_billing_cycle",
                'log_data' => json_encode($this->input->post()),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );       
            
            if($this->updations_model->updt_master_billingcycle($form_data,$this->input->post('cid')) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'Billing Cycle Updated Successfully.';
            }
            else
            {
                $output['error'] = true;
                $output['title'] = 'Error';
                $output['message'] = 'Some Error occurred, Try again.';
            }
            echo json_encode($output);
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = validation_errors();
            echo json_encode($output);
        }
    }

    public function master_codcycles()
    {
        $this->form_validation->set_rules('codcycle_title', 'Title', 'required|trim|edit_unique_no_condition[master_cod_cycle.cod_cycle_title.cod_cycle_id]');
        $this->form_validation->set_rules('codcycle_dates', 'Dates', 'required|trim');

        if($this->form_validation->run() == TRUE)
        {
            $form_data = array(
                'cod_cycle_title' => $this->input->post('codcycle_title'),
                'cod_cycle_dates' => $this->input->post('codcycle_dates'),
                'updated_by' => $this->session->userdata['user_session']['admin_username'],               
            );

            $tracking_data = array(
                'activity_type' => "update_cod_cycle",
                'log_data' => json_encode($this->input->post()),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );       
            
            if($this->updations_model->updt_master_codcycle($form_data,$this->input->post('cid')) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'COD Cycle Updated Successfully.';
            }
            else
            {
                $output['error'] = true;
                $output['title'] = 'Error';
                $output['message'] = 'Some Error occurred, Try again.';
            }
            echo json_encode($output);
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = validation_errors();
            echo json_encode($output);
        }
    }

    public function master_transitpartners()
    {
        $this->form_validation->set_rules('transitpartner_name', 'Partner Name', 'required|trim|edit_unique_no_condition[master_transit_partners.transitpartner_name.transitpartner_id]');
        $this->form_validation->set_rules('logo_name', 'Logo filename', 'required|trim');
        $this->form_validation->set_rules('transitpartner_description', 'Description', 'trim');

        if($this->form_validation->run() == TRUE)
        {
            $form_data = array(
                'transitpartner_name' => $this->input->post('transitpartner_name'),
                'transitpartner_logo' => $this->input->post('logo_name'),
                'transitpartner_description' => $this->input->post('transitpartner_description'),
                'updated_by' => $this->session->userdata['user_session']['admin_username'],               
            );

            $tracking_data = array(
                'activity_type' => "update_transitpartner",
                'log_data' => json_encode($this->input->post()),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );       
            
            if($this->updations_model->updt_master_transitpartner($form_data,$this->input->post('cid')) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'Transit Partner Updated Successfully.';
            }
            else
            {
                $output['error'] = true;
                $output['title'] = 'Error';
                $output['message'] = 'Some Error occurred, Try again.';
            }
            echo json_encode($output);
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = validation_errors();
            echo json_encode($output);
        }
    }

    public function master_transitpartners_accounts()
    {
        $this->form_validation->set_rules('account_name', 'Account Name', 'required|trim|update_unique_no_condition[master_transitpartners_accounts.account_name.account_id.cid_acc]');
        $this->form_validation->set_rules('parent_id', 'Parent', 'required');
        $this->form_validation->set_rules('base_weight', 'Account Key', 'required|trim');
        $this->form_validation->set_rules('account_key', 'Account Key', 'trim');
        $this->form_validation->set_rules('account_username', 'Account username', 'trim');
        $this->form_validation->set_rules('account_password', 'Account password', 'trim');
        $this->form_validation->set_rules('account_description', 'Description', 'trim');

        if($this->form_validation->run() == TRUE)
        {
            $form_data = array(
                'account_name' => $this->input->post('account_name'),
                'parent_id' => $this->input->post('parent_id'),
                'base_weight' => $this->input->post('base_weight'),
                'account_key' => $this->input->post('account_key'),
                'account_username' => $this->input->post('account_username'),
                'account_password' => $this->input->post('account_password'),
                'account_description' => $this->input->post('account_description'),
                'updated_by' => $this->session->userdata['user_session']['admin_username'],               
            );
            $tracking_data = array(
                'activity_type' => "update_transitpartner_accounts",
                'log_data' => json_encode($this->input->post()),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );

            if($this->updations_model->updt_master_transitpartner_account($form_data,$this->input->post('cid_acc')) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'Transit Partner Accounts Updated Successfully.';
            }
            else
            {
                $output['error'] = true;
                $output['title'] = 'Error';
                $output['message'] = 'Some Error occurred, Try again.';
            }
            echo json_encode($output);
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = validation_errors();
            echo json_encode($output);
        }
    }

    public function master_weightslabs()
    {
        $this->form_validation->set_rules('slab_title', 'Slab title', 'required|trim|edit_unique_no_condition[master_weightslab.slab_title.weightslab_id]');
        $this->form_validation->set_rules('base_weight', 'Base weight', 'required|trim|regex_match[/^\d{0,10}(\.\d{0,2})?$/]');
        $this->form_validation->set_rules('additional_weight', 'Additional weight', 'required|trim');


        if($this->form_validation->run() == TRUE)
        {
            $form_data = array(
                'slab_title' => $this->input->post('slab_title'),
                'base_weight' => $this->input->post('base_weight'),
                'additional_weight' => $this->input->post('additional_weight'),
                'updated_by' => $this->session->userdata['user_session']['admin_username'],               
            );

            $tracking_data = array(
                'activity_type' => "update_weightslab",
                'log_data' => json_encode($this->input->post()),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );       
            
            if($this->updations_model->updt_master_weightslab($form_data,$this->input->post('cid')) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'Weight-slab Updated Successfully.';
            }
            else
            {
                $output['error'] = true;
                $output['title'] = 'Error';
                $output['message'] = 'Some Error occurred, Try again.';
            }
            echo json_encode($output);
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = validation_errors();
            echo json_encode($output);
        }
    }

    public function master_pincodes()
    {
        $this->form_validation->set_rules('f_pincode', 'Pincode', 'required|trim|edit_unique_no_condition[tbl_pincodes.pincode.pincode_id]');
        $this->form_validation->set_rules('f_pin_city', 'City', 'required|trim|strtoupper');
        $this->form_validation->set_rules('f_pin_state', 'State', 'required|trim');

        if($this->form_validation->run() == TRUE)
        {
            $form_data = array(
                'pincode' => $this->input->post('f_pincode'),
                'pin_city' => $this->input->post('f_pin_city'),
                'pin_state' => $this->input->post('f_pin_state'),
                'updated_by' => $this->session->userdata['user_session']['admin_username'],               
            );

            $tracking_data = array(
                'activity_type' => "update_pincode",
                'log_data' => json_encode($this->input->post()),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );       
            
            if($this->updations_model->updt_master_pincode($form_data,$this->input->post('cid')) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'Pincode Updated Successfully.';
            }
            else
            {
                $output['error'] = true;
                $output['title'] = 'Error';
                $output['message'] = 'Some Error occurred, Try again.';
            }
            echo json_encode($output);
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = validation_errors();
            echo json_encode($output);
        }
    }

    public function master_zones()
    {
        $this->form_validation->set_rules('f_source', 'Source city', 'required|trim|strtoupper');
        $this->form_validation->set_rules('f_destination_pin', 'Destination pin', 'required|trim|min_length[6]|max_length[6]');
        $this->form_validation->set_rules('f_zone', 'Zone', 'required|trim|min_length[1]|max_length[1]|regex_match[/^[A-Fa-f]*$/]|strtoupper');

        $this->form_validation->set_message('regex_match', 'The %s must have value betwen A to F.');

        if($this->form_validation->run() == TRUE)
        {
            $form_data = array(
                'source_city' => $this->input->post('f_source'),
                'destination_pin' => $this->input->post('f_destination_pin'),
                'zone' => $this->input->post('f_zone'),
                'updated_by' => $this->session->userdata['user_session']['admin_username'],               
            );

            $tracking_data = array(
                'activity_type' => "update_zone",
                'log_data' => json_encode($this->input->post()),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );       
            
            if($this->updations_model->updt_master_zone($form_data,$this->input->post('cid')) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'Zone Updated Successfully.';
            }
            else
            {
                $output['error'] = true;
                $output['title'] = 'Error';
                $output['message'] = 'Some Error occurred, Try again.';
            }
            echo json_encode($output);
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = validation_errors();
            echo json_encode($output);
        }
    }

    
}
?>
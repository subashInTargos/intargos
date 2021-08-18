<?php

class Statusupdate_model extends CI_Model
{
    public function status_admin_role($record_id,$new_status)
    {
        return $this->db->set('role_status',$new_status)
                        ->set('updated_by',$this->session->userdata['user_session']['admin_username'])
                        ->where('admin_role_id',$record_id)
                        ->update('administrator_roles');
    }


    public function status_admin_module($record_id,$new_status)
    {
        return $this->db->set('module_status',$new_status)
                        ->set('updated_by',$this->session->userdata['user_session']['admin_username'])
                        ->where('admin_module_id',$record_id)
                        ->update('administrator_modules');
    }

    public function status_admin_user($record_id,$new_status)
    {
        return $this->db->set('admin_status',$new_status)
                        ->set('updated_by',$this->session->userdata['user_session']['admin_username'])
                        ->where('admin_uid',$record_id)
                        ->update('admin_users');
    }

    public function status_master_billingcycle($record_id,$new_status)
    {
        return $this->db->set('billing_cycle_status',$new_status)
                        ->set('updated_by',$this->session->userdata['user_session']['admin_username'])
                        ->where('billing_cycle_id',$record_id)
                        ->update('master_billing_cycle');
    }


    public function status_master_codcycle($record_id,$new_status)
    {
        return $this->db->set('cod_cycle_status',$new_status)
                        ->set('updated_by',$this->session->userdata['user_session']['admin_username'])
                        ->where('cod_cycle_id',$record_id)
                        ->update('master_cod_cycle');
    }


    public function status_master_transitpartner($record_id,$new_status)
    {
        // return $this->db->set('transitpartner_status',$new_status)
        //                 ->set('updated_by',$this->session->userdata['user_session']['admin_username'])
        //                 ->where('transitpartner_id',$record_id)
        //                 ->update('master_transit_partners');

        $this->db->trans_start();
        $this->db->set('transitpartner_status',$new_status)
                ->set('updated_by',$this->session->userdata['user_session']['admin_username'])
                ->where('transitpartner_id',$record_id)
                ->update('master_transit_partners');
        
        $this->db->set('account_status',$new_status)
                ->set('updated_by',$this->session->userdata['user_session']['admin_username'])
                ->where('parent_id',$record_id)
                ->update('master_transitpartners_accounts');
        $this->db->trans_complete();
        return ($this->db->trans_status() === TRUE ? '1'  : FALSE);
    }

    public function status_master_transitpartner_account($record_id,$new_status)
    {
        return $this->db->set('account_status',$new_status)
                        ->set('updated_by',$this->session->userdata['user_session']['admin_username'])
                        ->where('account_id',$record_id)
                        ->update('master_transitpartners_accounts');
    }


    public function status_master_weightslab($record_id,$new_status)
    {
        return $this->db->set('slab_status',$new_status)
                        ->set('updated_by',$this->session->userdata['user_session']['admin_username'])
                        ->where('weightslab_id',$record_id)
                        ->update('master_weightslab');
    }

    public function status_master_user($record_id,$new_status)
    {
        return $this->db->set('account_status',$new_status)
                        ->set('updated_by',$this->session->userdata['user_session']['admin_username'])
                        ->where('user_id',$record_id)
                        ->update('users');
    }


}




?>
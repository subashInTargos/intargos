<?php
class Updations_model extends CI_Model
{
    public function updt_admin_role($formdata,$rec_id)
    {
        return $this->db->where('admin_role_id',$rec_id)
                        ->update('administrator_roles',$formdata);
    }

    public function updt_admin_module($formdata,$rec_id)
    {
        return $this->db->where('admin_module_id',$rec_id)
                        ->update('administrator_modules',$formdata);
    }

    public function updt_admin_user($formdata,$rec_id)
    {
        return $this->db->where('admin_uid',$rec_id)
                        ->update('admin_users',$formdata);
    }

    public function updt_master_billingcycle($formdata,$rec_id)
    {
        return $this->db->where('billing_cycle_id',$rec_id)
                        ->update('master_billing_cycle',$formdata);
    }

    public function updt_master_codcycle($formdata,$rec_id)
    {
        return $this->db->where('cod_cycle_id',$rec_id)
                        ->update('master_cod_cycle',$formdata);
    }


    public function updt_master_transitpartner($formdata,$rec_id)
    {
        return $this->db->where('transitpartner_id',$rec_id)
                        ->update('master_transit_partners',$formdata);
    }

    public function updt_master_transitpartner_account($formdata,$rec_id)
    {
        return $this->db->where('account_id',$rec_id)
                        ->update('master_transitpartners_accounts',$formdata);
    }

    public function updt_master_weightslab($formdata,$rec_id)
    {
        return $this->db->where('weightslab_id',$rec_id)
                        ->update('master_weightslab',$formdata);
    }

    public function updt_master_pincode($formdata,$rec_id)
    {
        return $this->db->where('pincode_id',$rec_id)
                        ->update('tbl_pincodes',$formdata);
    }

    public function updt_master_zone($formdata,$rec_id)
    {
        return $this->db->where('zone_id',$rec_id)
                        ->update('tbl_zone',$formdata);
    }

    public function activity_logs($activity,$log)
    {
        $tracking_data = array(
                'activity_type' => $activity,
                'log_data' => $log,
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );

        return $this->db->insert('admin_activity_logs',$tracking_data);
    }

    public function update($table, $where,$data) {
        $this->db->where($where);
        $update = $this->db->update($table, $data); 
        return $update;
    }
}

?>
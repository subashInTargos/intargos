<?php
class Insertions_model extends CI_Model
{
    /* For Admin Roles */
    public function ins_admin_role($formdata)
    {
        return $this->db->insert('administrator_roles',$formdata);
    }

    /* For Module */
    public function ins_admin_modules($formdata)
    {
        return $this->db->insert('administrator_modules',$formdata);
    }

    /* For Admin User */
    public function ins_admin_user($formdata)
    {
        return $this->db->insert('admin_users',$formdata);
    }

    /* For Billing Cycle */
    public function ins_master_billingcycle($formdata)
    {
        return $this->db->insert('master_billing_cycle',$formdata);
    }

    /* For COD Cycle */
    public function ins_master_codcycle($formdata)
    {
        return $this->db->insert('master_cod_cycle',$formdata);
    }

    /* For Transit Partner */
    public function ins_master_transitpartner($formdata)
    {
        return $this->db->insert('master_transit_partners',$formdata);
    }

    /* For Transit Partner accounts */
    public function ins_master_transitpartner_account($formdata)
    {
        if($formdata['parent_id']=='1' || $formdata['parent_id']=='4')
            $this->db->query("UPDATE users_address set registered_status='0'");

        return $this->db->insert('master_transitpartners_accounts',$formdata);
    }

    /* For Pincode */
    public function ins_master_pincode($formdata)
    {
        return $this->db->insert('tbl_pincodes',$formdata);
    }

    /* For Pin Services */
    public function ins_master_pinservices($formdata)
    {
        $this->db->trans_start();
        $this->db->delete('master_pincodeserviceability', array('account_id' => $formdata[0]['account_id']));
        $this->db->insert_batch('master_pincodeserviceability',$formdata);
        $this->db->trans_complete();
        return ($this->db->trans_status() === TRUE ? '1'  : FALSE);
    }

    /* For weightslab */
    public function ins_master_weightslab($formdata)
    {
        return $this->db->insert('master_weightslab',$formdata);
    }

    /* For Zone */
    public function ins_master_zones($formdata)
    {
        return $this->db->insert_batch('tbl_zone',$formdata);
        // print_r($this->db->last_query());
    }

    /* For User's Complete Registration  */
    public function ins_user_completeregis($form_data_user,$form_data_wtslab,$form_data_balances,$form_data_kyc,$form_data_poc)
    {
        // $passkey = random_string('alpha', 6);
        // $passkey = random_string('alpha', 6);
        // $token_key = strtoupper(random_string('alnum', 30));
        // $passhash= password_hash($passkey, PASSWORD_BCRYPT);

        $this->db->trans_start();

        // $form_data_user['password']=$passhash;
        // $form_data_user['passkey']=$passkey;
        // $form_data_user['token_key']=$token_key;
        
        $this->db->insert('users',$form_data_user);
        $user_id=$this->db->insert_id();

        $form_data_balances['user_id']=$user_id;
        // $form_data_cp['user_id']=$user_id;
        $form_data_kyc['user_id']=$user_id;
        $form_data_poc['user_id']=$user_id;
        // $form_data_rates[0]['user_id']=$user_id;
        // $form_data_rates[1]['user_id']=$user_id;
        // $form_data_rates[2]['user_id']=$user_id;

        for($i=0; $i<count($form_data_wtslab); $i++)
        {
            $form_data_wtslab[$i]['user_id'] = $user_id;
        }

        $this->db->insert_batch('users_weightslabs',$form_data_wtslab);
        $this->db->insert('users_balances',$form_data_balances);
        $this->db->insert('users_kyc',$form_data_kyc);
        $this->db->insert('users_poc',$form_data_poc);
        // print_r($this->db->last_query());
        $this->db->trans_complete();

        return ($this->db->trans_status() === TRUE ? '1'  : FALSE);
    }

    /* For Adding Users Weight slab */
    public function ins_user_weightSlab($data)
    {
        return $this->db->insert_batch('users_weightslabs',$data);
    }

    /* For Rates */
    public function ins_user_ratechart($formdata)
    {
        $this->db->set('rate_status','0')
                 ->where('user_id',$formdata[0]['user_id'])
                 ->update('users_rates');
        // print_r($this->db->last_query());
        // die();
        return $this->db->insert_batch('users_rates',$formdata);
        // print_r($this->db->last_query());
    }

    /* For Priority */
    public function ins_user_courierpriority($formdata)
    {
        $this->db->trans_start();
        $this->db->set('priority_status','0')
                ->where('user_id',$formdata[0]['user_id'])
                ->update('users_courier_priority');
        $this->db->insert_batch('users_courier_priority',$formdata);
        $this->db->trans_complete();
        return ($this->db->trans_status() === TRUE ? '1'  : FALSE);
    }

    /* For Uploading AWBs */
    public function ins_upoad_awbs($formdata)
    {
        return $this->db->insert_batch('pregenerated_awbs',$formdata);
        // print_r($this->db->last_query());
    }

    /* For Tracking Admin Activities */
    public function activity_logs($formdata)
    {
        return $this->db->insert('admin_activity_logs',$formdata);
    }

    public function insert($table,$data) {
        $this->db->insert($table,$data);
        $insert_id  = $this->db->insert_id();
        return $insert_id;
    }
}
?>
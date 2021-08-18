<?php
class Exportdata_model extends CI_Model
{
    public function search_condition($fields)
    {
        $conditions = array();
        // loop through the defined fields
        foreach($fields as $field) {
            // if the field is set and not empty
            if(isset($_POST[$field]) && $_POST[$field] != '')
                $conditions[$field] = $_POST[$field];
        }
        return $conditions;
    }

    public function searched_invoices($sql,$date_between,$date_field)
    {
        if($date_between != "")
            $query = $this->db->select("shipments_invoices.*, email_id,contact,alt_contact,email_id,username, business_name,billing_type")
                          ->where($sql)
                          ->where("$date_field $date_between")
                          ->join('users','users.user_id=shipments_invoices.user_id','LEFT')
                          ->order_by('1', 'ASC')
                          ->get('shipments_invoices');
        else
        $query = $this->db->select("shipments_invoices.*, email_id,contact,alt_contact,email_id,username, business_name,billing_type")
                          ->where($sql)
                          ->join('users','users.user_id=shipments_invoices.user_id','LEFT')
                          ->order_by('1', 'ASC')
                          ->get('shipments_invoices');
        
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function download_invoice_awbs($invoice_number)
    {
        if($invoice_number != "")
            $query = $this->db->select('S.shipment_id,S.shipment_type,S.waybill_number,payment_mode,consignee_name,consignee_city,consignee_state,consignee_pincode,order_date,S.total_amount as order_amt,S.cod_amount,shipment_length,shipment_width,shipment_height,express_type,user_status,address_title,addressee,pincode,SB.*,SS.status_title')
                        ->where('SB.invoice_number',$invoice_number)
                        ->join('shipments_billing SB','SB.shipment_id=S.shipment_id','LEFT')
                        ->join('users_address UA','S.pick_address_id=UA.user_address_id','LEFT')
                        ->join('shipments_status SS','S.user_status=SS.status_id','LEFT')
                        ->order_by('S.shipment_id', 'ASC')
                        ->get('shipments S');
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function download_cod_awbs($cod_id)
    {
        if(!empty($cod_id))
            $query = $this->db->select('shipment_id, waybill_number, invoice_number, billing_date, cod_amount, cod_gap, cod_status, cod_date, cod_eligible_date, cod_cycle_date, cod_trn')
                        ->where('cod_trn',$cod_id)
                        ->order_by('shipment_id', 'ASC')
                        ->get('shipments_billing');
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function searched_cods($sql,$date_between,$date_field)
    {
        if($date_between != "")
            $query = $this->db->select("shipments_cods.*, codadjust,email_id,contact,alt_contact, business_name,billing_type,beneficiary_name, account_number, ifsc_code, bank_name, branch_name")
                          ->where($sql)
                          ->where("$date_field $date_between")
                          ->join('users','users.user_id=shipments_cods.user_id','LEFT')
                          ->order_by('1', 'ASC')
                          ->get('shipments_cods');
        else
        $query = $this->db->select("shipments_cods.*, codadjust,email_id,contact,alt_contact, business_name,billing_type,beneficiary_name, account_number, ifsc_code, bank_name, branch_name")
                          ->where($sql)
                          ->join('users','users.user_id=shipments_cods.user_id','LEFT')
                          ->order_by('1', 'ASC')
                          ->get('shipments_cods');
        
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function reports_shipments($fields)
    {
        $this->db->select('username,fullname,business_name,contact,shipment_type,shipment_id,waybill_number,payment_mode,consignee_name,consignee_mobile, consignee_address1, consignee_address2, consignee_pincode, consignee_city,consignee_state,order_date,shipment_length,shipment_width,shipment_height,shipment_weight,express_type,fulfilled_by,user_status,status_title,cod_amount,remark_1,account_name');
        if (!empty($fields["username"]))
            $this->db->where('username', $fields["username"]);
        if (!empty($fields["business_name"]))
            $this->db->where('business_name', $fields["business_name"]);
        if (!empty($fields["shipment_type"]))
            $this->db->where('shipment_type', $fields["shipment_type"]);
        if (!empty($fields["shipment_id"]))
            $this->db->where('shipment_id', $fields["shipment_id"]);
        if (!empty($fields["waybill_number"]))
            $this->db->where('waybill_number', $fields["waybill_number"]);
        if (!empty($fields["payment_mode"]))
            $this->db->where('payment_mode', $fields["payment_mode"]);
        if (!empty($fields["express_type"]))
            $this->db->where('express_type', $fields["express_type"]);
        if (!empty($fields["fulfilled_account"]))
            $this->db->where('fulfilled_account', $fields["fulfilled_account"]);
        if (!empty($fields["user_status"]))
            $this->db->where_in('user_status', $fields["user_status"]);
        if (!empty($fields["from_date"]) && !empty($fields["to_date"]))
            $this->db->where("order_date BETWEEN '".date('Y-m-d',strtotime($fields["from_date"])). "' AND '".date('Y-m-d',strtotime($fields["to_date"]))."'");
        
        $this->db->join('shipments_status SS','SS.status_id=S.user_status');
        $this->db->join('users U','U.user_id=S.user_id');
        $this->db->join('master_transitpartners_accounts MTPA','MTPA.account_id=S.fulfilled_account');
        $this->db->order_by('S.shipment_id', 'DESC');
        $query = $this->db->get('shipments S');
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function reports_mis_csv_search_download_csv($file_type=null,$query_data=null){
		//print_r($file_type);exit;
			$returns=array();
		    $handle=fopen($query_data, 'r');
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
			if($file_type=='waybill_number'){
				$this->db->where('S.waybill_number',$data[0]);
			}elseif($file_type=='shipment_id'){
				$this->db->where('S.shipment_id',$data[0]);
			}else{
				$this->db->where('S.invoice_number',$data[0]);
			}
			
			$this->db->select('username,fullname,contact,shipment_type,S.shipment_id,S.waybill_number,payment_mode,consignee_name,consignee_mobile, consignee_address1, consignee_address2, consignee_pincode, consignee_city,consignee_state,S.order_date,shipment_length,shipment_width,shipment_height,shipment_weight,express_type,fulfilled_by,user_status,status_title,cod_amount,remark_1,account_name,address_title,SP.invoice_number,product_name,product_quantity,SP.product_value,zone,au.admin_name as sales_poc,aus.admin_name as ops_poc');
            $this->db->join('shipments_status SS','SS.status_id=S.user_status','left');
            $this->db->join('users U','U.user_id=S.user_id','left');
            $this->db->join('users_address UA','UA.user_address_id=S.pick_address_id','left');
            $this->db->join('shipments_products SP','SP.shipment_id=S.shipment_id','left');
            $this->db->join('master_transitpartners_accounts MTPA','MTPA.account_id=S.fulfilled_account','left');
			$this->db->join('users_poc up','U.user_id=up.user_id');
			$this->db->join('admin_users au','up.sales_poc_id=au.admin_uid','left');
			$this->db->join('admin_users aus','up.ops_poc_id=aus.admin_uid','left');
            $this->db->order_by('S.shipment_id', 'DESC');
            $query = $this->db->get('shipments S');
             $returns[]= $query->result();
			}
			//echo "<pre>";print_r($returns);exit;
			return $returns;
    }

    public function reports_mis($fields=null,$action=null)
    {
		//echo "<pre>";print_r($fields);exit;
        if($action=='single')
        {
			$this->db->select('username,fullname,contact,shipment_type,S.shipment_id,S.waybill_number,payment_mode,consignee_name,consignee_mobile, consignee_address1, consignee_address2, consignee_pincode, consignee_city,consignee_state,S.order_date,shipment_length,shipment_width,shipment_height,shipment_weight,express_type,fulfilled_by,user_status,status_title,cod_amount,remark_1,account_name,address_title,SP.invoice_number,product_name,product_quantity,SP.product_value,zone,au.admin_name as sales_poc,aus.admin_name as ops_poc');
			//,sales_poc,ops_poc
        if (!empty($fields["username"]))
            $this->db->like('username', $fields["username"]);
        if (!empty($fields["waybill_number"]))
            $this->db->like('SP.waybill_number', $fields["waybill_number"]);      
        if (!empty($fields["shipment_id"]))
             $this->db->where('SP.shipment_id', $fields["shipment_id"]); 
        if (!empty($fields["address_title"]))
             $this->db->where('address_title', $fields["address_title"]); 
        if (!empty($fields["user_status"]))
            $this->db->where_in('user_status', $fields["user_status"]);   
        if (!empty($fields["payment_mode"]))
            $this->db->where('payment_mode', $fields["payment_mode"]);
        if (!empty($fields["express_type"]))
            $this->db->where('express_type', $fields["express_type"]);
        if (!empty($fields["shipment_type"]))
            $this->db->where('shipment_type', $fields["shipment_type"]);
        if (!empty($fields["fulfilled_account"]))
            $this->db->where('fulfilled_account', $fields["fulfilled_account"]);
         
        if (!empty($fields["from_date"]) && !empty($fields["to_date"]))
            $this->db->where("S.order_date BETWEEN '".date('Y-m-d',strtotime($fields["from_date"])). "' AND '".date('Y-m-d',strtotime($fields["to_date"]))."'"); 
		$this->db->join('shipments_status SS','SS.status_id=S.user_status','left');
	    $this->db->join('users U','U.user_id=S.user_id','left');
	    $this->db->join('users_address UA','UA.user_address_id=S.pick_address_id','left');
		$this->db->join('shipments_products SP','SP.shipment_id=S.shipment_id','left');
		$this->db->join('master_transitpartners_accounts MTPA','MTPA.account_id=S.fulfilled_account','left');
		$this->db->join('users_poc up','U.user_id=up.user_id');
		$this->db->join('admin_users au','up.sales_poc_id=au.admin_uid','left');
		$this->db->join('admin_users aus','up.ops_poc_id=aus.admin_uid','left');
       /*  $this->db->join('shipments_status SS','SS.status_id=S.user_status');
        $this->db->join('users U','U.user_id=S.user_id');
        $this->db->join('users_address UA','UA.user_address_id=S.pick_address_id');
        $this->db->join('shipments_products SP','SP.shipment_id=S.shipment_id');
        $this->db->join('master_transitpartners_accounts MTPA','MTPA.account_id=S.fulfilled_account'); */
        $this->db->order_by('S.shipment_id', 'DESC');
        $query = $this->db->get('shipments S');
          return $query->result();
		// echo  $this->db->last_query();exit;
        }
    }
}
?>
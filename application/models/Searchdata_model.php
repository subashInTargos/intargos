<?php
class Searchdata_model extends CI_Model
{

    public function search_condition($post_fields)
    {
        $conditions = array();
        // loop through the defined fields
        foreach($post_fields as $field)
        {
            // if the field is set and not empty
            if(isset($_POST[$field]) && $_POST[$field] != '')
                $conditions[$field] = $_POST[$field];
        }
        return $conditions;
    }

    public function count_recordswithdate($param,$date_between,$date_field,$table)
    {
        if($date_between != "")
            $query = $this->db->where($param)
                          ->where("$date_field $date_between")
                          ->join('users','users.user_id='.$table.'.user_id','LEFT')
                          ->get($table);
        else
            $query = $this->db->where($param)
                            ->join('users','users.user_id='.$table.'.user_id','LEFT')
                            ->get($table);
        // echo '<br/>'; 
        // print_r($this->db->last_query());
        return $query->num_rows();
    }


    public function search_pincode($sql,$limit, $start)
    {
        $query = $this->db->like($sql)->get('tbl_pincodes',$limit, $start);
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function search_pinservices($sql,$limit, $start)
    {
        $query = $this->db->like($sql)->get('master_pincodeserviceability',$limit, $start);
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function count_records($param, $table)
    {
        $query = $this->db->like($param)->get($table);
        // print_r($this->db->last_query());
        // echo '<br/>';    
        return $query->num_rows();
    }

    public function search_zone($sql,$limit, $start)
    {
        $query = $this->db->like($sql)->get('tbl_zone',$limit, $start);
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function search_user($sql,$limit='', $start='')
    {
        $query = $this->db->like($sql)->where('account_status !=','0')->get('users',$limit, $start);
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function search_invoice($sql,$limit, $start,$date_between,$date_field)
    {
        if($date_between != "")
            $query = $this->db->select("shipments_invoices.*, email_id,contact,alt_contact,username, business_name,billing_type")
                ->where($sql)
                ->where("$date_field $date_between")
                ->join('users','users.user_id=shipments_invoices.user_id','LEFT')
                ->order_by('1', 'ASC')
                ->get('shipments_invoices',$limit, $start);
        else
            $query = $this->db->select("shipments_invoices.*, email_id,contact,alt_contact,username, business_name,billing_type")
                ->where($sql)
                ->join('users','users.user_id=shipments_invoices.user_id','LEFT')
                ->order_by('1', 'ASC')
                ->get('shipments_invoices',$limit, $start);
        
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function search_invoicepayments($sql,$limit, $start)
    {
        $query = $this->db->where($sql)->get('invoice_payments',$limit, $start);
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function search_cods($sql,$limit, $start,$date_between,$date_field)
    {
        if($date_between != "")
            $query = $this->db->select("shipments_cods.*, email_id,contact,alt_contact,username, business_name,billing_type,beneficiary_name,account_number,ifsc_code,bank_name,codadjust")
                ->where($sql)
                ->where("$date_field $date_between")
                ->join('users','users.user_id=shipments_cods.user_id','LEFT')
                ->order_by('1', 'ASC')
                ->get('shipments_cods',$limit, $start);
        else
            $query = $this->db->select("shipments_cods.*, email_id,contact,alt_contact,username, business_name,billing_type,beneficiary_name,account_number,ifsc_code,bank_name,codadjust")
                ->where($sql)
                ->join('users','users.user_id=shipments_cods.user_id','LEFT')
                ->order_by('1', 'ASC')
                ->get('shipments_cods',$limit, $start);
        
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function search_shipments($fields,$limit='', $start='')
    {
        $this->db->select('username,fullname,business_name,contact,shipment_type,shipment_id,waybill_number,payment_mode,consignee_name,consignee_mobile, consignee_address1, consignee_address2, consignee_pincode, consignee_city,consignee_state,order_date,express_type,fulfilled_by,user_status,status_title,cod_amount,remark_1,account_name');
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
        $query = $this->db->get('shipments S',$limit, $start);
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function search_failedshipments($fields,$limit='', $start='')
    {
        $this->db->select('username,shipment_type,shipment_id,payment_mode,consignee_name,consignee_mobile, consignee_address1, consignee_address2, consignee_pincode, consignee_city,consignee_state,order_date,express_type,user_status,status_title,remark_1');
        if (!empty($fields["username"]))
            $this->db->where('username', $fields["username"]);
        if (!empty($fields["business_name"]))
            $this->db->where('business_name', $fields["business_name"]);
        if (!empty($fields["shipment_type"]))
            $this->db->where('shipment_type', $fields["shipment_type"]);
        if (!empty($fields["express_type"]))
            $this->db->where('express_type', $fields["express_type"]);
        if (!empty($fields["payment_mode"]))
            $this->db->where('payment_mode', $fields["payment_mode"]);
        if (!empty($fields["user_status"]))
            $this->db->where_in('user_status', $fields["user_status"]);
        if (empty($fields["user_status"]))
            $this->db->where_in('user_status', '200,220,229', false);
        if (!empty($fields["from_date"]) && !empty($fields["to_date"]))
            $this->db->where("order_date BETWEEN '".date('Y-m-d',strtotime($fields["from_date"])). "' AND '".date('Y-m-d',strtotime($fields["to_date"]))."'");
        
        $this->db->join('shipments_status SS','SS.status_id=S.user_status');
        $this->db->join('users U','U.user_id=S.user_id');
        $this->db->order_by('S.shipment_id', 'DESC');
        $query = $this->db->get('shipments S',$limit, $start);
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function search_viewbalance($fields,$limit='', $start='')
    {
        $this->db->select('username,business_name,fullname,contact,main_balance,promo_balance,total_balance,UB.updated_on');
        if (!empty($fields["username"]))
            $this->db->where('username', $fields["username"]);
        if (!empty($fields["business_name"]))
            $this->db->where('business_name', $fields["business_name"]);
        if (!empty($fields["amount_from"]) && !empty($fields["amount_to"]))
            $this->db->where("total_balance BETWEEN '".$fields["amount_from"]. "' AND '".$fields["amount_to"]."'");
        
        $this->db->where('billing_type','prepaid');
        $this->db->join('users U','U.user_id=UB.user_id');
        $this->db->order_by('username');
        $query = $this->db->get('users_balances UB',$limit, $start);
        // print_r($this->db->last_query());
        return $query->result();
    }

    public function search_allpayments($fields,$limit='', $start='')
    {
        $this->db->select('username,business_name,fullname,contact,billing_type,UTXN.transaction_amount,transaction_type,TXNTYPE.transaction_remark,transaction_reference_id,razorpay_payment_id,razorpay_order_id,UTXN.transaction_on,UTXN.added_by');
        
        if (!empty($fields["username"]))
            $this->db->where('username', $fields["username"]);
        if (!empty($fields["business_name"]))
            $this->db->where('business_name', $fields["business_name"]);
        if (!empty($fields["billing_type"]))
            $this->db->where('billing_type', $fields["billing_type"]);
        if (empty($fields["transaction_type"]))
            $this->db->where_in('transaction_type', '1001,1011,1013', false);
        if (!empty($fields["transaction_type"]))
            $this->db->where_in('transaction_type', $fields["transaction_type"]);
        if (!empty($fields["transaction_reference_id"]))
            $this->db->where('transaction_reference_id', $fields["transaction_reference_id"]);
        if (!empty($fields["gateway_order_id"]))
            $this->db->where('razorpay_order_id', $fields["gateway_order_id"]);
        if (!empty($fields["amount_from"]) && !empty($fields["amount_to"]))
            $this->db->where("UTXN.transaction_amount BETWEEN '".$fields["amount_from"]. "' AND '".$fields["amount_to"]."'");
        if (!empty($fields["gateway_payment_id"]))
            $this->db->where('razorpay_payment_id', $fields["gateway_payment_id"]);
        if (!empty($fields["from_date"]) && !empty($fields["to_date"]))
            $this->db->where("UTXN.transaction_on BETWEEN '".date('Y-m-d H:i:s',strtotime($fields["from_date"])). "' AND '".date('Y-m-d H:i:s',strtotime($fields["to_date"]))."'");
        
        $this->db->join('transactions_razorpay TXNRZP','TXNRZP.internal_order_id=UTXN.transaction_reference_id','LEFT');
        $this->db->join('transaction_types TXNTYPE','TXNTYPE.transaction_type_id=UTXN.transaction_type');
        $this->db->join('users U','U.user_id=UTXN.user_id');
        $this->db->order_by('transaction_on','DESC');
        $query = $this->db->get('users_transactions UTXN',$limit, $start);
        // print_r("<br/><br/>".$this->db->last_query());
        return $query->result();
    }

    public function search_alltransactions($fields,$limit='', $start='')
    {
        $this->db->select('username,business_name,fullname,contact,UTXN.*,TXNTYPE.transaction_remark as txn_rmk');
        
        if (!empty($fields["username"]))
            $this->db->where('username', $fields["username"]);
        if (!empty($fields["business_name"]))
            $this->db->where('business_name', $fields["business_name"]);
        if (!empty($fields["shipment_id"]))
            $this->db->where('shipment_id', $fields["shipment_id"]);
        if (!empty($fields["waybill_number"]))
            $this->db->where('waybill_number', $fields["waybill_number"]);
        // if (empty($fields["transaction_type"]))
        //     $this->db->where_in('transaction_type', '1001,1011,1013', false);
        if (!empty($fields["transaction_type"]))
            $this->db->where_in('transaction_type', $fields["transaction_type"]);
        if (!empty($fields["transaction_reference_id"]))
            $this->db->where('transaction_reference_id', $fields["transaction_reference_id"]);
        if (!empty($fields["action_type"]))
            $this->db->where('action_type', $fields["action_type"]);
        if (!empty($fields["balance_type"]))
            $this->db->where('balance_type', $fields["balance_type"]);
        if (!empty($fields["from_date"]) && !empty($fields["to_date"]))
            $this->db->where("transaction_on BETWEEN '".date('Y-m-d H:i:s',strtotime($fields["from_date"])). "' AND '".date('Y-m-d H:i:s',strtotime($fields["to_date"]))."'");
        
        $this->db->join('transaction_types TXNTYPE','TXNTYPE.transaction_type_id=UTXN.transaction_type');
        $this->db->join('users U','U.user_id=UTXN.user_id');
        $this->db->order_by('transaction_on','DESC');
        $query = $this->db->get('users_transactions UTXN',$limit, $start);
        // print_r("<br/><br/>".$this->db->last_query());
        return $query->result();
    }

    public function search_userledger($fields,$limit='', $start='')
    {
        // $this->db->select('username,business_name,fullname,contact,UTXN.*,TXNTYPE.transaction_remark as txn_rmk');
        
        if (!empty($fields["username"]))
            $this->db->where('username', $fields["username"]);
        if (!empty($fields["from_date"]) && !empty($fields["to_date"]))
            $this->db->where("transaction_on BETWEEN '".date('Y-m-d H:i:s',strtotime($fields["from_date"])). "' AND '".date('Y-m-d H:i:s',strtotime($fields["to_date"]))."'");
        
        $this->db->join('transaction_types TXNTYPE','TXNTYPE.transaction_type_id=UTXN.transaction_type');
        $this->db->join('users U','U.user_id=UTXN.user_id');
        $this->db->order_by('transaction_on','DESC');
        $query = $this->db->get('users_transactions UTXN',$limit, $start);
        // print_r("<br/><br/>".$this->db->last_query());
        return $query->result();
    }
}
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Actionsearch extends CI_Controller
{
    public function search_pincodes()
    {
        // define the list of fields
        $fields = array('pincode', 'pin_city', 'pin_state');
        $conditions = array();

        // loop through the defined fields
        foreach($fields as $field)
        {
            // if the field is set and not empty
            if(isset($_POST[$field]) && $_POST[$field] != '')
                $conditions[$field] = $_POST[$field];
        }

        // print_r($conditions)."<br/>";

        $config = array();
        $config["base_url"] = base_url() . "master_pincodes";
        $config["total_rows"] = $this->searchdata_model->count_records($conditions,'tbl_pincodes');
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['pincodes'] = $this->searchdata_model->search_pincode($conditions,$config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['row_count'] = $config["total_rows"];

        $output ='';
        if(!empty($data['pincodes']))
        {
            $output .= '<div class="table-responsive" id="render_searchdata">
                <table id="datatable-search" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Pincode</th>
                            <th class="text-center">City</th>
                            <th class="text-center">State</th>
                            <th class="text-center" style="width: 5%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h5><b><span class="text-success">Found: '.$data['row_count'].'</span> (filtered from '.$this->db->count_all('tbl_pincodes').' records)</b></h5>';

                        foreach ($data['pincodes'] as $pin)
                        {
                            $output .='<tr>
                                <td class="text-center">'. $pin->pincode.'</td>
                                <td class="text-center">'. $pin->pin_city.'</td>
                                <td class="text-center">'. $pin->pin_state.'</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="#modal-add-edit" data-toggle="modal" title="Edit" class="btn btn-xs btn-default" data-id="'. $pin->pincode_id.'"><i class="fa fa-pencil"></i></a>
                                    </div>
                                </td>
                            </tr>';
                        }

            $output .='</tbody></table>
                        <div class="text-right">'. $data['links'].'</div>
            </div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }

    public function search_pinservices()
    {
        // define the list of fields
        $post_fields = array('account_id','pincode');
        $conditions = $this->searchdata_model->search_condition($post_fields);

        // print_r($conditions)."<br/>";
        // die();

        $config = array();
        $config["base_url"] = base_url() . "master_pinservices";
        $config["total_rows"] = $this->searchdata_model->count_records($conditions,'master_pincodeserviceability');
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;

        $data['pincodeservices'] = $this->searchdata_model->search_pinservices($conditions,$config["per_page"], $page);
        $data["links"] = $this->pagination->create_links();
        $data['row_count'] = $config["total_rows"];

        $output ='';
        if(!empty($data['pincodeservices']))
        {
            $output .= '<div class="table-responsive" id="render_searchdata">
                <table id="datatable-search" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Pincode</th>
                            <th class="text-center">Pickup</th>
                            <th class="text-center">Reverse</th>
                            <th class="text-center">Prepaid</th>
                            <th class="text-center">COD</th>
                            <th class="text-center">DG</th>
                            <th class="text-center">NDD</th>
                            <th class="text-center">Added On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h5><b><span class="text-success">Found: '.$data['row_count'].' records)</b></h5>';

                        foreach ($data['pincodeservices'] as $pinservice)
                        {
                            $output .='<tr>
                                <td class="text-center">'. $pinservice->pincode.'</td>
                                <td class="text-center">'. $pinservice->pickup.'</td>
                                <td class="text-center">'. $pinservice->reverse.'</td>
                                <td class="text-center">'. $pinservice->prepaid.'</td>
                                <td class="text-center">'. $pinservice->cod.'</td>
                                <td class="text-center">'. $pinservice->dangerous_goods.'</td>
                                <td class="text-center">'. $pinservice->ndd.'</td>
                                <td class="text-center">'. $pinservice->added_on.'</td>
                            </tr>';
                        }
            $output .='</tbody></table>
                        <div class="text-right">'. $data['links'].'</div>
            </div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }

    public function search_zones()
    {
        // define the list of fields
        $fields = array('source_city', 'destination_pin');
        $conditions = array();

        // loop through the defined fields
        foreach($fields as $field)
        {
            // if the field is set and not empty
            if(isset($_POST[$field]) && $_POST[$field] != '')
                $conditions[$field] = $_POST[$field];
        }

        // print_r($conditions)."<br/>";

        $config = array();
        $config["base_url"] = base_url() . "master_zones";
        $config["total_rows"] = $this->searchdata_model->count_records($conditions,'tbl_zone');
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['zones'] = $this->searchdata_model->search_zone($conditions,$config["per_page"], $page);
        $data["pages"] = $this->pagination->create_links();
        $data['row_count'] = $config["total_rows"];

        $output ='';
        if(!empty($data['zones']))
        {
            $output .= '<div class="table-responsive" id="render_searchdata">
                <table id="datatable-search" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Source City</th>
                            <th class="text-center">Destination Pin</th>
                            <th class="text-center">Zone</th>
                            <th class="text-center" style="width: 5%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h5><b><span class="text-success">Found: '.$data['row_count'].'</span> (filtered from '.$this->db->count_all('tbl_pincodes').' records)</b></h5>';

                        foreach ($data['zones'] as $zone)
                        {
                            $output .='<tr>
                                <td class="text-center">'. $zone->source_city.'</td>
                                <td class="text-center">'. $zone->destination_pin.'</td>
                                <td class="text-center">'. $zone->zone.'</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="javascript:void(0)" data-toggle="tooltip" title="Edit" class="btn btn-xs btn-default"   onclick="getedit_data('.$zone->zone_id.')"><i class="fa fa-pencil"></i></a>
                                    </div>
                                </td>
                            </tr>';
                        }

            $output .='</tbody></table>
                        <div class="text-right">'. $data['pages'].'</div>
            </div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }

    public function search_users()
    {
        $config = array();
        $config["base_url"] = base_url() . "users";
        $config["total_rows"] = count($this->searchdata_model->search_user($_POST));
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);

        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['users'] = $this->searchdata_model->search_user($_POST,$config["per_page"], $page);
        $data["paginate"] = $this->pagination->create_links();
        $data['row_count'] = $config["total_rows"];

        $output ='';
        if(!empty($data['users']))
        {
            $output .= '<div class="table-responsive" id="render_searchdata">
                <table id="datatable-search" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Username</th>
                            <th class="text-center">BusinessName/Type</th>
                            <th class="text-center">Contact</th>
                            <th class="text-center">Billing Type</th>
                            <th class="text-center">State</th>
                            <th class="text-center">Reg.On</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" width="15%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h5><b><span class="text-success">Found: '.$data['row_count'].'</span> (filtered from '.$this->db->count_all('users').' records)</b></h5>';

                        foreach ($data['users'] as $user)
                        {
                            $output .='<tr>
                                <td class="text-center">'.$user->username.'</td>
                                <td class="text-center">'.$user->business_name.'<br/>'.$user->business_type.'</td>
                                <td class="text-center">'.$user->contact.'<br/>'.$user->alt_contact.'</td>
                                <td class="text-center">'.ucwords($user->billing_type).'</td>
                                <td class="text-center">'.$user->billing_state.'</td>
                                <td class="text-center">'.$user->added_on.'</td>
                                <td class="text-center">';
                                    if($user->account_status==1)
                                    {
                                        $output .='<a href="javascript:void(0);" onclick="changestatus('.$user->user_id.','.$user->account_status.')" class="label label-success">Active</a>';
                                    }
                                    else if($user->account_status==2)
                                    {
                                       $output .='<a href="javascript:void(0);" onclick="changestatus('.$user->user_id.','.$user->account_status.')" class="label label-danger">Blocked</a>';
                                    }
                                $output .='</td>  

                                <td class="text-center">
                                    <div class="btn-group">
                                        <a href="users_ratechart?uid='.base64_encode($user->user_id).'" target="_blank" data-toggle="tooltip" title="Manage Ratechart" class="btn btn-xs btn-success"><i class="fa fa-money"></i></a>

                                        <a href="users_courierpriority?uid='.base64_encode($user->user_id).'" target="_blank" data-toggle="tooltip" title="Set Courier Priority" class="btn btn-xs btn-info" style="margin-left: 5px;"><i class="gi gi-cargo"></i></a>

                                        <a href="users_weightslab?uid='.base64_encode($user->user_id).'" target="_blank" data-toggle="tooltip" title="Add Weight Slabs" class="btn btn-xs btn-warning" style="margin-left: 5px;"><i class="fa fa-balance-scale"></i></a>

                                        <a href="modifyuser?uid='.base64_encode($user->user_id).'" target="_blank" data-toggle="tooltip" title="Modify User" class="btn btn-xs btn-default" style="margin-left: 5px;"><i class="fa fa-pencil"></i></a>
                                    </div>
                                </td>
                            </tr>';
                        }

            $output .='</tbody></table>
                        <div class="text-right">'. $data['paginate'].'</div>
            </div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }

    public function search_invoice()
    {
        $date_condition="";
        // define the list of fields
        $post_fields = array('px_invoice_number', 'username', 'business_name','billing_type','invoice_status');
        $conditions = $this->searchdata_model->search_condition($post_fields);

        if(isset($_POST['from_date']) && $_POST['from_date'] != '' && isset($_POST['to_date']) && $_POST['to_date'] != '')
        $date_condition = "BETWEEN '".date('Y-m-d',strtotime($_POST['from_date'])). "' AND '".date('Y-m-d',strtotime($_POST['to_date']))."'";

        $config = array();
        $config["base_url"] = base_url() . "view_invoice";
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['invoices'] = $this->searchdata_model->search_invoice($conditions,$config["per_page"], $page,$date_condition,'invoice_date');
        $data["paginate"] = $this->pagination->create_links();
        $output ='';
        if(!empty($data['invoices']))
        {
            $output .= '<table id="datatable-search" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Invoice</th>
                            <th class="text-center">Date</th>
                            <th class="text-center">Business/Billing</th>
                            <th class="text-center">Account Details</th>
                            <th class="text-center">Counts</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Settlements</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Modified</th>
                            <th class="text-center" style="width: 10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>';
                        foreach ($data['invoices'] as $invdata)
                        {
                            $output .='<tr>
                                <td class="text-center">
                                    <a href="invoice?invoice='.base64_encode($invdata->px_invoice_number).'"><u><b>'.$invdata->px_invoice_number.'</b></u></a>
                                </td>
                                <td class="text-center">'.date('d-m-Y', strtotime($invdata->invoice_date)).'</td>
                                <td class="text-center">'.$invdata->business_name.'<br/>('.ucwords($invdata->billing_type).')</td>
                                <td class="text-center">'.$invdata->username.'<br/>'.$invdata->contact.'<br/>'.$invdata->alt_contact.'</td>
                                <td class="text-center">
                                    <a href="'.base_url('Actionexport/download_invoice_awbs').'?inv='.$invdata->px_invoice_number.'"><u><b>'.$invdata->shipments_count.'</b></u></a>
                                </td>
                                <td class="text-center">Invoice: '.$invdata->invoice_amount.'<br/>GST: '.$invdata->gst_amount.'<br/>Total: '.$invdata->total_amount.'</td>
                                <td class="text-center">Paid:'.$invdata->paid_amount.'<br/>Due:'.$invdata->due_amount.'</td>
                                <td class="text-center">';
                                    if($invdata->invoice_status=="1")
                                        $output .='<span class="label label-success">Paid</span>';
                                    else if($invdata->invoice_status=="0")
                                        $output .='<span class="label label-danger">Due</span>';
                                    else if($invdata->invoice_status=="2")
                                        $output .='<span class="label label-warning">Pending</span>';
                                $output .='</td>
                                <td class="text-center">'.$invdata->updated_on.'</td>
                                <td class="text-center">
                                    <div class="btn-group">';
                                        if($invdata->invoice_status=="0" && $invdata->billing_type=="postpaid")
                                        {
                                            $output .='<a href="add_payment?inv='.base64_encode($invdata->px_invoice_number).'" target="_blank" title="Add Payment" class="btn btn-xs btn-success"><i class="fa fa-money"></i></a>';
                                        }

                                        $output .='<a href="invoice?invoice='.base64_encode($invdata->px_invoice_number).'" target="_blank" title="View Invoice" class="btn btn-xs btn-info" style="margin-left: 5px;"><i class="fa fa-file-pdf-o"></i></a>

                                        <a href="'.base_url('Actionexport/download_invoice_awbs').'?inv='.$invdata->px_invoice_number.'" title="Download AWBs" class="btn btn-xs btn-default" style="margin-left: 5px;"><i class="fa fa-download"></i></a>
                                    </div>
                                </td>
                            </tr>';
                        }
            $output .='</tbody></table>
                        <div class="text-right">'. $data['paginate'].'</div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }

    public function search_invoicepayments()
    {
        // define the list of fields
        $post_fields = array('invoice_number', 'user_id');
        $conditions = $this->searchdata_model->search_condition($post_fields);

        $config = array();
        $config["base_url"] = base_url() . "add_payment";
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['payments'] = $this->searchdata_model->search_invoicepayments($conditions,$config["per_page"], $page);
        $data["paginate"] = $this->pagination->create_links();
        $output ='';
        if(!empty($data['payments']))
        {
            $output .= '<table id="datatable-search" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">Paid On</th>
                            <th class="text-center">Paid Amount</th>
                            <th class="text-center">Payment Mode</th>
                            <th class="text-center">Transaction #/Id</th>
                            <th class="text-center">Remark</th>
                            <th class="text-center">Added By</th>
                            <th class="text-center">Added On</th>
                        </tr>
                    </thead>
                    <tbody>';
                        foreach ($data['payments'] as $paydata)
                        {
                            $output .='<tr>
                                <td class="text-center">'.date('d-m-Y', strtotime($paydata->payment_date)).'</td>
                                <td class="text-center">'.$paydata->payment_amount.'</td>
                                <td class="text-center">'.$paydata->payment_mode.'</td>
                                <td class="text-center">'.$paydata->transaction_id.'</td>
                                <td class="text-center">'.$paydata->payment_remark.'</td>
                                <td class="text-center">'.$paydata->added_by.'</td>
                                <td class="text-center">'.$paydata->added_on.'</td>
                            </tr>';
                        }
            $output .='</tbody></table>
                        <div class="text-right">'. $data['paginate'].'</div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No payments added for selected invoice number.</b></h5>';

        echo $output;
    }

    public function search_cods()
    {
        $date_condition="";
        // define the list of fields
        $post_fields = array('cod_id', 'username', 'codadjust','billing_type','cod_status');
        $conditions = $this->searchdata_model->search_condition($post_fields);

        if(isset($_POST['from_date']) && $_POST['from_date'] != '' && isset($_POST['to_date']) && $_POST['to_date'] != '')
        $date_condition = "BETWEEN '".date('Y-m-d',strtotime($_POST['from_date'])). "' AND '".date('Y-m-d',strtotime($_POST['to_date']))."'";

        $config = array();
        $config["base_url"] = base_url() . "view_cods";
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        
        $data['cods'] = $this->searchdata_model->search_cods($conditions,$config["per_page"], $page,$date_condition,'cod_cycle_date');
        $data["paginate"] = $this->pagination->create_links();
        $output ='';
        if(!empty($data['cods']))
        {
            $output .= '<table id="datatable-search" class="table table-vcenter table-condensed table-bordered">
                    <thead>
                        <tr>
                            <th class="text-center">COD #</th>
                            <th class="text-center">COD_Date</th>
                            <th class="text-center">Business/Billing</th>
                            <th class="text-center">Profile Details</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Account Details</th>
                            <th class="text-center">Adjust COD</th>
                            <th class="text-center">Status</th>
                            <th class="text-center" style="width: 10%">Actions</th>
                        </tr>
                    </thead>
                    <tbody>';
                        foreach ($data['cods'] as $codData)
                        {
                            $output .='<tr>
                                <td class="text-center"><b>'.$codData->cod_id.'</b></td>
                                <td class="text-center">'.date('d-m-Y', strtotime($codData->cod_cycle_date)).'</td>
                                <td class="text-center">'.$codData->business_name.'<br/>('.ucwords($codData->billing_type).')</td>
                                <td class="text-center">'.$codData->email_id.'<br/>'.$codData->contact.'<br/>'.$codData->alt_contact.'</td>
                                <td class="text-center">'.$codData->cod_amount.'</td>
                                <td class="text-center">Beneficary:'.$codData->beneficiary_name.'<br/>A/C #:'.$codData->account_number.'<br/>IFSC:'.$codData->ifsc_code.'<br/>Bank:'.$codData->bank_name.'</td>
                                <td class="text-center">'. ucwords($codData->codadjust).'</td>
                                <td class="text-center">';
                                    if($codData->cod_status=="0")
                                        $output .='<span class="label label-default">Generated</span>';
                                    else if($codData->cod_status=="1")
                                        $output .='<span class="label label-success">Remitted</span>';
                                    else if($codData->cod_status=="2")
                                        $output .='<span class="label label-warning">Adjusted</span>';
                                    else if($codData->cod_status=="3")
                                        $output .='<span class="label label-info">Accruing</span>';
                                $output .='</td>
                                <td class="text-center">
                                    <div class="btn-group">
                                       <a href="'.base_url('Actionexport/download_cod_awbs').'?cod_id='.$codData->cod_id.'" title="Download AWBs" class="btn btn-xs btn-info" style="margin-left: 5px;"><i class="fa fa-download"></i></a>
                                    </div>
                                </td>
                            </tr>';
                        }
            $output .='</tbody></table>
                        <div class="text-right">'. $data['paginate'].'</div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }

    public function search_shipments()
    {
        $config = array();
        $config["base_url"] = base_url() . "reports/shipments";
        $config["total_rows"] = count($this->searchdata_model->search_shipments($_POST));
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['data'] = $this->searchdata_model->search_shipments($_POST,$config["per_page"], $page);
        $data["paginate"] = $this->pagination->create_links();
        $output ='';
        if(!empty($data['data']))
        {
            $output .= '<table id="datatable-search" class="table table-vcenter table-condensed table-bordered dt-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">Username</th>
                            <th class="text-center">UserDetails</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">OrderId</th>
                            <th class="text-center">AWB #</th>
                            <th class="text-center">Mode/Express</th>
                            <th class="text-center">COD Amount</th>
                            <th class="text-center">OrderDate</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Customer Details</th>
                            <th class="text-center">Customer Address</th>
                            <th class="text-center">FulfilledBy</th>
                            <th class="text-center">Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h5><b>Total '.$config["total_rows"].' Records</b></h5>';
                        foreach ($data['data'] as $records)
                        {
                            $output .='<tr>
                                <td class="text-center">'.$records->username.'</td>
                                <td class="text-center">'.$records->fullname."<br/>".$records->business_name."<br/>".$records->contact.'</td>
                                <td class="text-center">'.ucwords($records->shipment_type).'</td>
                                <td class="text-center">'.$records->shipment_id.'</td>
                                <td class="text-center">'.$records->waybill_number.'</td>
                                <td class="text-center">'.$records->payment_mode."<br/>".ucwords($records->express_type).'</td>
                                <td class="text-center">'.$records->cod_amount.'</td>
                                <td class="text-center">'.date('d-m-Y',strtotime($records->order_date)).'</td>
                                <td class="text-center">'.$records->status_title.'</td>
                                <td class="text-center">'.$records->consignee_name."<br/>".$records->consignee_mobile.'</td>
                                <td class="text-center">'.$records->consignee_address1.",".$records->consignee_address2."<br/>".$records->consignee_city.",".$records->consignee_state."-".$records->consignee_pincode.'</td>
                                <td class="text-center">'.$records->account_name.'</td>
                                <td class="text-center">'.$records->remark_1.'</td>
                            </tr>';
                        }
            $output .='</tbody></table>
                        <div class="text-right">'. $data['paginate'].'</div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }

    public function search_failedshipments()
    {
        $config = array();
        $config["base_url"] = base_url() . "reports/failedshipments";
        $config["total_rows"] = count($this->searchdata_model->search_failedshipments($_POST));
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['data'] = $this->searchdata_model->search_failedshipments($_POST,$config["per_page"], $page);
        $data["paginate"] = $this->pagination->create_links();
        
        $output ='';
        if(!empty($data['data']))
        {
            $output .= '<table id="datatable-search" class="table table-vcenter table-condensed table-bordered dt-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">Username</th>
                            <th class="text-center">Type</th>
                            <th class="text-center">OrderId</th>
                            <th class="text-center">Mode/Express</th>
                            <th class="text-center">OrderDate</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Customer Details</th>
                            <th class="text-center">Customer Address</th>
                            <th class="text-center">Remark</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h5><b>Total '.$config["total_rows"].' Records</b></h5>';
                        foreach ($data['data'] as $records)
                        {
                            $output .='<tr>
                                <td class="text-center">'.$records->username.'</td>
                                <td class="text-center">'.ucwords($records->shipment_type).'</td>
                                <td class="text-center">'.$records->shipment_id.'</td>
                                <td class="text-center">'.$records->payment_mode."<br/>".ucwords($records->express_type).'</td>
                                <td class="text-center">'.date('d-m-Y',strtotime($records->order_date)).'</td>
                                <td class="text-center">'.$records->status_title.'</td>
                                <td class="text-center">'.$records->consignee_name."<br/>".$records->consignee_mobile.'</td>
                                <td class="text-center">'.$records->consignee_address1.",".$records->consignee_address2."<br/>".$records->consignee_city.",".$records->consignee_state."-".$records->consignee_pincode.'</td>
                                <td class="text-center">'.$records->remark_1.'</td>
                            </tr>';
                        }
            $output .='</tbody></table>
                        <div class="text-right">'. $data['paginate'].'</div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }

    public function search_viewbalance()
    {
        $config = array();
        $config["base_url"] = base_url() . "reports/viewbalance";
        $config["total_rows"] = count($this->searchdata_model->search_viewbalance($_POST));
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['data'] = $this->searchdata_model->search_viewbalance($_POST,$config["per_page"], $page);
        $data["paginate"] = $this->pagination->create_links();
        
        $output ='';
        if(!empty($data['data']))
        {
            $output .= '<table id="datatable-search" class="table table-vcenter table-condensed table-bordered dt-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">Username</th>
                            <th class="text-center">Customer Details</th>
                            <th class="text-center">Main Balance</th>
                            <th class="text-center">Promo Balance</th>
                            <th class="text-center">Total Wallet Balance</th>
                            <th class="text-center">Last Updated On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h5><b>Total '.$config["total_rows"].' Records</b></h5>';
                        foreach ($data['data'] as $records)
                        {
                            $output .='<tr>
                                <td class="text-center">'.$records->username."<br/>".$records->business_name.'</td>
                                <td class="text-center">'.$records->fullname."<br/>".$records->contact.'</td>
                                <td class="text-center">'.$records->main_balance.'</td>
                                <td class="text-center">'.$records->promo_balance.'</td>
                                <td class="text-center">'.$records->total_balance.'</td>
                                <td class="text-center">'.$records->updated_on.'</td>
                            </tr>';
                        }
            $output .='</tbody></table>
                        <div class="text-right">'. $data['paginate'].'</div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }

    public function search_allpayments()
    {
        $config = array();
        $config["base_url"] = base_url() . "reports/allpayments";
        $config["total_rows"] = count($this->searchdata_model->search_allpayments($_POST));
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['data'] = $this->searchdata_model->search_allpayments($_POST,$config["per_page"], $page);
        $data["paginate"] = $this->pagination->create_links();
        
        $output ='';
        if(!empty($data['data']))
        {
            $output .= '<table id="datatable-search" class="table table-vcenter table-condensed table-bordered dt-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">Username</th>
                            <th class="text-center">CustomerDetails</th>
                            <th class="text-center">Billing</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Txn Detail</th>
                            <th class="text-center">Transaction Ref. #</th>
                            <th class="text-center">Payment Id</th>
                            <th class="text-center">Transaction On</th>
                            <th class="text-center">Txn By</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h5><b>Total '.$config["total_rows"].' Records</b></h5>';
                        foreach ($data['data'] as $records)
                        {
                            $output .='<tr>
                                <td class="text-center">'.$records->username."<br/>".$records->business_name.'</td>
                                <td class="text-center">'.$records->fullname."<br/>".$records->contact.'</td>
                                <td class="text-center">'.ucwords($records->billing_type).'</td>
                                <td class="text-center">'.$records->transaction_amount.'</td>
                                <td class="text-center">'.$records->transaction_remark.'</td>
                                <td class="text-center">'.$records->transaction_reference_id.'</td>
                                <td class="text-center">'.$records->razorpay_payment_id.'</td>
                                <td class="text-center">'.$records->transaction_on.'</td>
                                <td class="text-center">'.$records->added_by.'</td>
                            </tr>';
                        }
            $output .='</tbody></table>
                        <div class="text-right">'. $data['paginate'].'</div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }

    public function search_alltransactions()
    {
        $config = array();
        $config["base_url"] = base_url() . "reports/alltransactions";
        $config["total_rows"] = count($this->searchdata_model->search_alltransactions($_POST));
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['data'] = $this->searchdata_model->search_alltransactions($_POST,$config["per_page"], $page);
        $data["paginate"] = $this->pagination->create_links();
        
        $output ='';
        if(!empty($data['data']))
        {
            $output .= '<table id="datatable-search" class="table table-vcenter table-condensed table-bordered dt-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">Username</th>
                            <th class="text-center">CustomerDetails</th>
                            <th class="text-center">OrderDetails</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Txn Detail</th>
                            <th class="text-center">Transaction Ref. #</th>
                            <th class="text-center">Action</th>
                            <th class="text-center">Transaction On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h5><b>Total Count: <span class="text-danger">'.$config["total_rows"].' Record(s)</span> |
                        Total Amount: <span class="text-success" id="amt_sum">fff</span> INR</b></h5>';
                        foreach ($data['data'] as $records)
                        {
                            $output .='<tr>
                                <td class="text-center">'.$records->username."<br/>".$records->business_name.'</td>
                                <td class="text-center">'.$records->fullname."<br/>".$records->contact.'</td>
                                <td class="text-center">'.$records->shipment_id."<br/>".$records->waybill_number.'</td>
                                <td class="text-center">'.$records->transaction_amount.'</td>
                                <td class="text-center">'.$records->txn_rmk.'</td>
                                <td class="text-center">'.$records->transaction_reference_id.'</td>
                                <td class="text-center">'.ucwords($records->action_type).'</td>
                                <td class="text-center">'.$records->transaction_on.'</td>
                            </tr>';
                        }
            $output .='</tbody></table>
                        <div class="text-right">'. $data['paginate'].'</div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }

    public function search_userledger()
    {
        $config = array();
        $config["base_url"] = base_url() . "reports/userledger";
        $config["total_rows"] = count($this->searchdata_model->search_userledger($_POST));
        $config["per_page"] = 100;

        $config["full_tag_open"] = '<ul class="pagination">';
        $config["full_tag_close"] = '</ul>';

        $config["next_tag_open"] = '<li>';
        $config["next_tag_close"] = '</li>';
        $config["prev_tag_open"] = '<li>';
        $config["prev_tag_close"] = '</li>';

        $config["num_tag_open"] = '<li>';
        $config["num_tag_close"] = '</li>';

        $config["first_tag_open"] = '<li>';
        $config["first_tag_close"] = '</li>';
        $config["last_tag_open"] = '<li>';
        $config["last_tag_close"] = '</li>';

        $config["cur_tag_open"] = '<li class="active"><a href="javascript:void(0)">';
        $config["cur_tag_close"] = '</a></li>';

        $this->pagination->initialize($config);
        $page = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $data['data'] = $this->searchdata_model->search_userledger($_POST,$config["per_page"], $page);
        $data["paginate"] = $this->pagination->create_links();
        
        $output ='';
        if(!empty($data['data']))
        {
            $output .= '<table id="datatable-search" class="table table-vcenter table-condensed table-bordered dt-responsive">
                    <thead>
                        <tr>
                            <th class="text-center">Transaction On</th>
                            <th class="text-center">Reference #</th>
                            <th class="text-center">Order #</th>
                            <th class="text-center">Waybill #</th>
                            <th class="text-center">Particulars</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Opening Balance</th>
                            <th class="text-center">Closing Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        <h5><b>Total Count: <span class="text-danger">'.$config["total_rows"].' Record(s)</b></h5>';
                        foreach ($data['data'] as $records)
                        {
                            $output .='<tr>
                                <td class="text-center">'. date('d-m-Y H:i:s',strtotime($records->transaction_on)).'</td>
                                <td class="text-center">'.$records->transaction_reference_id.'</td>
                                <td class="text-center">'.$records->shipment_id.'</td>
                                <td class="text-center">'.$records->waybill_number.'</td>
                                <td class="text-center">'.$records->transaction_remark.'</td>
                                <td class="text-center"><b>'.$records->transaction_amount.'</b></td>
                                <td class="text-center">'.$records->opening_balance.'</td>
                                <td class="text-center">'.$records->closing_balance.'</td>
                            </tr>';
                        }
            $output .='</tbody></table>
                        <div class="text-right">'. $data['paginate'].'</div>';
        }
        else
            $output .= '<h5 class="text-center"><b>No data available for selected filter(s).</b></h5>';

        echo $output;
    }
}
?>
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Billing extends CI_Controller
{
    public function __construct()
    {
      parent::__construct();
      $this->load->helper('billings');
      $this->load->helper('file_upload');
      $this->load->helper('excel_data_validate');
    }

    public function get_prepaidusers()
    {
        $result=$this->billing_model->get_prepaiduser($this->input->post('id'));
        if($result!='0')
            echo $result['user_id']."#".$result['fullname']."#".$result['business_name']."#".$result['main_balance']."#".$result['promo_balance']."#".$result['total_balance'];
        else
           echo '0';
    }

    /* For UserBalances  */
    public function manage_balances()
    {
        $txn_ref="";
        $main_amt=$this->input->post('main_bal');
        $promo_amt=$this->input->post('promo_bal');
        $user_id=$this->input->post('user_id');
        $this->form_validation->set_rules('user_id', 'Username', 'required|trim');
        $this->form_validation->set_rules('balance_type', 'Balance', 'required');
        $this->form_validation->set_rules('action_type', 'Action', 'required');
        $this->form_validation->set_rules('transaction_amount', 'Amount', 'required|trim');
        $this->form_validation->set_rules('transaction_remark', 'Remark', 'trim');

        if($this->form_validation->run() == TRUE)
        {
            if($this->input->post('action_type')=='credit')
            {
                $txn_ref=$this->input->post('user_id').now().'C'.rand(999,100000);
                $txn_type='1001';

                if($this->input->post('balance_type')=='main')
                    $main_amt+=$this->input->post('transaction_amount');
                else if($this->input->post('balance_type')=='promo')
                    $promo_amt+=$this->input->post('transaction_amount');
            }
            else if($this->input->post('action_type')=='debit')
            {
                $txn_ref=$this->input->post('user_id').now().'D'.rand(999,100000);
                $txn_type='1006';

                if($this->input->post('balance_type')=='main')
                    $main_amt-=$this->input->post('transaction_amount');
                else if($this->input->post('balance_type')=='promo')
                    $promo_amt-=$this->input->post('transaction_amount');
            }
            
            $form_data_txn = array(
                'user_id' => $this->input->post('user_id'),
                'balance_type' => $this->input->post('balance_type'),
                'action_type' => $this->input->post('action_type'),
                'transaction_reference_id' => $txn_ref,
                'transaction_type' => $txn_type,
                'transaction_status' => '551',
                'transaction_amount' => $this->input->post('transaction_amount'),
                'opening_balance' => $this->input->post('total_balance'),
                'closing_balance' => $main_amt+$promo_amt,
                'transaction_remark' => $this->input->post('transaction_remark'),
                'added_by' => $this->session->userdata['user_session']['admin_username'],
            );

            $form_data_bal = array(
                'user_id' => $this->input->post('user_id'),
                'main_balance' => $main_amt,
                'promo_balance' => $promo_amt,
                'total_balance' => $main_amt+$promo_amt,
                'updated_by' => $this->session->userdata['user_session']['admin_username'],
            );

            $tracking_data = array(
                'activity_type' => "manage_balance",
                'log_data' => json_encode($this->input->post()),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );       

            if($this->billing_model->manage_balance($form_data_txn,$form_data_bal,$user_id) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'User balance '.$this->input->post('action_type').'ed successfully.';
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

    //To Change Status Manually
    public function change_status()
    {        
        $date = date('Y-m-d',strtotime($this->input->post('status_date')));
        $del_date = date('d',strtotime($this->input->post('status_date')));
        $awb = $this->input->post('awb_num');
        $cod_data = array();
        $order_details = $this->billing_model->get_orderdetails_invoice($awb);
        // print_r($order_details);

        if(!empty($order_details))
        {
            if($order_details['billing_eligibility']=='0')
            {
                $invoice_data = array (
                    'user_id'          => $order_details['user_id'],
                    'invoice_date'     => get_billingdate($order_details['billing_cycle_dates'],$del_date),
                    'invoice_amount'   => $order_details['charges_total'],
                    'gst_amount'       => $order_details['gst_amount'],
                    'total_amount'     => $order_details['total_amount'],
                    'shipments_count'  => '1',
                    'paid_amount'      => $order_details['paid_amount'],
                    'due_amount'       => $order_details['total_amount']-$order_details['paid_amount'],
                );

                if($this->input->post('order_status')=='226') // If Delivered
                {
                    // Update shipment status to Delivered
                    $query_s = "Update shipments set system_status ='106',user_status='226',updated_by='system' where system_status<>'107' AND waybill_number = '$awb'";
                    //Calculate COD Eligible Date
                    $cod_edate = date('Y-m-d', strtotime($date. ' + '.$order_details['cod_gap'].'days'));
                    //$cod_cdate = get_codcycledate($order_details['cod_cycle_dates'],date('d',strtotime($cod_edate)));
                    $cod_cdate = get_codcycledate_alt($order_details['cod_cycle_dates'],$cod_edate);
                    
                    if($order_details['payment_mode']=='COD' && $order_details['cod_status']=='0')
                    {
                        $sb_data = array(
                            'billing_eligibility'   => '1',
                            'billing_eligible_date' => $date,
                            'cod_status'            => '1',
                            'cod_date'              => $date,
                            'cod_eligible_date'     => $cod_edate,
                            'cod_cycle_date'        => $cod_cdate,
                            'updated_by'            => 'system'
                        );

                        $cod_data = array(
                            'user_id'          => $order_details['user_id'],
                            'cod_cycle_date'   => $cod_cdate,
                            'cod_amount'       => $order_details['cod_amount'],
                        );
                    }
                    else
                        $sb_data = array(
                            'billing_eligibility'   => '1',
                            'billing_eligible_date' => $date,
                            'updated_by'            => 'system'
                        );
                    
                    //Insert Data invoice_accrue_ondel
                    if($this->billing_model->invoice_accrue_ondel($query_s,$sb_data,$awb,$invoice_data,$cod_data))
                    {
                        $output['title'] = 'Congrats';
                        $output['message'] = 'Status Updated Successfully.';
                    }
                    else
                    {
                        $output['error'] = true;
                        $output['title'] = 'Error';
                        $output['message'] = 'Some Error occurred, Try again.';
                    }
                }
                else if($this->input->post('order_status')=='225') //If RTO
                {
                    // Update shipment status to RTO
                    $query_s = "Update shipments set system_status = '115',user_status='225',updated_by='system' where system_status<>'107' AND waybill_number = '$awb'";

                    //Calculate RTO Charges & Update Amounts
                    $sb_data = $this->getdata_model->get_rtocharges($awb);

                    $sb_data['billing_eligibility']='1';
                    $sb_data['billing_eligible_date']=$date;
                    $sb_data['cod_status']='3';
                    $sb_data['updated_by']='system';

                    //Insert Data invoice_accrue_onrto
                    if($this->billing_model->invoice_accrue_onrto($query_s,$sb_data,$awb,$invoice_data,$order_details['billing_type']))
                    {
                        $output['title'] = 'Congrats';
                        $output['message'] = 'Status Updated Successfully.';
                    }
                    else
                    {
                        $output['error'] = true;
                        $output['title'] = 'Error';
                        $output['message'] = 'Some Error occurred, Try again.';
                    }
                }
            }
            else if($order_details['billing_eligibility']=='1')
            {
                if($this->input->post('order_status')=='226' && $order_details['user_status'] != '226') // If Delivered from RTO
                {
                    // Update shipment status to Delivered
                    $query_s = "Update shipments set system_status ='106',user_status='226',updated_by='system' where system_status<>'107' AND waybill_number = '$awb'";

                    //Calculate Forward Charges
                    $sb_data = $this->getdata_model->get_fwdcharges($awb);

                    //Calculate COD Eligible Date
                    $cod_edate = date('Y-m-d', strtotime($date. ' + '.$order_details['cod_gap'].'days'));
                    // $cod_cdate = get_codcycledate($order_details['cod_cycle_dates'],date('d',strtotime($cod_edate)));
                    $cod_cdate = get_codcycledate_alt($order_details['cod_cycle_dates'],$cod_edate);
                    
                    if($order_details['payment_mode']=='COD' && $order_details['cod_status']=='3')
                    {
                        $sb_data['cod_status']        = '1';
                        $sb_data['cod_eligible_date'] = $cod_edate;
                        $sb_data['cod_cycle_date']    = $cod_cdate;
                        $sb_data['cod_date']          = $date;

                        $cod_data = array(
                            'user_id'          => $order_details['user_id'],
                            'cod_cycle_date'   => $cod_cdate,
                            'cod_amount'       => $order_details['cod_amount'],
                        );
                    }
                    
                    // print_r($sb_data);

                    $invoice_data_diff = array(
                        'user_id'        => $order_details['user_id'],
                        'difference_amt' => $sb_data['charges_total'] - $order_details['charges_total'],
                        'difference_gst' => $sb_data['gst_amount'] - $order_details['gst_amount'],
                        'difference_tot' => $sb_data['total_amount'] - $order_details['total_amount'],
                    );               
    
                    if($this->billing_model->status_update_delrto($query_s,$sb_data,$awb,$invoice_data_diff,$cod_data,$order_details['billing_type']))
                    {
                        $output['title'] = 'Congrats';
                        $output['message'] = 'Status Updated Successfully.';
                    }
                    else
                    {
                        $output['error'] = true;
                        $output['title'] = 'Error';
                        $output['message'] = 'Some Error occurred, Try again.';
                    }
                }
                else if($this->input->post('order_status')=='225' && $order_details['user_status'] != '225') // If RTO from Delivered
                {
                    // Update shipment status to RTO
                    $query_s = "Update shipments set system_status = '115',user_status='225',updated_by='system' where system_status<>'107' AND waybill_number = '$awb'";

                    //Calculate RTO Charges & Update Amounts
                    $sb_data = $this->getdata_model->get_rtocharges($awb);
                    $sb_data['cod_status']        = '3';

                    if($order_details['payment_mode']=='COD' && $order_details['cod_status']=='1')
                    {
                        $sb_data['cod_eligible_date'] = '';
                        $sb_data['cod_cycle_date']    = '';
                        // $sb_data['cod_trn']           = '';
                        $sb_data['cod_date']          = '';

                        $cod_data = array(
                            'user_id'          => $order_details['user_id'],
                            'cod_amount'       => '-'.$order_details['cod_amount'],
                        );
                    }

                    $invoice_data_diff = array(
                        'user_id'        => $order_details['user_id'],
                        'difference_amt' => $sb_data['charges_total'] - $order_details['charges_total'],
                        'difference_gst' => $sb_data['gst_amount'] - $order_details['gst_amount'],
                        'difference_tot' => $sb_data['total_amount'] - $order_details['total_amount'],
                    );               
    
                    if($this->billing_model->status_update_delrto($query_s,$sb_data,$awb,$invoice_data_diff,$cod_data,$order_details['billing_type']))
                    {
                        $output['title'] = 'Congrats';
                        $output['message'] = 'Status Updated Successfully.';
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
                    $output['message'] = 'Status uptodate.';
                }
            }
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = 'Invalid waybill number';
        }

        echo json_encode($output);
    }

    //To Change Status via webhook
    public function change_status_webhook()
    {
        $_POST = (array) json_decode(file_get_contents('php://input'), TRUE);
        // echo "\nIn Portal > Change Status\n";
        // print_r($_POST);
        // die();
        
        $date = date('Y-m-d',strtotime($_POST['status_date']));
        $del_date = date('d',strtotime($_POST['status_date']));
        $awb = $_POST['awb_num'];
        $cod_data = array();
        $order_details = $this->billing_model->get_orderdetails_invoice($awb);
        // print_r($order_details);

        if(!empty($order_details))
        {
            if($order_details['billing_eligibility']=='0')
            {
                $invoice_data = array (
                    'user_id'          => $order_details['user_id'],
                    'invoice_date'     => get_billingdate($order_details['billing_cycle_dates'],$del_date),
                    'invoice_amount'   => $order_details['charges_total'],
                    'gst_amount'       => $order_details['gst_amount'],
                    'total_amount'     => $order_details['total_amount'],
                    'shipments_count'  => '1',
                    'paid_amount'      => $order_details['paid_amount'],
                    'due_amount'       => $order_details['total_amount']-$order_details['paid_amount'],
                );

                if($_POST['order_status']=='226') // If Delivered
                {
                    // Update shipment status to Delivered
                    $query_s = "Update shipments set system_status ='106',user_status='226',updated_by='system' where system_status<>'107' AND waybill_number = '$awb'";
                    //Calculate COD Eligible Date
                    $cod_edate = date('Y-m-d', strtotime($date. ' + '.$order_details['cod_gap'].'days'));
                    //$cod_cdate = get_codcycledate($order_details['cod_cycle_dates'],date('d',strtotime($cod_edate)));
                    $cod_cdate = get_codcycledate_alt($order_details['cod_cycle_dates'],$cod_edate);
                    
                    if($order_details['payment_mode']=='COD' && $order_details['cod_status']=='0')
                    {
                        $sb_data = array(
                            'billing_eligibility'   => '1',
                            'billing_eligible_date' => $date,
                            'cod_status'            => '1',
                            'cod_date'              => $date,
                            'cod_eligible_date'     => $cod_edate,
                            'cod_cycle_date'        => $cod_cdate,
                            'updated_by'            => 'system'
                        );

                        $cod_data = array(
                            'user_id'          => $order_details['user_id'],
                            'cod_cycle_date'   => $cod_cdate,
                            'cod_amount'       => $order_details['cod_amount'],
                        );
                    }
                    else
                        $sb_data = array(
                            'billing_eligibility'   => '1',
                            'billing_eligible_date' => $date,
                            'updated_by'            => 'system'
                        );
                    
                    //Insert Data invoice_accrue_ondel
                    if($this->billing_model->invoice_accrue_ondel($query_s,$sb_data,$awb,$invoice_data,$cod_data))
                    {
                        $output['title'] = 'Congrats';
                        $output['message'] = 'Status Updated Successfully.';
                    }
                    else
                    {
                        $output['error'] = true;
                        $output['title'] = 'Error';
                        $output['message'] = 'Some Error occurred, Try again.';
                    }
                }
                else if($_POST['order_status']=='225') //If RTO
                {
                    // Update shipment status to RTO
                    $query_s = "Update shipments set system_status = '115',user_status='225',updated_by='system' where system_status<>'107' AND waybill_number = '$awb'";

                    //Calculate RTO Charges & Update Amounts
                    $sb_data = $this->getdata_model->get_rtocharges($awb);

                    $sb_data['billing_eligibility']='1';
                    $sb_data['billing_eligible_date']=$date;
                    $sb_data['cod_status']='3';
                    $sb_data['updated_by']='system';

                    //Insert Data invoice_accrue_onrto
                    if($this->billing_model->invoice_accrue_onrto($query_s,$sb_data,$awb,$invoice_data,$order_details['billing_type']))
                    {
                        $output['title'] = 'Congrats';
                        $output['message'] = 'Status Updated Successfully.';
                    }
                    else
                    {
                        $output['error'] = true;
                        $output['title'] = 'Error';
                        $output['message'] = 'Some Error occurred, Try again.';
                    }
                }
            }
            else if($order_details['billing_eligibility']=='1')
            {
                if($_POST['order_status']=='226' && $order_details['user_status'] != '226') // If Delivered from RTO
                {
                    // Update shipment status to Delivered
                    $query_s = "Update shipments set system_status ='106',user_status='226',updated_by='system' where system_status<>'107' AND waybill_number = '$awb'";

                    //Calculate Forward Charges
                    $sb_data = $this->getdata_model->get_fwdcharges($awb);

                    //Calculate COD Eligible Date
                    $cod_edate = date('Y-m-d', strtotime($date. ' + '.$order_details['cod_gap'].'days'));
                    //$cod_cdate = get_codcycledate($order_details['cod_cycle_dates'],date('d',strtotime($cod_edate)));
                    $cod_cdate = get_codcycledate_alt($order_details['cod_cycle_dates'],$cod_edate);
                    
                    if($order_details['payment_mode']=='COD' && $order_details['cod_status']=='3')
                    {
                        $sb_data['cod_status']        = '1';
                        $sb_data['cod_eligible_date'] = $cod_edate;
                        $sb_data['cod_cycle_date']    = $cod_cdate;
                        $sb_data['cod_date']          = $date;

                        $cod_data = array(
                            'user_id'          => $order_details['user_id'],
                            'cod_cycle_date'   => $cod_cdate,
                            'cod_amount'       => $order_details['cod_amount'],
                        );
                    }
                    
                    // print_r($sb_data);

                    $invoice_data_diff = array(
                        'user_id'        => $order_details['user_id'],
                        'difference_amt' => $sb_data['charges_total'] - $order_details['charges_total'],
                        'difference_gst' => $sb_data['gst_amount'] - $order_details['gst_amount'],
                        'difference_tot' => $sb_data['total_amount'] - $order_details['total_amount'],
                    );               
    
                    if($this->billing_model->status_update_delrto($query_s,$sb_data,$awb,$invoice_data_diff,$cod_data,$order_details['billing_type']))
                    {
                        $output['title'] = 'Congrats';
                        $output['message'] = 'Status Updated Successfully.';
                    }
                    else
                    {
                        $output['error'] = true;
                        $output['title'] = 'Error';
                        $output['message'] = 'Some Error occurred, Try again.';
                    }
                }
                else if($_POST['order_status']=='225' && $order_details['user_status'] != '225') // If RTO from Delivered
                {
                    // Update shipment status to RTO
                    $query_s = "Update shipments set system_status = '115',user_status='225',updated_by='system' where system_status<>'107' AND waybill_number = '$awb'";

                    //Calculate RTO Charges & Update Amounts
                    $sb_data = $this->getdata_model->get_rtocharges($awb);
                    $sb_data['cod_status']        = '3';

                    if($order_details['payment_mode']=='COD' && $order_details['cod_status']=='1')
                    {
                        $sb_data['cod_eligible_date'] = '';
                        $sb_data['cod_cycle_date']    = '';
                        // $sb_data['cod_trn']           = '';
                        $sb_data['cod_date']          = '';

                        $cod_data = array(
                            'user_id'          => $order_details['user_id'],
                            'cod_amount'       => '-'.$order_details['cod_amount'],
                        );
                    }

                    $invoice_data_diff = array(
                        'user_id'        => $order_details['user_id'],
                        'difference_amt' => $sb_data['charges_total'] - $order_details['charges_total'],
                        'difference_gst' => $sb_data['gst_amount'] - $order_details['gst_amount'],
                        'difference_tot' => $sb_data['total_amount'] - $order_details['total_amount'],
                    );               
    
                    if($this->billing_model->status_update_delrto($query_s,$sb_data,$awb,$invoice_data_diff,$cod_data,$order_details['billing_type']))
                    {
                        $output['title'] = 'Congrats';
                        $output['message'] = 'Status Updated Successfully.';
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
                    $output['message'] = 'Status uptodate.';
                }
            }
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = 'Invalid waybill number';
        }

        echo json_encode($output);
    }

    // For Weight Update Data Preview 
    public function preview_weightupdate()
    {
        $fileupload_res['weightupdate'] = excel_upload('weight_file','weight_update');

        if($fileupload_res['weightupdate']['title']=="Success")
        {
            // print_r($fileupload_res['pinservices']);
            list($form_data, $error_data) = read_weightupdatedata($fileupload_res['weightupdate']['message']);
            $error_preview ='';
            if(!empty($error_data))
            {
                $error_preview .= '<table id="datatable-preview" class="table table-vcenter table-condensed table-bordered">
                <thead>
                    <tr>
                        <th class="text-center">Waybill #</th>
                        <th class="text-center">New Weight</th>
                        <th class="text-center">Error</th>
                    </tr>
                </thead>
                <tbody>
                    <h5><b><span class="text-danger">Found: '.count($error_data). ' errors</span>.</b></h5>';

                    foreach ($error_data as $errors)
                    {
                        $error_preview .='<tr>
                            <td class="text-center">'. $errors['waybill_number'].'</td>
                            <td class="text-center">'. $errors['billing_weight'].'</td>
                            <td class="text-center">'. $errors['error'].'</td>
                        </tr>';
                    }

                    $error_preview .='</tbody></table><form method="post" id="form_weightupdateanyway" style="display:none;" onsubmit="return false;">';
                    foreach ($form_data as $data => $data_value)
                    {
                        $error_preview .='<input type="hidden" name="data['.$data.'][waybill_number]" value="'.$data_value['waybill_number'].'" />
                        <input type="hidden" name="data['.$data.'][billing_weight]" value="'.$data_value['billing_weight'].'" />
                        <input type="hidden" name="data['.$data.'][updated_by]" value="'.$data_value['updated_by'].'" />';
                    }
                    $error_preview .='<input type="hidden" name="tracking_data" value="'.$fileupload_res['weightupdate']['message'].'"/></form>';
                    $error_preview .='<div class="col-md-12" style="margin-top:15px;">
                    <button type="button" onclick="reupload();" class="btn btn-sm btn-primary" id="reuploadbtn"><i class="fa fa-repeat"></i> Reupload</button>
                    <button type="button" onclick="saveanyway();" class="btn btn-sm btn-success" id="continuebtn"><i class="fa fa-save"></i> Skip Error(s) & Continue</button></div>';

                echo json_encode(array('message' => $error_preview), JSON_HEX_QUOT | JSON_HEX_TAG);
            }
            else
            {
                list($success_count, $error_records) = $this->billing_model->update_weight($form_data);
                $tracking_data = array(
                    'activity_type' => "weight_reconciliation",
                    'log_data' => json_encode($fileupload_res['weightupdate']['message']."<br />\\n\\nUpdated ".$success_count." Records.<br />\\n\\nError Logs".json_encode($error_records)),
                    'admin_id' => $this->session->userdata['user_session']['admin_username'],
                );
                // print_r($tracking_data);
                if($this->insertions_model->activity_logs($tracking_data))
                {
                    $output['updated'] = $success_count;
                    $output['errors'] = $error_records;
                    $output['title'] = 'Success';
                    $output['action'] = 'WeightUpdate';
                }
                else
                {
                    $output['error'] = true;
                    $output['title'] = 'Error';
                    $output['message'] = 'Some Error occurred, Try again.';
                }
                echo json_encode($output);
            }
        }
        else
            echo json_encode($fileupload_res['weightupdate']);
    }

    /* For Update weight excluding errors  */
    public function weightupdate()
    {
        list($success_count, $error_records) = $this->billing_model->update_weight($this->input->post('data'));
        $tracking_data = array(
            'activity_type' => "weight_reconciliation",
            'log_data' => json_encode($this->input->post('tracking_data')."<br />\\n\\nUpdated ".$success_count." Records.<br />\\n\\nError Logs".json_encode($error_records)),
            'admin_id' => $this->session->userdata['user_session']['admin_username'],
        );
        // print_r($tracking_data);
        if($this->insertions_model->activity_logs($tracking_data))
        {
            $output['updated'] = $success_count;
            $output['errors'] = $error_records;
            $output['title'] = 'Success';
            $output['action'] = 'WeightUpdate';
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = 'Some Error occurred, Try again.';
        }
        echo json_encode($output);
    }

    //For Invoice Generation
    public function generate_invoice()
    {
        $post_data =  $this->input->post();
        if($post_data['billing_date']==date('Y-m-d', strtotime("-1 day")))
        {
            $validate_billing = $this->db->where('invoice_date',$post_data['billing_date'])->where('invoice_status','2')->get('shipments_invoices')->num_rows();
            if($validate_billing > 0)
            {
                $tracking_data = array(
                    'activity_type' => "generate_invoice",
                    'log_data' => json_encode($post_data),
                    'admin_id' => $this->session->userdata['user_session']['admin_username'],
                );

                if($this->billing_model->generate_invoice($post_data['billing_date']) && $this->insertions_model->activity_logs($tracking_data))
                {
                    $output['title'] = 'Congrats';
                    $output['message'] = 'Invoice Generated Successfully.';
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
                $output['message'] = 'Invoice already generated or <b>No</b> billing for given billing date.';
            }
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = 'Invalid billing date.';
        }
        
        echo json_encode($output);        
    }

    /* For AddingPayment against invoice  */
    public function add_invoicepayments()
    {
        $post_data =  $this->input->post();
        $due_amt = $this->db->select('due_amount')->where('px_invoice_number',$post_data['invoice_number'])
        ->where('user_id',$post_data['user_id'])->get('shipments_invoices')->row();

        // echo $due_amt->due_amount;

        $this->form_validation->set_rules('invoice_number', 'Invoice #', 'required|trim');
        $this->form_validation->set_rules('payment_amount', 'Payment Amount', 'required|trim|regex_match[/^\d{0,10}(\.\d{0,2})?$/i]|less_than_equal_to['.$due_amt->due_amount.']');
        $this->form_validation->set_rules('payment_mode', 'Payment Mode', 'required');
        $this->form_validation->set_rules('payment_date', 'Payment Date', 'required');

        $this->form_validation->set_message('less_than_equal_to', 'The %s should be less than or equal to due amount.');

        switch($post_data['payment_mode'])
        {
            case "cheque":
                $this->form_validation->set_rules('transaction_id', 'Cheque number', 'required|trim');
                break;
            case "cod_adjustment":
                $this->form_validation->set_rules('transaction_id', 'COD TRN', 'required|trim');
                break;
            case "creditnote":
                $this->form_validation->set_rules('transaction_id', 'CN number', 'required|trim');
                break;
            case "netbanking":
                $this->form_validation->set_rules('transaction_id', 'UTR Number', 'required|trim');
                break;
            case "tds":
                $this->form_validation->set_rules('transaction_id', 'TAN', 'required|trim');
                break;
        }

        if($this->form_validation->run() == TRUE)
        {
            // print_r($post_data);
            $user_tan = "";
            $form_data_pay = array(
                'user_id'           => $post_data['user_id'],
                'invoice_number'    => $post_data['invoice_number'],
                'payment_date'      => date('Y-m-d',strtotime($post_data['payment_date'])),
                'payment_amount'    => $post_data['payment_amount'],
                'payment_mode'      => strtoupper(str_replace('_',' ',$post_data['payment_mode'])),
                'payment_remark'    => $post_data['payment_remark'],
                'added_by' => $this->session->userdata['user_session']['admin_username'],
            );

            if($post_data['payment_mode'] == "tds" && empty($post_data['tan_number']))
                $user_tan = $post_data['transaction_id'];
            else if($post_data['payment_mode'] != "tds")
                $form_data_pay['transaction_id'] = 	$post_data['transaction_id'];

            $tracking_data = array(
                'activity_type' => "add_payment",
                'log_data' => json_encode($post_data),
                'admin_id' => $this->session->userdata['user_session']['admin_username'],
            );       

            if($this->billing_model->add_invoicepayment($form_data_pay,$user_tan) && $this->insertions_model->activity_logs($tracking_data))
            {
                $output['title'] = 'Congrats';
                $output['message'] = 'Payment added successfully.';
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

    //For COD Generation
    public function generate_cod()
    {
        $post_data =  $this->input->post();
        if($post_data['cod_cycle_date']==date('Y-m-d', strtotime("-1 day")))
        {
            $validate_cod = $this->db->where('cod_cycle_date',$post_data['cod_cycle_date'])->where('cod_status','3')->get('shipments_cods')->num_rows();
            if($validate_cod > 0)
            {
                $tracking_data = array(
                    'activity_type' => "generate_cod",
                    'log_data' => json_encode($post_data),
                    'admin_id' => $this->session->userdata['user_session']['admin_username'],
                );

                if($this->billing_model->generate_cod($post_data['cod_cycle_date']) && $this->insertions_model->activity_logs($tracking_data))
                {
                    $output['title'] = 'Congrats';
                    $output['message'] = 'COD Generated Successfully.';
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
                $output['message'] = 'COD already generated or <b>No</b> COD for given date.';
            }
        }
        else
        {
            $output['error'] = true;
            $output['title'] = 'Error';
            $output['message'] = 'Invalid COD date.';
        }
        
        echo json_encode($output);        
    }

}
?>
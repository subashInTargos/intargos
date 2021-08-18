<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Actionexport extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        // load model
        $this->load->model('exportdata_model');
      $this->load->helper('file_upload');
      $this->load->helper('excel_data_validate');
    }

    public function weight_update_errordownload()
	{
        $postData=$this->input->post();
        // print_r($postData);
        // die();
	    $filename = 'WeightUpdate_Errors-'.now().'.xlsx';       
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $table_columns = array('AWB Number', 'Updated Weight','Error');
        $column = 0;
        foreach($table_columns as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $rowCount = 2;
        $i=0;
        foreach ($postData['waybill'] as $errors)
        {            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCount,($postData['waybill'][$i]!='null')?$postData['waybill'][$i]:'');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$rowCount,($postData['weight'][$i]!='null')?$postData['weight'][$i]:'');
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$rowCount, ($postData['error'][$i]!='null')?$postData['error'][$i]:'');
            
            $rowCount++;  
            $i++;      
        }
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
        ob_end_clean();//Clear the buffer, to avoid garbled 
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'); 
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter->save('php://output');
	}

    //Create Excel for dowloading searched invoice
    public function searched_invoices()
    {
        $date_condition="";
        // define the list of fields
        $fields = array('px_invoice_number', 'username', 'business_name','billing_type','invoice_status');
        $conditions = $this->searchdata_model->search_condition($fields);

        if(isset($_POST['from_date']) && $_POST['from_date'] != '' && isset($_POST['to_date']) && $_POST['to_date'] != '')
                $date_condition = "BETWEEN '".date('Y-m-d',strtotime($_POST['from_date'])). "' AND '".date('Y-m-d',strtotime($_POST['to_date']))."'";

        $datalist = $this->exportdata_model->searched_invoices($conditions,$date_condition,'invoice_date');
        // create file name
        $filename = 'Invoices -'.now().'.xlsx';       
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $table_columns = array("Invoice #", "Invoice Date", "Business Name", "Billing", "Email", "Contact", "Shipment Counts", "Taxable Amount", "Tax Amount", "Invoice Amount", "Paid Amount", "Due Amount", "Status","Modified On");
        $column = 0;
        foreach($table_columns as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $rowCount = 2;
        foreach ($datalist as $list) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCount, $list->px_invoice_number);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$rowCount, date('d-m-Y', strtotime($list->invoice_date)));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$rowCount, $list->business_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$rowCount, ucwords($list->billing_type));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$rowCount, $list->email_id);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$rowCount, $list->contact);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$rowCount, $list->shipments_count);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$rowCount, $list->invoice_amount);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$rowCount, $list->gst_amount);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$rowCount, $list->total_amount);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$rowCount, $list->paid_amount);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$rowCount, $list->due_amount);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$rowCount, $list->invoice_status == 0 ? 'Due' : ($list->invoice_status == 1 ? 'Paid' : 'Pending'));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$rowCount, $list->updated_on);
            $rowCount++;
        }
        //Highlight Head Row
        $objPHPExcel->getActiveSheet()->getStyle('A1:N1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7db831');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
        ob_end_clean();//Clear the buffer, to avoid garbled 
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter->save('php://output');
    }

    //Create Excel for dowloading AWBs from invoice
    public function download_invoice_awbs()
    {
        if(isset($_GET['inv']))
        {
            $invoice_number = $_GET['inv'];
            $datalist = $this->exportdata_model->download_invoice_awbs($invoice_number);
            // create file name
            $filename = 'Invoice AWBs - '.$invoice_number.'-'.now().'.xlsx';       
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $table_columns = array("ORDER ID", "AWB NUMBER", "SHIPMENT TYPE", "ORDER TYPE", "DESTINATION PERSON", "DESTINATION CITY","DESTINATION STATE" ,"DESTINATION PINCODE", "ORDERED ON", "ORDER AMOUNT", "COD AMOUNT", "DIMENSIONS", "MODE","STATUS","STATUS DATE","SOURCE LOCATION","SOURCE PINCODE","BILLING WEIGHT", "FORWARD CHARGES", "RTO CHARGES", "COD CHARGES", "FOV CHARGES", "FSC CHARGES", "SURCHARGE 1", "SURCHARGE 2", "SURCHARGE 3", "SURCHARGE 4", "NDD CHARGES", "AWB CHARGES", "CHARGES TOTAL", "GST AMOUNT", "TOTAL AMOUNT");
            $column = 0;
            foreach($table_columns as $field)
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }
            $rowCount = 2;
            foreach ($datalist as $list) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCount, $list->shipment_id);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$rowCount, $list->waybill_number);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$rowCount, ucwords($list->shipment_type));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$rowCount, ucwords($list->payment_mode));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$rowCount, ucwords($list->consignee_name));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$rowCount, ucwords($list->consignee_city));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$rowCount, ucwords($list->consignee_state));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$rowCount, $list->consignee_pincode);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$rowCount, date('d-m-Y', strtotime($list->order_date)));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$rowCount, $list->order_amt);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$rowCount, $list->cod_amount);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$rowCount, $list->shipment_length.' x '.$list->shipment_width.' x '.$list->shipment_height);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$rowCount, ucwords($list->express_type));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$rowCount, $list->status_title);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$rowCount, $list->billing_eligible_date);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$rowCount, $list->address_title);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$rowCount, $list->pincode);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17,$rowCount, $list->billing_weight);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18,$rowCount, $list->forward_charges);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19,$rowCount, $list->rto_charges);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20,$rowCount, $list->cod_charges);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21,$rowCount, $list->fov_charges);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22,$rowCount, $list->fsc_charges);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(23,$rowCount, $list->surcharge_1);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(24,$rowCount, $list->surcharge_2);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(25,$rowCount, $list->surcharge_3);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(26,$rowCount, $list->surcharge_4);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(27,$rowCount, $list->ndd_charges);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(28,$rowCount, $list->awb_charges);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(29,$rowCount, $list->charges_total);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(30,$rowCount, $list->gst_amount);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(31,$rowCount, $list->total_amount);
                $rowCount++;
            }
            //Highlight Head Row
            $objPHPExcel->getActiveSheet()->getStyle('A1:AF1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7db831');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
            ob_end_clean();//Clear the buffer, to avoid garbled 
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0'); 
            $objWriter->save('php://output');
        }
    }

    //Create Excel for dowloading AWBs from COD
    public function download_cod_awbs()
    {
        if(isset($_GET['cod_id']))
        {
            $cod_trn = $_GET['cod_id'];
            $datalist = $this->exportdata_model->download_cod_awbs($cod_trn);
            // create file name
            $filename = 'COD AWBs - '.$cod_trn.'-'.now().'.xlsx';       
            $objPHPExcel = new PHPExcel();
            $objPHPExcel->setActiveSheetIndex(0);
            $table_columns = array("COD TRN","ORDER ID", "AWB NUMBER", "INVOICE #", "BILLING DATE", "COD AMOUNT", "STATUS","DELIVERY DATE","COD GAP","COD ELIGIBLE ON","COD CYCLE");
            $column = 0;
            foreach($table_columns as $field)
            {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
                $column++;
            }
            $rowCount = 2;
            foreach ($datalist as $list) {
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCount, $list->cod_trn);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$rowCount, $list->shipment_id);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$rowCount, $list->waybill_number);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$rowCount, $list->invoice_number);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$rowCount, date('d-m-Y', strtotime($list->billing_date)));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$rowCount, $list->cod_amount);
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$rowCount, $list->cod_status == '0' ? 'Pending' : ($list->cod_status == '1' ? 'Received' : ($list->cod_status == '2' ? 'Billed' : 'NA')));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$rowCount, date('d-m-Y', strtotime($list->cod_date)));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$rowCount, $list->cod_gap." Days");
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$rowCount, date('d-m-Y', strtotime($list->cod_eligible_date)));
                $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$rowCount, date('d-m-Y', strtotime($list->cod_cycle_date)));
                $rowCount++;
            }
            //Highlight Head Row
            $objPHPExcel->getActiveSheet()->getStyle('A1:K1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7db831');

            $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
            ob_end_clean();//Clear the buffer, to avoid garbled 
            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
            header('Content-Disposition: attachment;filename="'.$filename.'"');
            header('Cache-Control: max-age=0'); 
            $objWriter->save('php://output');
        }
    }

    //Create Excel for dowloading searched CODs
    public function searched_cods()
    {
        $date_condition="";
        // define the list of fields
        $fields = array('cod_id', 'username', 'codadjust','billing_type','cod_status');
        $conditions = $this->searchdata_model->search_condition($fields);

        if(isset($_POST['from_date']) && $_POST['from_date'] != '' && isset($_POST['to_date']) && $_POST['to_date'] != '')
                $date_condition = "BETWEEN '".date('Y-m-d',strtotime($_POST['from_date'])). "' AND '".date('Y-m-d',strtotime($_POST['to_date']))."'";

        $datalist = $this->exportdata_model->searched_cods($conditions,$date_condition,'cod_cycle_date');
        // create file name
        $filename = 'CODs -'.now().'.xlsx';       
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $table_columns = array("BUSINESS NAME", "BILLING", "EMAIL", "CONTACT","COD TRN","COD AMOUNT","COD CYCLE DATE","COD ADJUST","BENEFICIARY NAME", "IFSC CODE", "ACCOUNT #", "BANK NAME", "BRANCH NAME");
        $column = 0;
        foreach($table_columns as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $rowCount = 2;
        foreach ($datalist as $list) {
            
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCount, $list->business_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$rowCount, ucwords($list->billing_type));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$rowCount, $list->email_id);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$rowCount, $list->contact);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$rowCount, $list->cod_id);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$rowCount, $list->cod_amount);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$rowCount, date('d-m-Y', strtotime($list->cod_cycle_date)));
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$rowCount, $list->codadjust);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$rowCount, $list->beneficiary_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$rowCount, $list->ifsc_code);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$rowCount, $list->account_number);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$rowCount, $list->bank_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$rowCount, $list->branch_name);
            $rowCount++;
        }
        //Highlight Head Row
        $objPHPExcel->getActiveSheet()->getStyle('A1:M1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7db831');

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007'); 
        ob_end_clean();//Clear the buffer, to avoid garbled 
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter->save('php://output');
    }

    //Create Excel for dowloading Shipments Reports - Processed Orders
    public function reports_shipments()
    {
        $datalist = $this->exportdata_model->reports_shipments($_POST);
        // create file name
        $filename = 'ProcessedShipments.xlsx';       
        $objPHPExcel = new PHPExcel();
        $objPHPExcel->setActiveSheetIndex(0);
        $table_columns = array("Username","OrderId", "WayBill Number", "Order Date", "Mode", "Express", "Consignee Name", "Consignee Contact", "Address", "City", "State", "Pincode", "COD Amount" , "Dimensions" , "Weight" , "Fulfilled By" , "Status");
        $column = 0;
        foreach($table_columns as $field)
        {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
            $column++;
        }
        $rowCount = 2;
        foreach ($datalist as $list) {
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCount, $list->username);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$rowCount, $list->shipment_id);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$rowCount, $list->waybill_number);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$rowCount, $list->order_date);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$rowCount, $list->payment_mode);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$rowCount, $list->express_type);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$rowCount, $list->consignee_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$rowCount, $list->consignee_mobile);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$rowCount, $list->consignee_address1.",".$list->consignee_address2);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$rowCount, $list->consignee_city);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$rowCount, $list->consignee_state);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$rowCount, $list->consignee_pincode);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$rowCount, $list->cod_amount);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$rowCount, $list->shipment_length."x".$list->shipment_width."x".$list->shipment_height);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$rowCount, $list->shipment_weight);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$rowCount, $list->account_name);
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$rowCount, $list->status_title);
            $rowCount++;
        }
        //Highlight Head Row
        $objPHPExcel->getActiveSheet()->getStyle('A1:Q1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7db831');
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        //Clear the buffer, to avoid garbled 
        if (ob_get_contents())
        ob_end_clean();
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'); 
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        header('Cache-Control: max-age=0'); 
        $objWriter->save('php://output');
    }

   public function reports_mis()
    {
		$extrafields=$this->input->post('extrafields')??"";
		if(!empty($this->input->post('file_type')) && !empty($this->input->post($_FILES['mis_file'])))
		{
			$fileupload_res['misreport'] = excel_upload('mis_file','reports_mis');
				
			if($fileupload_res['misreport']['title']=="Success")
			{
				
				$this->csv_search_download_file_in_excel($this->input->post('file_type'),$fileupload_res['misreport']['message'],$extrafields);
			}
		}
		else
		{
			$search_field=array(
			   'username'=>$this->input->post('username'),
			   'waybill_number'=>$this->input->post('waybill_number'),
			   'shipment_id'=>$this->input->post('shipment_id'),
			   'address_title'=>$this->input->post('address_title'),
			   'user_status'=>$this->input->post('user_status'),
			   'payment_mode'=>$this->input->post('payment_mode'),
			   'express_type'=>$this->input->post('express_type'),
			   'shipment_type'=>$this->input->post('shipment_type'),
			   'fulfilled_account'=>$this->input->post('fulfilled_account'),
			  
			   'from_date'=>$this->input->post('from_date'),
			   'to_date'=>$this->input->post('to_date'),
			);
			$datalist = $this->exportdata_model->reports_mis($search_field,'single');
			//echo "<pre>";print_r($datalist);exit;
			/*Download file single search basis and add extra field */
			 if(!empty($extrafields)){
				$filename = 'MisReports.xlsx';       
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->setActiveSheetIndex(0);                       
				$table_columns = array("Username","WayBill Number","OrderId","Addres Title","Status","Mode","Express","Order Type","Fulfilled By" ,"Order Date","Invoice/Ref #","Item Name", "Item Quantity", "Item Value",  "Consignee Name", "Consignee Contact", "Address", "City", "State", "Pincode", "COD Amount" , "Dimensions" , "Weight");
				foreach($extrafields as $addfield){
					array_push($table_columns,$addfield);
					
				}
				$column = 0;
				foreach($table_columns as $field)
				{
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
					$column++;
				}
				$rowCount = 2;
				foreach ($datalist as $list) { 
				//print_r($list);die;
				$col=22;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCount, $list->username);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$rowCount, $list->waybill_number);                                 
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$rowCount, $list->shipment_id);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$rowCount, $list->address_title."(".$list->username.")");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$rowCount, $list->status_title);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$rowCount, $list->payment_mode);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$rowCount, $list->express_type);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$rowCount, $list->shipment_type);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$rowCount, $list->fulfilled_by);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$rowCount, $list->order_date);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$rowCount, $list->invoice_number);                                   
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$rowCount, $list->product_name);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$rowCount, $list->product_quantity);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$rowCount, $list->product_value);                                    
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$rowCount, $list->consignee_name);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$rowCount, $list->consignee_mobile);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$rowCount, $list->consignee_address1.",".$list->consignee_address2);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17,$rowCount, $list->consignee_city);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18,$rowCount, $list->consignee_state);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19,$rowCount, $list->consignee_pincode);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20,$rowCount, $list->cod_amount);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21,$rowCount, $list->shipment_length."x".$list->shipment_width."x".$list->shipment_height);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22,$rowCount, $list->shipment_weight);
				   // foreach ($extrafields as $efields){ 
					   $efields= $_POST['extrafields'];
					 if (in_array('shipment_length', $efields)){  
                         $col=$col+1;						 
						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->shipment_length);
					 }
					if (in_array('shipment_width', $efields)){ 
                       $col=$col+1;					
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->shipment_width);
					   }
					if (in_array('shipment_height', $efields)){ 
					$col=$col+1;
					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$rowCount, $list->shipment_height);
					}
					
					if (in_array('shipment_weight', $efields)){ 
                         $col=$col+1;					
						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->shipment_weight);
					 }
					/* if (in_array('billing_weight', $efields)){  
					$col=$col+1;	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->billing_weight);
					} */
					if (in_array('zone', $efields)){ 
                        $col=$col+1;	
                        					
					   $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->zone);
					}
					if (in_array('consignee_address1', $efields)){ 
                      $col=$col+1;
						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->consignee_address1);
					 }
					if (in_array('consignee_mobile', $efields)){  
					$col=$col+1;
						
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->consignee_mobile);
					}
					if (in_array('consignee_pincode', $efields)){ 
                     $col=$col+1;					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->consignee_pincode);
					}
					
					if (in_array('consignee_city', $efields)){  
                         $col=$col+1;					
						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->consignee_city);
					 }
					if (in_array('consignee_state', $efields)){  
					$col=$col+1;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->consignee_state);
					}
					if (in_array('sales_poc', $efields)){  
                    $col=$col+1;					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->sales_poc);
					}
					if (in_array('ops_poc', $efields)){ 
                     $col=$col+1;					
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->ops_poc);
					} 
					
					$rowCount++;
				   // }
				  $col="";
				}
					//Highlight Head Row
					$objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7db831');
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					//Clear the buffer, to avoid garbled 
					if (ob_get_contents())
					ob_end_clean();
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'); 
					header('Content-Disposition: attachment;filename="'.$filename.'"');
					header('Cache-Control: max-age=0'); 
					$objWriter->save('php://output');
			}else{
					$filename = 'MisReportsCheck.xlsx';       
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);                       
					$table_columns = array("Username","WayBill Number","OrderId","Addres Title","Status","Mode","Express","Order Type","Fulfilled By" ,"Order Date","Invoice/Ref #","Item Name", "Item Quantity", "Item Value",  "Consignee Name", "Consignee Contact", "Address", "City", "State", "Pincode", "COD Amount" , "Dimensions" , "Weight");
				  
					$column = 0;
					foreach($table_columns as $field)
					{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
						$column++;
					}
					$rowCount = 2;
					foreach ($datalist as $list) { //print_r($list);die;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCount, $list->username);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$rowCount, $list->waybill_number);                                 
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$rowCount, $list->shipment_id);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$rowCount, $list->address_title."(".$list->username.")");
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$rowCount, $list->status_title);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$rowCount, $list->payment_mode);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$rowCount, $list->express_type);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$rowCount, $list->shipment_type);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$rowCount, $list->fulfilled_by);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$rowCount, $list->order_date);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$rowCount, $list->invoice_number);                                   
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$rowCount, $list->product_name);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$rowCount, $list->product_quantity);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$rowCount, $list->product_value);                                    
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$rowCount, $list->consignee_name);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$rowCount, $list->consignee_mobile);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$rowCount, $list->consignee_address1.",".$list->consignee_address2);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17,$rowCount, $list->consignee_city);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18,$rowCount, $list->consignee_state);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19,$rowCount, $list->consignee_pincode);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20,$rowCount, $list->cod_amount);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21,$rowCount, $list->shipment_length."x".$list->shipment_width."x".$list->shipment_height);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22,$rowCount, $list->shipment_weight);
						$rowCount++;
					   
					  }		  
						$objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7db831');
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
						//Clear the buffer, to avoid garbled 
						if (ob_get_contents())
						ob_end_clean();
						header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'); 
						header('Content-Disposition: attachment;filename="'.$filename.'"');
						header('Cache-Control: max-age=0'); 
						$objWriter->save('php://output');
				  }
	    }
    } 

   private function csv_search_download_file_in_excel($file_type,$csv_file_data,$add_extra_field=null){
		$datalist = $this->exportdata_model->reports_mis_csv_search_download_csv($file_type,$csv_file_data);
		if(!empty($add_extra_field)){
				$filename = 'MisReportsCheck.xlsx';       
				$objPHPExcel = new PHPExcel();
				$objPHPExcel->setActiveSheetIndex(0);                       
				$table_columns = array("Username","WayBill Number","OrderId","Addres Title","Status","Mode","Express","Order Type","Fulfilled By" ,"Order Date","Invoice/Ref #","Item Name", "Item Quantity", "Item Value",  "Consignee Name", "Consignee Contact", "Address", "City", "State", "Pincode", "COD Amount" , "Dimensions" , "Weight");
				foreach($add_extra_field as $addfield){
					array_push($table_columns,$addfield);
					
				}
				$column = 0;
				foreach($table_columns as $field)
				{
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
					$column++;
				}
				$rowCount = 2;
				
			foreach($datalist as $val){
				
				foreach ($val as $list) { 
				//print_r($list);die;
				$col=22;
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCount, $list->username);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$rowCount, $list->waybill_number);                                 
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$rowCount, $list->shipment_id);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$rowCount, $list->address_title."(".$list->username.")");
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$rowCount, $list->status_title);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$rowCount, $list->payment_mode);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$rowCount, $list->express_type);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$rowCount, $list->shipment_type);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$rowCount, $list->fulfilled_by);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$rowCount, $list->order_date);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$rowCount, $list->invoice_number);                                   
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$rowCount, $list->product_name);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$rowCount, $list->product_quantity);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$rowCount, $list->product_value);                                    
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$rowCount, $list->consignee_name);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$rowCount, $list->consignee_mobile);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$rowCount, $list->consignee_address1.",".$list->consignee_address2);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17,$rowCount, $list->consignee_city);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18,$rowCount, $list->consignee_state);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19,$rowCount, $list->consignee_pincode);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20,$rowCount, $list->cod_amount);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21,$rowCount, $list->shipment_length."x".$list->shipment_width."x".$list->shipment_height);
					$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22,$rowCount, $list->shipment_weight);
				   // foreach ($extrafields as $efields){ 
					$efields= $_POST['extrafields'];
					 if (in_array('shipment_length', $efields)){  
                         $col=$col+1;						 
						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->shipment_length);
					 }
					if (in_array('shipment_width', $efields)){ 
						$col=$col+1;					
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->shipment_width);
					}
					if (in_array('shipment_height', $efields)){ 
						$col=$col+1;
						
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col+1,$rowCount, $list->shipment_height);
					}
					
					if (in_array('shipment_weight', $efields)){ 
                         $col=$col+1;					
						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->shipment_weight);
					 }
					/* if (in_array('billing_weight', $efields)){  
					$col=$col+1;	$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->billing_weight);
					} */
					if (in_array('zone', $efields)){ 
                        $col=$col+1;	
                        					
					    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->zone);
					}
					if (in_array('consignee_address1', $efields)){ 
                         $col=$col+1;
						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->consignee_address1);
					 }
					if (in_array('consignee_mobile', $efields)){  
					    $col=$col+1;
						
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->consignee_mobile);
					}
					if (in_array('consignee_pincode', $efields)){ 
                        $col=$col+1;					
					    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->consignee_pincode);
					}
					
					if (in_array('consignee_city', $efields)){  
                         $col=$col+1;					
						 $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->consignee_city);
					 }
					if (in_array('consignee_state', $efields)){  
					    $col=$col+1;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->consignee_state);
					}
					if (in_array('sales_poc', $efields)){  
						$col=$col+1;					
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->sales_poc);
					}
					if (in_array('ops_poc', $efields)){ 
						$col=$col+1;					
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($col,$rowCount, $list->ops_poc);
					} 
					
					$rowCount++;
				   // }
				  $col="";
				}
				
			}
					//Highlight Head Row
					$objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7db831');
					$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
					//Clear the buffer, to avoid garbled 
					if (ob_get_contents())
					ob_end_clean();
					header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'); 
					header('Content-Disposition: attachment;filename="'.$filename.'"');
					header('Cache-Control: max-age=0'); 
					$objWriter->save('php://output');
			}else{
				    $filename = 'MisReportsCheck.xlsx';       
					$objPHPExcel = new PHPExcel();
					$objPHPExcel->setActiveSheetIndex(0);                       
					$table_columns = array("Username","WayBill Number","OrderId","Addres Title","Status","Mode","Express","Order Type","Fulfilled By" ,"Order Date","Invoice/Ref #","Item Name", "Item Quantity", "Item Value",  "Consignee Name", "Consignee Contact", "Address", "City", "State", "Pincode", "COD Amount" , "Dimensions" , "Weight");
				  
					$column = 0;
					foreach($table_columns as $field)
					{
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($column, 1, $field);
						$column++;
					}
					$rowCount = 2;
				foreach($datalist as $val){
					foreach ($val as $list) { //print_r($list);die;
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0,$rowCount, $list->username);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(1,$rowCount, $list->waybill_number);                                 
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2,$rowCount, $list->shipment_id);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3,$rowCount, $list->address_title."(".$list->username.")");
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(4,$rowCount, $list->status_title);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(5,$rowCount, $list->payment_mode);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(6,$rowCount, $list->express_type);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(7,$rowCount, $list->shipment_type);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(8,$rowCount, $list->fulfilled_by);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(9,$rowCount, $list->order_date);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(10,$rowCount, $list->invoice_number);                                   
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(11,$rowCount, $list->product_name);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(12,$rowCount, $list->product_quantity);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(13,$rowCount, $list->product_value);                                    
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(14,$rowCount, $list->consignee_name);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(15,$rowCount, $list->consignee_mobile);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(16,$rowCount, $list->consignee_address1.",".$list->consignee_address2);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(17,$rowCount, $list->consignee_city);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(18,$rowCount, $list->consignee_state);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(19,$rowCount, $list->consignee_pincode);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(20,$rowCount, $list->cod_amount);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(21,$rowCount, $list->shipment_length."x".$list->shipment_width."x".$list->shipment_height);
						$objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(22,$rowCount, $list->shipment_weight);
						$rowCount++;
					   
					  }	
					}					  
						$objPHPExcel->getActiveSheet()->getStyle('A1:Z1')->getFill()->setFillType(PHPExcel_Style_Fill::FILL_SOLID)->getStartColor()->setRGB('7db831');
						$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
						//Clear the buffer, to avoid garbled 
						if (ob_get_contents())
						ob_end_clean();
						header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet;charset=utf-8'); 
						header('Content-Disposition: attachment;filename="'.$filename.'"');
						header('Cache-Control: max-age=0'); 
						$objWriter->save('php://output');
			}
	}	
	
}
?>
<?php
$page_id = 'shipments_mis';
?>
<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <?php require_once('head.php'); ?>
        <link rel="stylesheet" href="https://cdn.datatables.net/responsive/1.0.7/css/responsive.dataTables.min.css"/>
    </head>
     
     <style>
      
      .checkboxes input{
         margin: 10px 10px 10px 10px;
    }

      .checkboxes label{
         margin: 10px 10px 10px 10px;
     }

    </style>

    <body>
        <!-- Page Wrapper -->
        <div id="page-wrapper">
            <!-- Preloader -->
                <?php require_once('preloader.php'); ?>
            <!-- END Preloader -->
            
            <!-- Page Container -->
            <div id="page-container" class="sidebar-partial sidebar-visible-lg sidebar-no-animations">

                <!-- Main Sidebar -->
                 <?php require_once('sidebar-main.php'); ?>
                <!-- END Main Sidebar -->

                <!-- Main Container -->
                <div id="main-container">
                    <!-- Header -->
                    <?php require_once('topbar.php'); ?>
                    <!-- END Header -->

                    <!-- Page content -->
                    <div id="page-content">
                        <!-- Validation Header -->
                        <!-- <div class="content-header">
                            <div class="header-section">
                                <h1>
                                    <i class="fa fa-truck"></i>All Shipments<br><small>Search/View all shipments</small>
                                </h1>
                            </div>
                        </div> -->
                        <ul class="breadcrumb breadcrumb-top">
                            <li>Report</li>
                            <li><a href="javascript:void">MIS </a></li>
                        </ul>
                        <!-- END Validation Header -->
                        <div class="row">
                            <div class="col-md-12">
                                <!-- Horizontal Form Block -->
                                <div class="block">
                                    <!-- Horizontal Form Title -->
                                    <div class="block-title themed-background-dark">
                                        <h2 style="color: #fff;">Search <strong>MIS Reports</strong></h2>
                                    </div>
                                    <!-- END Horizontal Form Title -->

                                    <!-- Horizontal Form Content -->
                               
                                    <form method="post" id="form_reportmis" target="_blank" action="<?php echo base_url('Actionexport/reports_mis')?>" enctype="multipart/form-data" class="form-horizontal form-bordered">
                                        <div class="form-group">
                                          <label>Single Search</label><br><br>
                                            <div class="col-md-3">
                                                <label>Username</label>
                                                <input type="text" id="username" name="username" class="form-control autocomplete-username" autocomplete="off" placeholder="Search using username...">
                                            </div>

                                            <div class="col-md-3">
                                                <label>Waybill Number</label>
                                                <input type="text" id="waybill_number" name="waybill_number" class="form-control" placeholder="Search using waybill number...">
                                            </div>

                                            <div class="col-md-3">
                                                <label>Parcelx Order Id</label>
                                                <input type="text" id="shipment_id" name="shipment_id" class="form-control" placeholder="Search using order id...">
                                            </div>

                                            <div class="col-md-3">
                                                <label>Warehouses</label>
                                              <select name="address_title" id="address_title" class="form-control">
                                                    <option value="">List of all warehouses</option>
                                                    <?php
                                                    $result=$this->db->query('select UA.address_title,U.username from users_address UA JOIN users U on UA.user_id = U.user_id');
                                                    foreach($result->result() as $row)
                                                    {
                                                    ?>
                                                    <option value="<?php echo $row->address_title; ?>"><?php echo $row->address_title; ?><?php echo " (" . $row->username . ")"; ?></option>
                                                    <?php } ?>
                                                </select>

                                            </div>                                           
                                            
                                       </div>

                                        <div class="form-group">               
                                           <div class="col-md-3">
                                                <label>Status</label>
                                                <select name="user_status[]" id="user_status" class="select-chosen form-control" style="width: 100%;" data-placeholder="Search by status...." multiple>
                                                    <option></option>
                                                    <?php
                                                    $result=$this->db->where('status_for','User')->where_not_in('status_id','200,220,229', false)->get('shipments_status');
                                                    // print_r($this->db->last_query());
                                                   foreach($result->result() as $row)
                                                    {
                                                    ?>
                                                    <option value="<?php echo $row->status_id; ?>"><?php echo $row->status_title; ?></option>
                                                   <?php } ?>
                                                </select>
                                            </div>
                                           
                                            <div class="col-md-3">
                                                <label>Payment Mode</label>
                                                <select name="payment_mode" id="payment_mode" class="form-control">
                                                    <option value="">Search by Mode</option>
                                                    <option value="COD">COD</option>
                                                    <option value="PPD">Prepaid</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label>Express</label>
                                                <select name="express_type" id="express_type" class="form-control">
                                                    <option value="">Search by express</option>
                                                    <option value="air">Air</option>
                                                    <option value="surface">Surface</option>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label>Order Type</label>
                                                <select name="shipment_type" id="shipment_type" class="form-control">
                                                    <option value="">Search by Type</option>
                                                    <option value="forward">Forward</option>
                                                    <option value="reverse">Reverse</option>
                                                </select>
                                            </div>
                                        </div>
                                           
                                        <div class="form-group">
                                            <div class="col-md-3">
                                                <label>Fulfilled By</label>
                                                <select name="fulfilled_account" id="fulfilled_account" class="form-control">
                                                    <option value="">Search by courier partner</option>
                                                    <?php
                                                    // $result=$this->db->query('select transitpartner_id, transitpartner_name from master_transit_partners MTP JOIN master_transitpartners_accounts MTPA on MTP.transitpartner_id = MTPA.parent_id where account_id in (select DISTINCT priority_1 from ( select `priority_1` from users_courier_priority union all select `priority_2` from users_courier_priority union all select `priority_3` from users_courier_priority) m)');

                                                    $result = $this->db->select('account_id,account_name')->get('master_transitpartners_accounts');
                                                    // print_r($this->db->last_query());
                                                    foreach($result->result() as $row)
                                                    {
                                                    ?>
                                                    <option value="<?php echo $row->account_id; ?>"><?php echo $row->account_name; ?></option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                            <div class="col-md-3">
                                                <label>Datetype</label>
                                                <select name="order_date" id="order_date" class="form-control" >
                                                    <option value="">Search by datetype</option>
                                                    <option value="placed">Placed</option>
                                                    <option value="picked">Picked</option>
                                                </select>
                                           </div>  

                                           <div class="col-md-3">
                                                <label>From Order Date</label>
                                                <input type="text" id="from_date" name="from_date" class="form-control input-datepicker-close" data-date-format="dd-mm-yyyy 00:00:00" placeholder="dd-mm-yyyy hh:ii:ss" required>
                                            </div>

                                            <div class="col-md-3">
                                                <label>To Order Date</label>
                                                <input type="text" id="to_date" name="to_date" class="form-control input-datepicker-close" data-date-format="dd-mm-yyyy 23:59:59" placeholder="dd-mm-yyyy hh:ii:ss" required>
                                            </div>
                                            
                                        </div> 
                            
                                 <div class="form-group"> 
                                 <label>Bulk Search</label><br>   
                                        <div class="col-md-3 ">
                                          <label>Uploading</label>
                                                <select name="file_type" id="file_type" class="form-control" >
                                                    <option value="">Select File Type</option>
                                                    <option value="waybill_number">Waybill Number</option>
                                                    <option value="shipment_id">Parcelx Order Ids</option>
                                                    <option value="invoice_number">Invoice/Ref Nums</option>
                                                </select>                                             
                                         </div>  
                                            <div class="col-md-3 ">
                                               <label></label>
                                              <input type="file" id="mis_file" name="mis_file" class="form-control" accept=".csv" required>  
                                              <!-- <a class="btn btn-primary" href="</?php echo base_url('Actionexport/read_report_mis')?>">Import Data</a>                                      -->
                                           </div>                    
                                           <div>
                                           <label class="col-md-3 control-label col-md-offset-2" style="text-align: left; margin-top: -15px;">
                                                <span class="text-success" style="font-size: 30px;"><i class="fi fi-csv"></i></span>
                                                <a href="<?= base_url("assets/samples/MIS_Report.csv")?>" download>Download Sample</a>
                                            </label>
                                           </div>
                                  </div>
                                                                  
                                  <div class="form-group">  
                                        <div class="col-md-12 checkboxes">
                                         <label>Fields you may need in report</label><br>                
                                         <input type="checkbox" name="extrafields[]" value="shipment_length"> Shipment Lenght
                                         <input type="checkbox" name="extrafields[]" value="shipment_width"> Shipment Width
			            	             <input type="checkbox" name="extrafields[]" value="shipment_height"> Shipment Height
                                         <input type="checkbox" name="extrafields[]" value="shipment_weight"> Shipment Weight
                                         <input type="checkbox" name="extrafields[]" value="billing_weight"> Charged Weight
                                         <input type="checkbox" name="extrafields[]" value="zone"> Zone
                                         <input type="checkbox" name="extrafields[]" value="consignee_address1"> Pickup Address<br>
                                         <input type="checkbox" name="extrafields[]" value="consignee_mobile"> Pickup Phone Number
                                         <input type="checkbox" name="extrafields[]" value="consignee_pincode"> Pickup Pincode
                                         <input type="checkbox" name="extrafields[]" value="consignee_city"> Pickup City
                                         <input type="checkbox" name="extrafields[]" value="consignee_state"> Pickup State
                                         <!-- <input type="checkbox" name="extrafields[]" value="first_attempt_date"> First Attempt Date
                                         <input type="checkbox" name="extrafields[]" value="first_attmpt_failure_reason"> First Attempt Failure Reason<br>
                                         <input type="checkbox" name="extrafields[]" value="second_attempt_date"> Second Attempt Date
                                         <input type="checkbox" name="extrafields[]" value="second_attempt_failure_reason"> Second Attempt Failure Reason
                                         <input type="checkbox" name="extrafields[]" value="third_attempt_date"> Third Attempt Date
                                         <input type="checkbox" name="extrafields[]" value="third_attempt_failure_reason"> Third Attempt Failure Reason
                                         <input type="checkbox" name="extrafields[]" value="last_attempt_date"> Last Attempt Date<br>
                                         <input type="checkbox" name="extrafields[]" value="last_reason_for_rto"> Last Reason For RTO
                                         <input type="checkbox" name="extrafields[]" value="turn_around_time"> Turn Around Time
                                         <input type="checkbox" name="extrafields[]" value="return_awb"> Return AWB
                                         <input type="checkbox" name="extrafields[]" value="received_by_for_digital_pod"> Received By For Digital POD -->
                                         <input type="checkbox" name="extrafields[]" value="sales_poc"> Sales POC
                                         <input type="checkbox" name="extrafields[]" value="ops_poc"> Ops POC
                                         <!-- <input type="checkbox" name="extrafields[]" value="is_mps"> Is MPS? -->
                                        
                                        </div>
                                    </div>
                                        <div class="form-group form-actions">
                                            <div class="col-md-12">
                                                <!-- <button type="button" class="btn btn-sm btn-info" id="searchbtn"><i class="fa fa-search"></i> Search</button> -->
                                               
                                               <a href="javascript:void;"><button type="button" class="btn btn-sm btn-success" id="btn_exportexcel"><i class="fa fa-cloud-download"></i>Download Report</button></a>
                                
                                                <button type="reset" id="btnReset" class="btn btn-sm btn-primary" id="btn_reset"><i class="fa fa-repeat"></i> All Data</button>
                                            </div>
                                        </div>
                                    </form>
                                    <!-- END Horizontal Form Content -->
                                </div>
                                <!-- END Horizontal Form Block -->                                
                            </div>
                        </div>

                    <!-- END Page Content -->

                    <!-- Footer -->
                    <?php require_once('footer.php'); ?>
                    <!-- END Footer -->
                </div>
                <!-- END Main Container -->
            </div>
            <!-- END Page Container -->
        </div>
        <!-- END Page Wrapper -->

        <!-- Scroll to top link, initialized in js/app.js - scrollToTop() -->
        <a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>

        <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
        <script src="<?= base_url();?>assets/js/vendor/jquery.min.js"></script>
        <script src="<?= base_url();?>assets/js/vendor/bootstrap.min.js"></script>
        <script src="<?= base_url();?>assets/js/plugins.js"></script>
        <script src="<?= base_url();?>assets/js/app.js"></script>

        <!-- Load and execute javascript code used only in this page -->
        <script src="<?= base_url();?>assets/js/pages/formsValidation.js"></script>
         <script src="<?= base_url();?>assets/js/pages/tablesDatatables.js"></script>
        <script src="<?= base_url();?>assets/js/pages/uiProgress.js"></script>
        <script src="<?= base_url();?>assets/js/customjs.js"></script>
        <script src="<?= base_url();?>assets/js/autocomplete.js"></script>
        <script type="text/javascript" language="javascript" src="https://cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
        <script>
            $(function(){
                FormsValidation.init();
                UiProgress.init();
                TablesDatatables.init();

                $('#from_date').datepicker('setDate', 'now');
                $('#to_date').datepicker('setDate', 'now');
            });

            // $('#searchbtn').on('click', function()
            // {
            //     // $("#user_status").val($("#status").val());
            //     search_data("form_searchshipments","<?= base_url();?>actionsearch/search_shipments/0");
            // });

            $('#btn_exportexcel').on('click', function(e)
            {
                $('#form_reportmis').submit();
            });


        </script>

    </body>
</html>
      
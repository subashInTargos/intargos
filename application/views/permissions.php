<?php
$page_id = 'permissions';
?>
<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <?php require_once('head.php'); ?>
    </head>
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
                        <!-- Header -->
                        <div class="content-header">
                            <div class="header-section">
                                <h1>
                                    <i class="fa fa-user-secret"></i>Permissions<br><small>Manage role based & custom permissions</small>
                                </h1>
                            </div>
                        </div>
                        <ul class="breadcrumb breadcrumb-top">
                            <li>Administration</li>
                            <li><a href="javascript:void;">Permissions</a></li>
                        </ul>
                        <!-- END Header -->

                        <!-- Tabs Block -->
                        <div class="block">
                            <!-- Tabs Title -->
                            <div class="block-title">
                                <ul class="nav nav-tabs" data-toggle="tabs">
                                    <li class="active"><a href="#tab-permissions">Permissions</a></li>
                                    <li><a href="#tab-custompermissions">Custom Permissions</a></li>
                                </ul>
                            </div>
                            <!-- END Tabs Title -->

                            <!-- Tabs Content -->
                            <div class="tab-content">
                                <!-- Permissions -->
                                <div class="tab-pane active" id="tab-permissions">
                                    <!-- Tab Content -->
                                    <p>Role based permissions</p>
                                    <!-- End Tab Content -->                                    
                                </div>
                                <!-- END Permissions -->

                                <!-- Custom Permissions -->
                                <div class="tab-pane" id="tab-custompermissions">
                                    <!-- Tab Content -->
                                    <p>Custom permissions</p>
                                    <!-- End Tab Content -->
                                </div>
                                <!-- END Custom Permissions -->

                            </div>
                            <!-- END Tabs Content -->
                        </div>
                        <!-- END Tabs Block -->
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
        <script>
            $(function(){
                FormsValidation.init();
                UiProgress.init();
                TablesDatatables.init();
            });

            $('#form_managebalance').on('submit', function(e)
            {
                if($("#form_managebalance").valid())
                {
                    $('#savebtn').prop('disabled', true);
                    ins_data("form_managebalance","<?= base_url();?>billing/user_balances");
                }
                else
                    e.preventDefault();        
            });

            function getprepaid_user(userid)
            {
                // alert(userid);
                $.ajax({
                    url :  "<?= base_url();?>billing/get_prepaidusers",
                    type : "POST",
                    datatype : "json",
                    data : { 'id'   : userid },
                    success:function(response)
                    {
                        if(response!='0')
                        {
                            var r = response.split("#");
                            // alert(r);
                            $("#user_id").val(r[0].trim());
                            $("#full_name").html(r[1].trim());
                            $("#business_name").html(r[2].trim());
                            $("#main_bal").val(r[3].trim());
                            $("#promo_bal").val(r[4].trim());
                            $("#total_balance").val(r[5].trim());
                            $("#balance_type").focus();
                        }
                        else
                        {
                            $.bootstrapGrowl('<h4><i class="fa fa-ban"></i> Not found</h4> <p>Invalid username or account is postpaid.</p>', {
                                type: 'danger',
                                delay: 2500,
                                allow_dismiss: true
                            });
                        }
                    }
                });
            }
        </script>

    </body>
</html>
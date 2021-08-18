<div id="sidebar">
    <!-- Wrapper for scrolling functionality -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Brand -->
            <a href="dashboard" class="sidebar-brand">
                <i class="hi hi-chevron-right"></i><span class="sidebar-nav-mini-hide">In<strong>Targos</strong></span>
            </a>
            <!-- END Brand -->

            <!-- User Info -->
            <div class="sidebar-section sidebar-user clearfix sidebar-nav-mini-hide">
                <div class="sidebar-user-avatar">
                    <a href="javascript:void">
                        <img src="<?= base_url();?>assets/img/placeholders/avatars/avatar<?php echo $this->session->userdata['user_session']['avatar'];?>.png" alt="ParcelX Admin">
                    </a>
                </div>
                <div class="sidebar-user-name"><b><?php echo $this->session->userdata['user_session']['admin_name']; ?></b></div>
                <?php echo $this->session->userdata['user_session']['role_name']; ?>
            </div>
            <!-- END User Info -->

            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav">
                <li>
                    <a href="<?= base_url()?>dashboard" class="<?php echo ($page_id == "dashboard" ? "active" : "");?>"><i class="gi gi-stopwatch sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Dashboard</span></a>
                </li>

                <li class="sidebar-header">
                    <span class="sidebar-header-options clearfix">
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Manage all admin related activities like creating roles, sub-users, permissions."><i class="fa fa-info-circle"></i></a></span>
                    <span class="sidebar-header-title">Tech Admin</span>
                </li>
                <li class="<?php echo ($page_id == "admin_roles" || $page_id == "admin_modules" || $page_id == "admin_users" || $page_id == "permissions" ? "active" : "");?>">
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-sampler sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Administration</span></a>
                    <ul>
                        <li>
                            <a href="<?= base_url()?>admin_roles" class="<?php echo ($page_id == "admin_roles" ? "active" : "");?>">Roles</a>
                        </li>
                        <li>
                            <a href="<?= base_url()?>admin_modules" class="<?php echo ($page_id == "admin_modules" ? "active" : "");?>">Modules</a>
                        </li>
                        <li>
                            <a href="<?= base_url()?>permissions"  class="<?php echo ($page_id == "permissions" ? "active" : "");?>">Permissions</a>
                        </li>
                        <li>
                            <a href="<?= base_url()?>admin_users" class="<?php echo ($page_id == "admin_users" ? "active" : "");?>">Sub-Admins</a>
                        </li>
                        
                    </ul>
                </li>

                <li class="<?php echo ($page_id == "" || $page_id == "" || $page_id == "" || $page_id == "permissions" ? "active" : "");?>">
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-desktop sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Site Management</span></a>
                    <ul>
                        <li>
                            <!-- BG Image & Content in CK Editor -->
                            <a href="" class="<?php echo ($page_id == "" ? "active" : "");?>">User Panel</a>
                        </li>
                        <li>
                            <!-- BG Image -->
                            <a href="" class="<?php echo ($page_id == "" ? "active" : "");?>">Admin Panel</a>
                        </li>
                        <li>
                            <!-- Send Notifications to users -->
                            <a href="" class="<?php echo ($page_id == "" ? "active" : "");?>">Notifications</a>
                        </li>
                        
                    </ul>
                </li>

                <li class="<?php echo ($page_id == "master_transit_partners" || $page_id == "awb_generation" || $page_id == "" || $page_id == "" || $page_id == "permissions" ? "active" : "");?>">
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-cubes sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">System Management</span></a>
                    <ul>
                        <li>
                            <a href="<?= base_url()?>master_transit_partners" class="<?php echo ($page_id == "master_transit_partners" ? "active" : "");?>">Transit Partners</a>
                        </li>
                        <li>
                            <a href="<?= base_url()?>awb_generation" class="<?php echo ($page_id == "awb_generation" ? "active" : "");?>">Upload AWBs</a>
                        </li>
                    </ul>
                </li>

                <li class="sidebar-header">
                    <span class="sidebar-header-options clearfix"><a href="javascript:void(0)" data-toggle="tooltip" title="Manage all ops related activities."><i class="fa fa-info-circle"></i></a></span>
                    <span class="sidebar-header-title">Ops Admin</span>
                </li>
                <li class="<?php echo ($page_id == "master_cod_cycle" || $page_id == "master_billing_cycle" || $page_id == "master_pincodes" || $page_id == "master_pinservices" || $page_id == "master_weightslab" || $page_id == "master_zones" ? "active" : "");?>">
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-dashboard sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Master</span></a>
                    <ul>
                        
                        <li class="<?php echo ($page_id == "master_cod_cycle" || $page_id == "master_billing_cycle" ? "active" : "");?>">
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-angle-left sidebar-nav-indicator"></i>Cycles</a>
                            <ul>
                                <li>
                                    <a href="<?= base_url()?>master_billing_cycle" class="<?php echo ($page_id == "master_billing_cycle" ? "active" : "");?>">Billing Cycle</a>
                                </li>
                                <li>
                                    <a href="<?= base_url()?>master_cod_cycle" class="<?php echo ($page_id == "master_cod_cycle" ? "active" : "");?>">COD Cycle</a>
                                </li>
                            </ul>
                        </li>

                        <!-- <li>
                            <a href="master_billing_cycle" class="<?php //echo ($page_id == "master_billing_cycle" ? "active" : "");?>">Billing Cycle</a>
                        </li>
                        <li>
                            <a href="master_cod_cycle" class="<?php //echo ($page_id == "master_cod_cycle" ? "active" : "");?>">COD Cycle</a>
                        </li> -->
                        <li>
                            <a href="<?= base_url()?>master_weightslab" class="<?php echo ($page_id == "master_weightslab" ? "active" : "");?>">Weight Slab</a>
                        </li>


                        <li class="<?php echo ($page_id == "master_pincodes" || $page_id == "master_pinservices" ? "active" : "");?>">
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-angle-left sidebar-nav-indicator"></i>Pincode</a>
                            <ul>
                                <li>
                                <a href="<?= base_url()?>master_pincodes" class="<?php echo ($page_id == "master_pincodes" ? "active" : "");?>">Pincode List</a>
                                </li>
                                <li>
                                <a href="<?= base_url()?>master_pinservices" class="<?php echo ($page_id == "master_pinservices" ? "active" : "");?>">Pincodes Services</a>
                                </li>
                            </ul>
                        </li>
                        <!-- <li>
                            <a href="master_pincodes" class="<?php echo ($page_id == "master_pincodes" ? "active" : "");?>">Pincodes</a>
                        </li> -->
                        <li>
                            <a href="<?= base_url()?>master_zones" class="<?php echo ($page_id == "master_zones" ? "active" : "");?>">Zones</a>
                        </li>
                    </ul>
                </li>

                <li class="<?php echo ($page_id == "users_registration" || $page_id == "users_manage" || $page_id == "users_courierpriority" || $page_id == "users_ratechart" || $page_id == "users_weightslab" || $page_id == "complete_registration" ? "active" : "");?>">
                    <a href="<?= base_url()?>#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-users sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Users</span></a>
                    <ul>
                        <li>
                            <a href="<?= base_url()?>users" class="<?php echo ($page_id == "users_manage" || $page_id == "users_ratechart" || $page_id == "users_weightslab" || $page_id == "users_courierpriority" ? "active" : "");?>">Manage Users</a>
                        </li>
                        <li>
                            <a href="<?= base_url()?>complete_registration" class="<?php echo ($page_id == "complete_registration" ? "active" : "");?>">Complete Registration </a>
                        </li>
                        <li>
                            <a href="<?= base_url()?>users_registration" class="<?php echo ($page_id == "users_registration" ? "active" : "");?>">Register user</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-shoe_steps sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Referrals</span></a>
                    <ul>
                        <li>
                            <a href="#">Manage Affiliate</a>
                        </li>
                        <li>
                            <a href="#">Change Affiliate </a>
                        </li>
                        <li>
                            <a href="#">Manage partner</a>
                        </li>
                        <li>
                            <a href="#">Change Partner</a>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-bullhorn sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Promotions</span></a>
                    <ul>
                        <li>
                            <a href="#">Promo Codes</a>
                        </li>
                        <!-- <li>
                            <a href="#">Billing Cycle</a>
                        </li> -->
                    </ul>
                </li>

                <li class="sidebar-header">
                    <span class="sidebar-header-options clearfix"><a href="javascript:void(0)" data-toggle="tooltip" title="Manage finance/billing related activities."><i class="fa fa-info-circle"></i></a></span>
                    <span class="sidebar-header-title">Finance</span>
                </li>

                <li>
                    <a href="<?= base_url()?>change_status" class="<?php echo ($page_id == "change_status" ? "active" : "");?>"><i class="gi gi-stopwatch sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Change Status</span></a>
                </li>

                <li class="<?php echo ($page_id == "weight_update" || $page_id == "generate_invoice" || $page_id == "view_invoice" || $page_id == "add_payment" || $page_id == "invoice" || $page_id == "generate_cod" || $page_id == "view_cods" ? "active" : "");?>">
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-money sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Billing</span></a>
                    <ul>
                        <li>
                            <a href="<?= base_url()?>weight_update" class="<?php echo ($page_id == "weight_update" ? "active" : "");?>">Update Weight</a>
                        </li>

                        <li class="<?php echo ($page_id == "generate_invoice" || $page_id == "invoice" || $page_id == "view_invoice" || $page_id == "add_payment" ? "active" : "");?>">
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-angle-left sidebar-nav-indicator"></i>Invoice</a>
                            <ul>
                                <li>
                                    <a href="<?= base_url()?>generate_invoice" class="<?php echo ($page_id == "generate_invoice" ? "active" : "");?>">Generate</a>
                                </li>
                                <li>
                                    <a href="<?= base_url()?>view_invoice" class="<?php echo ($page_id == "view_invoice" ? "active" : "");?>">View</a>
                                </li>
                                <li>
                                    <a href="<?= base_url()?>add_payment" class="<?php echo ($page_id == "add_payment" ? "active" : "");?>">Add Payment</a>
                                </li>                       
                            </ul>
                        </li>

                        <li class="<?php echo ($page_id == "generate_cod" || $page_id == "view_cods" ? "active" : "");?>">
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-angle-left sidebar-nav-indicator"></i>COD</a>
                            <ul>
                                <li>
                                    <a href="<?= base_url()?>generate_cod" class="<?php echo ($page_id == "generate_cod" ? "active" : "");?>">Generate</a>
                                </li>

                                <li>
                                    <a href="<?= base_url()?>view_cods" class="<?php echo ($page_id == "view_cods" ? "active" : "");?>">View</a>
                                </li>
                            </ul>
                        </li>

                        <li class="<?php echo ($page_id == "" || $page_id == "" ? "active" : "");?>">
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-angle-left sidebar-nav-indicator"></i>Credit Note</a>
                            <ul>
                                
                                <li>
                                    <a href="" class="<?php echo ($page_id == "" ? "active" : "");?>">Create</a>
                                </li>

                                <li>
                                    <a href="" class="<?php echo ($page_id == "" ? "active" : "");?>">View</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>

                <li>
                    <a href="<?= base_url('manage_balance')?>" class="<?php echo ($page_id == "add_balance" ? "active" : "");?>"><i class="gi gi-money sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Add PX-Cash</span></a>
                </li>

                <li class="sidebar-header">
                    <span class="sidebar-header-options clearfix">
                        <a href="javascript:void(0)" data-toggle="tooltip" title="Search & View the reports"><i class="fa fa-info-circle"></i></a></span>
                    <span class="sidebar-header-title">Reports</span>
                </li>

                <li class="<?php echo ($page_id == "shipments" || $page_id == "failed_shipments"||  $page_id=="shipments_mis"? "active" : "");?>">
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-truck sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Shipments</span></a>
                    <ul>
                        <li>
                            <a href="<?= base_url('reports/mis');?>" class="<?php echo ($page_id == "shipments_mis" ? "active" : "");?>">MIS Report</a>
                        </li>
                        <li>
                            <a href="<?= base_url('reports/shipments');?>" class="<?php echo ($page_id == "shipments" ? "active" : "");?>">Processed</a>
                        </li>

                        <li>
                            <a href="<?= base_url('reports/failedshipments');?>" class="<?php echo ($page_id == "failed_shipments" ? "active" : "");?>">Unprocessed/Failed</a>
                        </li>
                    </ul>
                </li>

                <li class="<?php echo ($page_id == "view_balance" || $page_id == "all_payments" || $page_id == "all_transactions" || $page_id == "user_ledger" ? "active" : "");?>">
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-credit-card sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Transactions</span></a>
                    <ul>
                        <li>
                            <a href="<?= base_url('reports/viewbalance')?>" class="<?php echo ($page_id == "view_balance" ? "active" : "");?>">View Balance</a>
                        </li>

                        <li>
                            <a href="<?= base_url('reports/allpayments')?>" class="<?php echo ($page_id == "all_payments" ? "active" : "");?>">Payments</a>
                        </li>

                        <li>
                            <a href="<?= base_url('reports/alltransactions')?>" class="<?php echo ($page_id == "all_transactions" ? "active" : "");?>">All Transactions</a>
                        </li>

                        <li>
                            <a href="<?= base_url('reports/userledger')?>" class="<?php echo ($page_id == "user_ledger" ? "active" : "");?>">User Ledger</a>
                        </li>
                    </ul>
                </li>

                <!-- <li class="sidebar-header">
                    <span class="sidebar-header-options clearfix"><a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a><a href="javascript:void(0)" data-toggle="tooltip" title="Create the most amazing pages with the widget kit!"><i class="gi gi-lightbulb"></i></a></span>
                    <span class="sidebar-header-title">Template Menu</span>
                </li>

                <li>
                    <a href="index2.html"><i class="gi gi-leaf sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Dashboard 2</span></a>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-shopping_cart sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">eCommerce</span></a>
                    <ul>
                        <li>
                            <a href="page_ecom_dashboard.html">Dashboard</a>
                        </li>
                        <li>
                            <a href="page_ecom_orders.html">Orders</a>
                        </li>
                        <li>
                            <a href="page_ecom_order_view.html">Order View</a>
                        </li>
                        <li>
                            <a href="page_ecom_products.html">Products</a>
                        </li>
                        <li>
                            <a href="page_ecom_product_edit.html">Product Edit</a>
                        </li>
                        <li>
                            <a href="page_ecom_customer_view.html">Customer View</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-header">
                    <span class="sidebar-header-options clearfix"><a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a><a href="javascript:void(0)" data-toggle="tooltip" title="Create the most amazing pages with the widget kit!"><i class="gi gi-lightbulb"></i></a></span>
                    <span class="sidebar-header-title">Widget Kit</span>
                </li>
                <li>
                    <a href="page_widgets_stats.html"><i class="gi gi-charts sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Statistics</span></a>
                </li>
                <li>
                    <a href="page_widgets_social.html"><i class="gi gi-share_alt sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Social</span></a>
                </li>
                <li>
                    <a href="page_widgets_media.html"><i class="gi gi-film sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Media</span></a>
                </li>
                <li>
                    <a href="page_widgets_links.html"><i class="gi gi-link sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Links</span></a>
                </li>
                <li class="sidebar-header">
                    <span class="sidebar-header-options clearfix"><a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a></span>
                    <span class="sidebar-header-title">Design Kit</span>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-certificate sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">User Interface</span></a>
                    <ul>
                        <li>
                            <a href="page_ui_grid_blocks.html">Grid &amp; Blocks</a>
                        </li>
                        <li>
                            <a href="page_ui_draggable_blocks.html">Draggable Blocks</a>
                        </li>
                        <li>
                            <a href="page_ui_typography.html">Typography</a>
                        </li>
                        <li>
                            <a href="page_ui_buttons_dropdowns.html">Buttons &amp; Dropdowns</a>
                        </li>
                        <li>
                            <a href="page_ui_navigation_more.html">Navigation &amp; More</a>
                        </li>
                        <li>
                            <a href="page_ui_horizontal_menu.html">Horizontal Menu</a>
                        </li>
                        <li>
                            <a href="page_ui_progress_loading.html">Progress &amp; Loading</a>
                        </li>
                        <li>
                            <a href="page_ui_preloader.html">Page Preloader</a>
                        </li>
                        <li>
                            <a href="page_ui_color_themes.html">Color Themes</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-notes_2 sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Forms</span></a>
                    <ul>
                        <li>
                            <a href="page_forms_general.html">General</a>
                        </li>
                        <li>
                            <a href="page_forms_components.html">Components</a>
                        </li>
                        <li>
                            <a href="page_forms_validation.html">Validation</a>
                        </li>
                        <li>
                            <a href="page_forms_wizard.html">Wizard</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-table sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Tables</span></a>
                    <ul>
                        <li>
                            <a href="page_tables_general.html">General</a>
                        </li>
                        <li>
                            <a href="page_tables_responsive.html">Responsive</a>
                        </li>
                        <li>
                            <a href="page_tables_datatables.html">Datatables</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-cup sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Icon Sets</span></a>
                    <ul>
                        <li>
                            <a href="page_icons_fontawesome.html">Font Awesome</a>
                        </li>
                        <li>
                            <a href="page_icons_glyphicons_pro.html">Glyphicons Pro</a>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-show_big_thumbnails sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Page Layouts</span></a>
                    <ul>
                        <li>
                            <a href="page_layout_static.html">Static</a>
                        </li>
                        <li>
                            <a href="page_layout_static_fixed_footer.html">Static + Fixed Footer</a>
                        </li>
                        <li>
                            <a href="page_layout_fixed_top.html">Fixed Top Header</a>
                        </li>
                        <li>
                            <a href="page_layout_fixed_top_footer.html">Fixed Top Header + Footer</a>
                        </li>
                        <li>
                            <a href="page_layout_fixed_bottom.html">Fixed Bottom Header</a>
                        </li>
                        <li>
                            <a href="page_layout_fixed_bottom_footer.html">Fixed Bottom Header + Footer</a>
                        </li>
                        <li>
                            <a href="page_layout_static_main_sidebar_mini.html">Mini Main Sidebar</a>
                        </li>
                        <li>
                            <a href="page_layout_static_main_sidebar_partial.html">Partial Main Sidebar</a>
                        </li>
                        <li>
                            <a href="page_layout_static_main_sidebar_visible.html">Visible Main Sidebar</a>
                        </li>
                        <li>
                            <a href="page_layout_static_alternative_sidebar_partial.html">Partial Alternative Sidebar</a>
                        </li>
                        <li>
                            <a href="page_layout_static_alternative_sidebar_visible.html">Visible Alternative Sidebar</a>
                        </li>
                        <li>
                            <a href="page_layout_static_no_sidebars.html">No Sidebars</a>
                        </li>
                        <li>
                            <a href="page_layout_static_both_partial.html">Both Sidebars Partial</a>
                        </li>
                        <li>
                            <a href="page_layout_static_animated.html">Animated Sidebar Transitions</a>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-header">
                    <span class="sidebar-header-options clearfix"><a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a></span>
                    <span class="sidebar-header-title">Develop Kit</span>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="gi gi-brush sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Ready Pages</span></a>
                    <ul>
                        <li>
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-angle-left sidebar-nav-indicator"></i>Errors</a>
                            <ul>
                                <li>
                                    <a href="page_ready_400.html">400</a>
                                </li>
                                <li>
                                    <a href="page_ready_401.html">401</a>
                                </li>
                                <li>
                                    <a href="page_ready_403.html">403</a>
                                </li>
                                <li>
                                    <a href="page_ready_404.html">404</a>
                                </li>
                                <li>
                                    <a href="page_ready_500.html">500</a>
                                </li>
                                <li>
                                    <a href="page_ready_503.html">503</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-angle-left sidebar-nav-indicator"></i>Get Started</a>
                            <ul>
                                <li>
                                    <a href="page_ready_blank.html">Blank</a>
                                </li>
                                <li>
                                    <a href="page_ready_blank_alt.html">Blank Alternative</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="page_ready_search_results.html">Search Results (4)</a>
                        </li>
                        <li>
                            <a href="page_ready_article.html">Article</a>
                        </li>
                        <li>
                            <a href="page_ready_user_profile.html">User Profile</a>
                        </li>
                        <li>
                            <a href="page_ready_contacts.html">Contacts</a>
                        </li>
                        <li>
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-angle-left sidebar-nav-indicator"></i>e-Learning</a>
                            <ul>
                                <li>
                                    <a href="page_ready_elearning_courses.html">Courses</a>
                                </li>
                                <li>
                                    <a href="page_ready_elearning_course_lessons.html">Course - Lessons</a>
                                </li>
                                <li>
                                    <a href="page_ready_elearning_course_lesson.html">Course - Lesson Page</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-angle-left sidebar-nav-indicator"></i>Message Center</a>
                            <ul>
                                <li>
                                    <a href="page_ready_inbox.html">Inbox</a>
                                </li>
                                <li>
                                    <a href="page_ready_inbox_compose.html">Compose Message</a>
                                </li>
                                <li>
                                    <a href="page_ready_inbox_message.html">View Message</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="page_ready_chat.html">Chat</a>
                        </li>
                        <li>
                            <a href="page_ready_timeline.html">Timeline</a>
                        </li>
                        <li>
                            <a href="page_ready_files.html">Files</a>
                        </li>
                        <li>
                            <a href="page_ready_tickets.html">Tickets</a>
                        </li>
                        <li>
                            <a href="page_ready_bug_tracker.html">Bug Tracker</a>
                        </li>
                        <li>
                            <a href="page_ready_tasks.html">Tasks</a>
                        </li>
                        <li>
                            <a href="page_ready_faq.html">FAQ</a>
                        </li>
                        <li>
                            <a href="page_ready_pricing_tables.html">Pricing Tables</a>
                        </li>
                        <li>
                            <a href="page_ready_invoice.html">Invoice</a>
                        </li>
                        <li>
                            <a href="page_ready_forum.html">Forum (3)</a>
                        </li>
                        <li>
                            <a href="page_ready_coming_soon.html">Coming Soon</a>
                        </li>
                        <li>
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-angle-left sidebar-nav-indicator"></i>Login, Register &amp; Lock</a>
                            <ul>
                                <li>
                                    <a href="login.html">Login</a>
                                </li>
                                <li>
                                    <a href="login_full.html">Login (Full Background)</a>
                                </li>
                                <li>
                                    <a href="login_alt.html">Login 2</a>
                                </li>
                                <li>
                                    <a href="login.html#reminder">Password Reminder</a>
                                </li>
                                <li>
                                    <a href="login_alt.html#reminder">Password Reminder 2</a>
                                </li>
                                <li>
                                    <a href="login.html#register">Register</a>
                                </li>
                                <li>
                                    <a href="login_alt.html#register">Register 2</a>
                                </li>
                                <li>
                                    <a href="page_ready_lock_screen.html">Lock Screen</a>
                                </li>
                                <li>
                                    <a href="page_ready_lock_screen_alt.html">Lock Screen 2</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="sidebar-nav-menu"><i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i><i class="fa fa-wrench sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Components</span></a>
                    <ul>
                        <li>
                            <a href="#" class="sidebar-nav-submenu"><i class="fa fa-angle-left sidebar-nav-indicator"></i>3 Level Menu</a>
                            <ul>
                                <li>
                                    <a href="#">Link 1</a>
                                </li>
                                <li>
                                    <a href="#">Link 2</a>
                                </li>
                            </ul>
                        </li>
                        <li>
                            <a href="page_comp_maps.html">Maps</a>
                        </li>
                        <li>
                            <a href="page_comp_charts.html">Charts</a>
                        </li>
                        <li>
                            <a href="page_comp_gallery.html">Gallery</a>
                        </li>
                        <li>
                            <a href="page_comp_carousel.html">Carousel</a>
                        </li>
                        <li>
                            <a href="page_comp_calendar.html">Calendar</a>
                        </li>
                        <li>
                            <a href="page_comp_animations.html">CSS3 Animations</a>
                        </li>
                        <li>
                            <a href="page_comp_syntax_highlighting.html">Syntax Highlighting</a>
                        </li>
                    </ul>
                </li> -->
            </ul>
            <!-- END Sidebar Navigation -->

            <!-- Sidebar Notifications -->
            <!-- <div class="sidebar-header sidebar-nav-mini-hide">
                <span class="sidebar-header-options clearfix">
                    <a href="javascript:void(0)" data-toggle="tooltip" title="Refresh"><i class="gi gi-refresh"></i></a>
                </span>
                <span class="sidebar-header-title">Activity</span>
            </div>
            <div class="sidebar-section sidebar-nav-mini-hide">
                <div class="alert alert-success alert-alt">
                    <small>5 min ago</small><br>
                    <i class="fa fa-thumbs-up fa-fw"></i> You had a new sale ($10)
                </div>
                <div class="alert alert-info alert-alt">
                    <small>10 min ago</small><br>
                    <i class="fa fa-arrow-up fa-fw"></i> Upgraded to Pro plan
                </div>
                <div class="alert alert-warning alert-alt">
                    <small>3 hours ago</small><br>
                    <i class="fa fa-exclamation fa-fw"></i> Running low on space<br><strong>18GB in use</strong> 2GB left
                </div>
                <div class="alert alert-danger alert-alt">
                    <small>Yesterday</small><br>
                    <i class="fa fa-bug fa-fw"></i> <a href="javascript:void(0)"><strong>New bug submitted</strong></a>
                </div>
            </div> -->
            <!-- END Sidebar Notifications -->
        </div>
        <!-- END Sidebar Content -->
    </div>
    <!-- END Wrapper for scrolling functionality -->
</div>
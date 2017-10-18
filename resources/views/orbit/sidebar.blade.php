
            <!-- BEGIN SIDEBAR -->
            <div class="page-sidebar-wrapper">
                <!-- END SIDEBAR -->
                <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                <div class="page-sidebar navbar-collapse collapse">
                    <!-- BEGIN SIDEBAR MENU -->
                    <!-- DOC: Apply "page-sidebar-menu-light" class right after "page-sidebar-menu" to enable light sidebar menu style(without borders) -->
                    <!-- DOC: Apply "page-sidebar-menu-hover-submenu" class right after "page-sidebar-menu" to enable hoverable(hover vs accordion) sub menu mode -->
                    <!-- DOC: Apply "page-sidebar-menu-closed" class right after "page-sidebar-menu" to collapse("page-sidebar-closed" class must be applied to the body element) the sidebar sub menu mode -->
                    <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing -->
                    <!-- DOC: Set data-keep-expand="true" to keep the submenues expanded -->
                    <!-- DOC: Set data-auto-speed="200" to adjust the sub menu slide up/down speed -->
                    <ul class="page-sidebar-menu  page-header-fixed page-sidebar-menu-compact " data-keep-expanded="false" data-auto-scroll="true" data-slide-speed="200">
                        <li class="nav-item start ">
                            <a href="/referral" class="nav-link">
                                <i class="icon-paper-plane"></i>
                                <span class="title">Referrals</span>
                                <span class="arrow"></span>
                            </a>
                        </li>


                        @if ( in_array( \App\Http\helpers::getRole(), ['Administrator', 'AdminSp' , 'VLA']) )

                        <li class="nav-item">
                            <a href="/booking" class="nav-link">
                                <i class="icon-calendar"></i>
                                <span class="title">Bookings</span>
                                <span class="arrow"></span>
                            </a>
                        </li>                        

                        @endif

                        <li class="nav-item">
                            <a href="/service" class="nav-link">
                                <i class="icon-notebook"></i>
                                <span class="title">Services</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item ">
                                    <a href="/service" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Services</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="/service_provider" class="nav-link">
                                <i class="fa fa-briefcase"></i>
                                <span class="title">Service providers</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        @if ( \App\Http\helpers::getRole() == 'Administrator' )

                        <li class="nav-item">
                            <a href="/matter" class="nav-link nav-toggle">
                                <i class="fa fa-legal"></i>
                                <span class="title">Legal Matter</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item ">
                                    <a href="/matter" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Legal Matter</span>
                                    </a>
                                </li>
                                <li class="nav-item hidden ">
                                    <a href="/matter_type" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Matter Type</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="fa fa-question"></i>
                                <span class="title">Eligibility</span>
                                <span class="arrow"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item start ">
                                    <a href="/legal_matter_questions" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Legal Matter Conditions</span>
                                    </a>
                                </li>
                                <li class="nav-item start ">
                                    <a href="/eligibility_criteria" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Eligibility Criteria</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item">
                            <a href="/sms_template" class="nav-link">
                                <i class="fa fa-envelope"></i>
                                <span class="title">Sms Templates</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/catchment" class="nav-link">
                                <i class="fa fa-map-signs"></i>
                                <span class="title">Catchment</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="/user" class="nav-link">
                                <i class="fa fa-users"></i>
                                <span class="title">Users</span>
                                <span class="arrow"></span>
                            </a>
                        </li>
                        @endif
                    </ul>
                    <!-- END SIDEBAR MENU -->
                </div>
                <!-- END SIDEBAR -->
                
                <div class="hidden role" id="{{ \App\Http\helpers::getRole() }}"></div>
                <div class="hidden sp_id" id="{{ \App\Http\helpers::getUSerServiceProviderId() }}"></div>

            </div>
            <!-- END SIDEBAR -->
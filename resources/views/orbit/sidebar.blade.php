
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
                        <li class="nav-item start {{ Request::is('referral','referral/*') ? 'active' : null }}">
                            <a href="/referral" class="nav-link nav-toggle">
                                <i class="icon-paper-plane"></i>
                                <span class="title">Referrals</span>
                                <span class="arrow {{ Request::is('referral','referral/*') ? 'open' : null }}"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item {{ Request::is('referral') ? 'active' : null }}">
                                    <a href="/referral" class="nav-link">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Inbound</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('referral/outbound') ? 'active' : null }}">
                                    <a href="/referral/outbound" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Outbound</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        @if ( in_array( \App\Http\helpers::getRole(), ['Administrator', 'AdminSp' , 'VLA']) )

                        <li class="nav-item {{ Request::is('booking','booking/*') ? 'active' : null }}">
                            <a href="/booking/by_service_provider" class="nav-link nav-toggle">
                                <i class="icon-calendar"></i>
                                <span class="title">Bookings</span>
                                <span class="arrow {{ Request::is('booking','booking/*') ? 'open' : null }}"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item {{ Request::is('booking/by_service_provider') ? 'active' : null }}">
                                    <a href="/booking/by_service_provider" class="nav-link ">
                                        <i class="icon-calendar"></i>
                                        <span class="title">My Bookings</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('booking/next_bookings') ? 'active' : null }}">
                                    <a href="/booking/next_bookings" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Upcoming</span>
                                    </a>
                                </li>
                                @if( in_array(\App\Http\helpers::getUSerServiceProviderId(), [0,112]) && in_array( \App\Http\helpers::getRole(), ['Administrator', 'AdminSp']) )
                                <li class="nav-item {{ Request::is('booking/legalHelp') ? 'active' : null }}">
                                    <a href="/booking/legalHelp" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Legal Help</span>
                                    </a>
                                </li>
                                @endif
                                @if( in_array( \App\Http\helpers::getRole(), ['Administrator', 'AdminSp']) )
                                <li class="nav-item">
                                    <a href="{{ env('BOOKING_ENGINE_BASE_URL') }}login_vla" class="nav-link" target="_blank">
                                        <i class="fa fa-cog"></i>
                                        <span class="title">Manage Bookings</span>
                                    </a>
                                </li>
                                @endif
                            </ul>
                        </li>
                        @endif
                        <li class="nav-item {{ Request::is('no_reply_emails','no_reply_emails/*') ? 'active' : null }}">
                            <a href="/no_reply_emails" class="nav-link nav-toggle">
                                <i class="icon-envelope"></i>
                                <span class="title">Info Emails</span>
                                <span class="arrow {{ Request::is('no_reply_emails','no_reply_emails/*') ? 'open' : null }}"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item {{ Request::is('no_reply_emails/templates') ? 'active' : null }}">
                                    <a href="/no_reply_emails/templates/" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Templates</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('no_reply_emails') ? 'active' : null }}">
                                    <a href="/no_reply_emails/" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">View emails sent</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item {{ Request::is('service','service/*') ? 'active' : null }}">
                            <a href="/service" class="nav-link">
                                <i class="icon-notebook"></i>
                                <span class="title">Services</span>
                                <span class="arrow {{ Request::is('service','service/*') ? 'open' : null }}"></span>
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('service_provider','service_provider/*') ? 'active' : null }}">
                            <a href="/service_provider" class="nav-link">
                                <i class="fa fa-briefcase"></i>
                                <span class="title">Service providers</span>
                                <span class="arrow {{ Request::is('service_provider','service_provider/*') ? 'open' : null }}"></span>
                            </a>
                        </li>

                        @if ( in_array( \App\Http\helpers::getRole(), ['Administrator', 'AdminSp']) )

                        <li class="nav-item {{ Request::is('service_booking') ? 'active' : null }}">
                            <a href="/service_booking" class="nav-link">
                                <i class="fa fa-book"></i>
                                <span class="title">Service Booking</span>
                                <span class="arrow"></span>
                            </a>
                        </li>
                        @endif

                        @if ( \App\Http\helpers::getRole() == 'Administrator' )

                        <li class="nav-item {{ Request::is('matter','matter/*') ? 'active' : null }}">
                            <a href="/matter" class="nav-link nav-toggle">
                                <i class="fa fa-legal"></i>
                                <span class="title">Legal Matter</span>
                                <span class="arrow {{ Request::is('matter','matter/*') ? 'open' : null }}"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item {{ Request::is('matter') ? 'active' : null }}">
                                    <a href="/matter" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Legal Matter</span>
                                    </a>
                                </li>
                                <li class="nav-item hidden {{ Request::is('matter_type') ? 'active' : null }}">
                                    <a href="/matter_type" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Matter Type</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item {{ Request::is('legal_matter_questions','eligibility_criteria', 'question_group/*') ? 'active' : null }}">
                            <a href="javascript:;" class="nav-link nav-toggle">
                                <i class="fa fa-question"></i>
                                <span class="title">Eligibility</span>
                                <span class="arrow {{ Request::is('legal_matter_questions','eligibility_criteria') ? 'open' : null }}"></span>
                            </a>
                            <ul class="sub-menu">
                                <li class="nav-item {{ Request::is('legal_matter_questions') ? 'active' : null }}">
                                    <a href="/legal_matter_questions" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Legal Matter Conditions</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('eligibility_criteria') ? 'active' : null }}">
                                    <a href="/eligibility_criteria" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Eligibility Criteria</span>
                                    </a>
                                </li>
                                <li class="nav-item {{ Request::is('question_group') ? 'active' : null }}">
                                    <a href="/question_group" class="nav-link ">
                                        <i class="fa fa-list"></i>
                                        <span class="title">Eligibility Group</span>
                                    </a>
                                </li>
                            </ul>
                        </li>

                        <li class="nav-item {{ Request::is('sms_template') ? 'active' : null }}">
                            <a href="/sms_template" class="nav-link">
                                <i class="fa fa-envelope"></i>
                                <span class="title">Sms Templates</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('catchment') ? 'active' : null }}">
                            <a href="/catchment" class="nav-link">
                                <i class="fa fa-map-signs"></i>
                                <span class="title">Catchment</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('statistic') ? 'active' : null }}">
                            <a href="/statistic" class="nav-link">
                                <i class="fa fa-bar-chart"></i>
                                <span class="title">Statistics</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('user') ? 'active' : null }}">
                            <a href="/user" class="nav-link">
                                <i class="fa fa-users"></i>
                                <span class="title">Users</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('dashboard') ? 'active' : null }}">
                            <a href="/dashboard" class="nav-link">
                                <i class="fa fa-desktop"></i>
                                <span class="title">Dashboard wall</span>
                                <span class="arrow"></span>
                            </a>
                        </li>

                        <li class="nav-item start {{ Request::is('e_referral','e_referral/*') ? 'active' : null }}">
                            <a href="/e_referral" class="nav-link">
                                <i class="icon-paper-plane"></i>
                                <span class="title">E-Referrals</span>
                                <span class="arrow {{ Request::is('e_referral','e_referral/*') ? 'open' : null }}"></span>
                            </a>
                        </li>

                        <li class="nav-item {{ Request::is('panel_lawyers') ? 'active' : null }}">
                            <a href="/panel_lawyers" class="nav-link">
                                <i class="fa fa-gavel"></i>
                                <span class="title">Panel Lawyer</span>
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
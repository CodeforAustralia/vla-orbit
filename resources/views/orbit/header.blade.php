    <div id="header_app">
        <!-- BEGIN HEADER -->
        <div class="page-header navbar navbar-fixed-top">
            <!-- BEGIN HEADER INNER -->
            <div class="page-header-inner ">
                <!-- BEGIN LOGO -->
                <div class="page-logo">
                    <a href="/">
                        <img src="/assets/global/img/ORBIT_logo_White.png" alt="logo" class="logo-default" /> </a>
                    <div class="menu-toggler sidebar-toggler">
                        <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header -->
                    </div>
                </div>
                <!-- END LOGO -->
                <!-- BEGIN RESPONSIVE MENU TOGGLER -->
                <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a>
                <!-- END RESPONSIVE MENU TOGGLER -->
                <!-- BEGIN PAGE TOP -->
                <div class="page-top">
                    <!-- BEGIN HEADER SEARCH BOX -->
                    <!-- BEGIN TOP NAVIGATION MENU -->
                    @if(Auth::check())
                    <div class="pull-left top-buttons">
                        <a href="/referral/create/location" class="btn btn-sm btn-default main-green ">
                            New Referral
                        </a>

                        @if ( isset(Auth::user()->roles()->first()->name) && in_array( Auth::user()->roles()->first()->name, ['Administrator', 'AdminSp' , 'VLA']) )

                        <a href="/booking/new" class="btn btn-sm btn-default main-green ">
                            New Booking / Intake
                        </a>
                        @endif
                        <a href="/no_reply_emails/new" id='no-replay-email' class="btn btn-sm btn-default main-green ">
                            New Info Email
                        </a>

                    </div>
                    @endif

                    <div class="top-menu">
                        <ul class="nav navbar-nav pull-right">

                        @if(Auth::check())
                            <!-- BEGIN NOTIFICATION DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class below "dropdown-extended" to change the dropdown styte -->
                            <!-- DOC: Apply "dropdown-hoverable" class after below "dropdown" and remove data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to enable hover dropdown mode -->
                            <!-- DOC: Remove "dropdown-hoverable" and add data-toggle="dropdown" data-hover="dropdown" data-close-others="true" attributes to the below A element with dropdown-toggle class -->
                            <li class="dropdown dropdown-extended dropdown-notification" id="header_notification_bar">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" v-on:click="onNotificationsSeen">
                                    <i class="icon-bell"></i>
                                    <span class="badge badge-default"> {{ Auth::user()->userNotifications()['count'] }} </span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="external">
                                        <h3>
                                            <span class="bold">{{ Auth::user()->userNotifications()['count'] }} pending</span> notifications</h3>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list scroller" style="height: 250px;" data-handle-color="#637283">
                                            @foreach( Auth::user()->userNotifications()['all'] as $notification )
                                            <li>
                                                <a href="{{ $notification->url . '?'. $notification->object_type .'=' . $notification->object_id }}">
                                                    <span class="details">
                                                        <span class="label label-sm label-icon label-success">
                                                            <i class="icon-share"></i>
                                                        </span> {{ $notification->message }}. </span>
                                                </a>
                                            </li>
                                            @endforeach
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <!-- END NOTIFICATION DROPDOWN -->
                            <!-- BEGIN INBOX DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-extended dropdown-inbox" id="header_inbox_bar">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <i class="fa fa-question"></i>
                                    <span class="badge badge-default"></span>
                                </a>
                                <ul class="dropdown-menu">
                                    <li class="external">
                                        <h3>Problems? Suggestions?</h3>
                                    </li>
                                    <li>
                                        <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">

                                            <li>
                                                <a href="javascript:;">
                                                    <span class="details">
                                                        Thanks for using {{ ucfirst(config('app.name')) }}. We are still making changes and improvements to the tool,
                                                        and need to know what is working, what isn't and if something is missing. </span>
                                                </a>
                                                <a href="mailto:{{ config('app.team_email') }}">
                                                    <span class="details">Please send any feedback to
                                                        <span class="bold">{{ config('app.team_email') }}</span>
                                                        or use the chat tool in the bottom left side of the page.
                                                    </span>
                                                </a>
                                            </li>
                                        </ul>
                                    </li>
                                </ul>
                            </li>
                            <!-- END INBOX DROPDOWN -->
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-user">
                                <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <span class="username username-hide-on-mobile"> {{ Auth::user()->name }} </span>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-default">
                                    <li class="hidden">
                                        <a href="#">
                                            <i class="icon-user"></i> Office Settings </a>
                                    </li>
                                    <li class="hidden">
                                        <a href="#">
                                            <i class="icon-calendar"></i> My Bookings </a>
                                    </li>
                                    <li>
                                        <a href="/logout">
                                            <i class="icon-key"></i> Log Out </a>
                                    </li>
                                    <li>
                                        <a href="/change_password">
                                            <i class="icon-user"></i> Change Password </a>
                                    </li>
                                </ul>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->

                        @else
                            <!-- BEGIN USER LOGIN DROPDOWN -->
                            <!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
                            <li class="dropdown dropdown-user">
                                <a href="/login" class="dropdown-toggle">
                                    <span> Login </span>
                                    <i class="fa fa-sign-in" aria-hidden="true"></i>
                                </a>
                            </li>
                            <!-- END USER LOGIN DROPDOWN -->
                        @endif

                        </ul>

                    </div>
                    <!-- END TOP NAVIGATION MENU -->
                </div>
                <!-- END PAGE TOP -->
            </div>
            <!-- END HEADER INNER -->
        </div>
        <!-- END HEADER -->
        <!-- BEGIN HEADER & CONTENT DIVIDER -->
        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->

        <div class="clearfix"> </div>
        <!-- END HEADER & CONTENT DIVIDER -->
    </div>

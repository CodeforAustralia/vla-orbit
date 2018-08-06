<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="robots" content="noindex">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta content="Preview page of {{ ucfirst(config('app.name')) }}" name="description" />
    <meta content="Code For Australia and Victoria Legal Aid" name="author" />
    <meta name="_token" content="{{ csrf_token() }}"/>
    <link href="/assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="/assets/global/plugins/bootstrap-sweetalert/sweetalert.css" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Raleway" rel="stylesheet">
    <link href="/css/information.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="/assets/global/img/favicon/favicon-16x16.png" sizes="16x16">
    <link rel="icon" type="image/png" href="/assets/global/img/favicon/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/assets/global/img/favicon/favicon-92x92.png" sizes="92x92">

    <title>{{ strtoupper(config('app.name')) }}</title>

</head>
<body>
    <!-- Top Menu-->
    <div class="navbar navbar-inverse navbar-fixed-top">

        <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="#">{{ strtoupper(config('app.name')) }}</a>
        </div>
        <div class="collapse navbar-collapse">
        <ul class="nav navbar-nav">
            <li><a href="#">Home</a></li>
            <li><a href="#info">About</a></li>
            <li><a href="#how_it_works">How it works</a></li>
            <li><a href="#quotes">Testimonials</a></li>
            <li><a href="#contact">Request Access</a></li>
            <li class="visible-xs"><a href="#contact" data-toggle="collapse" data-target=".navbar-collapse.in">Close</a></li>
        </ul>
        </div><!--/.nav-collapse -->
    </div>

    <!-- End - Top Menu-->

    <!-- Banner Section -->
    <div class="banner">
        <iframe class="youtube-video" src="https://www.youtube.com/embed/3ltXlQduKWQ?autoplay=1&showinfo=0&mute=1&enablejsapi=1&controls=0&loop=1&playlist=3ltXlQduKWQ" frameborder="0" allowfullscreen></iframe>
        <div class="sq-overlay">
            <div class="col-sm-8 info text-center">
                <h1>{{ strtoupper(config('app.name')) }}</h1>
                <h2> Your new online tool to help clients get to legal services in Victoria</h2>
                <a href="#contact" target="_self" role="button" class="button">
                    <span>Request access</span>
                </a>
            </div>
            <div class="col-sm-12 text-center dashboard-img">
                <img src="/assets/pages/img/information/dashboard.png" />
            </div>
        </div>
    </div>
    <!-- End - Banner Section -->


    <!-- Main Info Section -->
    <div class="row section_1" id="info">

        <div class="col-xs-10 col-xs-offset-1">
            <p>With more than 500 legal services across Victoria and a web of eligibility questions determining if a client qualifies for a service, finding appropriate legal assistance for eligible clients is challenging for frontline staff.</p>
            <p>{{ strtoupper(config('app.name')) }} is an online referral booking and information tool that aims to tackle some of the challenges staff and clients meet when a legal problem occurs while focusing on improving both the client and staff experience.</p>
        </div>

        <div class="col-xs-10 col-xs-offset-1">
            <div class="text-center col-xs-12 col-sm-4">
                <p class="purple-font">Accurate referrals</p>
                <p class="small-text">Get easy access to relevant, comprehensive, up-to-date information on legal and social services across Victoria.</p>
            </div>
            <div class="text-center col-xs-12 col-sm-4">
                <p class="purple-font">Free up time</p>
                <p class="small-text">Take the guesswork out of referrals and free up time to focus on other tasks.</p>
                <p class="small-text">No more worrying about staying updated on ever-changing service guidelines and eligibility criteria. </p>
            </div>
            <div class="text-center col-xs-12 col-sm-4">
            <p class="purple-font">Early intervention</p>
            <p class="small-text">Share targeted relevant information with clients specific to their circumstances.</p>
            </div>
        </div>

    </div>
    <!-- End - Main Info Section -->

    <!-- How it works Section -->
    <div class="how_it_works text-center" id="how_it_works">
        <h3>How it works</h3>
        <div class="row">

            <div class="col-xs-10 col-xs-offset-1 col-sm-5 col-sm-offset-1">
                <img src="/assets/pages/img/information/Referral.png" />
            </div>

            <div class="col-xs-10 col-xs-offset-1 col-sm-5 col-sm-offset-0">
                <h4 class="text-left">Referrals</h4>
                <p>Based on clients' circumstances, type of legal matter(s), what stage the legal matter is in and where they live, {{ strtoupper(config('app.name')) }} makes it easy for staff to find accurate referral options.</p>
                <p>Referral options include VLA services, CLC services and common support services. ​</p>
                <p>Referral information can easily be shared with the client over SMS or email.</p>
            </div>

        </div>

        <div class="row">

            <div class="col-xs-10 col-xs-offset-1 col-sm-5 col-sm-offset-1">
                <h4 class="text-left">Bookings</h4>
                <p>Administer your bookable services in {{ strtoupper(config('app.name')) }} and allow staff to easily find the next available appointment and book clients directly.</p>
                <p>If appropriate open up bookings to other offices and let them book clients into appointments directly. ​</p>
                <p>Bookings in {{ strtoupper(config('app.name')) }} includes the option of sending clients automatic SMS reminders to attend appointments. </p>
            </div>
            <div class="col-xs-10 col-xs-offset-1 col-sm-5 col-sm-offset-0">
                <img src="/assets/pages/img/information/book_pop_up.png" />
            </div>

        </div>

        <div class="row">

            <div class="col-xs-10 col-xs-offset-1 col-sm-5 col-sm-offset-1">
                <img src="/assets/pages/img/information/NRE.png" />
            </div>

            <div class="col-xs-10 col-xs-offset-1 col-sm-5 col-sm-offset-0">
                <h4 class="text-left">Information</h4>
                <p>{{ strtoupper(config('app.name')) }} provides over 100 email templates. The emails cover basic information and have been developed by VLA’s Legal Help and Community Legal Education teams.</p>
                <p>The emails provide a good alternative to a referral for clients who are capable of self-help or as a supplement to a referral.</p>
            </div>

        </div>
    </div>
    <!-- End - How it works Section -->

    <!-- Quotes Section -->
    <div class="row col-sm-12 quote text-center" id="quotes">

        <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2">
            <h5>Brendan Lacota Moonee Valley Legal Service</h5>
            <p>"Our staff and volunteers use {{ strtoupper(config('app.name')) }} daily to help with referrals. We have a large number of volunteers who appreciate {{ strtoupper(config('app.name')) }}’s easy to use and intuitive interface, which is a testament to its human centred design. {{ strtoupper(config('app.name')) }} helps us to save time and provide better referrals to clients."</p>
        </div>
        <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2">
            <h5>Legal Help Officer</h5>
            <p>"I like the fact that it makes me think of other relevant services. Especially non legal services."</p>
        </div>

    </div>
    <!-- End - Quotes Section -->

    <!-- CLC Logos Section -->
    <div class="row logos" id="logos">
        <div class="col-xs-10 col-xs-offset-1">
            <div class="col-xs-12">
                <p>{{ strtoupper(config('app.name')) }} is the product of a thorough research phase learning about client and staff needs. The development has been in close collaboration with frontline staff at Victoria Legal Aid offices and community legal centres.</p>

                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="https://www.fitzroy-legal.org.au/" target="blank">
                        <img src="/assets/pages/img/information/clcs/FLS.png" alt="">
                    </a>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="http://www.skls.org.au/" target="blank">
                        <img src="/assets/pages/img/information/clcs/St kilda.png" alt="">
                    </a>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="https://imcl.org.au/" target="blank">
                        <img src="/assets/pages/img/information/clcs/IMLS.png" alt="">
                    </a>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="http://www.westjustice.org.au/" target="blank">
                        <img src="/assets/pages/img/information/clcs/Westjustice.png" alt="">
                    </a>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="http://womenslegal.org.au/about-us.html" target="blank">
                        <img src="/assets/pages/img/information/clcs/Womens.png" alt="">
                    </a>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="http://smls.org.au/" target="blank">
                        <img src="/assets/pages/img/information/clcs/springvale.png" alt="">
                    </a>
                </div>

                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="https://www.comm-unityplus.org.au/legal-services" target="blank">
                    <img src="/assets/pages/img/information/clcs/Cplus.png" alt="">
                    </a>
                </div>

                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="http://www.legalaid.vic.gov.au/" target="blank">
                    <img src="/assets/pages/img/information/clcs/VLA.png" alt="">
                    </a>
                </div>

                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="https://www.justiceconnect.org.au/" target="blank">
                    <img src="/assets/pages/img/information/clcs/JusticeConnect.png" alt="">
                    </a>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="http://www.fclc.org.au/" target="blank">
                    <img src="/assets/pages/img/information/clcs/FCLC.png" alt="">
                    </a>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="https://www.mvls.org.au/" target="blank">
                    <img src="/assets/pages/img/information/clcs/MVLS.png" alt="">
                    </a>
                </div>
                <div class="col-xs-6 col-sm-4 col-md-2 img_container">
                    <a href="https://consumeraction.org.au/" target="blank">
                    <img src="/assets/pages/img/information/clcs/CALC.png" alt="">
                    </a>
                </div>
            </div>
            </a>
        </div>
    </div>
    <!-- End - CLC Logos Section -->

    <!-- Form Section -->
    <div class="row contact" id="contact">
        <div class="sq-overlay">
            <div class="col-xs-10 col-xs-offset-1 col-sm-8 col-sm-offset-2 contact-form">
                <h6>Request access</h6>
                <p>To get access to {{ strtoupper(config('app.name')) }} or schedule a demo, please fill in your contact details</p>
                <form  @submit="onSubmit" @keydown="form.errors.clear($event.target.name)">

                    <div class="form-group row">
                        <div class="col-xs-12 col-sm-6">
                            <input type="text" class="form-control" name="name" v-model="form.name" id="name" placeholder="Name *">
                            <span class="help-block is-danger" v-if="form.errors.has('name')" v-text="form.errors.get('name')"></span>
                        </div>
                        <div class="col-xs-12 col-sm-6">
                            <input type="email" class="form-control" name="email" v-model="form.email" id="email" placeholder="Email *">
                            <span class="help-block is-danger" v-if="form.errors.has('email')" v-text="form.errors.get('email')"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12">
                            <textarea class="form-control" name="message" id="message" v-model="form.message" rows="5" placeholder="Message"></textarea>
                            <span class="help-block is-danger" v-if="form.errors.has('message')" v-text="form.errors.get('message')"></span>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="col-xs-12">
                            <button type="submit" value="" class="btn btn-default btn-blue" :disabled="form.errors.any()">Send</button>
                        </div>
                    </div>

                </form>
                <div class="col-xs-6 text-right email-container">
                    <a href="mailto:{{ config('app.team_email') }}">{{ config('app.team_email') }}</a>
                </div>
                <div class="col-xs-6 text-left">
                    <a href="{{ config('app.url') }}">{{ config('app.url') }}</a>
                </div>
            </div>
        </div>
    </div>
    <!-- End - Form Section -->

    <footer class="row text-center">
        <p>© 2018 Victoria Legal Aid & Code for Australia</p>
    </footer>

    <script src="/assets/global/plugins/jquery.min.js" type="text/javascript"></script>

    <script src="/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="/assets/pages/scripts/ui-sweetalert.min.js" type="text/javascript"></script>
    <script src="/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js" type="text/javascript"></script>

    <script src="/js/information_vue.js"></script>
</body>
</html>
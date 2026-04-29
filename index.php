<?php
require_once('./config.php');
require_once("./links.php");
$base = rtrim(APP_URL, '/');
?>

<!--title-->
<title>Home : Hasnet ICT Solution</title>
<meta name="description" content="Hasnet ICT Solutions offers top-notch IT services in Zanzibar, including website design, graphic design, social media management, mobile app development, and hardware maintenance. Empowering your business with innovative technology solutions.">



</head>

<body>


    <!--dark light switcher-->
    <!--<button class="dark-light-switcher" id="theme-switch">-->
        <!--<span class="light-sun"><i class="fa-solid fa-sun"></i></span>src="assets/img/shapes/migrate-frame.svg"-->
        <!--<span class="dark-moon"><i class="fa-solid fa-moon"></i></span>-->
    </button>

    <!--body overlay -->
    <div class="body-overlay"></div>

    <!--scrolltop button -->
    <button class="scrolltop-btn"><i class="fa-solid fa-angle-up"></i></button>

    <!-- preloader start -->
    <!-- <div class="loader-wrap">
        <div class="preloader">
            <div id="handle-preloader" class="handle-preloader">
                <div class="animation-preloader">
                    <div class="spinner"></div>
                    <div class="txt-loading">
                        <span data-text-preloader="H" class="letters-loading">H</span>
                        <span data-text-preloader="A" class="letters-loading">A</span>
                        <span data-text-preloader="S" class="letters-loading">S</span>
                        <span data-text-preloader="N" class="letters-loading">N</span>
                        <span data-text-preloader="E" class="letters-loading">E</span>
                        <span data-text-preloader="T" class="letters-loading">T</span>
                    </div>
                </div> 
            </div>
        </div>
    </div>  -->
    <!-- preloader end -->

    <!-- Header -->
    <?php
    require_once("./header.php");
    require_once("./modal.php");
    ?>
    <!-- Header ends here -->





    <style>
    /* ── Hero Carousel ─────────────────────────── */
    #carouselExampleDark {
        height: 95vh;
        min-height: 480px;
        overflow: hidden;
        position: relative;
    }
    #carouselExampleDark .carousel-inner,
    #carouselExampleDark .carousel-item {
        height: 100%;
        width: 100%;
        position: relative;
    }
    #carouselExampleDark .carousel-item img.slide-bg {
        position: absolute;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        object-position: center;
        display: block;
        z-index: 0;
    }
    .carousel-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0,0,0,.52);
        z-index: 1;
    }
    .carousel-caption {
        position: absolute;
        inset: 0;
        z-index: 2;
        display: flex !important;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 20px;
    }
    .carousel-caption h5 {
        color: #fff;
        font-size: clamp(1.6rem, 4vw, 3.2rem);
        font-weight: 700;
        margin-bottom: 16px;
    }
    .carousel-caption p { color: rgba(255,255,255,.9); max-width: 640px; }
    @media (max-width: 768px) {
        #carouselExampleDark { height: 60vh; min-height: 360px; }
    }
    @keyframes bounce {
        0%,100% { transform: translateY(0); }
        50%      { transform: translateY(8px); }
    }
</style>

<div id="carouselExampleDark" class="carousel carousel-dark slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active" data-bs-interval="4000">
            <img src="<?= $base ?>/assets/img/home-slider/bg-02.jpg" class="slide-bg" alt="Web Design">
            <div class="carousel-overlay"></div>
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center text-white">
                <h5>Web Design, Hosting &amp; Digital Marketing</h5>
                <p>We transform your online presence with stunning web design, reliable hosting, and impactful digital marketing strategies to drive your success.</p>
                <div class="migrate-btns">
                    <a href="web-design-hosting-and-digital-marketing.php" class="template-btn outline-btn">Explore More</a>
                    <a href="#1st-services" class="scroll-down-bt d-block mt-3">
                        <i class="fa-solid fa-chevron-down" style="color:white;font-size:24px;animation:bounce 1.5s infinite;display:block"></i>
                        <i class="fa-solid fa-chevron-down" style="color:white;font-size:24px;animation:bounce 1.5s infinite 0.2s;display:block"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="carousel-item" data-bs-interval="4000">
            <img src="<?= $base ?>/assets/img/home-slider/bg-05.jpg" class="slide-bg" alt="Networking">
            <div class="carousel-overlay"></div>
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center text-white">
                <h5>Networking &amp; Digital Security</h5>
                <p>We ensure seamless connectivity and robust protection with our advanced networking solutions and cutting-edge digital security measures.</p>
                <div class="migrate-btns">
                    <a href="networking-and-digital-security.php" class="template-btn outline-btn">Explore More</a>
                    <a href="#1st-services" class="scroll-down-bt d-block mt-3">
                        <i class="fa-solid fa-chevron-down" style="color:white;font-size:24px;animation:bounce 1.5s infinite;display:block"></i>
                        <i class="fa-solid fa-chevron-down" style="color:white;font-size:24px;animation:bounce 1.5s infinite 0.2s;display:block"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="carousel-item" data-bs-interval="4000">
            <img src="<?= $base ?>/assets/img/home-slider/bg-06.jpg" class="slide-bg" alt="Graphic Design">
            <div class="carousel-overlay"></div>
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center text-white">
                <h5>Graphic Design &amp; Publish Printing</h5>
                <p>We create captivating visuals and high-quality prints, bringing your brand’s vision to life with our professional graphic design and publishing services.</p>
                <div class="migrate-btns">
                    <a href="graphic-design-and-publish-printing.php" class="template-btn outline-btn">Explore More</a>
                    <a href="#1st-services" class="scroll-down-bt d-block mt-3">
                        <i class="fa-solid fa-chevron-down" style="color:white;font-size:24px;animation:bounce 1.5s infinite;display:block"></i>
                        <i class="fa-solid fa-chevron-down" style="color:white;font-size:24px;animation:bounce 1.5s infinite 0.2s;display:block"></i>
                    </a>
                </div>
            </div>
        </div>

        <div class="carousel-item" data-bs-interval="4000">
            <img src="<?= $base ?>/assets/img/home-slider/bg-07.jpg" class="slide-bg" alt="ICT Hardware">
            <div class="carousel-overlay"></div>
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center text-center text-white">
                <h5>ICT Hardware Supply, Installation &amp; Maintenance</h5>
                <p>We provide comprehensive ICT hardware solutions, from supply and installation to ongoing maintenance, ensuring your technology infrastructure is always at its best.</p>
                <div class="migrate-btns">
                    <a href="ict-hardware-supply-and-maintanance.php" class="template-btn outline-btn">Explore More</a>
                    <a href="#1st-services" class="scroll-down-bt d-block mt-3">
                        <i class="fa-solid fa-chevron-down" style="color:white;font-size:24px;animation:bounce 1.5s infinite;display:block"></i>
                        <i class="fa-solid fa-chevron-down" style="color:white;font-size:24px;animation:bounce 1.5s infinite 0.2s;display:block"></i>
                    </a>
                </div>
            </div>
        </div>

    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleDark" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>



<!-- welcome quote -->
<section class="pricing-tab-section pb-50  position-relative zindex-1 overflow-hidden" style="padding-top: 13px;" id="1st-services">
       <div class="container">


            <div class="money-back-area text-center bg-white rounded-10 mt-4 position-relative zindex-1 overflow-hidden">
               <h3>Your satisfaction is our success</h3>
                <p> "We employ our minds in innovation, to bring about technological revolution" <br>  Its all about <strong>"Enginnovation"</strong> </p>
              
            </div>
        </div>
    </section>


<!-- Our Services -->

<div class="row justify-content-center">
                    <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="gh-section-title text-center">
                        <div class="section-title text-center">
                            <h2 class="mb-3">Our Services</h2>
                            <p>From web design and digital marketing to network setup and hardware maintenance,
                                 we deliver comprehensive IT solutions tailored to enhance your business efficiency and security.</p>
                        </div>
                    </div>
                </div>
            </div>
                    </div>

                    
<section class="mt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">

                <div class="row mb-3">
                    <div class="col-lg-6 col-md-6 mb-3">
                        <a href="web-design-hosting-and-digital-marketing.php" class="text-decoration-none">
                            <div class="promo-item bg-white rounded primary-shadow shadow">
                                <div class="promo-top d-flex align-items-center">
                                    <div class="fa fa-database"></div>
                                    <h5 class="ms-3 mb-0">Web & Digital Solutions</h5>
                                </div>
                                <ul class="text-muted ms-4 mb-0">
                                    <li><a href="web-design-hosting-and-digital-marketing.php" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>Website & Database Development</a></li>
                                    <li><a href="web-design-hosting-and-digital-marketing.php#hosting" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>Domain & Hosting</a></li>
                                    <li><a href="web-design-hosting-and-digital-marketing.php#digital-marketing" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>Digital Marketing (SEO & Social Medias)</a></li>
                                </ul>
                            </div>
                        </a>
                    </div>

                    <div class="col-lg-6 col-md-6 mb-3">
                        <a href="networking-and-digital-security.php" class="text-decoration-none">
                            <div class="promo-item bg-white rounded primary-shadow shadow">
                                <div class="promo-top d-flex align-items-center">
                                    <div class="fa fa-wifi"></div>
                                    <h5 class="ms-3 mb-0">Networking & Security</h5>
                                </div>
                                <ul class="text-muted ms-4 mb-0">
                                    <li><a href="networking-and-digital-security.php" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>CCTV Cameras</a></li>
                                    <li><a href="networking-and-digital-security.php#accesscontrol" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>Access Control</a></li>
                                    <li><a href="networking-and-digital-security.php#networking&ipphone" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>Network Infrastructure</a></li>

                                </ul>
                            </div>
                        </a>
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-6 col-md-6 mb-3">
                        <a href="graphic-design-and-publish-printing.php" class="text-decoration-none">
                            <div class="promo-item bg-white rounded primary-shadow shadow">
                                <div class="promo-top d-flex align-items-center">
                                    <div class="fa fa-image"></div>
                                    <h5 class="ms-3 mb-0">Branding & Printing</h5>
                                </div>
                                <ul class="text-muted ms-4 mb-0">
                                    <li><a href="graphic-design-and-publish-printing.php#digital-printing" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>Design & Advertisement</a></li>
                                    <li><a href="graphic-design-and-publish-printing.php#digital-printing" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>Digital Printing</a></li>
                                    <li><a href="graphic-design-and-publish-printing.php#large-format" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>Large Format Printing</a></li>
                                </ul>
                            </div>
                        </a>
                    </div>


                    <div class="col-lg-6 col-md-6 mb-3">
    <a href="ict-hardware-supply-and-maintanance.php" class="text-decoration-none">
        <div class="promo-item bg-white rounded primary-shadow shadow">
            <div class="promo-top d-flex align-items-center">
                <div class="fa fa-cogs"></div>
                <h5 class="ms-3 mb-0">IT Infrastructure & Support</h5>
            </div>
            <ul class="text-muted ms-4 mb-0">
                <li><a href="ict-hardware-supply-and-maintanance.php#accessories" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>Hardware Supply</a></li>
                <li><a href="ict-hardware-supply-and-maintanance.php#consultancy" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>Power & Data Backup</a></li>
                <li><a href="ict-hardware-supply-and-maintanance.php#consultancy" class="text-decoration-none"><i class="fa fa-caret-right me-2"></i>IT Support & Consultancy</a></li>
            </ul>
        </div>
    </a>
</div>



                </div>
            </div>
        </div>
    </div>
</section>





    <!--Why Choosing Us-->
    <section class="pricing-tab-section light-bg pb-120  position-relative zindex-1 overflow-hidden" style="padding-top: 250px;">
        <img src="assets/img/shapes/line-red-top.png" alt="line shape" class="position-absolute right-top d-none d-lg-block">
        <!--<img src="assets/img/shapes/line-red.svg" alt="line shape" class="position-absolute left-bottom">-->
        <div class="container">


            <div class="money-back-area text-center bg-white rounded-10 mt-4 position-relative zindex-1 overflow-hidden">
                <img src="assets/img/shapes/mb-circle-left.png" class="position-absolute left-top" alt="circle">
                <img src="assets/img/shapes/mb-circle-right.png" class="position-absolute right-bottom" alt="circle" id="#get-started">
                <h3>Why Choosing Us?</h3>
                <p> Check out the 3 reasons ...</p>
                <ul class="money-back_ft-list d-flex align-items-center justify-content-start justify-content-md-center mt-30">
                    <li><i class="fa-solid fa-check-square"></i><span class="ms-2">ICT Expertise and After Service Support</span></li>
                    <li><i class="fa-solid fa-check-square"></i><span class="ms-2">Affordable Cost at Higher Standard</span></li>
                    <li><i class="fa-solid fa-check-square"></i><span class="ms-2">Innovation-Driven Approach</span></li>
                </ul>
            </div>
        </div>
    </section>
    <!--Why Choosing Us end-->



    <!--hero section end-->



    <!--migrate hosting start-->
    <section class="migrate-hosting bg-primary-gradient position-relative zindex-1">
        <!--<img src="assets/img/shapes/migrate-shape-1.svg" alt="circle" class="position-absolute left-bottom soft-light">-->
        <!--<img src="assets/img/shapes/plus-rounded.svg" alt="plus" class="position-absolute rounded-plus">-->
        <!--<img src="assets/img/shapes/migrate-frame.svg" alt="frame" class="position-absolute migrate-frame".>-->
        <!--<img src="assets/img/shapes/line-wave.svg" alt="frame" class="position-absolute line-wave soft-light">-->
        <div class="container">
            <div class="row justify-content-between align-items-center">
                <div class="col-xl-5 col-lg-6 order-2 order-lg-1">
                    <div class="migrate-content">
                        <h2 class="mb-4">Your Satisfaction Is Our Success</h2>
                        <p class="mb-50">
                        Our team of experts is committed to provide reliable and innovative solutions that will help your business succeed.

                        <br>
                        <br>
                            Trust us to deliver the latest technology solutions and equipment,
                            so you can focus on growing your business.
                        </p>
                        <div class="migrate-btns">
                            <a href="contact.php" class="template-btn outline-btn">Contact Us</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5 order-1 order-lg-2">
                    <div class="migrate-feature-img mb-5 mb-lg-0">
                        <img src="assets/img/migrate.png" alt="migrate" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!--migrate hosting end-->






    <!--faq section start-->
    <section class="ds-faq pb-120  mt-90 bg-white">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-7">
                    <div class="section-title text-center mt-5">
                        <h2 class="mb-3">Get ready to be inspired! </h2>
                        <p>
                            We invite you to explore our compelling mission and vision statements, Our Objectives,
                            Our Organograph plus What we do and discover the exciting range of activities and services we currently offer.
                            Take a closer look and see how we're making a positive impact in our community!</p>
                    </div>
                </div>
            </div>
            <div class="mt-5">
                <div class="d-flex align-items-center justify-content-center ds-faq-controls">
                    <ul class="nav nav-tabs border-0 ds-faq-controls justify-content-center">

                        <li><button class="active" data-bs-toggle="tab" data-bs-target="#faq_could_hosting"><i class="fa-solid fa-tasks"></i><span class="ms-3">What We Do?</span></button></li>

                        <li><button data-bs-toggle="tab" data-bs-target="#faq_shared_hosting"><i class="fa-solid fa-bullseye"></i><span class="ms-3">Our Mission</span></button></li>

                        <li><button data-bs-toggle="tab" data-bs-target="#faq_vps_hosting"><i class="fa-solid fa-eye"></i><span class="ms-3">Our Vision</span></button></li>

                        <li><button data-bs-toggle="tab" data-bs-target="#faq_wp_hosting"><i class="fa-solid fa-object-group"></i><span class="ms-3">Our
                                    Objectives</span></button></li>

                        <li><button data-bs-toggle="tab" data-bs-target="#faq_could_hosting-admin"><i class="fa-solid fa-users"></i><span class="ms-3">Our Organograph</span></button>
                        </li>
                    </ul>
                </div>
                <div class="tab-content mt-30 position-relative zindex-1">

                    <!-- ==================================== WHAT WE DO===========================================================-->

                    <div class="tab-pane fade show active" id="faq_could_hosting">
                        <div class="accordion hm2-accordion rounded-2 deep-shadow bg-white" id="accordion_4">
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_14" data-bs-toggle="collapse">Technology is what we believe
                                    </a>
                                </div>
                                <div class="accordion-collapse collapse show" id="sh_14" data-bs-parent="#accordion_4">
                                    <div class="accordion-body">
                                        <p class="mb-0">We specialize in comprehensive ICT solutions, including website design, database system development, domain registration & hosting, digital marketing, graphic design and printing, CCTV supply & installation, networking, ICT consultancy & training. With a focus on innovation, we turn ideas into reality.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ==============================WHAT WE DO END===================================================== -->

                    <div class="tab-pane fade " id="faq_shared_hosting">
                        <div class="accordion hm2-accordion rounded-2 deep-shadow bg-white" id="accordion_1">
                            <div class="accordion-item">

                                <!-- ==========================================OUR MISSION======================================================= -->

                                <div class="accordion-header">
                                    <a href="#sh_1" data-bs-toggle="collapse">Mission Number One </a>
                                </div>
                                <div class="accordion-collapse collapse show" id="sh_1" data-bs-parent="#accordion_1">
                                    <div class="accordion-body">
                                        <p class="mb-0">To provide better, effective, reliable, and affordable services
                                            on Websites & Database Systems development, Domain Registration & Hosting,
                                            Digital Marketing,
                                            Graphic Design & Printing,
                                            CCTV Cameras Supply & Installation, Computerized Maintenance, Networking,
                                            ICT Consultancy & Training.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_2" data-bs-toggle="collapse" class="collapsed">Mission Number Two
                                    </a>
                                </div>
                                <div class="accordion-collapse collapse" id="sh_2" data-bs-parent="#accordion_1">
                                    <div class="accordion-body">
                                        <p class="mb-0">To enhance innovation in our community, and help our Country
                                            solving the socio-economic challenges through technology, by establishing
                                            the Innovation Hub, which will handle and grow up the ideas into an
                                            effectiveness.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_3" data-bs-toggle="collapse" class="collapsed">Mission Number Three
                                    </a>
                                </div>
                                <div class="accordion-collapse collapse" id="sh_3" data-bs-parent="#accordion_1">
                                    <div class="accordion-body">
                                        <p class="mb-0">To build long term relationships with our customers/clients and
                                            provide exceptional customer services by pursuing services through care,
                                            innovation and advanced technology.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_4" data-bs-toggle="collapse" class="collapsed">Mission Number Four
                                    </a>
                                </div>
                                <div class="accordion-collapse collapse" id="sh_4" data-bs-parent="#accordion_1">
                                    <div class="accordion-body">
                                        <p class="mb-0">To create opportunities to the young generation who are in eager
                                            on technological revolution.</p>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>

                    <!-- ==============================OUR MISSION END==================================== -->


                    <!-- =============================OUR OBJECTIVES=======================================  -->
                    <div class="tab-pane fade" id="faq_wp_hosting">
                        <div class="accordion hm2-accordion rounded-2 deep-shadow bg-white" id="accordion_2">

                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_9" data-bs-toggle="collapse">Objective Number One</a>
                                </div>
                                <div class="accordion-collapse collapse show" id="sh_9" data-bs-parent="#accordion_2">
                                    <div class="accordion-body">
                                        <p class="mb-0">To provide quality services that exceeds the expectations of
                                            esteemed customers.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_10" data-bs-toggle="collapse" class="collapsed">Objective Number Two
                                    </a>
                                </div>
                                <div class="accordion-collapse collapse" id="sh_10" data-bs-parent="#accordion_2">
                                    <div class="accordion-body">
                                        <p class="mb-0">To provide a affordable and reliable services in all classes
                                            regarding lower or upper class.</p>
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_11" data-bs-toggle="collapse" class="collapsed">Objective Number Three
                                    </a>
                                </div>
                                <div class="accordion-collapse collapse" id="sh_11" data-bs-parent="#accordion_2">
                                    <div class="accordion-body">
                                        <p class="mb-0">To make sure we are there any time, when our customers need us.
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_12" data-bs-toggle="collapse" class="collapsed">Objective Number Four
                                    </a>
                                </div>
                                <div class="accordion-collapse collapse" id="sh_12" data-bs-parent="#accordion_2">
                                    <div class="accordion-body">
                                        <p class="mb-0">Ensuring super quality services that will maintain our relation
                                            with our honored customers.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_13" data-bs-toggle="collapse" class="collapsed">Objective Number Five
                                    </a>
                                </div>
                                <div class="accordion-collapse collapse" id="sh_13" data-bs-parent="#accordion_2">
                                    <div class="accordion-body">
                                        <p class="mb-0">Maintaining truth and preserve the customer’s security and
                                            privacy.</p>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_14" data-bs-toggle="collapse" class="collapsed">Objective Number Six
                                    </a>
                                </div>
                                <div class="accordion-collapse collapse" id="sh_14" data-bs-parent="#accordion_2">
                                    <div class="accordion-body">
                                        <p class="mb-0">Bringing the societies together by motivating them through
                                            innovation, and provide consultancy on issues of technology, this will help
                                            the Company being closer to the societies and result a positive outcomes to
                                            our Country.</p>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>

                    <!-- =============================OUR OBJECTIVES END============================================================ -->

                    <!-- ============================OUR VISION=====================================================================-->

                    <div class="tab-pane fade" id="faq_vps_hosting">
                        <div class="accordion hm2-accordion rounded-2 deep-shadow bg-white" id="accordion_3">
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_12" data-bs-toggle="collapse">Our Vision</a>
                                </div>
                                <div class="accordion-collapse collapse show" id="sh_12" data-bs-parent="#accordion_3">
                                    <div class="accordion-body">
                                        <p class="mb-0"> <b>Our Vision </b>"is to become the leading ICT Company in
                                            Zanzibar and
                                            then opening more branches in Tanzania Mainland, which provides the better,
                                            affordable and efficient ICT services, which satisfy the clients’ needs".
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- ===============================OUR VISION ENDS========================================================== -->



                    <!-- ==================================================ORGANOGRAPH============================ -->
                    <div class="tab-pane fade" id="faq_could_hosting-admin">
                        <div class="accordion hm2-accordion rounded-2 deep-shadow bg-white" id="accordion_4">
                            <div class="accordion-item">
                                <div class="accordion-header">
                                    <a href="#sh_14" data-bs-toggle="collapse">Our Organograph
                                    </a>
                                </div>
                                <div class="accordion-collapse collapse show" id="sh_14" data-bs-parent="#accordion_4">
                                    <div class="accordion-body">
                                        <p class="mb-0">Our Company structure is made up of General Manager (GM) along with Assistant (GM), followed by Operation Manager (OM) and Finance Manager (FM). <br>
                                            And there are three main operational departments; Engineering, Software and Printing Department. Our Team is made up of Engineers, Technicians (IT and Electrical), Programmers and Graphic Designers.</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- ==================================================ORGANOGRAPH END============================ -->


                </div>

            </div>
        </div>
    </section>
    <!--faq section end-->


<!-- Clients Section -->
<!-- <section class="clients-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Our Clients</h2>
                <div class="client-logos-container" style="overflow: hidden;">
                    <div class="client-logos" style="white-space: nowrap; animation: scroll 20s linear infinite;">
                        <div class="client-logo" style="display: inline-block; margin-right: 20px;">
                            <a href="https://www.client1website.com" target="_blank"><img src="assets/img/game-hosting/Hyatt.jpg" alt="Client 1 Logo" style="max-width: 100px; height: auto;"></a>
                        </div>
                        <div class="client-logo" style="display: inline-block; margin-right: 20px;">
                            <a href="https://www.client2website.com" target="_blank"><img src="assets/img/game-hosting/Hyatt.jpg" alt="Client 2 Logo" style="max-width: 100px; height: auto;"></a>
                        </div>
                        <div class="client-logo" style="display: inline-block; margin-right: 20px;">
                            <a href="https://www.client2website.com" target="_blank"><img src="assets/img/game-hosting/Hyatt.jpg" alt="Client 2 Logo" style="max-width: 100px; height: auto;"></a>
                        </div>
                        <div class="client-logo" style="display: inline-block; margin-right: 20px;">
                            <a href="https://www.client2website.com" target="_blank"><img src="assets/img/game-hosting/Hyatt.jpg" alt="Client 2 Logo" style="max-width: 100px; height: auto;"></a>
                        </div>
                        <div class="client-logo" style="display: inline-block; margin-right: 20px;">
                            <a href="https://www.client2website.com" target="_blank"><img src="assets/img/game-hosting/Hyatt.jpg" alt="Client 2 Logo" style="max-width: 100px; height: auto;"></a>
                        </div>
                        <div class="client-logo" style="display: inline-block; margin-right: 20px;">
                            <a href="https://www.client2website.com" target="_blank"><img src="assets/img/game-hosting/Hyatt.jpg" alt="Client 2 Logo" style="max-width: 100px; height: auto;"></a>
                        </div>
                        <div class="client-logo" style="display: inline-block; margin-right: 20px;">
                            <a href="https://www.client2website.com" target="_blank"><img src="assets/img/game-hosting/Hyatt.jpg" alt="Client 2 Logo" style="max-width: 100px; height: auto;"></a>
                        </div>
                        <div class="client-logo" style="display: inline-block; margin-right: 20px;">
                            <a href="https://www.client2website.com" target="_blank"><img src="assets/img/game-hosting/Hyatt.jpg" alt="Client 2 Logo" style="max-width: 100px; height: auto;"></a>
                        </div>
                        <div class="client-logo" style="display: inline-block; margin-right: 20px;">
                            <a href="https://www.client2website.com" target="_blank"><img src="assets/img/game-hosting/Hyatt.jpg" alt="Client 2 Logo" style="max-width: 100px; height: auto;"></a>
                        </div>
                        <div class="client-logo" style="display: inline-block; margin-right: 20px;">
                            <a href="https://www.client2website.com" target="_blank"><img src="assets/img/game-hosting/Hyatt.jpg" alt="Client 2 Logo" style="max-width: 100px; height: auto;"></a>
                        </div>
                        <div class="client-logo" style="display: inline-block; margin-right: 20px;">
                            <a href="https://www.client2website.com" target="_blank"><img src="assets/img/game-hosting/Hyatt.jpg" alt="Client 2 Logo" style="max-width: 100px; height: auto;"></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section> -->




<!-- Among Our Clients Start -->
<section class="clients-section ptb-120 bg-white mb-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="gh-section-title text-center">
                    <h2 class="mb-3">Among Our Clients</h2>
                    <p>We are proud to work with these great partners.</p>
                </div>
            </div>
        </div>
        <div class="clients-wrapper mt-5">
            <div class="clients-list">
                <!-- Client Logos -->
                <div class="client-logo">
                    <a href="client1-link" target="_blank"><img src="assets/img/clients/Dnata.jpg" alt="Dnata"></a>
                </div>
                <div class="client-logo">
                    <a href="client2-link" target="_blank"><img src="assets/img/clients/fun beach.jpg" alt="Fun beach"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/Golden Tulip.jpg" alt="golden tulip"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/nabaki africa 2.jpg" alt="nabaki africa"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/Jad logo.webp" alt="jad hotel suplies"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/Fumba ports.jpg" alt="Fumba ports"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/emirates.png" alt="emirates"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/TigoZantel-logo.jpg" alt="tigo zantel"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/remax_omela.jpeg" alt="remax omela"></a>
                </div>
                <div class="client-logo">
                    <a href="client1-link" target="_blank"><img src="assets/img/clients/halotel.jpg" alt="halotel"></a>
                </div>
                <div class="client-logo">
                    <a href="client2-link" target="_blank"><img src="assets/img/clients/Hyatt.jpg" alt="park hyatt"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/Jafferji.jpg" alt="jafferji"></a>
                </div>
                
                <div class="client-logo">
                    <a href="client2-link" target="_blank"><img src="assets/img/clients/Vivid Dream.jpg" alt="vivid dream"></a>
                </div>,
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/Vigor.jpg" alt="vigor"></a>
                </div>
                <div class="client-logo">
                    <a href="client1-link" target="_blank"><img src="assets/img/clients/ZAA.jpg" alt="zanzibar Airport"></a>
                </div>
                <div class="client-logo">
                    <a href="client2-link" target="_blank"><img src="assets/img/clients/Zafayco.jpg" alt="zafayco"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/Zpra.jpg" alt="zpra"></a>
                </div>




                <div class="client-logo">
                    <a href="client1-link" target="_blank"><img src="assets/img/clients/Dnata.jpg" alt="Client 1 Logo"></a>
                </div>
                <div class="client-logo">
                    <a href="client2-link" target="_blank"><img src="assets/img/clients/fun beach.jpg" alt="Client 2 Logo"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/Golden Tulip.jpg" alt="Client 3 Logo"></a>
                </div>
                <div class="client-logo">
                    <a href="client1-link" target="_blank"><img src="assets/img/clients/halotel.jpg" alt="Client 4 Logo"></a>
                </div>
                <div class="client-logo">
                    <a href="client2-link" target="_blank"><img src="assets/img/clients/Hyatt.jpg" alt="Client 5 Logo"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/Jafferji.jpg" alt="Client 6 Logo"></a>
                </div>
                <div class="client-logo">
                    <a href="client1-link" target="_blank"><img src="assets/img/clients/Kena Beach.jpg" alt="Client 7 Logo"></a>
                </div>
                <div class="client-logo">
                    <a href="client2-link" target="_blank"><img src="assets/img/clients/Vivid Dream.jpg" alt="Client 8 Logo"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/Vigor.jpg" alt="Client 9 Logo"></a>
                </div>
                <div class="client-logo">
                    <a href="client1-link" target="_blank"><img src="assets/img/clients/ZAA.jpg" alt="Client 10 Logo"></a>
                </div>
                <div class="client-logo">
                    <a href="client2-link" target="_blank"><img src="assets/img/clients/Zafayco.jpg" alt="Client 11 Logo"></a>
                </div>
                <div class="client-logo">
                    <a href="client3-link" target="_blank"><img src="assets/img/clients/Zpra.jpg" alt="Client 12 Logo"></a>
                </div>
                <!-- Add more client logos as needed -->
            </div>
        </div>
    </div>
</section>

<!-- Among Our Clients End -->

<style>
.clients-wrapper {
    overflow: hidden;
}

.clients-list {
    display: flex;
    width: 400%; /* Make the container wide enough to fit all logos */
    animation: scroll 5s linear infinite; /* Infinite loop, adjust duration as needed */
}

.client-logo {
    padding: 0 10px; /* Adjust spacing between logos */
    flex-shrink: 0; /* Prevents logos from shrinking */
}

.client-logo img {
    width: 100px;
    height: 100px;
    object-fit: contain; /* Maintain aspect ratio */
}

@keyframes scroll {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(calc(-20%)); /* Adjust percentage based on number of logos (100% / 5 logos) */
    }
}


</style>












    <!--recent works start-->
    <!-- <section class="gh-server ptb-120 gh-bg">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="gh-section-title text-center">
                        <div class="section-title text-center">
                            <h2 class="mb-3">Among Our Clients</h2>
                            <p>We are proud of who we are, but we are nothing without our Customers <br> It
                                is always our pleasure doing business with you!.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="gh-game-server mt-5">
                <div class="gh-filter-top mb-40 d-flex align-items-center justify-content-center justify-content-lg-between flex-wrap">


                </div>
                <div class="row g-3 gh-filter-items">
                    <div class="col-xl-3 col-lg-4 col-md-6 star_wars ea_original">
                        <div class="gh-single-game position-relative overflow-hidden rounded-2">
                            <img src="assets/img/game-hosting/Hyatt.jpg" alt="not found" class="img-fluid rounded-2">

                            <div class="gh-single-game-content text-center position-absolute">
                                <h6 class="text-white mb-0">Hyatt</h6>
                                <span class="text-white d-block">CCTV Camera </span>
                                <a href="#" class="template-btn gh-primary-btn mt-3 btn-small">Explore More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 es_sports sims ea_original">
                        <div class="gh-single-game position-relative overflow-hidden rounded-2">
                            <img src="assets/img/game-hosting/ZAA.jpg" alt="not found" class="img-fluid rounded-2">

                            <div class="gh-single-game-content text-center position-absolute">
                                <h6 class="text-white mb-0">Zanzibar Airport Authority</h6>
                                <span class="text-white d-block">Access Control</span>
                                <a href="#" class="template-btn gh-primary-btn mt-3 btn-small">Explore More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 star_wars ea_original">
                        <div class="gh-single-game position-relative overflow-hidden rounded-2">
                            <img src="assets/img/game-hosting/Zafayco.jpg" alt="not found" class="img-fluid rounded-2">

                            <div class="gh-single-game-content text-center position-absolute">
                                <h6 class="text-white mb-0">ZAFAYCO</h6>
                                <span class="text-white d-block">Website & Printing</span>
                                <a href="#" class="template-btn gh-primary-btn mt-3 btn-small">Explore More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 es_sports">
                        <div class="gh-single-game position-relative overflow-hidden rounded-2">
                            <img src="assets/img/game-hosting/fun beach.jpg" alt="not found" class="img-fluid rounded-2">

                            <div class="gh-single-game-content text-center position-absolute">
                                <h6 class="text-white mb-0">Fun Beach Hotel</h6>
                                <span class="text-white d-block">CCTV Camera Installation</span>
                                <a href="#" class="template-btn gh-primary-btn mt-3 btn-small">Explore More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 star_wars ea_original">
                        <div class="gh-single-game position-relative overflow-hidden rounded-2">
                            <img src="assets/img/game-hosting/Golden Tulip.jpg" alt="not found" class="img-fluid rounded-2">

                            <div class="gh-single-game-content text-center position-absolute">
                                <h6 class="text-white mb-0">Golden Tulip</h6>
                                <span class="text-white d-block">CCTV Camera</span>
                                <a href="#" class="template-btn gh-primary-btn mt-3 btn-small">Explore More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 es_sports ea_original">
                        <div class="gh-single-game position-relative overflow-hidden rounded-2">
                            <img src="assets/img/game-hosting/Kena Beach.jpg" alt="not found" class="img-fluid rounded-2">

                            <div class="gh-single-game-content text-center position-absolute">
                                <h6 class="text-white mb-0">Kena Beach Hotel</h6>
                                <span class="text-white d-block">CCTV Camera</span>
                                <a href="#" class="template-btn gh-primary-btn mt-3 btn-small">Explore More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 sims">
                        <div class="gh-single-game position-relative overflow-hidden rounded-2">
                            <img src="assets/img/game-hosting/Vivid Dream.jpg" alt="not found" class="img-fluid rounded-2">

                            <div class="gh-single-game-content text-center position-absolute">
                                <h6 class="text-white mb-0">Vivid Dream School</h6>
                                <span class="text-white d-block">Graphic Design & Printing</span>
                                <a href="#" class="template-btn gh-primary-btn mt-3 btn-small">Explore More</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-md-6 sims es_sports">
                        <div class="gh-single-game position-relative overflow-hidden rounded-2">
                            <img src="assets/img/game-hosting/Vogor.jpg" alt="not found" class="img-fluid rounded-2">

                            <div class="gh-single-game-content text-center position-absolute">
                                <h6 class="text-white mb-0">Vigor</h6>
                                <span class="text-white d-block">ict services</span>
                                <a href="#" class="template-btn gh-primary-btn mt-3 btn-small">Explore More</a>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </section> -->
    <!--recent works end-->


<!-- Our Services -->
<section id="our-services" class="section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="gh-section-title text-center">
                    <div class="section-title text-center">
                        <h2 class="mb-3">Our Services</h2>
                        <p>From web design and digital marketing to network setup and hardware maintenance,
                            we deliver comprehensive IT solutions tailored to enhance your business efficiency and security.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

        <div class="row p-5">

            <!-- Service Item 1: Website Design -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="web-design-hosting-and-digital-marketing.php" class="service-link">
                <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                <div class="row">
                            <div class="col-md-4 d-flex justify-content-center">
                                <img src="assets/img/services/web desing.png" width="150px" alt="Website Design" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Website Design</h3>
                                <p>Custom web design tailored to your business needs, ensuring a professional online presence that reflects your brand.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Service Item 2: Domain & Hosting -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="web-design-hosting-and-digital-marketing.php#hosting" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/services/domain.png" width="150px" alt="Domain & Hosting" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Domain & Hosting</h3>
                                <p> Reliable web hosting solutions for your business, providing secure and fast access to your website.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Service Item 3: Digital Marketing -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="web-design-hosting-and-digital-marketing.php#digital-marketing" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/services/seo.png" width="150px" alt="Digital Marketing" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Digital Marketing</h3>
                                <p>Effective digital marketing strategies to boost your online presence and attract more customers.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

           
            <!-- Service Item 5: Digital Printing -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="graphic-design-and-publish-printing.php#digital-printing" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/services/digital printing.png" width="150px" alt="Digital Printing" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Digital Printing</h3>
                                <p>High-quality digital printing services for various needs, ensuring sharp and vibrant prints.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Service Item 6: Large Format Printing -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="graphic-design-and-publish-printing.php#large-format" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/services/large printing.png" width="150px" alt="Large Format Printing" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Large Format Printing</h3>
                                <p>Print large-scale graphics with precision and quality, ideal for banners, posters, and signs.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Service Item 7: CCTV Camera -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="networking-and-digital-security.php#security" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/services/cctv camera.png" width="150px" alt="Digital Security Solutions" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>CCTV Camera</h3>
                                <p>Secure your business with advanced CCTV Camera solutions, providing constant surveillance.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

             <!-- Service Item 7: Access Control -->
         <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="networking-and-digital-security.php#accesscontrol" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/affiliate/access control.png" width="150px" alt="Digital Security Solutions" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Access Control</h3>
                                <p>Enhance security with advanced access control systems, including keycards and biometric scanners.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <!-- Service Item 8: Networking -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="networking-and-digital-security.php#networking&ipphone" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/services/networking.png" width="150px" alt="Networking" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Networking</h3>
                                <p>Optimize your network infrastructure for seamless operations.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

             <!-- Service Item 9: Firewall Implementation -->
         <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="networking-and-digital-security.php#networking&ipphone" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/affiliate/ipphone.png" width="150px" alt="ip phone" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>IP Phone & Video Confrencing</h3>
                                <p>Modernize your communication with IP phones and video conferencing solutions for clear and efficient meetings.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


             <!-- Service Item 8: Networking -->
         <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="networking-and-digital-security.php#fence" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/affiliate/electric-fence.jpg" width="150px" alt="electric fence" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Electric Fence</h3>
                                <p>Secure your perimeter with high-voltage electric fences designed for maximum protection.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

             <!-- Service Item 8: Networking -->
         <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="networking-and-digital-security.php#gate" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/affiliate/gate-motor.jpg" width="150px" alt="gate motors" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Gate Motors</h3>
                                <p>Automate your gates for easy and secure access with durable gate motors.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        <!-- Service Item 8: Networking -->
        <div class="col-lg-4 col-md-6 mb-4">
                <a href="networking-and-digital-security.php#gate" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/affiliate/alarm.jpg" width="150px" alt="alarm" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Intruder alarms</h3>
                                <p>Protect your property with advanced intruder alarm systems that detect and deter unauthorized access.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>


            <!-- Service Item 8: Networking -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="ict-hardware-supply-and-maintanance.php#pos" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/affiliate/pos machine.png" width="150px" alt="pos" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Computer & POS</h3>
                                <p>Enhance your business operations with reliable computer systems and point-of-sale solutions.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>



            <!-- Service Item 12: Maintenance and Support -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="ict-hardware-supply-and-maintanance.php" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/affiliate/consultancy.png" width="150px" alt="consultancy" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>ICT Consultancy</h3>
                                <p>Quality hardware supply tailored to your IT needs, ensuring uninterrupted power supply for critical systems.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Service Item 10: Hardware Supply -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="ict-hardware-supply-and-maintanance.php" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/affiliate/power-backup.png" width="150px" alt="power backup" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Power Backup Solution</h3>
                                <p>Quality hardware supply tailored to your IT needs, ensuring uninterrupted power supply for critical systems.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Service Item 10: Hardware Supply -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="ict-hardware-supply-and-maintanance.php" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/affiliate/data rcovery.png" width="150px" alt="data storage" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>Data Storage</h3>
                                <p>Secure and scalable data storage solutions to protect and manage your valuable information.</p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            
              <!-- Service Item 10: Hardware Supply | GPS -->
        <div class="col-lg-4 col-md-6 col-sm-12 mb-4">
                <a href="networking-and-digital-security.php#networking&gps" class="service-link">
        <div class="bg-white shadow rounded service-item" style="height: 250px; display: flex; flex-direction: column; justify-content: center; align-items: center; overflow: hidden;">
                        <div class="row">
                        <div class="col-md-4 d-flex justify-content-center">
                        <img src="assets/img/affiliate/GPS.png" width="150px" alt="data storage" class="img-fluid">
                            </div>
                            <div class="col-md-8">
                                <h3>GPS Trackers</h3>
                                <p> Track your vehicle’s location in real-time with our advanced GPS solutions. Enjoy features like remote engine cut-off, geofencing, and more for full protection and control. </p>
                            </div>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    <!-- </div> -->
</section>

<style>
    .service-item {
        padding: 20px;
    }

    .service-item img {
        max-width: 100%;
        height: auto;
    }

    .service-link {
        text-decoration: none; /* Remove underline from links */
        color: inherit; /* Inherit text color */
    }
</style>







    <!-- Google Review -->

    <script src="https://static.elfsight.com/platform/platform.js" data-use-service-core defer></script>
    <div class="elfsight-app-1375855b-96ce-4dc7-b9b6-926a14b11408" data-elfsight-app-lazy></div>

    <!--feedback section start-->
    <!-- <section class="feedback-section mt-5 bg-primary-gradient pt-120 position-relative overflow-hidden">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="feedbackt-top text-center">
                        <h2>Testimonials</h2>
                        <p>We truly cannot say enough about the level of customer service that hasnet Ict Solution
                            provides <br>
                            it's always a great experience, with very personable and helpful support.</p>
                    </div>
                </div>
            </div>
            <div class="feedback-wrapper mt-30">
                <div class="row g-4 feedback-massonry">


                <script src="https://widget.trustmary.com/6a0RjnQpJ"></script>

                    <div class="col-lg-4 col-md-6">
                        <div class="feedback-single bg-white rounded-2 position-relative">
                            <img src="assets/img/shapes/quote-icon.svg" alt="quote" class="position-absolute quote-icon">
                            <div class="feedback-top d-flex align-items-center">
                                <img src="assets/img/client-1.png" alt="client" class="img-fluid rounded-circle flex-shrink-0">
                                <div class="feedback-top-content ms-3">
                                    <h5>Edi Moyo</h5>
                                    <img src="assets/img/two-star.svg" alt="3 star" class="img-fluid">
                                </div>
                            </div>
                            <p class="feedback-comment mt-30 mb-30">"If I was supposed to give you a test for customer
                                care competence, you have won it already!."</p>
                            <img src="assets/img/brands/stamps.png" alt="not found" class="img-fluid">
                        </div>
                    </div>



                    <div class="col-lg-4 col-md-6">
                        <div class="feedback-single bg-white rounded-2 position-relative">
                            <img src="assets/img/shapes/quote-icon.svg" alt="quote" class="position-absolute quote-icon">
                            <div class="feedback-top d-flex align-items-center">
                                <img src="assets/img/client-1.png" alt="client" class="img-fluid rounded-circle flex-shrink-0">
                                <div class="feedback-top-content ms-3">
                                    <h5>Olve Paul</h5>
                                    <img src="assets/img/two-star.svg" alt="3 star" class="img-fluid">
                                </div>
                            </div>
                            <p class="feedback-comment mt-30 mb-30">"Professionalism, Quality, Responsiveness, Value
                                HASNET ICT SOLUTION are very reliable and absolutely supportive.
                                I worked with them for a while and I can assure anyone who want their service to look for them.

                                They are very accessible all the time, their response, quality of service, reasonable prices, delivery of spares they are top."</p>
                            <img src="assets/img/brands/stamps.png" alt="not found" class="img-fluid">
                        </div>
                    </div>



                    <div class="col-lg-4 col-md-6">
                        <div class="feedback-single bg-white rounded-2 position-relative">
                            <img src="assets/img/shapes/quote-icon.svg" alt="quote" class="position-absolute quote-icon">
                            <div class="feedback-top d-flex align-items-center">
                                <img src="assets/img/client-1.png" alt="client" class="img-fluid rounded-circle flex-shrink-0">
                                <div class="feedback-top-content ms-3">
                                    <h5>Abdi Soud</h5>
                                    <img src="assets/img/two-star.svg" alt="3 star" class="img-fluid">
                                </div>
                            </div>
                            <p class="feedback-comment mt-30 mb-30">"If I were to choose the future technology Company
                                in Zanzibar, you guys have transformed the course!"</p>
                            <img src="assets/img/brands/stamps.png" alt="not found" class="img-fluid">
                        </div>
                    </div>

                    
                    

                  
                    
                   
                    
                   
                    
                </div>
            </div>
        </div>

    </section> -->
    <!--feedback section end-->


    <!-- Footer -->
    <?php
    require_once("./footer.php");
?>

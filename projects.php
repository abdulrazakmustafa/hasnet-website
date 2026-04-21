<?php 
require_once("./links.php");
?>

<!--title-->
<title>Our Projects : Hasnet ICT Solution</title>
</head>

<body>

    <!--dark light switcher-->
    <button class="dark-light-switcher" id="theme-switch">
        <span class="light-sun"><i class="fa-solid fa-sun"></i></span>
        <span class="dark-moon"><i class="fa-solid fa-moon"></i></span>
    </button>

    <!--body overlay -->
    <div class="body-overlay"></div>

    <!--scrolltop button -->
    <button class="scrolltop-btn"><i class="fa-solid fa-angle-up"></i></button>

   <!-- Header -->
   <?php
    require_once("./header.php");
    require_once("./modal.php");
   ?>
   <!-- Header ends here -->


    <!-- HERO SECTION -->
    <section class="gh-hero-section overflow-hidden" data-background="assets/img/game-hosting/hero-bg-1.jpg" style="height:60vh;">
        <div class="container">
            <div class="row mt-5">
                <div class="col-lg-6 align-self-center">
                    <div class="gh-hero-left mt-5">
                        <h1 class="display-4 fw-bold gh-heading mb-4">Our Projects</h1>
                        <p class="gh-heading">
                            Explore some of our completed works across different industries including CCTV, networking, and web development.
                        </p>

                        <div class="gh-hero-btns mt-4">
                            <button type="button" class="btn btn-primary btn-lg" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                <b>Get Quote</b>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- HERO END -->


    <!-- PROJECT FILTER (OPTIONAL) -->
    <section class="pt-60 pb-30">
        <div class="container text-center">
            <div class="section-title">
                <h2>Our Work Showcase</h2>
                <p>Browse through some of our recent projects delivered with excellence.</p>
            </div>

            <!-- Filter Buttons -->
            <div class="mt-4">
                <button class="btn btn-outline-primary m-1 filter-btn active" data-filter="all">All</button>
                <button class="btn btn-outline-primary m-1 filter-btn" data-filter="cctv">CCTV</button>
                <button class="btn btn-outline-primary m-1 filter-btn" data-filter="network">Networking</button>
                <button class="btn btn-outline-primary m-1 filter-btn" data-filter="web">Websites</button>
            </div>
        </div>
    </section>


    <!-- PROJECT GRID -->
    <section class="pb-120">
        <div class="container">
            <div class="row g-4" id="project-container">

                <!-- PROJECT 1 -->
                <div class="col-lg-4 col-md-6 project-item cctv">
                    <div class="card shadow-sm h-100 project-card">
                        <img src="assets/img/services/installation.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">CCTV Installation – Hotel Project</h5>
                            <p class="card-text">Complete surveillance system setup ensuring security across all hotel areas.</p>
                        </div>
                    </div>
                </div>

                <!-- PROJECT 2 -->
                <div class="col-lg-4 col-md-6 project-item network">
                    <div class="card shadow-sm h-100 project-card">
                        <img src="assets/img/home-slider/bg-05.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">Network Setup – Office Building</h5>
                            <p class="card-text">Structured cabling and network deployment for seamless connectivity.</p>
                        </div>
                    </div>
                </div>

                <!-- PROJECT 3 -->
                <div class="col-lg-4 col-md-6 project-item web">
                    <div class="card shadow-sm h-100 project-card">
                        <img src="assets/img/services/installation.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">Website Development – Company Profile</h5>
                            <p class="card-text">Modern responsive website tailored for corporate branding.</p>
                        </div>
                    </div>
                </div>

                <!-- PROJECT 4 -->
                <div class="col-lg-4 col-md-6 project-item cctv">
                    <div class="card shadow-sm h-100 project-card">
                        <img src="assets/img/home-slider/bg-05.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">CCTV Installation – School Campus</h5>
                            <p class="card-text">Advanced monitoring system covering classrooms and surroundings.</p>
                        </div>
                    </div>
                </div>

                <!-- PROJECT 5 -->
                <div class="col-lg-4 col-md-6 project-item network">
                    <div class="card shadow-sm h-100 project-card">
                        <img src="assets/img/services/installation.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">Network Upgrade – Government Office</h5>
                            <p class="card-text">Improved network infrastructure for high-speed operations.</p>
                        </div>
                    </div>
                </div>

                <!-- PROJECT 6 -->
                <div class="col-lg-4 col-md-6 project-item web">
                    <div class="card shadow-sm h-100 project-card">
                        <img src="assets/img/home-slider/bg-05.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">E-commerce Website – Online Store</h5>
                            <p class="card-text">Full-featured online shopping platform with payment integration.</p>
                        </div>
                    </div>
                </div>

                <!-- PROJECT 7 -->
                <div class="col-lg-4 col-md-6 project-item cctv">
                    <div class="card shadow-sm h-100 project-card">
                        <img src="assets/img/services/installation.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">Security System – Warehouse</h5>
                            <p class="card-text">High-definition cameras installed for 24/7 monitoring.</p>
                        </div>
                    </div>
                </div>

                <!-- PROJECT 8 -->
                <div class="col-lg-4 col-md-6 project-item web">
                    <div class="card shadow-sm h-100 project-card">
                        <img src="assets/img/home-slider/bg-05.jpg" class="card-img-top" alt="">
                        <div class="card-body">
                            <h5 class="card-title">Portfolio Website – Creative Agency</h5>
                            <p class="card-text">Elegant portfolio showcasing services and past work.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>


    <!-- FOOTER -->
    <?php require_once("./footer.php"); ?>


    <!-- FILTER SCRIPT -->
    <script>
        const filterButtons = document.querySelectorAll(".filter-btn");
        const projects = document.querySelectorAll(".project-item");

        filterButtons.forEach(btn => {
            btn.addEventListener("click", () => {
                filterButtons.forEach(b => b.classList.remove("active"));
                btn.classList.add("active");

                const filter = btn.getAttribute("data-filter");

                projects.forEach(project => {
                    if (filter === "all" || project.classList.contains(filter)) {
                        project.style.display = "block";
                    } else {
                        project.style.display = "none";
                    }
                });
            });
        });
    </script>


    <!-- OPTIONAL HOVER EFFECT -->
    <style>
        .project-card {
            transition: 0.3s ease;
        }

        .project-card:hover {
            transform: translateY(-8px);
        }
    </style>

</body>
</html>
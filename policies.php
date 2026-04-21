
<?php 
require_once("./links.php");
?>

<!--title-->
<title>Policies : Hasnet ICT Solution</title>
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
   

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HASNET Policies</title>
    <!-- Bootstrap CSS (required for collapsible sections) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <!-- Paste the policies section here -->
<!-- HASNET ICT SOLUTION Policies Page -->
<section class="policies-section container mt-5">

    <h2 class="text-center mb-4">Our Policies</h2>

    <!-- Privacy Policy -->
    <div class="policy-item mb-3">
        <button class="btn btn-outline-primary w-100 text-left" type="button" data-bs-toggle="collapse" data-bs-target="#privacyPolicy" aria-expanded="false" aria-controls="privacyPolicy">
            Privacy Policy
        </button>
        <div class="collapse mt-2" id="privacyPolicy">
            <div class="card card-body">
                At HASNET ICT SOLUTION, we respect your privacy. We collect personal information such as name, email, phone number, and device details only to provide and improve our services. Your data will never be sold or shared with third parties without your consent. We use cookies and analytics to improve user experience, which you can manage through your browser settings.
            </div>
        </div>
    </div>

    <!-- Terms of Service -->
    <div class="policy-item mb-3">
        <button class="btn btn-outline-primary w-100 text-left" type="button" data-bs-toggle="collapse" data-bs-target="#termsService" aria-expanded="false" aria-controls="termsService">
            Terms of Service
        </button>
        <div class="collapse mt-2" id="termsService">
            <div class="card card-body">
                By using our website or services, you agree to our terms and conditions:
                <ul>
                    <li>All content and materials on this website are for informational purposes only.</li>
                    <li>Users are responsible for providing accurate information during registration or purchase.</li>
                    <li>HASNET ICT SOLUTION reserves the right to suspend or terminate accounts for violations or misuse.</li>
                    <li>We may update these terms from time to time; continued use implies acceptance of the updated terms.</li>
                </ul>
            </div>
        </div>
    </div>

   
    <!-- Shipping / Delivery Policy -->
    <div class="policy-item mb-3">
        <button class="btn btn-outline-primary w-100 text-left" type="button" data-bs-toggle="collapse" data-bs-target="#shippingPolicy" aria-expanded="false" aria-controls="shippingPolicy">
            Shipping / Delivery Policy
        </button>
        <div class="collapse mt-2" id="shippingPolicy">
            <div class="card card-body">
                We deliver products within the agreed timeframe, depending on your location. Delivery costs and options will be clearly stated at the time of purchase. HASNET ICT SOLUTION is not responsible for delays caused by third-party courier services.
            </div>
        </div>
    </div>

    <!-- Payment Policy -->
    <div class="policy-item mb-3">
        <button class="btn btn-outline-primary w-100 text-left" type="button" data-bs-toggle="collapse" data-bs-target="#paymentPolicy" aria-expanded="false" aria-controls="paymentPolicy">
            Payment Policy
        </button>
        <div class="collapse mt-2" id="paymentPolicy">
            <div class="card card-body">
                We accept payments via bank transfer, mobile money, and other listed methods at checkout. All payments are secured; HASNET ICT SOLUTION does not store full payment card details. Subscription fees are billed according to your chosen plan and are non-refundable once the service has commenced, except as noted in our Refund Policy.
            </div>
        </div>
    </div>

    <!-- Warranty / Service Policy -->
    <div class="policy-item mb-3">
        <button class="btn btn-outline-primary w-100 text-left" type="button" data-bs-toggle="collapse" data-bs-target="#warrantyPolicy" aria-expanded="false" aria-controls="warrantyPolicy">
            Warranty / Service Policy
        </button>
        <div class="collapse mt-2" id="warrantyPolicy">
            <div class="card card-body">
                Somne devices purchased from HASNET ICT SOLUTION come with a manufacturer or service warranty as indicated at the time of purchase. Warranty covers defects in materials or workmanship and does not cover physical damage or misuse. For service requests, contact our support team with your proof of purchase.
            </div>
        </div>
    </div>
   
    <!-- Cookie Policy -->
    <div class="policy-item mb-3">
        <button class="btn btn-outline-primary w-100 text-left" type="button" data-bs-toggle="collapse" data-bs-target="#cookiePolicy" aria-expanded="false" aria-controls="cookiePolicy">
            Cookie Policy
        </button>
        <div class="collapse mt-2" id="cookiePolicy">
            <div class="card card-body">
                Our website uses cookies to enhance user experience, track website performance, and display relevant content. By using our website, you consent to the use of cookies. You can disable cookies via your browser settings, but some features may not function properly.
            </div>
        </div>
    </div>

    <!-- Disclaimer -->
    <div class="policy-item mb-3">
        <button class="btn btn-outline-primary w-100 text-left" type="button" data-bs-toggle="collapse" data-bs-target="#disclaimerPolicy" aria-expanded="false" aria-controls="disclaimerPolicy">
            Disclaimer
        </button>
        <div class="collapse mt-2" id="disclaimerPolicy">
            <div class="card card-body">
                HASNET ICT SOLUTION strives for accuracy in all content and services. However:
                <ul>
                    <li>We are not liable for losses or damages arising from website use or service interruptions.</li>
                    <li>External links are provided for convenience; we are not responsible for third-party websites.</li>
                </ul>
            </div>
        </div>
    </div>

</section>


    <!-- Bootstrap JS (required for collapse functionality) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
   



    
 <!-- Footer -->
 <?php
    require_once("./footer.php");
    ?>
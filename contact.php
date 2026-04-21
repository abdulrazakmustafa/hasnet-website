<!DOCTYPE html>
<html lang="en">
<head>
    <?php require_once("./links.php"); ?>
    <title>Contact : Hasnet ICT Solution</title>

    <!-- reCAPTCHA v2 -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>

    <!-- AJAX Submission -->
    <!--<script>
        document.addEventListener('DOMContentLoaded', function () {
            document.getElementById('contactForm').addEventListener('submit', function (event) {
                event.preventDefault();
                var formData = new FormData(this);

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'send_email.php', true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === XMLHttpRequest.DONE) {
                        if (xhr.status === 200) {
                            alert(xhr.responseText);
                            document.getElementById('contactForm').reset();
                            grecaptcha.reset();
                        } else {
                            alert('Error: ' + xhr.responseText);
                        }
                    }
                };
                xhr.send(formData);
            });
        });
    </script>-->
</head>
<body>

<?php require_once("./header.php"); ?>
<?php require_once("./modal.php"); ?>


  <!-- Breadcrumb area start -->
    <section class="hero-style-1 contact-hero bg-white">
    <div class="hero-area overflow-hidden position-relative zindex-1 bg-primary-gradient pt-120">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="hero1-content-wrap">
                        <h1 class="display-font mt-4">Contact Us,<br> We’re Here to Help</h1>
                        <p class="mt-4">Our dedicated support team is always ready to assist you. Reach out anytime for quick, reliable, and friendly customer service tailored to your needs.</p>
                        <a href="https://wa.me/+255777019901" class="template-btn secondary-btn rounded-pill mt-4"><span class="me-2"><i class="fa-solid fa-comments"></i></span>Live Chat</a>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="hero-right text-center text-lg-end"> <!-- Added 'text-center text-lg-end' classes -->
                        <img src="assets/img/0001.svg" alt="hero image">
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="hm-contact-info promo-area position-relative">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="hm-ct-info-wrapper rounded text-center"> <!-- Added 'text-center' class -->
                        <span class="icon-wrapper rounded-circle d-inline-flex align-items-center justify-content-center">
                          <i class="fa-solid fa-phone"></i>
                      </span>
                        <div class="info-content mt-4">
                            <h4>Call Us</h4>
                            <a href="tel:+255777019901">+255 777 019 901</a></br>
                            <a href="tel:+255718019901">+255 718 019 901</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="hm-ct-info-wrapper rounded text-center"> <!-- Added 'text-center' class -->
                        <span class="icon-wrapper rounded-circle d-inline-flex align-items-center justify-content-center">
                          <i class="fa-solid fa-at"></i>
                      </span>
                        <div class="info-content mt-4">
                            <h4>Email Us</h4>
                            <p><strong>General Inquiries:</strong> 
                                <a href="mailto:info@hasnet.co.tz">info@hasnet.co.tz</a>
                            </p>
                            <p><strong>Sales:</strong> 
                                <a href="mailto:sales@hasnet.co.tz">sales@hasnet.co.tz</a>
                            </p>
                            <p><strong>Support:</strong> 
                                <a href="mailto:support@hasnet.co.tz">support@hasnet.co.tz</a>
                            </p>
                        </div>

                    </div>
                </div>
                <div class="col-lg-4 col-md-6 col-sm-10">
                    <div class="hm-ct-info-wrapper rounded text-center"> <!-- Added 'text-center' class -->
                        <span class="icon-wrapper rounded-circle d-inline-flex align-items-center justify-content-center">
                          <i class="fa-solid fa-house"></i>
                      </span>
                        <div class="info-content mt-4">
                            <h4>Our Locations</h4>
                            <div class="mt-2">
                              <strong>Taveta Branch:</strong><br>
                              <span>Taveta, Fuoni Road, Zanzibar - TZ</span>
                            </div>
                            
                            <div class="mt-3">
                              <strong>Mwembetanga Branch:</strong><br>
                              <span>Mwembetanga, Mtendeni Road, Zanzibar - TZ</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

    <!-- Breadcrumb area end -->
    

<!-- Contact Section-->
<!--<section class="hm-contact-area pt-60 pb-120 bg-white">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-5 text-center">
                <h2>Send Us Message!</h2>
            </div>
        </div>
        <div class="row justify-content-center mt-5">
            <div class="col-lg-8">
                <form action="send_email.php" method="POST" id="contactForm" class="contact-us-form">
                    <input type="hidden" name="form_type" value="contact_form">
                    <input type="text" name="website" style="display:none;"> <!-- Honeypot -->

                    <!--<div class="row g-4">
                        <div class="col-md-6">
                            <label for="name">Name</label>
                            <input type="text" name="name" id="name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="email">Email</label>
                            <input type="email" name="email" id="email" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="phone">Phone</label>
                            <input type="text" name="phone" id="phone" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label for="subject">Subject</label>
                            <input type="text" name="subject" id="subject" class="form-control" required>
                        </div>
                        <div class="col-md-12">
                            <label for="message">Message</label>
                            <textarea name="message" id="message" class="form-control" rows="5" required></textarea>
                        </div>
                        <div class="col-md-12 text-center">
                            <div class="g-recaptcha mb-3" data-sitekey="6LctQoErAAAAADObMskiBehEEH0JuWs72jrIf8df"></div>
                            <button type="submit" class="template-btn primary-btn border-0 rounded-pill">
                                Send Message <i class="fa-solid fa-chevron-right ms-2"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Google Maps Embed Section -->
<section class="google-maps-section py-5">
    <div class="container-fluid">
        <div class="row justify-content-center">

            <!-- Taveta Office -->
            <div class="col-md-6 text-center mb-4">
                <h5 class="mb-3">Taveta Office</h5>
                <iframe 
                    src="https://www.google.com/maps?q=-6.1853404,39.2370123&hl=en&z=17&output=embed"
                    width="100%" 
                    height="400" 
                    style="border:0; border-radius:10px;" 
                    allowfullscreen="" 
                    loading="lazy">
                </iframe>
                <div class="mt-3">
                    <a href="https://www.google.com/maps?q=-6.1853404,39.2370123" 
                       target="_blank" 
                       class="btn btn-primary btn-sm">
                        View on Google Maps
                    </a>
                </div>
            </div>

            <!-- Mwembetanga Office -->
            <div class="col-md-6 text-center mb-4">
                <h5 class="mb-3">Mwembetanga Office</h5>
                <iframe 
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3966.7622777282745!2d39.19502887700657!3d-6.162583460401252!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x185cd1bda6c27b75%3A0xda238df8b6b488af!2sHASNET%20ICT%20SOLUTION!5e0!3m2!1sen!2stz!4v1776081889818!5m2!1sen!2stz"
                    width="100%" 
                    height="400" 
                    style="border:0; border-radius:10px;" 
                    allowfullscreen="" 
                    loading="lazy" 
                    referrerpolicy="no-referrer-when-downgrade">
                </iframe>
                <div class="mt-3">
                    <a href="https://www.google.com/maps/place/HASNET+ICT+SOLUTION" 
                       target="_blank" 
                       class="btn btn-primary btn-sm">
                        View on Google Maps
                    </a>
                </div>
            </div>

        </div>
    </div>
</section>
<!-- End Google Maps Embed Section -->


<?php require_once("./footer.php"); ?>
</body>
</html>
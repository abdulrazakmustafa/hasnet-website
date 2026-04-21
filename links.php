<?php

if (isset($_POST['submit'])) {
    $name = $_POST['name'];
    $location = $_POST['location'];
    $email = $_POST['email'];
    $phone = $_POST['phoneNumber'];
    $serviceOption = $_POST['serviceOption'];
    $msg = $_POST['message'];

    // Send acknowledgment email to the user
    /*
    $acknowledgmentSubject = 'Thank you for your inquiry';
    $acknowledgmentMessage = "Dear $name,\n\nThank you for contacting us. We have received your message and will get back to you as soon as possible.\n\nBest regards,\nHasnet ICT Solution";

    $acknowledgmentHeaders = "From: Hasnet ICT Solution <info@hasnet.co.tz>\r\n";
    $acknowledgmentHeaders .= "Content-type: text/plain; charset=UTF-8\r\n";

    mail($email, $acknowledgmentSubject, $acknowledgmentMessage, $acknowledgmentHeaders);
    */

    // Process the main request and send the email to your inbox
    $to = 'info@hasnet.co.tz';
    $subject = 'Website Message';

    // Create an HTML-formatted email message
    $message = "<html><body>";
    $message .= "<p><strong>Website Message received:</strong></p>";
    $message .= "<p><strong>Name:</strong> $name</p>";
    $message .= "<p><strong>Location:</strong> $location</p>";
    $message .= "<p><strong>Email:</strong> $email</p>";
    $message .= "<p><strong>Phone Number:</strong> $phone</p>";
    $message .= "<p><strong>Service Option:</strong> $serviceOption</p>";
    $message .= "<p><strong>Message:</strong></p>";
    $message .= "<p><strong><em>$msg</em></strong></p>";
    $message .= "</body></html>";

    $headers = "From: $email\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "Content-type: text/html; charset=UTF-8\r\n";

    /*
    if (mail($to, $subject, $message, $headers)) {
        echo "<script>alert('Sent Successfully');</script>";
    } else {
        echo "<script>alert('Sent Unsuccessfully');</script>";
    }
    */

    // Optional: Show a confirmation without sending email
    echo "<script>alert('Form submitted successfully (emails disabled)');</script>";
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <!--required meta tags-->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="We focus on Website, Databases, Domain, Hosting. Networking, IP Phones, CCTV Cameras, Access Control & Time Attendance. Graphic Design & Printing. Computer Accessories, Power and Data Backup.">

<meta name="keywords" content="Databases, Domain, Hosting. Networking, IP Phones, CCTV Cameras, Access Control & Time Attendance. Graphic Design & Printing. Computer Accessories, Power and Data Backup.">

   
    <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">
   <!-- Online Bootstrap Scripts-->


    <!--twitter og-->
    <meta name="twitter:site" content="@themetags" />
    <meta name="twitter:creator" content="@themetags" />
    <meta name="twitter:card" content="summary_large_image" />
    <meta name="twitter:title" content="Hasnet ICT Solution" />
    <meta name="twitter:description"
        content="Hasnet ICT Solution"/>
    <meta name="twitter:image" content="#" />

    <!--facebook og-->
    <meta property="og:url" content="#" />
    <meta name="twitter:title" content="Hasnet ICT Solution" />
    <meta property="og:description"
    content="Hasnet ICT Solution"/>
    <meta property="og:image" content="#" />
    <meta property="og:image:secure_url" content="#" />
    <meta property="og:image:type" content="image/png" />
    <meta property="og:image:width" content="1200" />
    <meta property="og:image:height" content="600" />

    <!--meta-->
    <meta name="description"
    content="Hasnet ICT Solution"/>
    <meta name="author" content="Hasnet ICT Solution" />

    <!--favicon icon-->
    <link rel="icon" href="assets/img/favicon.png" type="image/png" sizes="16x16" />

    <!--google fonts-->
    <link
        href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;500;600;700&amp;family=Urbanist:wght@600;700&amp;display=swap"
        rel="stylesheet">

    <!--build:css-->
    <link rel="stylesheet" href="assets/css/main.css" />
    <!-- endbuild -->
    
    <!--custom css-->
    <link rel="stylesheet" href="assets/css/custom.css" />
    

    
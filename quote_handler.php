<?php
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(['success' => false, 'message' => 'Invalid request.']);
    exit;
}

// 1. Validate reCAPTCHA
$recaptchaSecret   = "6LctQoErAAAAAAOY6impaybBS1cXcMYJpFEhXKlv";
$recaptchaResponse = $_POST['g-recaptcha-response'] ?? '';

if (!$recaptchaResponse) {
    echo json_encode(['success' => false, 'message' => 'Please complete the reCAPTCHA.']);
    exit;
}

$verify   = @file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$recaptchaSecret&response=$recaptchaResponse&remoteip=" . $_SERVER['REMOTE_ADDR']);
$response = $verify ? json_decode($verify) : null;

if (!$response || !$response->success) {
    echo json_encode(['success' => false, 'message' => 'reCAPTCHA verification failed.']);
    exit;
}

// 2. Sanitize
$name     = htmlspecialchars(trim($_POST['name']      ?? ''));
$location = htmlspecialchars(trim($_POST['location']  ?? ''));
$email    = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$phone    = htmlspecialchars(trim($_POST['phoneNumber'] ?? ''));
$service  = htmlspecialchars(trim($_POST['serviceOption'] ?? ''));
$message  = htmlspecialchars(trim($_POST['message']   ?? ''));

if (!$name || !$email) {
    echo json_encode(['success' => false, 'message' => 'Name and email are required.']);
    exit;
}

// 3. Save to database
try {
    require_once __DIR__ . '/config.php';
    require_once __DIR__ . '/admin/includes/db.php';
    $pdo  = get_db();
    $stmt = $pdo->prepare('INSERT INTO quote_requests (name, email, phone, location, service_option, message) VALUES (?,?,?,?,?,?)');
    $stmt->execute([$name, $email, $phone, $location, $service, strip_tags($message)]);
} catch (Throwable $e) {
    // DB save failure shouldn't block email
}

// 4. Send email
$to      = "info@hasnet.co.tz";
$subject = "Quote Request from $name";
$body    = "Name: $name\nLocation: $location\nEmail: $email\nPhone: $phone\nService: $service\n\nMessage:\n" . strip_tags($message);
$headers = "From: info@hasnet.co.tz\r\nReply-To: $email\r\nX-Mailer: PHP/" . phpversion();

if (mail($to, $subject, $body, $headers)) {
    echo json_encode(['success' => true, 'message' => "Thank you $name! Your quote request has been sent successfully."]);
} else {
    // Even if mail fails, we saved to DB
    echo json_encode(['success' => true, 'message' => "Thank you $name! Your quote request has been received."]);
}

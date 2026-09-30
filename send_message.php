<?php
use PHPMailer\PHPMailer\PHPMailer;
require 'vendor/autoload.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // 1. Sanitize all incoming fields matching your exact HTML form names
    $firstname     = htmlspecialchars(strip_tags(trim($_POST['firstname'])));
    $lastname      = htmlspecialchars(strip_tags(trim($_POST['lastname'])));
    $phone         = htmlspecialchars(strip_tags(trim($_POST['phone'])));
    $email         = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message       = htmlspecialchars(strip_tags(trim($_POST['message'])));
    $timezone      = htmlspecialchars(strip_tags(trim($_POST['timezone'])));
    $contacttime   = htmlspecialchars(strip_tags(trim($_POST['bestcontacttime'])));
    $contactmethod = htmlspecialchars(strip_tags(trim($_POST['contactmethod'])));
    $org           = htmlspecialchars(strip_tags(trim($_POST['org-name'])));

    // Require at least a name and a phone number to send the text
    if (empty($firstname) || empty($phone)) {
        echo "Error: First Name and Phone Number are required.";
        exit;
    }

    $mail = new PHPMailer(true);

    try {
        // 2. Configure credentials
        $mail->isSMTP();
        $mail->Host       = '://gmail.com'; 
        $mail->SMTPAuth   = true;
        $mail->Username   = 'NovelApogee@gmail.com'; 
        $mail->Password   = 'lkqoyqaztbcbatyt'; // Cleaned spaces
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; 
        $mail->Port       = 465;

        // 3. Sender & T-Mobile Destination [1]
        $mail->setFrom('NovelApogee@gmail.com', 'Novel Apogee Site');
        $mail->addAddress('7027930106@tmomail.net'); 

        // Optional: Keep this line below active if you want a full copy in your Gmail inbox too!
        $mail->addCC('NovelApogee@gmail.com');

        // 4. Construct a concise Text Message body
        $mail->Subject = 'New Rocketry Lead';
        $mail->Body    = "Name: $firstname $lastname\n" .
                         "Org: $org\n" .
                         "Phone: $phone\n" .
                         "Method: $contactmethod ($contacttime / $timezone)\n" .
                         "Msg: " . substr($message, 0, 80); // Keeps SMS short

        // 5. Send and route
        $mail->send();
        header("Location: success.html");
        exit; 
        
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
} else {
    echo "Invalid request method.";
}
?>

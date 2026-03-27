<?php
// mailer.php
$file = 'answers.txt';
$to = 'webcosmic1@gmail.com';
$subject = 'User Session Log: 10 Minute Mark';
$headers = "From: system@your-docker-app.com";

// Check if file exists and has content
if (file_exists($file) && filesize($file) > 0) {
    $content = file_get_contents($file);
    
    // Send the mail
    if (mail($to, $subject, $content, $headers)) {
        // Clear the file so the next user/session starts fresh
        file_put_contents($file, "");
        echo "Success";
    } else {
        echo "Mail failed";
    }
} else {
    echo "Nothing to send";
}
?>

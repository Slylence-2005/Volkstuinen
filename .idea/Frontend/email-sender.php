<?php
// Process the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize the email address
    $email = trim($_POST["email"]);
    
    // Validate the email address format
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Invalid email address.";
    }

    // Proceed if no error
    if (!isset($error)) {
        // Generate a unique token (for example, for email verification)
        // In production, store this token with the user's data in a database!
        $token = bin2hex(random_bytes(16));  // 32-character hexadecimal token
        
        // Build the verification link (update the domain and path as needed)
        $verificationLink = "https://www.example.com/verify.php?token=" . $token;
        
        // Email subject and message content
        $subject = "Your Verification Link";
        $message = "Hello,\n\n"
                 . "Please click the link below to verify your email address:\n"
                 . $verificationLink . "\n\n"
                 . "If you did not request this, please ignore this email.\n\n"
                 . "Thank you!";
        
        // Set the sender email address (must be valid and authorized on your server)
        $from = "ilunga866@gmail.com";
        
        // Set email headers
        $headers  = "From: $from\r\n";
        $headers .= "Reply-To: $from\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // Send the email
        if (mail($email, $subject, $message, $headers)) {
            $success = "A verification link has been sent to <strong>" . htmlspecialchars($email) . "</strong>.";
        } else {
            $error = "Failed to send email. Please try again later.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Verification Link</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input { width: 100%; padding: 8px; }
        button { padding: 10px 20px; }
        .message { margin-bottom: 20px; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Send a Verification Link</h1>
    
    <!-- Display success or error messages -->
    <?php if (isset($error)) : ?>
        <p class="message error"><?php echo $error; ?></p>
    <?php endif; ?>
    <?php if (isset($success)) : ?>
        <p class="message success"><?php echo $success; ?></p>
    <?php endif; ?>

    <!-- Email input form -->
    <form method="POST" action="">
        <div class="form-group">
            <label for="email">Enter your email address:</label>
            <input type="email" id="email" name="email" placeholder="your-email@example.com" required>
        </div>
        <button type="submit">Send Verification Link</button>
    </form>
</body>
</html>

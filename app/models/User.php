<?php

/**
 * User class
 */
class User
{
    use Model;

    protected $table = 'users';

    protected $allowedColumns = [
        'names',
        'email',
        'password',
        'token',
        'active'
    ];

    public function validate($data)
    {
        $this->errors = [];

        // Validation rules
        if (empty($data['email'])) {
            $this->errors['email'] = "Email is required";
        } else if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $this->errors['email'] = "Email is not valid";
        }

        if (empty($data['password'])) {
            $this->errors['password'] = "Password is required";
        }

        if (empty($data['terms'])) {
            $this->errors['terms'] = "Please accept the terms and conditions";
        }

        if (empty($this->errors)) {
            return true;
        }

        return false;
    }

    // Method to check if an email exists
    public function emailExists($email)
    {
        $result = $this->where(['email' => $email]);

        // Check if any result was returned
        return !empty($result);
    }

    public function sendEmail($email, $token)
    {
        // Email subject
        $subject = "Account Activation Code";

        // Email body
        $message = "
        <html>
        <head>
            <title>Account Activation</title>
        </head>
        <body>
            <p>Dear User,</p>
            <p>Thank you for signing up. Here are your details:</p>
            <ul>
                <li><strong>Token:</strong> $token</li>
            </ul>
            <p>Please use the following link to activate your account:</p>
            <p><a href='http://yourdomain.com/activation?token=$token'>Activate Your Account</a></p>
            <p>Thank you!</p>
            <p>Best regards,<br>Your Company</p>
        </body>
        </html>
        ";

        // Email headers
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: no-reply@yourdomain.com" . "\r\n";

        // Send email
        return mail($email, $subject, $message, $headers);
    }
}

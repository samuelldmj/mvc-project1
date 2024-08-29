<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

class User
{
    use Model;

    protected $table = 'users';

    protected $allowedColumns = [
        'names',
        'email',
        'password',
        'activation_token',
        'remember_me_token',
        'active'
    ];

    public function validate($data)
    {
        $this->errors = [];

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

        return empty($this->errors);
    }

    public function emailExists($email)
    {
        return !empty($this->where(['email' => $email]));
    }

    public function sendEmail($email, $token)
    {
        $mail = new PHPMailer(true);

        try {
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'inthebush87@gmail.com';
            $mail->Password = 'yefdnfokidadhvkh';
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;

            $mail->setFrom('inthebush87@gmail.com', 'Maravel');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Account Activation Code';
            $mail->Body = "
                <html>
                <head><title>Account Activation</title></head>
                <body>
                    <p>Dear User,</p>
                    <p>Thank you for signing up. Here are your details:</p>
                    <ul><li><strong>Token:</strong> $token</li></ul>
                    <p>Please use the following link to activate your account:</p>
                    <p><a href='http://localhost/mvc-project1/public/activation?token=$token'>Activate Your Account</a></p>
                    <p>Thank you!</p>
                    <p>Best regards,<br>Maravel</p>
                </body>
                </html>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            return false;
        }
    }

    public function updateToken($email, $token, $tokenType = 'activation_token')
    {
        $this->update(['email' => $email], [$tokenType => $token]);
    }

    public function updatePassword($email, $password)
    {
        $this->update(['email' => $email], ['password' => $password]);
    }

    public function clearToken($email, $tokenType = 'activation_token')
    {
        $this->update(['email' => $email], [$tokenType => null]);
    }
}

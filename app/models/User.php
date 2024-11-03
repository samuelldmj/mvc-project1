<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../../vendor/autoload.php';

class User
{
    use Model;

    protected $table = 'users';
    protected $allowedColumns = ['full_name', 'email', 'password', 'activation_token', 'remember_me_token', 'active'];

    public function validate($data)
    {
        $this->errors = [];

        if (empty($data['full_name'])) {
            $this->errors['full_name'] = "Full name is required";
        }

        if (empty($data['email'])) {
            $this->errors['email'] = "Email is required";
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
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
            $mail->Username = 'inthebush87@gmail.com'; // Replace with your email
            $mail->Password = 'scjc fguv lsnn saki'; // Use app password from environment variable
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;

            $mail->setFrom('inthebush87@gmail.com', 'Maravel');
            $mail->addAddress($email);

            $mail->isHTML(true);
            $mail->Subject = 'Account Activation Code';
            $mail->Body = "
                <p>Dear User,</p>
                <p>Your activation code is: <strong>$token</strong></p>
                <p><a href='" . ROOT . "/activation?token=$token'>Activate Your Account</a></p>
            ";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
            $this->errors['email'] = "Email could not be sent: " . $mail->ErrorInfo;
            return false;
        }
    }
}

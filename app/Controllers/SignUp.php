<?php

class SignUp
{
    use Controllers;

    public function index()
    {
        $data = [];
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $user = new User;

            // Sanitize input
            $email = htmlspecialchars($_POST['email']);
            $full_name = htmlspecialchars($_POST['full_name']);

            // Check if the email already exists
            if ($user->emailExists($email)) {
                $user->errors['email'] = "Email already exists";
            }

            // Validate user data
            if (empty($user->errors) && $user->validate($_POST)) {
                // Hash the password before saving
                $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $token = bin2hex(random_bytes(16)); // Generate a longer token

                // Store the data in the session instead of the database
                $_SESSION['signup_data'] = [
                    'email' => $email,
                    'full_name' => $full_name,
                    'token' => $token,
                    'active' => 0,
                    'password' => $hashedPassword,
                ];

                // Send activation email
                if (!$user->sendEmail($email, $token)) {
                    $data['errors']['email'] = "Failed to send activation email.";
                } else {
                    // Redirect to activation page
                    header("Location: " . ROOT . "/activation");
                    exit(); // Ensure no further code execution
                }
            } else {
                $data['errors'] = $user->errors;
            }
        }

        $data['title'] = 'Register';
        $this->views('signup', $data);
    }
}

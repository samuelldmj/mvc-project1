<?php

class SignUp
{
    use Controllers;

    public function index()
    {
        $data = [];
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $user = new User;

            // Extract email from POST data
            $email = $_POST['email'];

            // Check if the email already exists
            if ($user->emailExists($email)) {
                $user->errors['email'] = "Email already exists";
            }

            // Validate user data
            if (empty($user->errors) && $user->validate($_POST)) {
                // Hash the password before saving
                $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
                $token = bin2hex(random_bytes(16)); // Generate a unique token

                // Store the data in the session instead of the database
                $_SESSION['signup_data'] = [
                    'email' => $_POST['email'],
                    'names' => $_POST['names'],
                    'token' => $token,
                    'active' => 0, // Set active status to 0 (inactive)
                    'password' => $hashedPassword,
                ];
                // Redirect to activation page
                header("Location: " . ROOT . "/activation");

                // Send activation email
                if (!$user->sendEmail($_POST['email'], $token)) {
                    $data['errors']['email'] = "Failed to send activation email.";
                }

            } else {
                // Collect errors if any
                $data['errors'] = $user->errors;
            }
        }

        $data['title'] = 'Register';
        // Pass the data to the signup view
        $this->views('signup', $data);
    }
}

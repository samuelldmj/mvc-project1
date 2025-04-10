<?php

class Login
{
    use Controllers;

    public function index()
    {
        $data = [];
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User;

            // Retrieve user based on email
            $row = $user->first(['email' => $_POST['email']]);

            // Check if user exists and password matches
            if ($row && password_verify($_POST['password'], $row->password)) {
                // Set session
                $_SESSION['USER'] = $row;

                // Handle "Remember Me" functionality
                if (!empty($_POST['remember_me'])) {
                    $rememberToken = bin2hex(random_bytes(16));
                    $hashedToken = password_hash($rememberToken, PASSWORD_DEFAULT);
                    $user->update($row->id, ['remember_me_token' => $hashedToken]);

                    // Set a cookie that lasts for 30 days
                    setcookie('remember_me', $rememberToken, time() + (30 * 24 * 60 * 60), "/");
                }

                redirect('home');
            } else {
                // Handle incorrect email or password
                $user->errors['email'] = "Wrong Email or Password";
                $data['errors'] = $user->errors;
            }
        }

        $data['title'] = 'LogIn';
        $this->views('login', $data);
    }
}

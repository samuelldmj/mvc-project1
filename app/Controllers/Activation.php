<?php

class Activation
{
    use Controllers;

    public function index()
    {
        $data = [];
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $user = new User;
            $token = htmlspecialchars($_POST['active']);
            $signupData = $_SESSION['signup_data'] ?? [];

            if (!$token) {
                $data['errors'][] = "Activation code is required.";
            } elseif ($signupData && $signupData['token'] === $token) {
                // Insert the user data into the database
                $user->insert($signupData);

                // Activate the user by setting active to 1 and clearing the token
                $userRecord = $user->first(['email' => $signupData['email']]);
                if ($userRecord) {
                    $user->update($userRecord->id, ['active' => 1, 'token' => null]);
                }

                // Clear the signup data from session
                unset($_SESSION['signup_data']);

                // Redirect to login page
                redirect('login');
            } else {
                $data['errors'][] = "Invalid activation code.";
            }
        }

        $data['title'] = 'Activate Account';
        $this->views('activation', $data);
    }
}

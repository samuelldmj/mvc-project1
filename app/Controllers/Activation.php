<?php
// Handles activation logic

class Activation
{
    use Controllers;

    public function index()
    {
        $data = [];
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $user = new User;
            $token = $_POST['active'];
            $signupData = $_SESSION['signup_data'] ?? [];

            if (!$token) {
                $data['errors'][] = "Activation code is required.";
            } else {
                // Find the user by token
                $userRecord = $user->first(['token' => $token]);

                if ($userRecord && $userRecord->email === $signupData['email']) {
                    // Activate the user
                    $user->update($userRecord->id, ['active' => 1, 'token' => null]);

                    // Clear the signup data from session
                    unset($_SESSION['signup_data']);

                    // Redirect to login page with email and password
                    redirect('login', ['email' => $signupData['email'], 'password' => $signupData['password']]);
                } else {
                    $data['errors'][] = "Invalid activation code or email.";
                }
            }
        }

        $data['title'] = 'Activate Account';
        // Data helps us pass data into the activation page here to be viewed
        $this->views('activation', $data);
    }
}

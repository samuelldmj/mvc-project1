<?php
//Handles the logic of Your app

class Login
{
    use Controllers;

    public function index()
    {

        $data = [];
        // show($_POST);
        // if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //     $user = new User;
        //     $arr['email'] = $_POST['email'];
        //     $row = $user->first($arr);
        //     if ($row) {
        //         if ($row->password === $_POST['password']) {
        //             $_SESSION['USER'] = $row;
        //             redirect('home');
        //         }
        //     }
        //     $user->errors['email'] = "Wrong Email or Password";
        //     $data['errors'] = $user->errors;
        // }



        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User;

            // Check for validation errors
            if (empty($user->errors)) {
                // Retrieve user based on email
                $row = $user->first(['email' => $_POST['email']]);

                // Check if user exists and password matches
                if ($row && password_verify($_POST['password'], $row->password)) {
                    // Set session and redirect
                    $_SESSION['USER'] = $row;
                    redirect('home');
                } else {
                    // Handle incorrect email or password
                    $user->errors['email'] = "Wrong Email or Password";
                }
            }

            // Assign validation errors to the $data array
            $data['errors'] = $user->errors;
        }

        $data['title'] = 'LogIn';
        $this->views('login', $data);
    }
}


// $user->errors['email'] = "Wrong Email or Password";
// $data['errors'] = $user->errors;
<?php
//Handles the logic of Your app

class SignUp
{
    use Controllers;

    public function index()
    {


        // show($_POST);
        $data = [];
        if ($_SERVER['REQUEST_METHOD'] == "POST") {

            $user = new User;
            if ($user->validate($_POST)) {
                // Hash the password before saving
                $hashedPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

                // Prepare the data to be inserted
                $data = [
                    'email' => $_POST['email'],
                    'password' => $hashedPassword,
                ];

                // Insert the data into the database
                $user->insert($data);

                redirect('login');
            }

            
            

            //error will be available on the signup page
            $data['errors'] = $user->errors;
        }

        $data['title'] = 'SignUp';
//data helps us pass data into the signup page here to be viewed
        $this->views('signup', $data);
    }
}

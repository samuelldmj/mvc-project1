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

                $user->insert($_POST);
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

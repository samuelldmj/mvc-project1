<?php
//Handles the logic of Your app

class SignUp
{
    use Controllers;

    public function index()
    {

        $data = [];
        // show($_POST);
        $user = new User;
        if ($user->validate($_POST)) {

            $user->insert($_POST);
            redirect('home');
        }

        $data['errors'] = $user->errors;
        $this->views('signup', $data);
    }
}

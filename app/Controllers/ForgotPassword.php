<?php
//Handles the logic of Your app

class ForgotPassword
{
    use Controllers;

    public function index()
    {

        $data = [];
        // show($_POST);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = new User;
            $arr['email'] = $_POST['email'];
            $row = $user->first($arr);
            if ($row) {
                if ($row->password === $_POST['password']) {
                    $_SESSION['USER'] = $row;
                    redirect('home');
                }
            }
            $user->errors['email'] = "Wrong Email or Password";
            $data['errors'] = $user->errors;
        }
        $data['title'] = 'Forget Password';
        $this->views("forgotPassword", $data);
    }
}

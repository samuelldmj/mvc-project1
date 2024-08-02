<?php
//Handles the logic of Your app

class LogOut
{
    use Controllers;

    public function index()
    {
        if (!empty($_SESSION['USER']))
            unset($_SESSION['USER']);


        redirect('home');
    }
}

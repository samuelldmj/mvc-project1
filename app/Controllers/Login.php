<?php
//Handles the logic of Your app

class Login
{
    use Controllers;

    public function Index()
    {

        $this->views('login');
    }
}

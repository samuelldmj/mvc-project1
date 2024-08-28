<?php

class LogOut
{
    use Controllers;

    public function index()
    {
        // Clear session data
        if (!empty($_SESSION['USER'])) {
            unset($_SESSION['USER']);
        }

        // Clear the remember me cookie
        if (isset($_COOKIE['remember_me'])) {
            setcookie('remember_me', '', time() - 3600, "/"); // Expire the cookie
        }

        redirect('home');
    }
}

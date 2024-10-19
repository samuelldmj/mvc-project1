<?php




// define('ROOT', "http//:localhost/mvc/public");

//alternative that makes it easy production

if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === 'mvc-project1.test') {

    /**@database config for local environment */
    define('DBNAME', 'my_db');
    define('DBHOST', 'localhost');
    define('DBUSER', 'root');
    define('DBPASS', '');

    // Set the root URL for local environment
    if ($_SERVER['SERVER_NAME'] === 'localhost') {
        define('ROOT', "http://localhost/mvc-project1/public");
    } else {
        define('ROOT', "http://mvc-project1.test");
    }

} else {

    /**@database config for production environment */
    define('DBNAME', 'my_db');
    define('DBHOST', 'localhost');
    define('DBUSER', 'your_production_user');
    define('DBPASS', 'your_production_password');

    // Set the root URL for the production environment
    define('ROOT', "https://www.yourwebsite.com");
}

define('APP_NAME', "WEBSITE");
define('APP_DESC', "For practice");

// Set debug mode
define('DEBUG', true);  // Change to false in production


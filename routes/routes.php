<?php

Router::get('/home', 'home@index');
Router::get('/login', 'login@index');
Router::post('/login', 'login@index');
Router::get('/signup', 'signup@index');
Router::post('/signup', 'signup@index');
Router::get('/logout', 'logout@index');

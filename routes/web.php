<?php

$router->post('/login', 'AuthController@login');
$router->post('/register', 'AuthController@register');
$router->post('/payment', 'PaymentController@processPayment');

<?php

    error_reporting(E_ALL);

    date_default_timezone_set('Europe/Belgrade');

    // variables used for jwt
    $key = "4321";
    $iss = "http://example.org";
    $aud = "http://example.com";
    $iat = 1356999524;
    $nbf = 1357000000;


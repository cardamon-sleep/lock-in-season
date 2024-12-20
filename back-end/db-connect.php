<?php

/*******w******** 
    
    Name: Caleb Mustapha
    Date: 2024-11-24
    Description: CMS Lock In Season

****************/
/*
    db name, host, user & pass will require configuring when hosted on an online web server
*/

define('DB_DSN', 'mysql:host=localhost;dbname=lock_in_season;charset=utf8');
define('DB_USER', 'serveruser');
define('DB_PASS', 'gorgonzola7!');

// PDO is PHP Data Objects
// mysqli <-- BAD. 
// PDO <-- GOOD.
try {
    // Try creating new PDO connection to MySQL.
    $db = new PDO(DB_DSN, DB_USER, DB_PASS);
    //,array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
} catch (PDOException $e) {
    print "Error: " . $e->getMessage();
    die(); // Force execution to stop on errors.
    // When deploying to production you should handle this
    // situation more gracefully. ¯\_(ツ)_/¯
}
?>
<?php
/*
to allow the file paths to be usable from different folders an absolute path must be used 
    https://stackoverflow.com/questions/8668776/get-root-directory-path-of-a-php-project
    https://www.php.net/manual/en/language.constants.magic.php
    https://stackoverflow.com/questions/35460276/php-include-nav-bar-for-every-page-techniques
    https://stackoverflow.com/questions/32537477/how-to-use-dir

    https://www.w3schools.com/Php/php_superglobals_server.asp

given a file/directory path, dirname() returns the absolute path of its parent directory
__DIR__ returns the absolute path of the current directory
*/

// define("PROJECT_FOLDER", dirname($_SERVER['SCRIPT_NAME']));
// echo '<p style = "background-color: white;">' . PROJECT_FOLDER . '</p>';

$project_folder = '/webd-2013/lock-in-season-hard-copy-no-github/';


?>

<nav id="main-nav">
    <ul>
        <a href="<?= $project_folder ?>index.php"><li>HOME</li></a>
        <a href="<?= $project_folder ?>articles.php"><li>ARTICLES</li></a>
        <!-- <a href="#"><li>ABOUT</li></a> -->
    </ul>
</nav>
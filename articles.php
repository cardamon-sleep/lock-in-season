<?php

/*******w******** 
    
    Name: Caleb Mustapha
    Date: 2024-11-24
    Description: CMS Lock In Season

****************/

require('connect.php');

$query = "SELECT * FROM blog_posts ORDER BY created_at DESC";
$statement = $db->prepare($query);
$statement->execute(); 

?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    
    <title>Articles - Lock In Season</title>
    <link rel="icon" type="image/x-icon" href="/images/lis-favicon.png">

    <link rel = "stylesheet" type = "text/css" href = "articles-styles.css">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&display=swap">
</head>
<body>
    <header>
        <img alt = "Lock in season logo" class = "logo" src = "images/lis.png">
        <h1 id = "main-header">LOCK IN SEASON</h1>
    </header>
    
    <nav id = "main-nav">
        <ul>
            <a href = "index.php"><li>HOME</li></a>
            <a href = "articles.php"><li>ARTICLES</li></a>
            <a href = "about.html"><li>ABOUT</li></a>
            <!-- <a href = "#"><li>CONTACT</li></a> -->
        </ul>
    </nav>
    
    <br>

    <main>
        <button><a href = "new-article.php">Create New Article</a></button>
        
        <h2>All Articles</h2>
        <section>
            <?php while ($row = $statement->fetch()): ?>
                <article>
                    <h3><?= $row['title'] ?></h3>

                    <?php
                        // https://www.w3schools.com/php/func_date_date.asp
                        // https://stackoverflow.com/questions/136782/convert-from-mysql-datetime-to-another-format-with-php
                        $formatted_date = date('F j, Y, g:i a T', strtotime($row['created_at']));
                    ?>
                    
                    <h3><?= $formatted_date ?> - <a href = "edit-article.php?id=<?= $row['id']?>">edit</a></h3>

                    <p><?= $row['content'] ?></p>
                </article>
            <?php endwhile ?>
        </section>
    </main>

    
    <footer>
        <p>footer which has 400px top margin</p>
    </footer>
</body>
</html>
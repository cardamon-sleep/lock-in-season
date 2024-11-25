<?php

/*******w******** 
    
    Name: Caleb Mustapha
    Date: 2024-09-29
    Description: Assigment 3 - Blogging Application

****************/

if(require('connect.php'));
require('authenticate.php');


if ($_POST && !empty($_POST['title']) && !empty($_POST['content'])) 
{
    //  Sanitize user input to escape HTML entities and filter out dangerous characters.
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    $author = filter_var($_POST['author'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $image = filter_var($_POST['image'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    
    //  Build the parameterized SQL query and bind to the above sanitized values.
    $query = "INSERT INTO blog_posts (title, content, author, category_id, image_id) VALUES (:title, :content, :author, :category, :image)";
    $statement =  $db->prepare($query); // prepare it tries to cache information to use in the next step

    //  Bind values to the parameters (pass sanitized data to placeholder)
    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':author', $author);
    $statement->bindValue(':category_id', $category);
    $statement->bindValue(':image_id', $image);
    
    //  Execute the INSERT.
    //  execute() will check for possible SQL injection and remove if necessary
    $statement->execute();

    // Redirect after update.
    header("Location: articles.php"); 
    exit;
}

?>

<!DOCTYPE html>
<html lang = "en">
<head>
    <meta charset = "UTF-8">
    <meta name = "viewport" content = "width=device-width, initial-scale=1.0">
    
    <title>New Article - Lock In Season</title>
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
    
    <main>
        <h2>New Article</h2>
        <section>

            <form id = "new-blog-post" action = "post.php" method = "post">
            <fieldset>
                <legend>New Article</legend>

                <label for = "title">Title</label>
                <input name = "title" id = "title" type = "text">

                <br>
                <br>

                <label for = "content">Content</label>
                <textarea name = "content" id = "content"></textarea>

                <br>
                <br>
                
                <label for = "author">Author</label>
                <input name = "author" id = "author" type = "text">
                
                <br>
                <br>
                
                <label for = "category">Category</label>
                <select name = "category" id = "category">
                    <option value = ""></option>
                    <option value = "#">#</option>
                    <option value = "#">#</option>
                </select>

                <br>
                <br>
                
                <label for = "image">Image</label>
                <input name = "image" id = "image" type = "file">

                <br>
                <br>
                
                <input type = "submit" value = "Submit Blog" />
            </fieldset>
            
            
            
        </section>
    </main>

    
    <footer>
        <p>footer which has 400px top margin</p>
    </footer>
</body>
</html>
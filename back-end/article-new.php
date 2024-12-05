<?php

/*******w******** 
    
    Name: Caleb Mustapha
    Date: 2024-11-24
    Description: Assigment 3 - Blogging Application

****************/

require 'db-connect.php';
require 'authenticate.php';


if ($_POST && !empty($_POST['title']) && !empty($_POST['content']) && !empty($_POST['author'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $author = filter_var($_POST['author'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $new_image_path = null;
    $image_filename = null;

    // localhost config:
    $file_upload_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'article-img\\';
    // live site config:
    // $file_upload_path = $_SERVER['DOCUMENT_ROOT'] . '/img/article-img/';

    // echo "<script>alert({$file_upload_path})</script>";


    function file_is_an_image($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        $actual_mime_type        = getimagesize($temporary_path)['mime'];
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

        return $file_extension_is_valid && $mime_type_is_valid;
    }
    
    // 1. SAVE UPLOADED IMAGE TO FILE SYSTEM
    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
    // echo $image_upload_detected;
    
    if($image_upload_detected) 
    {
        $image_filename = $_FILES['image']['name'];
        $temporary_image_path = $_FILES['image']['tmp_name'];

        // after moving the file, this path will also be used in the db to store path
        $new_image_path = $file_upload_path . $image_filename;
        
        if(file_is_an_image($temporary_image_path, $new_image_path))
        {
            move_uploaded_file($temporary_image_path, $new_image_path);
        }
    }

    // 3. BUILD FULL ARTICLE QUERY
    //  Build the parameterized SQL query and bind to the above sanitized values. ":" denotes a placeholder
    $query = "INSERT INTO articles (title, content, author, category_id, image_path, image_filename) VALUES (:title, :content, :author, :category, :image, :image_name)";
    $statement = $db->prepare($query); // prepare it tries to cache information to use in the next step

    //  Bind values to the parameters (pass sanitized data to placeholder)
    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':author', $author);
    $statement->bindValue(':category', $category);
    $statement->bindValue(':image', $new_image_path);
    $statement->bindValue(':image_name', $image_filename);

    //  execute() will check for possible SQL injection and remove if necessary
    $statement->execute();
    
    // Redirect after update.
    header("Location: ../articles.php");
    exit;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>New Article - Lock In Season</title>

    <link rel="stylesheet" type="text/css" href="../css/articles.css">
    <link rel="stylesheet" type="text/css" href="../css/header-nav.css">
    

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&display=swap">
</head>

<body>
    <header>
    <img alt="Lock in season logo" class="logo" src="../img/logos/lis.png">
    <h1 id="main-header">LOCK IN SEASON</h1>
    </header>

    <nav id="main-nav">
    <ul>
        <a href="../index.php"><li>HOME</li></a>
        <a href="../articles.php"><li>ARTICLES</li></a>
        <!-- <a href="#"><li>ABOUT</li></a> -->
    </ul>
    </nav>

    <main>
        <h2>New Article</h2>
        <section>

            <form id="new-blog-post" action="article-new.php" method="post" enctype = "multipart/form-data">
                <fieldset>
                    <legend>New Article</legend>

                    <label for="title">Title</label>
                    <input name="title" id="title" type="text" required>

                    <br>
                    <br>

                    <label for="content">Content</label>
                    <textarea name="content" id="content" required></textarea>

                    <br>
                    <br>

                    <label for="author">Author</label>
                    <input name="author" id="author" type="text" required>

                    <br>
                    <br>

                    <label for="category">Category</label>
                    <select name="category" id="category">
                        <?php
                        $query = "SELECT * FROM categories";
                        $statement = $db->prepare($query);
                        $statement->execute();
                        ?>
                        
                        <?php while ($row = $statement->fetch()): ?>
                            <!-- conditional operation displays no text when the 1st category (Uncategorized) is the target -->
                            <option value="<?= $row['id'] ?>"><?= ($row['id'] == 1) ? '' : $row['name'] ?></option>
                        <?php endwhile ?>
                    </select>


                    <br>
                    <br>

                    <label for="image">Image</label>
                    <input name="image" id="image" type="file">

                    <br>
                    <br>

                    <input type="submit" value="Publish" />
                </fieldset>
            </form>


        </section>
    </main>


    <footer>
        <p>footer which has 400px top margin</p>
    </footer>
</body>

</html>
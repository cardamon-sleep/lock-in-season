<?php

/*******w******** 
    
    Name: Caleb Mustapha
    Date: 2024-11-24
    Description: Assigment 3 - Blogging Application

****************/

require 'db-connect.php';
require 'authenticate.php';


if ($_POST && !empty($_POST['title']) && !empty($_POST['content'])) {
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_var($_POST['content'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $author = filter_var($_POST['author'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $image = filter_var($_POST['image'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    if (empty($image)) {
        $image = null;
    }

    //  Build the parameterized SQL query and bind to the above sanitized values. ":" denotes a placeholder
    $query = "INSERT INTO articles (title, content, author, category_id, image_id) VALUES (:title, :content, :author, :category, :image)";
    $statement = $db->prepare($query); // prepare it tries to cache information to use in the next step

    //  Bind values to the parameters (pass sanitized data to placeholder)
    $statement->bindValue(':title', $title);
    $statement->bindValue(':content', $content);
    $statement->bindValue(':author', $author);
    $statement->bindValue(':category', $category);
    $statement->bindValue(':image', $image);

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
    <?php include '../components/header.php' ?>
    <?php include '../components/nav.php' ?>

    <main>
        <h2>New Article</h2>
        <section>

            <form id="new-blog-post" action="article-new.php" method="post">
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
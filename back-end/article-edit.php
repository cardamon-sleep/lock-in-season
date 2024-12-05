<?php

/*******w******** 
    
    Name: Caleb Mustapha
    Date: 2024-11-24
    Description: CMS Lock In Season

****************/

require('db-connect.php');
require('authenticate.php');

$error = "";
$post = null;


// after submitting/updating, post isset
if ($_POST && isset($_POST['id'])) {
    echo 'POST IS SET';
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $new_image_path = null;
    $image_filename = null;
    
    $file_upload_path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . 'article-img\\';
        // echo $file_upload_path;

    function file_is_an_image($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        $actual_mime_type        = getimagesize($temporary_path)['mime'];
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);

        return $file_extension_is_valid && $mime_type_is_valid;
    }
    
    // 1. IF NEW IMAGE UPLOADED SAVE UPLOADED IMAGE TO FILE SYSTEM
    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
    
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



    // echo '$category: ' . $category;

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    if (empty($category)) {
        $category = null;
    }

    if (isset($_POST['delete'])) {
        $query = "DELETE FROM articles WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        header("Location: ../articles.php");
        exit;
    } elseif (!empty($title) && !empty($content) && !empty($_POST['author'])) {
        // $query = "UPDATE articles SET title = :title, content = :content, author = :author, category_id = :category_id WHERE id = :id";
        $query = "UPDATE articles SET title = :title, content = :content, author = :author, category_id = :category_id, image_path = :image, image_filename = :image_name WHERE id = :id";
        $statement = $db->prepare($query);

        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':author', $author);
        $statement->bindValue(':category_id', $category);
        $statement->bindValue(':image', $new_image_path);
        $statement->bindValue(':image_name', $image_filename);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();
        header("Location: ../articles.php");
        exit;
    } else {
        $error = "Both title and content must be at least 1 character long.";
        // Preserve the submitted data
        $post = [
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'author' => $author,
            'category_id' => $category,
            'image_path'=> $new_image_path,
            'image_name' => $image_filename
        ];
    }
}


// article edit page accessed by edit-article.php?____, aka using GET
if (!$post && isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // retrieve the blog post in question based on id from GET
    $query = "SELECT * FROM articles WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT); // third param specifies $id is int
    $statement->execute();
    $post = $statement->fetch();
}

// if the post doesn't exist (invalid id) redirect back to articles
if (!$post) {
    echo 'POST ISNT SET';
    header("Location: ../articles.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - Lock In Season</title>

    <link rel="stylesheet" href="../css/articles.css">
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
        <h2>Edit Article</h2>
        <section>
            <form id="new-blog-post" action="article-edit.php" method="post">
                <fieldset>
                    <input type="hidden" name="id" value="<?= $post['id'] ?>">

                    <legend>Edit Article</legend>
                    <label for="title">Title</label>
                    <input name="title" id="title" type="text" value="<?= $post['title'] ?>" required>

                    <br>
                    <br>

                    <label for="content">Content</label>
                    <textarea name="content" id="content" required><?= $post['content'] ?></textarea>

                    <br>
                    <br>

                    <label for="author">Author</label>
                    <input name="author" id="author" type="text" value="<?= $post['author'] ?>" required>

                    <br>
                    <br>


                    <label for="category">Category</label>
                    <select name="category" id="category">
                        
                        <?php
                        //utilizes previously ran articles query to store category_id for this post before being flushed by the new query
                        // $article_category = 

                        $query = "SELECT * FROM categories";
                        $statement = $db->prepare($query);
                        $statement->execute();
                        ?>

                        <?php while ($row = $statement->fetch()): ?>
                            <!-- if the id of the category matches the idea of this article's category the select for that option is set default-->
                            <?php if($row['id'] == $post['category_id']): ?>
                                <option value="<?= $row['id'] ?>" selected><?= ($row['id'] == 1) ? '' : $row['name'] ?></option>
                            <?php else: ?>
                                <option value="<?= $row['id'] ?>"><?= ($row['id'] == 1) ? '' : $row['name'] ?></option>
                            <?php endif ?>
                        <?php endwhile ?>
                    </select>


                    <br>
                    <br>

                    <label for="image">Replace image</label>
                    <input name="image" id="image" type="file">

                    <?php if(isset($post['image_path'])): ?>
                        <p>Remove image: <u><?= $post['image_filename'] ?></u>?</p>
                        <label for = "no">No</label>
                        <input name = "remove-image" id = "no" type = "radio" value = "no" checked>
                        
                        <label for = "yes">Yes</label>
                        <input name = "remove-image" id = "no" type = "radio" value = "yes">
                    <?php endif ?>


                    <br>
                    <br>

                    <input type="submit" value="Publish" />
                    <input type="submit" name="delete" value="Delete" />
                </fieldset>
            </form>
        </section>
    </main>

</body>

</html>
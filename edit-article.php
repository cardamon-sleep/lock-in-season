<?php

/*******w******** 
    
    Name: Caleb Mustapha
    Date: 2024-11-24
    Description: CMS Lock In Season

****************/

require('connect.php');
require('authenticate.php');

$error = "";
$post = null;


// after submitting/updating, post isset
if ($_POST && isset($_POST['id'])) {
    echo 'POST IS SET';
    $title = filter_input(INPUT_POST, 'title', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $content = filter_input(INPUT_POST, 'content', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $author = filter_input(INPUT_POST, 'author', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    // $category = filter_var($_POST['category'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category = filter_input(INPUT_POST, 'category', FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    echo '$category: ' . $category;

    $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

    if (empty($category)) {
        $category = null;
    }

    if (isset($_POST['delete'])) {
        $query = "DELETE FROM blog_posts WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        header("Location: articles.php");
        exit;
    } elseif (!empty($title) && !empty($content)) {
        $query = "UPDATE blog_posts SET title = :title, content = :content, author = :author, category_id = :category_id WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':author', $author);
        $statement->bindValue(':category_id', $category);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        header("Location: articles.php");
        exit;
    } else {
        $error = "Both title and content must be at least 1 character long.";
        // Preserve the submitted data
        $post = [
            'id' => $id,
            'title' => $title,
            'content' => $content,
            'author' => $author,
            'category_id' => $category
        ];
    }
}


// article edit page accessed by edit-article.php?____, aka using GET
if (!$post && isset($_GET['id'])) {
    $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);

    // retrieve the blog post in question based on id from GET
    $query = "SELECT * FROM blog_posts WHERE id = :id";
    $statement = $db->prepare($query);
    $statement->bindValue(':id', $id, PDO::PARAM_INT); // third param specifies $id is int
    $statement->execute();
    $post = $statement->fetch();
}

// if the post doesn't exist (invalid id) redirect back to articles
if (!$post) {
    echo 'POST ISNT SET';
    header("Location: articles.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Article - Lock In Season</title>

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&display=swap">

    <link rel="stylesheet" href="articles-styles.css">
</head>

<body>
    <header>
        <img alt="Lock in season logo" class="logo" src="images/lis.png">
        <h1 id="main-header">LOCK IN SEASON</h1>
    </header>

    <nav id="main-nav">
        <ul>
            <a href="index.php">
                <li>HOME</li>
            </a>
            <a href="articles.php">
                <li>ARTICLES</li>
            </a>
            <a href="about.html">
                <li>ABOUT</li>
            </a>
            <!-- <a href = "#"><li>CONTACT</li></a> -->
        </ul>
    </nav>

    <main>
        <h2>Edit Article</h2>
        <section>
            <form id="new-blog-post" action="edit-article.php" method="post">
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
                        //utilizes previously ran blog_posts query to store category_id for this post before being flushed by the new query
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

                    <label for="image">Image</label>
                    <input name="image" id="image" type="file">

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
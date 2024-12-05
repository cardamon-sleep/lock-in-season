<?php

/*******w******** 
    
    Name: Caleb Mustapha
    Date: 2024-11-24
    Description: CMS Lock In Season

****************/

require 'back-end/db-connect.php';
include 'back-end/generate-category-styles.php';

$query = "SELECT * FROM articles ORDER BY created_at DESC";
$statement = $db->prepare($query);
$statement->execute();


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Articles - Lock In Season</title>

    <link rel="stylesheet" type="text/css" href="css/articles.css">
    <link rel=" stylesheet" type="text/css" href="css/header-nav.css">
    <link rel=stylesheet type="text/css" href="css/category.css">

    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Archivo:ital,wght@0,100..900;1,100..900&display=swap">
</head>

<body>
    <header>
    <img alt="Lock in season logo" class="logo" src="img/logos/lis.png">
    <h1 id="main-header">LOCK IN SEASON</h1>
    </header>

    <nav id="main-nav">
    <ul>
        <a href="index.php"><li>HOME</li></a>
        <a href="articles.php"><li>ARTICLES</li></a>
        <!-- <a href="#"><li>ABOUT</li></a> -->
    </ul>
    </nav>

    <br>

    <main>
        <button><a href="back-end/article-new.php">Create New Article</a></button>



        <?php

        // echo (boolean)(isset($_GET['id']));
        ?>

        <!-- if no article is selected -->
        <?php if (!isset($_GET['id'])): ?>
            <h2>All Articles</h2>
            <section>
                <?php while ($row = $statement->fetch()): ?>
                    <article>
                        <h3><a href="articles.php?id=<?= $row['id'] ?>"><?= htmlspecialchars($row['title']) ?></a></h3>

                        <?php
                        $article_category = "";
                        // grab category of the post
                        // query categories table for posts category_id
                        if (isset($row['category_id'])) {
                            $category_query = "SELECT * FROM categories WHERE id = {$row['category_id']}";
                            $category_statement = $db->prepare($category_query);
                            $category_statement->execute();

                            $category_row = $category_statement->fetch();
                            $article_category = "{$category_row['name']}";

                            // echo '<pre>' . print_r($category_row) . '</pre>';
                        } else {
                            $article_category = "Uncategorized";
                        }
                        ?>

                        <h4 class="<?= $article_category ?>"><?= $article_category ?></h4>

                        <?php
                        // https://www.w3schools.com/php/func_date_date.asp
                        // https://stackoverflow.com/questions/136782/convert-from-mysql-datetime-to-another-format-with-php
                        $formatted_date = date('F j, Y, g:i a T', strtotime(htmlspecialchars($row['created_at'])));
                        ?>

                        <h3><?= $formatted_date ?> - <a href="back-end/article-edit.php?id=<?= $row['id'] ?>">edit</a></h3>

                        <p><?= $row['content'] ?></p>
                    </article>
                <?php endwhile ?>
            </section>
        <?php else: ?>
            <?php
            $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
            $row = $statement->fetch();
            // echo "<h1>" . $id . "</h1>";

            if ($id) {
                // construct sql query
                $query = "SELECT * FROM articles WHERE id = :id";

                // cache query, allowing database to recognize it
                $statement = $db->prepare($query);

                /*
                    binds the id value of the query ^ to (esstentially,) the id of the selected blog post
        
                    PDO::PARAM_INT throws error if datatypes don't match, but it's been sanitized, and this 
                    statement exists in this true block
                */
                $statement->bindValue(':id', $id, PDO::PARAM_INT);

                // execute query, 
                $statement->execute();

                // sets fullPost to equal the retrieved blog post data with the selected blog post id
                $full_article = $statement->fetch(PDO::FETCH_ASSOC);
            }
            ?>

            <h2><?= $full_article['title'] ?></h2>

            <section>
                <?php
                $formatted_date = date('F j, Y, g:i a T', strtotime(htmlspecialchars($full_article['created_at'])));
                ?>
                <h3><?= $formatted_date ?></h3>

                <p><?= $full_article['content'] ?></p>

                <?php if(isset($full_article['image_path'])): ?>
                    <img alt = "Image of <?= $full_article['title'] ?>" src = "img/article-img/<?= $full_article['image_filename'] ?>" width = "300px">
                <?php endif ?>
            </section>
        <?php endif ?>
    </main>


    <footer>
        <p>footer which has 400px top margin</p>
    </footer>
</body>

</html>
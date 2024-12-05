<?php

/*******w******** 
    
    Name: Caleb Mustapha
    Date: 2024-11-24
    Description: CMS Lock In Season

****************/

require 'back-end/db-connect.php';

$query = "SELECT * FROM articles ORDER BY created_at DESC LIMIT 3";
$statement = $db->prepare($query);
$statement->execute();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home - Lock In Season</title>

    <link rel="stylesheet" type="text/css" href="css/main.css">
    <link rel=" stylesheet" type = "text/css" href="css/header-nav.css">
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
    

    <div class="banner">
        <img alt="Looking out the train window" src="img/banners/looking-out-train-window.jpg">
    </div>

    <main>
        <!-- sections are flex containters -->
        <section class="about">
            <h2>The People We're Becoming</h2>
            <img alt="Crowded train" src="img/article-img/crowded-train.jpg">
            <p>
                The harrowing fact is that there are countless external factors that hinder us from accomplishing our
                goals.
            </p>
            <p>
                Perhaps more distressing however, is that what is often the biggest obstacle, and the only variable we
                control is ourselves.
                Internal factors such as procrastination, self-doubt, insecurity, and laziness stop many people from
                advancing towards desired outcomes.
            </p>
            <p>
                In the modern age a wall of distraction often exists between individuals and doing the work
                required to attain a desired result. Constantly accessible entertainment and addictively-engineered
                services produce
                a weakened ability to focus, create dependency, and reinforce negative habits.
            </p>
            <p>
                To lock in is to be in a statlock mental strength and clarity that
                enforces self-control, increases
                ability to focus, and naturally produces healthy behaviours. Being locked in yields better mental and
                physical health and facilitates productivity.
            </p>
            <p>It's time to lock in. For the people we're becoming.</p>
        </section>


        
        <!-- <section class="company">
            <h2>Lock In Season</h2>
            <p>
                Established in 2024, <strong>LOCK IN SEASON</strong> strives to provide practical tools and information
                to help you
                build positive habits and healthy behaviours.
            </p>
        </section> -->




        
            <h2><u>Recent Articles</u></h2>
            <?php while ($row = $statement->fetch()): ?>
                <article>
                    <h3><?= $row['title'] ?></h3>

                    <?php
                    // https://www.w3schools.com/php/func_date_date.asp
                    // https://stackoverflow.com/questions/136782/convert-from-mysql-datetime-to-another-format-with-php
                    $formatted_date = date('F j, Y, g:i a T', strtotime($row['created_at']));
                    ?>

                    <h3><?= $formatted_date ?></h3>

                    <?php if(strlen($row['content']) > 200): ?>                    
                        <p> <?= htmlspecialchars(substr($row['content'], 0, 200)) ?>...</p>
                        <a href="articles.php?id=<?= $row['id'] ?>"> Read more </a>
                    <?php else: ?>
                        <p> <?= htmlspecialchars($row['content']) ?> </p>
                    <?php endif ?>
                    
                </article>
            <?php endwhile ?>
        
           
    </main>





    <footer>
        <p>footer which has 400px top margin</p>
    </footer>
</body>

</html>
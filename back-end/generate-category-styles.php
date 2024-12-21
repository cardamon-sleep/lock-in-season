<?php


$query = "SELECT * FROM categories";
$statement = $db->prepare($query);
$statement->execute();

$css = "";
$category_color = "#727272";
$text_color = "white";

while ($row = $statement->fetch()) {
    $css .= "\n.{$row['name']} {
    background-color: {$row['background_color']};
    color: {$row['text_color']};
    width: fit-content;
    padding: 0 2px 0 2px;
    margin: 0 0 10px 0;
}";
}
file_put_contents("css/category.css", $css);

?>
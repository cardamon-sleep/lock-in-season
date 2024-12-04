<?php


$query = "SELECT * FROM categories";
$statement = $db->prepare($query);
$statement->execute();

$css = "";
$category_color = "#727272";
$text_color = "white";

while ($row = $statement->fetch()) {
    $css .= "\n.{$row['name']} {
        \n\tbackground-color: {$row['background_color']};
        \n\tcolor: {$row['text_color']};\n
        \n\twidth: fit-content;
        \n\tpadding: 10px;
        \n\tborder-radius: 5px;
    }";
}
file_put_contents("css/category.css", $css);

?>
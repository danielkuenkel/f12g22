<?php
# Autor: Daniel Kuenkel
# Datum: 17.11.2012
#              
# Die Funktion UploadRecipe speichert die eingegebenen Daten in der Datenbank,
# und die werden später abgerufen.


session_start();
include 'dbOperations.php';
$title = addslashes($_POST['title']);
$abstract = $_POST['abstract'];
$preparation = $_POST['preparation'];
$prepTime = $_POST['prepTime'];
$numberPeople = $_POST['numberPeople'];
$userId = $_SESSION['user_id'];
$filePath = "../img/upload/";
$filePathForDB = "web/img/upload/";
$updateRecipeId = $_POST['updateRecipeId'];
$videoUrl = $_POST['videoUrl'];
$url = "http://sfsuswe.com/~f12g22/error";
$dbHasImages = "false";
$uploadNewImage = "false";
$categoryKeys = $_POST['categories'];
$splitKeys = explode(",", $categoryKeys);
foreach ($splitKeys as $value) {
    $categories[] = $value;
}

if ($updateRecipeId != "") {
    $deleteIngredientsQuery = "DELETE FROM ingredient WHERE recipe_id=$updateRecipeId";
    dbRequest($deleteIngredientsQuery);

    $hasImageQuery = "SELECT image_url, icon_url FROM recipe WHERE recipe_id=$updateRecipeId";
    $hasImageResult = dbRequest($hasImageQuery);
    $hasImageRow = mysql_fetch_object($hasImageResult);
    $imageUrl = $hasImageRow->image_url;
    $tinyUrl = $hasImageRow->icon_url;
    if (is_null($imageUrl) || is_null($tinyUrl)) {
        $dbHasImages = "false";
    } else {
        $dbHasImages = "true";
    }
}

//save the pictures
$allowedExts = array("jpg", "jpeg", "gif", "png");
$fileExtension = explode(".", $_FILES["file"]["name"]);
$extension = end($fileExtension);
if ($_FILES['file']['name'] != "") {
    if ((($_FILES["file"]["type"] == "image/gif")
            || ($_FILES["file"]["type"] == "image/jpeg")
            || ($_FILES["file"]["type"] == "image/png")
            || ($_FILES["file"]["type"] == "image/pjpeg"))
            && ($_FILES["file"]["size"] < 2000000)
            && in_array($extension, $allowedExts)) {
        if ($_FILES["file"]["error"] > 0) {
            
        } else {
            if (file_exists($filePath . $_FILES["file"]["name"])) {
                echo $_FILES["file"]["name"] . " already exists. ";
            } else {
                $hashwert = hash_hmac_file('md5', $_FILES["file"]["tmp_name"], 'geheimnis');
                $image_url = $filePath . $hashwert . "." . $extension;
                move_uploaded_file($_FILES["file"]["tmp_name"], $image_url);
                $image_url_tiny = $filePath . $hashwert . "tiny." . $extension;
                $image_url_large = $filePath . $hashwert . "large." . $extension;
                $image_url_tinyForDB = $filePathForDB . $hashwert . "tiny." . $extension;
                $image_url_largeForDB = $filePathForDB . $hashwert . "large." . $extension;
                createImage($image_url, $image_url_tiny, "tiny");
                createImage($image_url, $image_url_large, "large");
                unlink($image_url);
                $uploadNewImage = "true";
            }
        }
    }
} else if ($dbHasImages == "false") {
    $image_url_tinyForDB = "web/img/default.jpg";
    $image_url_largeForDB = "web/img/default-recipe.jpg";
    $uploadNewImage = "true";
}

if ($updateRecipeId != "" && $uploadNewImage == "true") {
    //udpate recipe cells
    $query = "UPDATE recipe SET title='$title', abstract='$abstract', cooking_time='$prepTime', servings=$numberPeople, preparation='$preparation', image_url='$image_url_largeForDB', icon_url='$image_url_tinyForDB', video_url='$videoUrl' WHERE recipe_id=$updateRecipeId";
} else if ($updateRecipeId != "" && $uploadNewImage == "false") {
    $query = "UPDATE recipe SET title='$title', abstract='$abstract', cooking_time='$prepTime', servings=$numberPeople, preparation='$preparation', video_url='$videoUrl' WHERE recipe_id=$updateRecipeId";
} else {
    //add recipe (except ingredients) to database
    $query = "INSERT INTO recipe (user_id, title, abstract, cooking_time, servings, preparation, image_url, icon_url, video_url) VALUES ($userId, '$title', '$abstract', '$prepTime', $numberPeople, '$preparation', '$image_url_largeForDB','$image_url_tinyForDB', '$videoUrl')";
}

if (!dbRequest($query)) {
    $url = "http://sfsuswe.com/~f12g22/error";
}

if ($updateRecipeId == "") {
//find recipe_id of inserted recipe
    $query = "SELECT * from recipe WHERE user_id = $userId ORDER BY recipe_id DESC";
    $result = dbRequest($query);
    $row = mysql_fetch_object($result);
    $recipe_id = $row->recipe_id;
} else {
    $recipe_id = $updateRecipeId;
}

if ($updateRecipeId != "") {
    $deleteCatQuery = "DELETE FROM category WHERE recipe_id=$updateRecipeId";
    dbRequest($deleteCatQuery);
}

foreach ($categories as $category) {
    $catQuery = "INSERT INTO category (recipe_id, name) VALUES ($recipe_id, '$category')";
    dbRequest($catQuery);
}

$url = "http://sfsuswe.com/~f12g22/?recipeId=" . $recipe_id;

//add ingredients to database
$i = 1;
while ($_POST["ingredientName" . $i] != null) {
    $ingredientName = $_POST["ingredientName" . $i];
    $ingredientUnit = $_POST["unitSelect" . $i];
    $ingredientQuantity = $_POST["ingredientQuantity" . $i];
    if (strpos($ingredientQuantity, '.') < strpos($ingredientQuantity, ',')) {
        $ingredientQuantityTemp = str_replace('.', '', $ingredientQuantity);
        $ingredientQuantityString = strtr($ingredientQuantityTemp, ',', '.');
    } else {
        $ingredientQuantityString = str_replace(',', '', $ingredientQuantity);
    }
    $ingredientQuantityFloat = (float) $ingredientQuantityString;
    if ($ingredientUnit == 0 && $ingredientQuantity != 0) {
        $query = "INSERT INTO ingredient (recipe_id, quantity, ingredient) VALUES ('" . $recipe_id . "','" . $ingredientQuantityFloat . "', '" . $ingredientName . "')";
    } else if ($ingredientQuantity == 0 && $ingredientUnit != 0) {
        $query = "INSERT INTO ingredient (recipe_id, ingredient, unit_id) VALUES ('" . $recipe_id . "', '" . $ingredientName . "','" . $ingredientUnit . "')";
    } else if ($ingredientQuantity == 0 && $ingredientUnit == 0) {
        $query = "INSERT INTO ingredient (recipe_id, ingredient) VALUES ('" . $recipe_id . "','" . $ingredientName . "')";
    } else {
        $query = "INSERT INTO ingredient (recipe_id, quantity, ingredient, unit_id) VALUES ('" . $recipe_id . "','" . $ingredientQuantityFloat . "', '" . $ingredientName . "','" . $ingredientUnit . "')";
    }
    $i++;
    if (!dbRequest($query)) {
        $url = "http://sfsuswe.com/~f12g22/?error=error";
    }
}

header("HTTP/1.1 301 Moved Permanently");
header("Location: $url");
exit();

//function to resize image and store it
function createImage($image_url, $new_url, $case) {
    if (!(file_exists($image_url)) || file_exists($new_url))
        return false;

    $image_attributes = getimagesize($image_url);
    $image_width_old = $image_attributes[0];
    $image_height_old = $image_attributes[1];
    $image_filetype = $image_attributes[2];

    if ($case == "tiny") {
        $image_width_new = 213;
        $image_height_new = 213;
    } else {
        $image_width_new = 400;
        $image_height_new = 400;
    }

    switch ($image_filetype) {
        case 1: //picture type is gif
            $image_old = imagecreatefromgif($image_url);
            $image_new = imagecreate($image_width_new, $image_height_new);
            imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old);
            imagegif($image_new, $new_url);
            break;

        case 2: //picture type is jpeg
            $image_old = imagecreatefromjpeg($image_url);
            $image_new = imagecreatetruecolor($image_width_new, $image_height_new);
            imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old);
            imagejpeg($image_new, $new_url);
            break;

        case 3: //picture type is png
            $image_old = imagecreatefrompng($image_url);
            $image_colordepth = imagecolorstotal($image_old);

            if ($image_colordepth == 0 || $image_colordepth > 255) {
                $image_new = imagecreatetruecolor($image_width_new, $image_height_new);
            } else {
                $image_new = imagecreate($image_width_new, $image_height_new);
            }

            imagealphablending($image_new, false);
            imagecopyresampled($image_new, $image_old, 0, 0, 0, 0, $image_width_new, $image_height_new, $image_width_old, $image_height_old);
            imagesavealpha($image_new, true);
            imagepng($image_new, $new_url);
            break;

        default:
            return false;
    }

    return true;
}
?>
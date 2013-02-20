<?php



#Autor: Daniel, Malkmus
# Datum: 29.11.2012
# PHP Datei die fuer die Druck Funtionalität zustandig ist.
# ruft die datenbank ab mit sql Request, und liest die Daten daraus


include '/web/php/dbOperations.php';
$recipeId = $_GET['recipeId'];
$queryRecipe = "SELECT * FROM recipe WHERE recipe_id=$recipeId";


$result = dbRequest($queryRecipe);
if (!$result) {
    echo "Unknow Error";
    exit;
}
if (mysql_num_rows($result) == 1) {
    $row = mysql_fetch_object($result);
    $title = $row->title;
    $image = $row->image_url;
    $abstract = $row->abstract;
    $prepa = $row->preparation;
}
?>

<!------------------------------------------------------------------------------
--->
<!DOCTYPE HTML">
<html>
    <title>Cooking Place</title>

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
        <style type="text/css"></style>
        <script type="text/javascript" src="web/js/jquery-1.8.2.js" charset="utf-8"></script>
        <script type="text/javascript" src="web/js/toggleStates.js" charset="utf-8"></script>
        <script type="text/javascript" src="web/js/ajax.js" charset="utf-8"></script>
        <script type="text/javascript" src="web/js/page.js" charset="utf-8"></script>
        <script type="text/javascript" src="web/js/getParams.js" charset="utf-8"></script>
    </head>

    <body>
        <div id="content"></div>
        <div id="preloader">
            <div id="loaderImage"></div>
        </div>
        <p class="printTitle">
            <?php echo $title; ?>
        </p>

        <p class="printImage">
            <img src="<?php echo $image; ?>" alt="no image">
        </p>

        <p class="printIngredients">
            <?php
            $ingredientQuery = "SELECT * FROM ingredient WHERE recipe_id=$row->recipe_id";
            $ingredientResult = dbRequest($ingredientQuery);
            while ($ingredientRow = mysql_fetch_object($ingredientResult)) {
                $quantity = is_null($ingredientRow->quantity) ? "" : $ingredientRow->quantity;
                $ingedientName = $ingredientRow->ingredient;
                $unit = "";


                if (!is_null($ingredientRow->unit_id)) {
                    $unitQuery = "SELECT * FROM unit WHERE unit_id=$ingredientRow->unit_id";
                    $unitResult = dbRequest($unitQuery);
                    $unitName = mysql_fetch_object($unitResult);
                    $unit = $unitName->unit_name;
                }

                echo $ingedientName . " " . $quantity . " " . $unit;
                echo "</br>";
            }
            ?>
        </p>

        <p class="printAbstract">
            <?php echo $abstract; ?>
        </p>

        <p class="printPreparation">
            <?php echo $prepa; ?>
        </p>
        <script type="text/javascript">
           
        </script>
    </body>
</html>


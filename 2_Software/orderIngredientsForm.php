<!--
Autor: Daniel, Kuenkel
Co-Autoren: Anne Moldt
            

Diese Datei handhabt die Aufrufe zu den einzelnen Seiten. Sei es über
direkten Aufruf oder über eine Ajax Anfrage.
-->

<head>
    <script type="text/javascript" src="web/js/jquery.validate.js" charset="utf-8"></script>
    <style type="text/css">
        @import "web/css/createRecipe.css";
    </style>

    <script type="text/javascript">
    
        $('#orderRecipe').validate(
        {
            errorClass: 'myerror',
            validClass: 'mysuccess',
            rules: {
                title: "required", 
                numberPeople: "required", 
                preparation: "required",
                prepTime: "required"
            },
            errorPlacement: $.noop
        });
    </script>
</head>
<body>
    <div class="contentWrapper">
        <input type="button" onclick="goBackToRecipe()" class="greenButton" value="go back to recipe"/>
        <div class="defaultHeadline">
            order ingredients
        </div>
        <div class="defaultText">
            Here you can quickly and easily edit your desired ingredients 
            for the recipe. Change quantities or delete an ingredient. 
            Send everything to the nearest market, to get a quote.
        </div>
        <div class="createRecipeFormContent">

            <form action="web/php/orderIngredients.php" name="orderRecipe" id="orderRecipe"
                  method="Post" enctype="multipart/form-data"> 

                <div class="inputLabel" id="ingredientsHeadline">The ingredients</div>
                <div class="ingredients" id="ingredients"></div>

                <input type="button" id="addIngredientButton" onclick="addIngredient('', '', '')" class="greenButton" value="add Ingredient"/>
                <input type="button" id="removeIngredientButton" onclick="removeIngredient()" class="redButton" value="remove last Ingredient"/> 
                <input type="submit" id="submitButton"  class="greenButton"  value="submit"/>

            </form>
        </div>
    </div>
</body>

<script type=text/javascript>
    var ingredientCount = 0;
    
    $(document).ready(function(){
        var url = "web/php/editOrder.php?recipeId=" + orderRecipeId + "&from=" + orderIngredientsFrom + "&to=" + orderIngredientsTo;
        request("GET", url, true, "editOrder");
    });
     // fuegt Ingredient im Recipe hinzu
    function addIngredient(quantity, unit, name)
    {
        ingredientCount ++;
        
        var ingredientContainer = document.createElement('div');
        ingredientContainer.className = "ingredientContainer";
        ingredientContainer.id = "ingredientContainer" + ingredientCount;
        document.getElementById("ingredients").appendChild(ingredientContainer);
        
        var ingredientDelete = document.createElement('div');
        ingredientDelete.className = "ingredientDelete";
        ingredientContainer.appendChild(ingredientDelete);
        
        var deleteButton = document.createElement('input');
        deleteButton.className = "redButton deleteIngredientButton";
        deleteButton.id = "deleteButton" + ingredientCount;
        deleteButton.setAttribute("value", "delete");
        deleteButton.setAttribute("type", "button");
        deleteButton.onclick = Function("removeIngredientById(" + ingredientCount + ");");
        ingredientDelete.appendChild(deleteButton);
        
        var ingredientQuantity = document.createElement('div');
        ingredientQuantity.className = "ingredientQuantity";
        ingredientContainer.appendChild(ingredientQuantity);
        
        var quantityLabel = document.createElement('div');
        quantityLabel.className = "inputLabel";
        quantityLabel.appendChild(document.createTextNode("Quantity:"));
        ingredientQuantity.appendChild(quantityLabel);
        
        var inputField = document.createElement('input');
        inputField.className = "inputField";
        inputField.name = "ingredientQuantity" + ingredientCount;
        inputField.id = "ingredientQuantity" + ingredientCount;
        inputField.value = quantity;
        ingredientQuantity.appendChild(inputField);
        
        
        var ingredientUnit = document.createElement('div');
        ingredientUnit.className = "ingredientUnit";
        ingredientContainer.appendChild(ingredientUnit);
        
        var unitLabel = document.createElement('div');
        unitLabel.className = "inputLabel";
        unitLabel.appendChild(document.createTextNode("Unit:"));
        ingredientUnit.appendChild(unitLabel);
        
        var unitSelect = document.createElement('select');
        unitSelect.className = "selectBox";
        unitSelect.name = "unitSelect" + ingredientCount;
        unitSelect.id = "unitSelect" + ingredientCount;
        unitSelect.setAttribute("title", "title");
        ingredientUnit.appendChild(unitSelect);
        
        
        var ingredientName = document.createElement('div');
        ingredientName.className = "ingredientName";
        ingredientContainer.appendChild(ingredientName);
        
        var nameLabel = document.createElement('div');
        nameLabel.className = "inputLabel";
        nameLabel.appendChild(document.createTextNode("Ingredient:"));
        ingredientName.appendChild(nameLabel);
        
        var nameInput = document.createElement('input');
        nameInput.className = "inputField required";
        nameInput.name = "ingredientName" + ingredientCount;
        nameInput.id = "ingredientName" + ingredientCount;
        nameInput.value = name;
        ingredientName.appendChild(nameInput);
        
        for(var i = 0; i < unitArray.length; i++)
        {
            var option = document.createElement('option');
            option.appendChild(document.createTextNode(unitArray[i]));
            option.setAttribute("value", i);
            unitSelect.appendChild(option);
        }
        
        if(unit != "")
        {
            unitSelect.selectedIndex = unit;
        }

    }
    // löscht Ingredient vom Recipe
    function removeIngredient()
    {
        if(ingredientCount > 0)
        {
            document.getElementById("ingredients").removeChild(document.getElementById("ingredientContainer" + ingredientCount));
            ingredientCount--;
            ingredientCount = Math.max(0, ingredientCount);
        }
    }
    // löscht Ingredient vom Recipe  je nach Id
    function removeIngredientById(id)
    {
        var child = document.getElementById("ingredientContainer" + id);
        document.getElementById("ingredients").removeChild(child);
        for(var i = id + 1; i <= ingredientCount; i++)
        {
            var container = document.getElementById("ingredientContainer" + i);
            container.id = "ingredientContainer" + (i-1);
            
            var deleteButton = document.getElementById("deleteButton" + i);
            deleteButton.setAttribute("id", "deleteButton" + (i-1));
            deleteButton.onclick = Function("removeIngredientById(" + (i-1) + ");");
            
            var quantity = document.getElementById("ingredientQuantity" + i);
            quantity.name = "ingredientQuantity" + (i-1);
            quantity.id = "ingredientQuantity" + (i-1);
            
            var unit = document.getElementById("unitSelect" + i);
            unit.setAttribute("name", "unitSelect" + (i-1));
            unit.setAttribute("id", "unitSelect" + (i-1));
            
            var name = document.getElementById("ingredientName" + i);
            name.setAttribute("name", "ingredientName" + (i-1));
            name.setAttribute("id", "ingredientName" + (i-1));
        }
        
        ingredientCount--;
    }
    // Lädt die Datei Recipe
    function goBackToRecipe()
    {
        if(currentRecipeId != 0)
        {
            onRecipeClick(currentRecipeId);
        }
    }
</script>

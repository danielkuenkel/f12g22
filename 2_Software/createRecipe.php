<!--
Autor: Daniel Kuenkle
Datum: 14.11.2012
Co-Autoren: Daniel Kuenkel
            Anne Moldt
            Florent Mepin

Diese Datei handhabt das Formular für Recipe erstellen, und fuegt die Daten in 
der Datenbank hinzu.
-->



<head>
    <script type="text/javascript" src="web/js/jquery.validate.js" charset="utf-8"></script>
    <style type="text/css">
        @import "web/css/createRecipe.css";
    </style>
    <script type="text/javascript">
    
        $('#recipeUpload').validate(
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
        <input type="button" onclick="goBackToRecipe()" id="goBackButton" class="greenButton" value="go back to recipe"/>
        <div class="defaultHeadline">
            create recipe
        </div>
        <div class="defaultText">
            Just be sure that it does not already have this recipe 
            here! We will delete duplicate recipes from our 
            database.
        </div>
        <div class="createRecipeFormContent">

            <form action="web/php/UploadRecipe.php" name="recipeUpload" id="recipeUpload" class="recipeUpload"
                  method="Post" enctype="multipart/form-data"> 
                <input class="inputField" name="updateRecipeId" id="updateRecipeId"/>

                <div class="inputLabel">Upload picture:</div>
                <input type="button" id="file" class="greenButton" value="select image"/>  
                <input type="file" class="inputFile" name="file"/>  

                <div class="inputLabel">Recipe name:*</div>
                <input class="inputField" name="title" id="recipeTitle"/>

                <div class="inputLabel">Short description:*</div>
                <input class="inputField required" name="abstract" id="abstract"/>

                <div class="inputLabel">Number of people:*</div>
                <input class="inputField" name="numberPeople" id="numberPeople"/>

                <div class="inputLabel">Recipe preperation:*</div>
                <textarea class="areaField" name="preparation" id="preparation"></textarea>   

                <div class="inputLabel">Preparation time:*</div>
                <input class="inputField" name="prepTime" id="prepTime"/>   

                <div class="inputLabel">Video url:</div>
                <input class="inputField" name="videoUrl" id="videoUrl"/>

                <div class="ingredients" id="ingredients"></div>
                <input type="button" id="addIngredientButton" onclick="addIngredient('', '', '')" class="greenButton" value="add Ingredient"/>
                <input type="button" id="removeIngredientButton" onclick="removeIngredient()" class="redButton" value="remove last Ingredient"/>

                <div class="inputLabel" id="categoryHeadline">Categories:</div>
                <div id="ingredientRecipeOptions">
                    <input type="radio" name="ingredients" value="meat">
                    <input type="radio" name="ingredients" value="fish">
                    <input type="radio" name="ingredients" value="vegetarian">
                    <input type="radio" name="ingredients" value="vegan">
                    <input type="radio" name="ingredients" value="exotic">

                    <input type="checkbox" name="courses" value="appetizer">
                    <input type="checkbox" name="courses" value="soup">
                    <input type="checkbox" name="courses" value="mainCourse">
                    <input type="checkbox" name="courses" value="garnish">
                    <input type="checkbox" name="courses" value="dessert">

                    <input type="radio" name="difficulties" value="simple">
                    <input type="radio" name="difficulties" value="medium">
                    <input type="radio" name="difficulties" value="heavy">

                    <input type="checkbox" name="seasons" value="spring">
                    <input type="checkbox" name="seasons" value="summer">
                    <input type="checkbox" name="seasons" value="autumn">
                    <input type="checkbox" name="seasons" value="winter">

                    <section id="catlinks" name="section">
                        <div class="radioRecipeIngredient">
                            <span class="filterCatHeadline">ingredient</span>
                            <a href="#" class="filterRadioButton" id="meat">Meat</a>
                            <a href="#" class="filterRadioButton" id="fish">Fish</a>
                            <a href="#" class="filterRadioButton" id="vegetarian">Vegetarian</a>
                            <a href="#" class="filterRadioButton" id="vegan">Vegan</a>
                            <a href="#" class="filterRadioButton" id="exotic">Exotic</a>
                        </div>

                        <div class="checkRecipeCourse">
                            <span class="filterCatHeadline">course</span>
                            <a href="#" class="filterRadioButton" id="appetizer">Appetizer</a>
                            <a href="#" class="filterRadioButton" id="soup">Soup</a>
                            <a href="#" class="filterRadioButton" id="mainCourse">Main Course</a>
                            <a href="#" class="filterRadioButton" id="garnish">Garnish</a>
                            <a href="#" class="filterRadioButton" id="dessert">Baking & Desserts</a>
                        </div>

                        <div class="radioRecipeDiff">
                            <span class="filterCatHeadline">difficulty</span>
                            <a href="#" class="filterRadioButton" id="simple">Simple</a>
                            <a href="#" class="filterRadioButton" id="medium">Medium</a>
                            <a href="#" class="filterRadioButton" id="heavy">Heavy</a>
                        </div>

                        <div class="checkRecipeSeason">
                            <span class="filterCatHeadline">season</span>
                            <a href="#" class="filterRadioButton" id="spring">Spring</a>
                            <a href="#" class="filterRadioButton" id="summer">Summer</a>
                            <a href="#" class="filterRadioButton" id="autumn">Autumn</a>
                            <a href="#" class="filterRadioButton" id="winter">Winter</a>
                        </div>
                    </section>
                </div>


                <input type="submit" id="submitButton"  class="greenButton"  value="submit"/>

            </form>
            <button class="redButton" id="resetCategoryButton" onclick="resetCategories()">reset categories</button>
        </div>
    </div>
</body>

<script type=text/javascript>
    
    var ingredientCount = 0;
    $(document).ready(function(){
        document.getElementById("updateRecipeId").value = "";
        
        if(!isRecipeEditable)
        {
            currentRecipeId = 0;
            addIngredient('', '', '');
            hideLoader();
        }
        else if(isRecipeEditable && editRecipeId != 0)
        {
            showLoader();
            document.getElementById("updateRecipeId").value = editRecipeId;
            request("GET", 'web/php/Recipe.php?recipeId=' + editRecipeId, true, "editRecipeValues");
        }
        
        var wrapper = $('<div/>').css({height:0,width:0,'overflow':'hidden'});
        var fileInput = $(':file').wrap(wrapper);

        fileInput.change(function(){
            $this = $(this);
            $('#file').text($this.val());
        })

        $('#file').click(function(){
            fileInput.click();
        }).show();
        
        
        $('#recipeUpload').submit(function(){ //listen for submit event
            var elements = $(".checkRecipeCourse a.sel");
            var filterKeys = "";
            
            for(var i=0; i < elements.length; i++)
            {
                var element = elements[i];
                filterKeys += element.id + ",";
            }
            
            elements = $(".checkRecipeSeason a.sel");
            
            for(i=0; i < elements.length; i++)
            {
                var element = elements[i];
                filterKeys += element.id + ",";
            }
            
            elements = $(".radioRecipeIngredient a.sel");
            
            for(i=0; i < elements.length; i++)
            {
                var element = elements[i];
                filterKeys += element.id + ",";
            }
            
            elements = $(".radioRecipeDiff a.sel");
            
            for(i=0; i < elements.length; i++)
            {
                var element = elements[i];
                filterKeys += element.id + ",";
            }
            
            filterKeys = filterKeys.substr(0, filterKeys.length-1);
            $('<input />').attr('type', 'hidden')
            .attr('name', "categories")
            .attr('value', filterKeys)
            .appendTo('#recipeUpload');
            return true;
        });
        
        
        $(".radioRecipeIngredient a").on("click", function(e){
            e.preventDefault();
		
            if($(this).hasClass("sel")) { 
                $(this).removeClass("sel");
            } else {
                $(".radioRecipeIngredient a.sel").removeClass("sel");
                $(this).addClass("sel");
			
                var rid = $(this).attr('id');
                var value = '[value="'+rid+'"]';
			
                $('input:radio[name="ingredients"]').filter(value).attr('checked', true);	
            }
        });
            
        $(".checkRecipeCourse a").on("click", function(e){
            e.preventDefault();
		
            if($(this).hasClass("sel")) {
                $(this).removeClass("sel");
            } else {
                $(this).addClass("sel");
			
                var rid = $(this).attr('id');
                var value = '[value="'+rid+'"]';
			
                $('input:radio[name="courses"]').filter(value).attr('checked', true);			
            }
        });
            
        $(".radioRecipeDiff a").on("click", function(e){
            e.preventDefault();
		
            if($(this).hasClass("sel")) {
                $(this).removeClass("sel");
            } else {
                $(".radioRecipeDiff a.sel").removeClass("sel");
                $(this).addClass("sel");
			
                var rid = $(this).attr('id');
                var value = '[value="'+rid+'"]';
			
                $('input:radio[name="difficulties"]').filter(value).attr('checked', true);			
            }
        });
             
        $(".checkRecipeSeason a").on("click", function(e){
            e.preventDefault();
		
            if($(this).hasClass("sel")) {
                $(this).removeClass("sel");
            } else {
                $(this).addClass("sel");
			
                var rid = $(this).attr('id');
                var value = '[value="'+rid+'"]';
			
                $('input:radio[name="seasons"]').filter(value).attr('checked', true);			
            }
        });
        
        if(currentRecipeId != 0)
        {
            document.getElementById("loginButton").style.display = "inline";
            document.getElementById("loginButton").style.visibility = "visible";
            
        }
        else
        {
            document.getElementById("loginButton").style.display = "none";
            document.getElementById("goBackButton").style.visibility = "hidden";
        }
    });
    // Initialisiert die Gatogorie Suche
    
    function resetCategories()
    {
        $(".radioRecipeIngredient a.sel").removeClass("sel");
        $(".checkRecipeCourse a.sel").removeClass("sel");
        $(".radioRecipeDiff a.sel").removeClass("sel");
        $(".checkRecipeSeason a.sel").removeClass("sel");
    }
        
    // fügt Ingredients hinzu
    
    function addIngredient(quantity, unit, name)
    {
        ingredientCount++;
        
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
        inputField.setAttribute("type", "text");
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
        inputField.setAttribute("type", "text");
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
    
    // löscht dei Ingredients
    function removeIngredient()
    {
        if(ingredientCount > 0)
        {
            document.getElementById("ingredients").removeChild(document.getElementById("ingredientContainer" + ingredientCount));
            ingredientCount--;
            ingredientCount = Math.max(0, ingredientCount);
        }
    }
    // löschen Ingredients je nach Id
    
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
    // lädt Recipe Seite
    
    function goBackToRecipe()
    {
        if(currentRecipeId != 0)
        {
            onRecipeClick(currentRecipeId);
        }
    }
</script>

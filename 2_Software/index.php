<?php
session_start();
if (isset($_SESSION['logon_name'])) {
    $_SESSION['loggedIn'] = 1;
} else {
    $_SESSION['loggedIn'] = 0;
}
?>
<!--
Autor: Daniel Malkmus
Co-Autoren: Daniel Kuenkel
            Anne Moldt
            Florent Mepin

Diese Datei handhabt die Aufrufe zu den einzelnen Seiten. Sei es über
direkten Aufruf oder über eine Ajax Anfrage.
-->

<html>
    <head>
        <title> Cooking Place</title>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">   
        <style type="text/css">
            @import "web/css/main.css";
            @import "web/css/searchResult.css";
            @import "web/css/recipe.css";
            @import "web/css/event.css";
            @import "web/css/form.css";
            @import "web/css/validate.css";
        </style>
        
        <script type="text/javascript" src="web/js/jquery-1.8.2.js" charset="utf-8"></script>
        <script type="text/javascript" src="web/js/toggleStates.js" charset="utf-8"></script>
        <script type="text/javascript" src="web/js/ajax.js" charset="utf-8"></script>
        <script type="text/javascript" src="web/js/page.js" charset="utf-8"></script>
        <script type="text/javascript" src="web/js/getParams.js" charset="utf-8"></script>
        <script type="text/javascript"
                src="https://maps.google.com/maps/api/js?sensor=true">
        </script>

    </head>

    <body>
        <div id="filterHeader">
            <div id="filterHeadlineWrapper">
                <div id="filterHeadline"><span class="filterHeadlineText">Browse recipes by filter</span></div>
            </div>
            <div id="filterOptions">
                <div id="ingredientOptions">
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

                    <section id="catlinks">
                        <div class="radioIngredient" id="radioIngredient">
                            <span class="filterCatHeadline">ingredient</span>
                            <a href="#" class="filterRadioButton" id="meatCat">Meat</a>
                            <a href="#" class="filterRadioButton" id="fishCat">Fish</a>
                            <a href="#" class="filterRadioButton" id="vegetarianCat">Vegetarian</a>
                            <a href="#" class="filterRadioButton" id="veganCat">Vegan</a>
                            <a href="#" class="filterRadioButton" id="exoticCat">Exotic</a>
                        </div>

                        <div class="checkCourse" id="checkCourse">
                            <span class="filterCatHeadline">course</span>
                            <a href="#" class="filterRadioButton" id="appetizerCat">Appetizer</a>
                            <a href="#" class="filterRadioButton" id="soupCat">Soup</a>
                            <a href="#" class="filterRadioButton" id="mainCourseCat">Main Course</a>
                            <a href="#" class="filterRadioButton" id="garnishCat">Garnish</a>
                            <a href="#" class="filterRadioButton" id="dessertCat">Baking & Desserts</a>
                        </div>

                        <div class="radioDiff" id="radioDiff">
                            <span class="filterCatHeadline">difficulty</span>
                            <a href="#" class="filterRadioButton" id="simpleCat">Simple</a>
                            <a href="#" class="filterRadioButton" id="mediumCat">Medium</a>
                            <a href="#" class="filterRadioButton" id="heavyCat">Heavy</a>
                        </div>

                        <div class="checkSeason" id="checkSeason">
                            <span class="filterCatHeadline">season</span>
                            <a href="#" class="filterRadioButton" id="springCat">Spring</a>
                            <a href="#" class="filterRadioButton" id="summerCat">Summer</a>
                            <a href="#" class="filterRadioButton" id="autumnCat">Autumn</a>
                            <a href="#" class="filterRadioButton" id="winterCat">Winter</a>
                        </div>
                    </section>
                    <div id="filterRecipeButtons">
                        <button class="greenButton" id="filterResetButton" onclick="filterSearch()">browse categories</button>
                        <button class="redButton" id="filterResetButton" onclick="resetFilter()">reset filter</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="header">
        <div id="logoHeader">
            <div id="logo" onclick="gotoWelcome();"></div>
        </div>
        <span class="greenSeperator" id="greenSperator"></span>

        <div id="headerBar">
            <div id="loginWrapper">
                <input id="loginButton" type="submit" class="greenButton"  value="login" onclick="toggleLoginForm()">
                <input id="accountButton" type="submit" class="greenButton" value="account" onclick="gotoAccount()">
            </div>

            <div id="keywordSearch">
                <input type="submit" id="toggleButton" value="" onclick="toggleFilterHeader()">
                <input id="search" type="text" name="search" value="Browse recipes by keyword">
                <input type="submit" id="loopbutton" value="" onclick="searchRequest()">
            </div>
        </div>
    </div>

    <div id="content">
    </div>

    <div id="footer">
        <div id="footerContent">
            <span style="color:#ffffff">cooking place</span> <span style="color:#9c9b9b">© copyright 2013</span>
            <div class="footerUrls">
                <a href="javascript:gotoImprint()">imprint</a>
                <a href="javascript:gotoContact()">contact</a>
                <a href="javascript:gotoFaq()">faq</a>
                <a href="javascript:scrollToTop()" id="topButton">Scroll to top</a>
            </div>
        </div>
    </div>


    <div id="sideBar">
        <button class="sideBarButton" id="buttonMyRecipes" onclick="gotoMyRecipes()"></button>
        <button class="sideBarButton" id="buttonFavoriteRecipes" onclick="gotoFavoriteRecipes()"></button>
        <button class="sideBarButton" id="buttonCreateRecipe" onclick="gotoCreateRecipe()"></button>
        <button class="sideBarButton" id="buttonBrowseEvents" onclick="gotoShowEvents()"></button>
        <button class="sideBarButton" id="buttonCreateEvent" onclick="gotoCreateEvent()"></button>
    </div>


    <div id="login">
        <div id="loginContent">
            <div id="loginForm">
                <form action="javascript:login();">
                    <p>
                        <input type="text" class="inputField" id="loginUser" value="Username">
                        <input type="password" class="inputField" id="loginPassword" value="Password"> 
                        <input type="submit" class="greenButton" id="loginSubmitButton" value="login">
                    </p>
                </form>
            </div>
            <div id="loginOptions">
                <input type="submit" class="greenButton" id="forgotButton" value="forgot?" onclick="gotoForgotPassword()">
                <input type="submit" class="greenButton" id="registerButton" value="register" onclick="gotoRegisterForm()">
                <input type="submit" class="redButton" id="closeLoginButton" value="close" onclick="hideLoginForm()">
            </div>
        </div>
    </div>

    <div id="preloader">
        <div id="loaderImage"></div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            checkSession();
            var hasVars = false;
            var recipeId = getURLParameters('recipeId');
            var eventId = getURLParameters('eventId');
            var activate = getURLParameters('activate');
            var logon = getURLParameters('logon');
            var error = getURLParameters('error');
            var order = getURLParameters('order');
            
                
            showLoader();
            request("GET", 'web/php/getUnit.php', true, "getUnits");
                
            if(recipeId != "")
            {
                showLoader();
                hasVars = true;
                request("GET", 'web/php/Recipe.php?recipeId=' + recipeId, true, "showRecipeById");
            }
            
            if(eventId != "")
            {
                showLoader();
                hasVars = true;
                showEvent(eventId);
            }
        
            if(activate != "" && logon != "")
            {
                hasVars = true;
                request("GET", 'web/php/Confirm.php?hash=' + activate + "&logon=" + logon, true, "activateAccount");
            }
 
            if(error != "")
            {
                hasVars = true;
                gotoUploadRecipeErrorPage();
            }
            
            if(order != "" && order == "success")
            {
                hasVars = true;
                gotoOrderSuccessPage();
            }
            
                      
            if(!hasVars)
            {
                gotoWelcome();
            }
            
           
            
            
            $(".radioIngredient a").on("click", function(e){
                e.preventDefault();
		
                if($(this).hasClass("sel")) { 
                    $(this).removeClass("sel");
                } else {
                    $(".radioIngredient a.sel").removeClass("sel");
                    $(this).addClass("sel");
			
                    var rid = $(this).attr('id');
                    var value = '[value="'+rid+'"]';
			
                    $('input:radio[name="ingredients"]').filter(value).attr('checked', true);			
                }
            });
            
            $(".checkCourse a").on("click", function(e){
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
            
            $(".radioDiff a").on("click", function(e){
                e.preventDefault();
		
                if($(this).hasClass("sel")) { 
                    $(this).removeClass("sel");
                } else {
                    $(".radioDiff a.sel").removeClass("sel");
                    $(this).addClass("sel");
			
                    var rid = $(this).attr('id');
                    var value = '[value="'+rid+'"]';
			
                    $('input:radio[name="difficulties"]').filter(value).attr('checked', true);			
                }
            });
             
            $(".checkSeason a").on("click", function(e){
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
        });
      
      
        $("#search").focusin(function(){
            $(this).val("");
        });
        $("#search").focusout(function(){
            var value = $(this).val();
            if (value == ""){
                $(this).val("Browse recipes by keyword");  
            }
        });
            
        $("#loginUser").focusin(function(){
            $(this).val("");
        });
        $("#loginUser").focusout(function(){
            var value = $(this).val();
            if (value == ""){
                $(this).val("Username");  
            }
        });
            
        $("#loginPassword").focusin(function(){
            $(this).val("");
        });
        $("#loginPassword").focusout(function(){
            var value = $(this).val();
            if (value == ""){
                $(this).val("Password");  
            }
        });
        // schickt die Suche ab
        function searchRequest()
        {
            var keyword = document.getElementById("search").value;
            var tempWord = keyword.replace(/\s/g,'');
            if(tempWord.length > 0 && keyword != "Browse recipes by keyword")
            {
                searchByKeyword(keyword);
            }
        }
        // initialisiert der Suche-filter
        function resetFilter()
        {
            $(".radioIngredient a.sel").removeClass("sel");
            $(".checkCourse a.sel").removeClass("sel");
            $(".radioDiff a.sel").removeClass("sel");
            $(".checkSeason a.sel").removeClass("sel");
        }
        
        function KeyCode(keyEvent)
        {
            if(keyEvent)
            {
                keyValue = keyEvent.which;
            }
            else
            {
                keyValue= window.event.keyCode;

            }

            if (keyValue ==13)
            {
                searchRequest();
            }
        }
        document.getElementById("search").onkeypress = KeyCode; 
          // Ermöglich sich einzulogen  
        function login()
        {
            showLoader();
            var logon = document.getElementById("loginUser").value;
            var password = document.getElementById("loginPassword").value;
            request("GET", "web/php/Login.php?logon=" + logon + "&password=" + password, true, "login");
        }
            // Prüft die Session
        function checkSession()
        {
            loggedIn = <?php echo $_SESSION['loggedIn'] ?>;
            if(loggedIn == 1)
            {
                showAccountButton(); 
            }
            else
            {
                hideAccountButton();
            }
        }
        // implementiert die Filter fuer die Suche per Keywort
        function filterSearch()
        {
            var elements = document.getElementById('catlinks').getElementsByTagName('a');
            var filterKeys = "";
            
            for(var i=0; i<elements.length; i++)
            {
                var element = elements[i];
                var rid = $(element).attr('id');
                if($(element).hasClass('sel'))
                {
                    filterKeys += rid.substr(0, rid.length-3) + ",";
                }
            }
            filterKeys = filterKeys.substr(0, filterKeys.length-1);
            searchByFilter(filterKeys);
        }
        
        // Springt die Seite total nach Oben
        function scrollToTop()
        {
            $('html, body').animate({scrollTop:0}, 100);
        }
    </script>
</body>
</html>


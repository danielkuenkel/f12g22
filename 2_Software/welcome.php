<!--
   Autor: Daniel, Künkel
   Datum: 15.11.2012
   PHP Script welches den Willkommenstext und Buttons für den Java-Teil
    beinhaltet
-->

<div id="randomRecipeContent">

</div>
<div class="contentWrapper welcomeContent">
    <div class="shadowedSection">
        <div class="defaultHeadline welcomeHeadline">
            welcome to cooking place
        </div>
        <div class="defaultText welcomeText">
            This is an interactive cooking community, which it has 
            taken on the task, quickly and easily find and share 
            recipes.
            <br/><br/>
            This is a Student Project and was developed as a part of 
            the master project from a group of students at the 
            University of Fulda.
            <input type="submit" class="greenButton" id="welcomeFaqButton" value="more informations" onclick="gotoFaq()"/>
        </div>
    </div>
    <br/><br/>
    <div class="defaultHeadline welcomeHeadline">
        java ee
    </div>
    <div class="defaultText welcomeText">
        This are local Java EE implementations
        <br/><br/>
        <input type="submit" class="greenButton brightButton" value="update user via JDBC" onclick="onUpdateUserClick()"/><br/><br/>
        <input type="submit" class="greenButton brightButton" value="save recipes to log file" onclick="onSaveRecipesClick()"/><br/><br/>
        <input type="submit" class="greenButton brightButton" value="register user with entity" onclick="onRegisterUserClick()"/><br/><br/>
        <input type="submit" class="greenButton brightButton" value="calculate average of all votings" onclick="onCalcAverageClick()"/>
    </div>
</div>

<script type="text/javascript">
    // Ajax Request
    $(document).ready(function(){
        request("GET", "web/php/GetRandomRecipe.php", true, "getRandomRecipe");
    });
    
    function onRegisterUserClick()
    {
        registerUserWithJavaEE=true;
        gotoRegisterForm();
    }
    
    function onSaveRecipesClick()
    {
        window.open("http://localhost:17232/CookingPlaceJavaEEDanielM/SaveServerLogServlet"); 
    }
    
    function onUpdateUserClick()
    {
        updateUserWithJavaEE = true;
        if(loggedIn)
        {
            gotoAccount();
        }
        else
        {
            gotoOverview();
        }
    }
    
    function onCalcAverageClick()
    {
        window.open("http://localhost:8080/EJB/index.jsp");
    }
</script>
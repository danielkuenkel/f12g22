<!--
Autor: Daniel, Kuenkel
Co-Autor: Moldt, Anne
Datum: 15.11.2012
Co-Autoren: 
  Hier ist das Registrirungsformal zusammengesetzt.   
-->

<head>
    <script type="text/javascript" src="web/js/jquery.validate.js" charset="utf-8"></script>
    <style type="text/css">
        @import "web/css/register.css";
    </style>

    <script>
        $('#registerForm').validate(
        {
            errorClass: 'myerror',
            validClass: 'mysuccess',
            rules: {
                forename: "required", 
                surname: "required", 
                logon: "required", 
                password: "required",
                mail: { required: true, email: true },
                street: "required", 
                housenumber: "required",
                zip: "required",
                city: "required"
            },
            errorPlacement: $.noop
        });
    </script>
</head>
<div class="contentWrapper">
    <div class="defaultHeadline">
        Register
    </div>
    <div class="defaultText">
        <p>
            With its free Web "Cooking Place" account gives you access to 
            all major functions such as uploading and sharing of recipes, 
            commenting on recipes and reviewing other recipes.
        </p>
        <br/>
        <p>
            You will arrange to meet with strangers or friends to a common 
            cooking event and can so foreign cultures and learn about eating 
            habits.
        </p>
    </div>
    <br/><br/>
    <div class="registerFormContent">
        <form action="javascript:registerUser()" id="registerForm" method="get"> 
            <div class="inputLine" id="forenameInput">
                <div class="inputLabel">Forename:*</div>
                <input class="inputField required" id="forename" name="forename" minlength="2"/>
            </div>
            <div class="inputLine" id="surenameInput">
                <div class="inputLabel">Surname:*</div>
                <input class="inputField" id="surname" name="surname"/>
            </div>
            <div class="inputLabel">Login name:*</div>
            <input class="inputField" id="logon" name="logon"/>

            <div class="inputLabel">Login password:*</div>
            <input type="password" class="inputField" id="password" name="password"/>

            <div class="inputLabel">E-mail address:*</div>
            <input class="inputField" id="mail" name="mail"/>

            <div class="inputLine" id="streetInput">
                <div class="inputLabel">Street:*</div>
                <input class="inputField" id="street" name="street"/>
            </div>

            <div class="inputLine" id="houseNumberInput">
                <div class="inputLabel">House number:*</div>
                <input class="inputField" id="housenumber" name="housenumber"/>
            </div>

            <div class="inputLine" id="zipInput">
                <div class="inputLabel">Zipcode:*</div>
                <input class="inputField" id="zip" name="zip" number="true" maxlength="5"/>
            </div>
            <div class="inputLine" id="cityInput">
                <div class="inputLabel">City:*</div>
                <input class="inputField" id="city" name="city"/>
            </div>

            <div class="inputLabel">Phone number:</div>
            <input class="inputField" id="phone"/>
            <p class="required">* required</p>
            <input class="greenButton" id="registerUserButton" type="submit" value="submit"/>
        </form>
    </div>
    <div id="errors"></div>
</div>
<script type="text/javascript">
    
    //fuegt die Daten in der Datenbank
    function registerUser()
    {
        showLoader();
        var parameters = "forename=" + document.getElementById("forename").value + "&" +
            "surname=" + document.getElementById("surname").value + "&" +
            "logon=" + document.getElementById("logon").value  + "&" +
            "password=" + document.getElementById("password").value  + "&" +
            "mail=" + document.getElementById("mail").value  + "&" +
            "street=" + document.getElementById("street").value  + "&" +
            "housenumber=" + document.getElementById("housenumber").value  + "&" +
            "zip=" + document.getElementById("zip").value  + "&" +
            "city=" + document.getElementById("city").value  + "&" +
            "phone=" + document.getElementById("phone").value;
        if(registerUserWithJavaEE==false)
        {
            request("Get", "web/php/RegisterUser.php?" + parameters, true, "registerUser");
        }
        else
        {
            request("Get", "http://localhost:8080/CookingPlaceJavaEEAnne/RegisterUserServlet?" + parameters, true, "registerUser");
        }
    }
</script>
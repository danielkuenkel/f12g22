<!--
Autor: Daniel, Kuenkel
Datum: 15.11.2012
Co-Autoren: 
 PHP  Datei zur Empfelung einer Registrierung      
-->

<head>
    <style type="text/css">
        @import "web/css/overview.css";
    </style>
</head>
<div class="contentWrapper">
    <div class="defaultHeadline">
        new here?
    </div>
    <div class="defaultText">
        <p>
            You're new and do not have an Acccount? Sign up quickly, so you can 
            use all the great features of cooking place.
        </p>
        <br/>
        <p>
            Or login if you already have an account with cooking place.
        </p>
        <br/><br/>
        <div>
            <input type="submit" class="greenButton" id="ovFaqButton" value="more information" onclick="gotoFaq()"/>
            <input type="submit" class="greenButton" id="ovLoginButton" value="login" onclick="showLoginForm()"/>
            <input type="submit" class="greenButton" id="ovRegisterButton" value="register" onclick="gotoRegisterForm()"/>
        </div>
    </div>
</div>
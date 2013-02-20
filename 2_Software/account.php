<?php
session_start();
?>
<!--
Autor: Daniel Kuenkel
Datum: 17.11.12
-->
<head>
    <style type="text/css">
        @import "web/css/account.css";
    </style>
</head>
<div class="contentWrapper">
    <div id="updateLayer">
        <div id="updateSuccessLayer">
            Everything updated!
            <br/><br/>
            <input class="greenButton" id="successOkButton" type="submit" value="ok" onclick="hideSuccessLayer()"/>
        </div>
        <div id="updateFailLayer">
            Ooops. Error!
            <br/><br/>
            <input class="greenButton" id="successOkButton" type="submit" value="ok" onclick="hideSuccessLayer()"/>
        </div>
    </div>

    <div class="defaultHeadline">
        your account
    </div>
    <div class="defaultText">
        Hey <?php echo $_SESSION['forename']; ?> <?php echo $_SESSION['surname']; ?> (alias <?php echo $_SESSION['logon_name']; ?>)! Here you can view and 
        change your personal information.
    </div>
    <br/><br/>
    <div class="accountFormContent">
        <form id="accountForm"> 
            <div class="inputLine" id="forenameInput">
                <div class="inputLabel">Forename:</div>
                <input class="inputField" id="forename" value="<?php echo $_SESSION['forename'] ?>" disabled/>
            </div>
            <div class="inputLine" id="surenameInput">
                <div class="inputLabel">Surname:</div>
                <input class="inputField" id="surname" value="<?php echo $_SESSION['surname'] ?>" disabled/>
            </div>

            <div id="passwordInput">
                <div class="inputLabel">New login password:</div>
                <input type="password" class="inputField" id="password" disabled/>
            </div>

            <div class="inputLine" id="streetInput">
                <div class="inputLabel">Street:</div>
                <input class="inputField" id="street" value="<?php echo $_SESSION['street'] ?>" disabled/>
            </div>
            <div class="inputLine" id="houseNumberInput">
                <div class="inputLabel">House number:</div>
                <input class="inputField" id="housenumber" value="<?php echo $_SESSION['house_number'] ?>" disabled/>
            </div>

            <div class="inputLine" id="zipInput">
                <div class="inputLabel">Zipcode:</div>
                <input class="inputField" id="zip" value="<?php echo $_SESSION['zip'] ?>" disabled/>
            </div>
            <div class="inputLine" id="cityInput">
                <div class="inputLabel">City:</div>
                <input class="inputField" id="city" value="<?php echo $_SESSION['city'] ?>" disabled/>
            </div>

            <div class="inputLabel">Phone number:</div>
            <input class="inputField" id="phone" value="<?php echo $_SESSION['phone_number'] ?>" disabled/>
        </form>
        <input id="logoutButton" type="submit" class="redButton" value="logout" onclick="logout()"/>
        <input class="greenButton" id="saveUserButton" type="submit" value="save" onclick="updateUser()"/>
        <input class="redButton" id="cancelSaveButton" type="submit" value="cancel" onclick="cancelSave()"/>
        <input class="greenButton" id="editUserButton" type="submit" value="update profile" onclick="editUser()"/>
    </div>
</div>
<script type="text/javascript">
    function logout()
    {
        request("post", "web/php/Logout.php", true, "logout");
        loggedIn = false;
        gotoWelcome();
    }
    function updateUser()
    {
        showLoader();
        var parameters = "forename=" + document.getElementById("forename").value + "&" +
            "surname=" + document.getElementById("surname").value + "&" +
            "password=" + document.getElementById("password").value  + "&" +
            "street=" + document.getElementById("street").value  + "&" +
            "housenumber=" + document.getElementById("housenumber").value  + "&" +
            "zip=" + document.getElementById("zip").value  + "&" +
            "city=" + document.getElementById("city").value  + "&" +
            "phone=" + document.getElementById("phone").value  + "&";
        
        if(updateUserWithJavaEE == false)
        {
            request("Get", "web/php/UpdateUser.php?" + parameters, true, "updateUser");
        }
        else
        {
            var userId = <?php echo $_SESSION['user_id'] ?>;
            parameters += "&userId=" + userId;
            request("Get", "http://localhost:8080/CookingPlaceJavaEEDanielK/UpdateUserServlet?" + parameters, true, "updateUser");
        }
        
    }
    
    function cancelSave()
    {
        hideUpdateButtons();
    }
    
    function editUser()
    {
        hideSuccessLayer();
        showUpdateButtons();
    }
    
    function showUpdateButtons()
    {
        enableInputFields();
        document.getElementById("saveUserButton").style.display = "inline";
        document.getElementById("saveUserButton").style.visibility = "visible";
        document.getElementById("cancelSaveButton").style.display = "inline";
        document.getElementById("cancelSaveButton").style.visibility = "visible";
        document.getElementById("editUserButton").style.display = "none";
        document.getElementById("editUserButton").style.visibility = "hidden";
    }
    function hideUpdateButtons()
    {
        disableInputFields();
        document.getElementById("cancelSaveButton").style.display = "none";
        document.getElementById("cancelSaveButton").style.visibility = "hidden";
        document.getElementById("saveUserButton").style.display = "none";
        document.getElementById("saveUserButton").style.visibility = "hidden";
        document.getElementById("editUserButton").style.display = "inline";
        document.getElementById("editUserButton").style.visibility = "visible";
    }
    
    function enableInputFields()
    {
        document.getElementById("forename").disabled = false;
        document.getElementById("surname").disabled = false;
        document.getElementById("password").disabled = false;
        document.getElementById("passwordInput").style.display = "inline";
        document.getElementById("passwordInput").style.visibility = "visible";
        document.getElementById("street").disabled = false;
        document.getElementById("housenumber").disabled = false;
        document.getElementById("zip").disabled = false;
        document.getElementById("city").disabled = false;
        document.getElementById("phone").disabled = false;
    }
    
    function disableInputFields()
    {
        document.getElementById("forename").disabled = true;
        document.getElementById("surname").disabled = true;
        document.getElementById("password").disabled = true;
        document.getElementById("passwordInput").style.display = "none";
        document.getElementById("passwordInput").style.visibility = "hidden";
        document.getElementById("street").disabled = true;
        document.getElementById("housenumber").disabled = true;
        document.getElementById("zip").disabled = true;
        document.getElementById("city").disabled = true;
        document.getElementById("phone").disabled = true;
    }
    
    function showSuccessLayer()
    {
        hideUpdateButtons();
        document.getElementById("updateLayer").style.display = "table";
        document.getElementById("updateLayer").style.visibility = "visible";
        document.getElementById("updateSuccessLayer").style.display = "inline";
        document.getElementById("updateSuccessLayer").style.visibility = "visible";
        document.getElementById("updateFailLayer").style.display = "none";
        document.getElementById("updateFailLayer").style.visibility = "hidden";
    }
    
    function hideSuccessLayer()
    {
        document.getElementById("updateLayer").style.display = "none";
        document.getElementById("updateLayer").style.visibility = "hidden";
    }
    
    function showFailLayer()
    {
        document.getElementById("updateLayer").style.display = "table";
        document.getElementById("updateLayer").style.visibility = "visible";
        document.getElementById("updateSuccessLayer").style.display = "none";
        document.getElementById("updateSuccessLayer").style.visibility = "hidden";
        document.getElementById("updateFailLayer").style.display = "inline";
        document.getElementById("updateFailLayer").style.visibility = "visible";
    }
</script>
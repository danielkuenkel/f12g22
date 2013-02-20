<!--
Autor: Daniel, Malkmus
Datum: 22.11.2012
Co-Autoren: Daniel Kuenkel
            Anne Moldt
Hier wird als php script die "Funktion password vergessen"   implementiert      
-->

 <head>
     
    <script type="text/javascript" src="web/js/jquery.validate.js" charset="utf-8"></script>
    <style type="text/css">
        @import "web/css/overview.css";
    </style>
    <script>
        $('#forgotPWForm').validate(
        {
            errorClass: 'myerror',
            validClass: 'mysuccess',
            rules: {
                mail: { required: true, email: true }
            },
            errorPlacement: $.noop
        });
        
    </script>
    <script>
        function forgotPassword()
        {
            var sendmail =  "forgotMail=" + document.getElementById("forgotMail").value + "&"; 
            request("Get", "web/php/forgotPWMail.php?" + sendmail, true, "forgotPassword");
        }
    </script>

</head>
<div class="contentWrapper">
    <div class="defaultHeadline">
        Forgot password?
    </div>
    <div class="defaultText">
        <p>
            You have forgot your password?
        </p>
        <br/>
        <p>
            Please enter your Emailaddress.
        </p>
        <br/>
        <div id="errorsPW"></div>
        <br/><br/>
        <div class="forgotPWFomContent">
            <form action="javascript:forgotPassword()" id="forgotPWForm">
                <div class="inputLabel">E-mail address:</div>
                <input class="inputField" id="forgotMail" name="mail"/>
                <input type="submit" class="greenButton" id="forgotButtonButton" value="send"/>
            </form>
        </div>

    </div>
</div>
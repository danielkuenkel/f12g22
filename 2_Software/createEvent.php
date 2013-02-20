<?php
session_start();
?>
<head>
    <script type="text/javascript" src="web/js/jquery.validate.js" charset="utf-8"></script>
    <script type="text/javascript" src="web/js/datePicker.js"></script>
    <script type="text/javascript" src="web/js/date.js"></script>
    <style type="text/css">
        @import "web/css/createRecipe.css";
        @import "web/css/datepicker.css";
    </style>
    <script type="text/javascript">
    
        $('#eventUpload').validate(
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
        <div class="defaultHeadline">
            create event
        </div>
        <div class="defaultText">
            create an event to cook with our members of our community!
        </div>
        <div class="createRecipeFormContent">

            <form action="javascript:onCreateEvent()" name="eventUpload" id="eventUpload" class="recipeUpload"
                  method="Post"> 
                <input class="inputField" name="updateEventId" id="updateEventId"/>

                <div class="inputLabel">Event title:*</div>
                <input class="inputField required" name="eventTitle" id="eventTitle"/>

                <div class="inputLabel">max. Persons (including you!):*</div>
                <input class="inputField required" name="maxPersons" id="maxPersons"/>

                <div class="inputLabel">Cost:*</div>
                <input class="inputField required" name="cost" id="cost"/>

                <div class="inputLabel">Date:*</div>
                <input class="inputField datepicker required" id="datepicker" name="date" disabled/>

                <div class="inputLabel">Hour:*</div>
                <input class="inputField" name="hour" id="hour">

                <div class="inputLabel">Minute:*</div>
                <input class="inputField" name="minute" id="minute">

                <div class="inputLabel">Street:*</div>
                <input class="inputField" name="street" id="street" value=""/>   

                <div class="inputLabel">House number:*</div>
                <input class="inputField" name="housenumber" id="housenumber" value=""/>   

                <div class="inputLabel">Zipcode:*</div>
                <input class="inputField" name="zipcode" id="zipcode" value=""/>

                <div class="inputLabel">City:*</div>
                <input class="inputField" name="city" id="city" value=""/>

                <div class="inputLabel">Short description:*</div>
                <textarea class="areaField" name="description" id="description"></textarea>

                <input type="submit" id="submitButton"  class="greenButton"  value="submit"/>

            </form>
        </div>
    </div>

    <script type="text/javascript">  
        $(document).ready(function() {
            $(".datepicker").datePicker();
            document.getElementById("updateEventId").value = "";
        
            if(!isEventEditable)
            {
                currentEventId = 0;
                hideLoader();
            }
            else if(isEventEditable && editEventId != 0)
            {
                showLoader();
                document.getElementById("updateEventId").value = editEventId;
                request("GET", 'web/php/Event.php?eventId=' + editEventId, true, "editEventValues");
            }
        });
              
        function onCreateEvent()
        {
            showLoader();
            var parameter = "eventTitle=" + document.getElementById("eventTitle").value + "&" +
                "maxPersons=" + document.getElementById("maxPersons").value + "&" + 
                "cost=" + document.getElementById("cost").value + "&" + 
                "street=" + document.getElementById("street").value + "&" + 
                "housenumber=" + document.getElementById("housenumber").value + "&" + 
                "zipcode=" + document.getElementById("zipcode").value + "&" + 
                "city=" + document.getElementById("city").value + "&" + 
                "description=" + document.getElementById("description").value + "&" + 
                "date=" + document.getElementById("datepicker").value + "&" + 
                "hour=" + document.getElementById("hour").value + "&" + 
                "minute=" + document.getElementById("minute").value + "&" +
                "updateEventId=";
            
            if(isEventEditable)
            {
                parameter += document.getElementById("updateEventId").value;
            }
            
            request("Get", "web/php/UploadEvent.php?" + parameter, true, "createEvent");
        }
    </script>

</body>


<!--<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
</head>-->
<body>
    <div class="eventContentWrapper">
        <div id="searchForEventsArea">
            <div id="eventFilterOptions">
                <div id="ingredientOptions">
                    <input type="radio" name="searchType" value="allEvents">
                    <input type="radio" name="searchType" value="myEvents">
                    <input type="radio" name="searchType" value="joinedEvents">

                    <section id="catEventlinks">
                        <div class="radioBrowseEvents" id="radioBrowseEvents">
                            <a href="#" class="filterEventRadioButton" id="allEvents">Show All Events</a>
                            <a href="#" class="filterEventRadioButton" id="myEvents">Show My Events</a>
                            <a href="#" class="filterEventRadioButton" id="joinedEvents">Show Joined Events</a>
                        </div>
                    </section>
                </div>
            </div>
            <div id="browseEventContainer">
                <input type="text" class="inputField" id="zipInput" value="Browse via Zipcode">
                <div id="browseEventsButton" onclick="showEventsByZipcode()"><div id="browseEventsButtonImage"></div></div>
            </div>
            <div id="resultHeadline">
                <div class="resultHeadline" id="zipHeadline">zip</div>
                <div class="resultHeadline" id="cityHeadline">city</div>
                <div class="resultHeadline" id="dateHeadline">date</div>
                <div class="resultHeadline" id="titleHeadline">title</div>
            </div>
        </div>
        <div id="searchForEventsResults"></div>
        <!--        <div id="map_canvas" style="width:430px; height:430px; visibility:hidden"></div>       -->
    </div>
</body>

<script type="text/javascript">
    $(document).ready(function(){
        document.getElementById('allEvents').classList.add("sel");
        showAllEvents("");
        
        
        $(".radioBrowseEvents a").on("click", function(e){
            e.preventDefault();
            
            if($(this).hasClass("sel")) { 
                
            } else {
                $(".radioBrowseEvents a.sel").removeClass("sel");
                $(this).addClass("sel");
                
                var rid = $(this).attr('id');
                var value = '[value="'+rid+'"]';
                
                $('input:radio[name="searchType"]').filter(value).attr('checked', true);
                
                switch(rid)
                {
                    case "myEvents":
                        showMyEvents("");
                        break;
                    case "joinedEvents":
                        showJoinedEvents("");
                        break;
                    default:
                        showAllEvents("");
                        break;
                }
            }
        });
    });

    
    function showAllEvents(zipcode){
        showLoader();
        request("Get", "web/php/SearchEvent.php?type=all&zipcode=" + zipcode, true, "searchEvent");
    }
    
    function showMyEvents(zipcode){
        showLoader();
        request("Get", "web/php/SearchEvent.php?type=my&zipcode=" + zipcode, true, "searchEvent");
    }
    
    function showJoinedEvents(zipcode){
        showLoader();
        request("Get", "web/php/SearchEvent.php?type=joined&zipcode=" + zipcode, true, "searchEvent");
    }
    
    function showEventsByZipcode()
    {      
        var elements = document.getElementById('catEventlinks').getElementsByTagName('a');
        var filterKeys = "";
            
        for(var i=0; i<elements.length; i++)
        {
            var element = elements[i];
            var rid = $(element).attr('id');
            if($(element).hasClass('sel'))
            {
                filterKeys = rid;
                break;
            }
        }
        
        var zipcode = document.getElementById("zipInput").value;
        var tempWord = zipcode.replace(/\s/g,'');
        if(tempWord.length > 0 && zipcode != "Browse via Zipcode")
        {
            switch(filterKeys)
            {
                case "myEvents":
                    showMyEvents(zipcode);
                    break;
                case "joinedEvents":
                    showJoinedEvents(zipcode);
                    break;
                case "allEvents":
                    showAllEvents(zipcode);
                    break;
                default:
                    showAllEvents("");
                    break;
            }
        }
        else
        {
            switch(filterKeys)
            {
                case "myEvents":
                    showMyEvents("");
                    break;
                case "joinedEvents":
                    showJoinedEvents("");
                    break;

                default:
                    showAllEvents("");
                    break;
            }
        }
    }
   
   
    $("#zipInput").focusin(function(){
        $(this).val("");
    });
    
    $("#zipInput").focusout(function(){
        var value = $(this).val();
        if (value == ""){
            $(this).val("Browse via Zipcode");  
        }
    });
</script>
/*
 * Autor: Daniel Kuenkel
 * Datum: 15.11.2012
 *
 * Co-Autoren:  Daniel Malkmus
 *              Anne Moldt
 *              Florent Mepin
 *              
 * Java Script Datei die die verschiednen Seitenaufrufe bearbeitet
 */ 

/* --- Funktionen --------------------------------------------------------------
 * gotoAccount()                - Lädt account.php
 * gotoActivateExists()         - Lädt activationExists.php
 * gotoActivateFailed()         - Lädt activationFail.php
 * gotoActivateSuccess()        - Lädt activationSuccess.php
 * gotoContact()                - Lädt contact.php
 * gotoCreateRecipe()           - Lädt createRecipe.php
 * gotoCreateEvent()            - Lädt createEvent.php
 * gotoEditRecipe(recipeId)     - Lädt createRecipe.php
 * gotoEmailSuccessPage()       - Lädt emailSuccess.php
 * gotoFaq()                    - Lädt faq.php
 * gotoFavoriteRecipe()         - Sendet Ajax request an GetMyFavorites.php
 * gotoForgotPassword()         - Lädt forgotPW.php
 * gotoImprint()                - Lädt imprint.php
 * gotMyRecipes()               - Sendet Ajax request an GetMyRecipes.php
 * gotoOrderIngredientsForm()   - Lädt orderIngredientsForm.php
 * gotoOverview()               - Lädt overview.php
 * gotoPasswordResetSuccess()   - Lädt passwordResetSuccess.php
 * gotoPrintPage(recipeID)      - Öffnet ein neues Fenster printPage.php mit id
 * gotoRegisterForm()           - Lädt register.php
 * gotoRegisterSuccess()        - Lädt registerSuccess.php
 * gotoShowEvents()             - Lädt showEvents.php
 * gotoWelcome()                - Lädt welcome.php
 * hideLoader()                 - Blendet Ladesequenz aus
 * showLoader()                 - Blendet Ledesequenz ein
 */
/* --- Historie ----------------------------------------------------------------
 * Daniel Künkel    (v1.0)      – Datei hinzugefügt mit einigen Funktionen
 *                              - Weitere Funktionen für Seiten hinzugefügt
 * Daniel Malkmus   (v1.1)      - Weitere Funktionen für Seiten hinzugefügt
 * Florent Mepin    (v1.2)      - Weitere Funktionen für Seiten hinzugefügt 
 */
//------------------------------------------------------------------------------
/* --- Funktionsbeschreibung ---------------------------------------------------
 * Diese Datei handhabt die Aufrufe zu den einzelnen Seiten. Sei es über
 * direkten Aufruf oder über eine Ajax Anfrage.
 * ---------------------------------------------------------------------------*/  


loggedIn = false;
isRecipeEditable = false;
editRecipeId = 0;
isEventEditable = false;
editEventId = 0;

function gotoWelcome()
{
    resetCurrentVariables();
    hideLoginForm();
    $("#content").load("welcome.php");
}

function gotoCreateRecipe()
{
    resetCurrentVariables();
    if(loggedIn)
    {
        isRecipeEditable = false;
        editRecipeId = 0;
        $("#content").load("createRecipe.php");
    }
    else
    {
        gotoOverview();
    }
}
function gotoMyRecipes()
{
    resetCurrentVariables();
    if(loggedIn)
    {
        showLoader();
        request("GET", 'web/php/GetMyRecipes.php?', true, "seachByKeyword");
    }
    else
    {
        gotoOverview();
    }
}
function gotoFavoriteRecipes()
{
    resetCurrentVariables();
    if(loggedIn)
    {
        showLoader();
        request("GET", 'web/php/GetMyFavorites.php?', true, "seachByKeyword");
    }
    else
    {
        gotoOverview();
    }
}

function gotoPrintPage(recipId)
{
    window.open("printPage.php?recipeId=" + recipId,"Printversion");
}

function gotoAccount()
{
    resetCurrentVariables();
    $("#content").load("account.php");
}
function gotoOverview()
{
    resetCurrentVariables();
    hideLoginForm();
    $("#content").load("overview.php");
}
function gotoRegisterForm()
{
    resetCurrentVariables();
    hideLoginForm();
    $("#content").load("register.php");
}
function gotoRegisterSuccess()
{
    resetCurrentVariables();
    hideLoginForm();
    $("#content").load("registerSuccess.php");
}
function gotoActivateSuccess()
{
    resetCurrentVariables();
    hideLoginForm();
    $("#content").load("activationSuccess.php");
}
function gotoActivateExists()
{
    resetCurrentVariables();
    hideLoginForm();
    $("#content").load("activationExists.php");
}
function gotoActivateFailed() 
{
    resetCurrentVariables();
    hideLoginForm();
    $("#content").load("activationFail.php");
}
function gotoNotActivated()
{
    resetCurrentVariables();
    hideLoginForm();
    $("#content").load("activationNot.php");
}
function gotoUploadRecipeErrorPage()
{
    hideLoginForm();
    $("#content").load("uploadError.php");
}
function gotoEditRecipe(recipeId)
{
    isRecipeEditable = true;
    editRecipeId = recipeId;
    $("#content").load("createRecipe.php");
}
function gotoOrderIngredientsForm()
{
    $("#content").load("orderIngredientsForm.php");
}

function showLoader()
{
    document.getElementById("preloader").style.visibility = "visible";
}

function hideLoader()
{
    document.getElementById("preloader").style.visibility = "hidden";
}

function gotoForgotPassword()
{
    resetCurrentVariables();
    hideLoginForm();
    $("#content").load("forgotPW.php");
}

function gotoPasswordResetSuccess()
{
    $("#content").load("passwordResetSuccess.php");
}

function gotoContact()
{
    resetCurrentVariables();
    $("#content").load("contact.php");
}
function gotoFaq()
{
    resetCurrentVariables();
    $("#content").load("faq.php");
   
}
function gotoImprint()
{
    resetCurrentVariables();
    $("#content").load("imprint.php");
}

function gotoOrderSuccessPage()
{
    $("#content").load("orderSuccess.php");
}

function resetCurrentVariables()
{
    currentRecipeId = 0;
    currentFilter = "";
    currentKeyword = "";
}

function gotoShowEvents()
{
    if(loggedIn)
    {
      //  showLoader();
      //  request("GET", 'web/php/GetMyFavorites.php?', true, "seachByKeyword");
        $("#content").load("showEvents.php");
    }
    else
    {
        gotoOverview();
    }   
}

function gotoCreateEvent()
{
    isEventEditable = false;
    
    if(loggedIn)
    {
      // showLoader();
      //  request("GET", 'web/php/GetMyFavorites.php?', true, "seachByKeyword");
        $("#content").load("createEvent.php");
    }
    else
    {
        gotoOverview();
    } 

}

function gotoEditEvent(eventId)
{
    isEventEditable = true;
    editEventId = eventId;
    $("#content").load("createEvent.php");
}
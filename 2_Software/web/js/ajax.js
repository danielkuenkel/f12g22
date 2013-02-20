/*
 * Autor: Daniel Kuenkel
 * Datum: 15.11.2012
 *
 * Co-Autoren:  Daniel Malkmus
 *              Anne Moldt
 *              Florent Mepin
 *              
 * Diese Datei ist für das senden und auslesen eines XMLHttpRequest zuständig.
 */

var imageLocation = "http://www.sfsuswe.com/~f12g22/";
var orderRecipeId;
var unitArray;
var orderIngredientsFrom;
var orderIngredientsTo;
var currentRecipeId;
var currentKeyword = "";
var currentFilter = "";
var updateUserWithJavaEE = false;
var registerUserWithJavaEE = false;

/*
 * Autor: Daniel Kuenkel
 * Führt einen XMLHttpRequest durch. 
 */
function request(method, url, async, requestId) {
    //erstellen des requests
    var req = getRequest();

    //Beim abschliessen des request wird diese Funktion ausgeführt
    req.onreadystatechange = function() 
    {
        switch (req.readyState) {
            case 4:
                if (req.status != 200) {
                    alert("Error:" + req.status);
                } else {
                    var text = req.responseText;	
		
                    try { // code for IE
                        var xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
                        xmlDoc.async = "false";
                        xmlDoc.loadXML(text);
                    }catch(error) { // code for Mozilla, Firefox, Opera, etc.
                        try {
                            var parser = new DOMParser();
                            var xmlDoc = parser.parseFromString(text,"text/xml");
                        }catch(error) {
                            alert(error.message);
                            return;
                        }
                    }
                    
                    switch(requestId)
                    {
                        case "searchByFilter":
                        case "seachByKeyword":
                            document.getElementById('content').innerHTML = "";
                            handleSearchResponse(xmlDoc);
                            break;
                        case "showRecipeById":
                            document.getElementById('content').innerHTML = "";
                            handleRecipeResponse(xmlDoc);
                            break;
                        case "login":
                            handleLoginResponse(xmlDoc)
                            break;
                        case "logout":
                            document.getElementById('content').innerHTML = "";
                            handleLogoutResponse(xmlDoc)
                            break;
                        case "registerUser":
                            document.getElementById('errors').innerHTML = "";
                            handleRegisterResponse(xmlDoc)
                            break;
                        case "forgotPassword":
                            document.getElementById('errorsPW').innerHTML = "";
                            handleForgotPWResponse(xmlDoc)
                            break;
                        case "updateUser":
                            handleUpdateUserResponse(xmlDoc)
                            break;
                        case "activateAccount":
                            document.getElementById('content').innerHTML = "";
                            handleActivationResponse(xmlDoc)
                            break;
                        case "addFavorite":
                            handleAddFavoriteResponse(xmlDoc);
                            break;
                        case "deleteFavorite":
                            handleDeleteFavoriteResponse(xmlDoc);
                            break;
                        case "getComments":
                            handleGetCommentsResponse(xmlDoc);
                            break;
                        case "addComment":
                            handleAddCommentResponse(xmlDoc);
                            break;
                        case "addAssessment":
                            handleAddAssessmentResponse(xmlDoc);
                            break;
                        case "deleteComment":
                            handleDeleteCommentResponse(xmlDoc);
                            break;
                        case "deleteRecipe":
                            handleDeleteRecipeResponse(xmlDoc);
                            break;
                        case "editRecipeValues":
                            handleEditRecipeValuesResponse(xmlDoc);
                            break;
                        case "convertIngredients":
                            handleConvertResponse(xmlDoc);
                            break;
                        case "getUnits":
                            handleGetUnitsResponse(xmlDoc);
                            break;
                        case "editOrder":
                            handleEditOrderResponse(xmlDoc);
                            break;
                        case "getRandomRecipe":
                            handleGetRandomRecipeResponse(xmlDoc);
                            break;
                        case "createEvent":
                            handleCreateEventResponse(xmlDoc);
                            break;
                        case "showEvent":
                            handleShowEventResponse(xmlDoc);
                            break;
                        case "searchEvent":
                            handleSearchEventResponse(xmlDoc);
                            break;
                        case "editEventValues":
                            handleEditEventValuesResponse(xmlDoc);
                            break;
                        case "deleteEvent":
                            handleDeleteEventResponse(xmlDoc);
                            break;  
                        case "joinEvent":
                            handleJoinEventResponse(xmlDoc);
                            break;
                        case "leaveEvent":
                            handleLeaveEventResponse(xmlDoc);
                            break;
                    }
                    
                }
                break;

            default:
                return false;
                break;
        }
    };

    //anfrage erstellen (GET, url ist localhost,
    //request ist asynchron
    req.open(method, url, async);
    req.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    req.send(null);
}


/*
 * gibt in Abhängigkeit des Browsers ein XMLHttpRequest-Objekt zurück.
 */
function getRequest()
{
    var req = null;
    try {
        req = new XMLHttpRequest();
    }
    catch (ms) {
        try {
            req = new ActiveXObject("Msxml2.XMLHTTP");
        }
        catch (nonms) {
            try {
                req = new ActiveXObject("Microsoft.XMLHTTP");
            }
            catch (failed) {
                req = null;
            }
        }
    }
    if (req == null) {
        alert("Error creating request object!");
    }
    return req;
}

/*
 * Sucht nach einem Rezept über ein Schlüsselwort. Ruft über request eine PHP-
 * Datei auf, welche die Suchlogik enthält.
 */
function searchByKeyword(keyword)
{
    showLoader();
    currentFilter = "";
    currentKeyword = keyword;
    request("GET", 'web/php/Search.php?search=' + keyword, true, "seachByKeyword");
}

/*
 * Sucht nach einem Rezept über ein Filter-Schlüsselwort. Ruft über request eine 
 * PHP-Datei auf, welche die Suchlogik enthält.
 */
function searchByFilter(filterKeys)
{
    showLoader();
    currentKeyword = "";
    currentFilter = filterKeys;
    request('GET', 'web/php/SearchFilter.php?filterKeys=' + filterKeys, true, 'searchByFilter');
}

function handleSearchResponse(xml)
{
    var recipes = xml.getElementsByTagName("recipe");
    document.getElementById('content').innerHTML = "";
    
    if(recipes.length > 0)
    {
        var wrapper = document.createElement('div');
        wrapper.id = "searchResultWrapper";
        
        for (var i = 0; i < recipes.length; i++)
        {
            var recipe = document.createElement('div');
            recipe.id = getNodeValue(recipes[i],'id'); 
            if(i%4 == 3)
            {
                recipe.className = 'recipeSearchResultLast';
            }
            else
            {
                recipe.className = 'recipeSearchResult';
            }
            
            var recipeImage = document.createElement('img');
            var imageUrl = getNodeValue(recipes[i],'url') != "default" ? imageLocation + getNodeValue(recipes[i],'url') : imageLocation + "web/img/default.jpg";
            recipeImage.src = imageUrl;
            recipeImage.id="searchRecipeImage";
            recipe.appendChild(recipeImage);
            
            var shadow = document.createElement('div');
            shadow.id = "recipeSearchResultShadow";
            recipe.appendChild(shadow);
                       
            var title = document.createElement('div');
            title.id = "recipeSearchResultTitle";
            
            var titleText = document.createElement('div');
            titleText.appendChild(document.createTextNode(getNodeValue(recipes[i],'title')));
            titleText.id = "recipeSearchResultTitleText";
            title.appendChild(titleText);
            recipe.appendChild(title);
            
            
            
            var votingContainer = document.createElement('div');
            votingContainer.id = "votingContainer";
            
            var spoonImage = document.createElement('div');
            spoonImage.id = "spoonImage";
            votingContainer.appendChild(spoonImage);
            
            var voting = document.createElement('div');
            voting.id = "searchVoting";
            var votingString = getNodeValue(recipes[i],'voting');
            voting.appendChild(document.createTextNode(votingString.replace(".", ",")));
            votingContainer.appendChild(voting);
            recipe.appendChild(votingContainer);
            
            
            
            var commentContainer = document.createElement('div');
            commentContainer.id = "commentContainer";
            
            var commentImage = document.createElement('div');
            commentImage.id = "commentImage";
            commentContainer.appendChild(commentImage);
            
            var comment = document.createElement('div');
            comment.id = "searchComment";
            comment.appendChild(document.createTextNode(getNodeValue(recipes[i],'comments')));
            commentContainer.appendChild(comment);
            recipe.appendChild(commentContainer);
            
            
            
            var favoriteContainer = document.createElement('div');
            favoriteContainer.id = "favoriteContainer";
            
            var favoriteImage = document.createElement('div');
            favoriteImage.id = "favoriteImage";
            favoriteContainer.appendChild(favoriteImage);
            
            var favorite = document.createElement('div');
            favorite.id = "searchFavorite";
            favorite.appendChild(document.createTextNode(getNodeValue(recipes[i],'favorites')));
            favoriteContainer.appendChild(favorite);
            recipe.appendChild(favoriteContainer);
            
            
            wrapper.appendChild(recipe);
            recipe.onclick = Function("onRecipeClick(" + recipe.id + ");");
        }
        document.getElementById('content').appendChild(wrapper);
    }
    else
    {
        var text = document.createElement('div');
        text.appendChild(document.createTextNode("No recipes found."));
        document.getElementById('content').appendChild(text);
    }
    
    hideLoader();
}

function handleRecipeResponse(xml)
{       
    var recipes = xml.getElementsByTagName("recipe");
    document.getElementById('content').innerHTML = "";
    
    orderIngredientsFrom = 0;
    orderIngredientsTo = 0;

    if(recipes.length == 1)
    {
        var recipeId = currentRecipeId = getNodeValue(recipes[0],'id');
        var recipeContent = document.createElement('div');
        recipeContent.id = "recipeContent";
        document.getElementById('content').appendChild(recipeContent);
              
        
        /* header content */
        
        var headerContent = document.createElement('div');
        headerContent.id = "recipeHeaderContent";
        recipeContent.appendChild(headerContent);
        
        var recipeImage = document.createElement('img');
        recipeImage.id = "recipeImage";
        var imageUrl = getNodeValue(recipes[0],'url') != "default" ? imageLocation + getNodeValue(recipes[0],'url') : imageLocation + "web/img/default-recipe.jpg";
        recipeImage.src = imageUrl;
        headerContent.appendChild(recipeImage);
        
        if(currentKeyword != "" || currentFilter != "")
        {
            var backButton = document.createElement('input');
            backButton.className = "greenButton";
            backButton.id = "goBackToSearchButton";
            backButton.setAttribute('value', 'go back');
            backButton.setAttribute('type', 'submit');
            headerContent.appendChild(backButton);
            
            if(currentKeyword != "")
            {
                backButton.onclick = Function("searchByKeyword('" + currentKeyword + "');");
            }
            else if(currentFilter != "")
            {
                backButton.onclick = Function("searchByFilter('" + currentFilter + "');");
            }
        }
        
        var recipeInfos = document.createElement('div');
        recipeInfos.id = "recipeInfos";
        headerContent.appendChild(recipeInfos);
        
        var headline = document.createElement('div');
        headline.id = "recipeHeadline";
        var recipeTitle = getNodeValue(recipes[0],'title')
        headline.appendChild(document.createTextNode(recipeTitle));
        recipeInfos.appendChild(headline);
        
        var createdFrom = document.createElement('div');
        createdFrom.id = "createdFrom";
        createdFrom.appendChild(document.createTextNode("by " + getNodeValue(recipes[0],'logon_name')));
        recipeInfos.appendChild(createdFrom);
        
        var recipeAbstract = document.createElement('div');
        recipeAbstract.id = "recipeAbstract";
        var recipeAbstractText = getNodeValue(recipes[0],'abstract')
        recipeAbstract.appendChild(document.createTextNode(recipeAbstractText));
        recipeInfos.appendChild(recipeAbstract);
        
        
        /* categories */
        
        var categoryContainer = document.createElement('div');
        categoryContainer.id = "categoryContainer";
        recipeInfos.appendChild(categoryContainer);
        
        var categories = xml.getElementsByTagName('category');
        
        for(var i = 0; i < categories.length; i++)
        {
            var category = document.createElement('div');
            category.className = "recipeCategory";
            category.appendChild(document.createTextNode(getNodeValue(categories[i], 'name')));
            categoryContainer.appendChild(category);
        }
        
        
        /* voting & assessments */
        
        renderVoting(xml, true);
        renderAssessment(recipeId);
        
        
        /* header buttons */
        
        var headerButtons = document.createElement('div');
        headerButtons.id = "headerButtonsContent";
        headerContent.appendChild(headerButtons);
        
        var fbButton = document.createElement('div');
        fbButton.id = "buttonFB";
        fbButton.onclick = Function("onShareRecipeClick('" + recipeId + "', '" + recipeTitle + "', '" + recipeAbstractText + "', '" + imageUrl + "');");
        headerButtons.appendChild(fbButton);
        
        var printButton = document.createElement('div');
        printButton.id = "buttonPrint";
        headerButtons.appendChild(printButton);
        printButton.onclick = Function("onPrintRecipeClick(" + recipeId + ")");
        
        var favoriteButton = document.createElement('div');
        favoriteButton.id = "buttonFavorite";
        headerButtons.appendChild(favoriteButton);
        favoriteButton.onclick = Function("onFavoriteClick(" + recipeId + ");");
        
        var favoriteDeleteButton = document.createElement('div');
        favoriteDeleteButton.id = "buttonDeleteFavorite";
        headerButtons.appendChild(favoriteDeleteButton);
        favoriteDeleteButton.onclick = Function("onFavoriteDeleteClick(" + recipeId + ");");
        
        if(getNodeValue(recipes[0],'isFavorite') == 0)
        {
            document.getElementById("buttonFavorite").style.visibility = "inline";
            document.getElementById("buttonFavorite").style.display = "visible";
            document.getElementById("buttonDeleteFavorite").style.visibility = "none";
            document.getElementById("buttonDeleteFavorite").style.display = "hidden";
        }
        else
        {
            document.getElementById("buttonFavorite").style.visibility = "hidden";
            document.getElementById("buttonFavorite").style.display = "none";
            document.getElementById("buttonDeleteFavorite").style.visibility = "visible";
            document.getElementById("buttonDeleteFavorite").style.display = "inline";
        }
        
        
        
        /* preperation content */
        
        var prepContent = document.createElement('div');
        prepContent.id = "recipePrepContent";
        recipeContent.appendChild(prepContent);
        
        var servingContainer = document.createElement('div');
        servingContainer.id = "servingContainer";
        prepContent.appendChild(servingContainer);
        
        var servingText1 = document.createElement('div');
        servingText1.className = "servingText";
        servingText1.appendChild(document.createTextNode("The ingredients for"));
        servingContainer.appendChild(servingText1);
        
        var inputField = document.createElement('input');
        inputField.className = "inputField";
        inputField.id = "servingInput";
        inputField.setAttribute("type", "number");
        inputField.setAttribute("min", 1);
        inputField.setAttribute("max", 99);
        inputField.setAttribute("maxlength", 2);
        orderIngredientsFrom = getNodeValue(recipes[0],'servings');
        inputField.setAttribute("value", orderIngredientsFrom);
        servingContainer.appendChild(inputField);
        
        var servingText2 = document.createElement('div');
        servingText2.className = "servingText";
        servingText2.appendChild(document.createTextNode("servings / people"));
        servingContainer.appendChild(servingText2);
        
        var convertButton = document.createElement('input');
        convertButton.className = "greenButton";
        convertButton.id = "convertButton";
        convertButton.setAttribute('value', 'convert');
        convertButton.setAttribute('type', 'submit');
        servingContainer.appendChild(convertButton);
        convertButton.onclick = Function("onConvertClick(" + recipeId + "," + getNodeValue(recipes[0],'servings') + ");");
        
        var orderButton = document.createElement('input');
        orderButton.className = "greenButton";
        orderButton.id = "orderButton";
        orderButton.setAttribute('value', 'order ingredients');
        orderButton.setAttribute('type', 'submit');
        servingContainer.appendChild(orderButton);
        orderButton.onclick = Function("onOrderClick(" + recipeId + ");");
        
        
        /* ingredients */
        
        var ingredientContent = document.createElement('div');
        ingredientContent.id = "ingredientContent";
        prepContent.appendChild(ingredientContent);
        
        var ingredientHeadline = document.createElement("div");
        ingredientHeadline.className = "prepContentHeadline";
        ingredientHeadline.appendChild(document.createTextNode("ingredients"));
        ingredientContent.appendChild(ingredientHeadline);
        
        var quantityContainer = document.createElement('div');
        quantityContainer.id = "quantityContainer";
        ingredientContent.appendChild(quantityContainer);
        
        var unitContainer = document.createElement('div');
        unitContainer.id = "unitContainer";
        ingredientContent.appendChild(unitContainer);
        
        var nameContainer = document.createElement('div');
        nameContainer.id = "nameContainer";
        ingredientContent.appendChild(nameContainer);
        
        renderIngredients(xml);
                
        var preperationContent= document.createElement('div');
        preperationContent.id = "preperationContent";
        prepContent.appendChild(preperationContent);
        
        var prepHeadline = document.createElement("div");
        prepHeadline.className = "prepContentHeadline";
        prepHeadline.appendChild(document.createTextNode("preperation"));
        preperationContent.appendChild(prepHeadline);
        
        var preparation = document.createElement('div');
        preparation.id = "recipePreparation";
        preparation.appendChild(document.createTextNode(getNodeValue(recipes[0],'preparation')));
        preperationContent.appendChild(preparation);
        
        var cookingTime = document.createElement('div');
        cookingTime.id = "recipeCookingTime";
        cookingTime.appendChild(document.createTextNode("cooking time: " + getNodeValue(recipes[0], 'cookingTime')));
        prepContent.appendChild(cookingTime);
        
        if(getNodeValue(recipes[0], 'videoUrl'))
        {
            var videoUrlButton = document.createElement('input');
            videoUrlButton.className = "greenButton";
            videoUrlButton.id = "watchVideoButton";
            videoUrlButton.setAttribute('value', 'watch video');
            videoUrlButton.setAttribute('type', 'submit');
            videoUrlButton.onclick = Function("window.open('" + getNodeValue(recipes[0], 'videoUrl') + "');");
            prepContent.appendChild(videoUrlButton);
        }
               
        
        if(getNodeValue(recipes[0],'isOwner') == 0)
        {           
            var deleteButton = document.createElement('input');
            deleteButton.className = "redButton";
            deleteButton.id = "deleteRecipeButton";
            deleteButton.setAttribute('value', 'delete recipe');
            deleteButton.setAttribute('type', 'submit');
            prepContent.appendChild(deleteButton);
            deleteButton.onclick = Function("onRecipeDeleteClick(" + recipeId + ");");
            
            var editButton = document.createElement('input');
            editButton.className = "greenButton";
            editButton.id = "editRecipeButton";
            editButton.setAttribute('value', 'edit recipe');
            editButton.setAttribute('type', 'submit');
            prepContent.appendChild(editButton);
            editButton.onclick = Function("onRecipeEditClick(" + recipeId + ");");
        }
        
        requestComments(recipeId);
    }
    
    else
    {
        hideLoader();
        var text = document.createElement('div');
        text.appendChild(document.createTextNode("No recipes found."));
        document.getElementById('content').appendChild(text);
    }
}

function renderVoting(xml, activateVoting)
{
    var recipes = xml.getElementsByTagName("recipe");
    var voting = parseFloat(getNodeValue(recipes[0], 'voting'));
    
    var votingContent = document.createElement('div');
    votingContent.id = "votingContentVoted";
    if(activateVoting && (!loggedIn || getNodeValue(recipes[0],'hasVoted') == 0))
    {
        votingContent.id = "votingContent";
        votingContent.onclick = Function("onVoteClick();");
    }
    
    if(activateVoting)
    {
        document.getElementById('recipeHeaderContent').appendChild(votingContent);
    } 
    else
    {
        votingContent.id = "randomRecipeVotingContentVoted";
        document.getElementById('randomRecipeVotingContent').appendChild(votingContent);
    }
    
    
    var splitted = voting.toString().split(".", 2);
    var decimal = parseInt(splitted[1]);
    var spoonBrake = parseFloat(splitted[0]);
    
    if(decimal >= 3 && decimal <= 7)
    {
        spoonBrake += 0.5;
    }
    else if(decimal > 7)
    {
        spoonBrake += 1;
    }
    
    splitted = spoonBrake.toString().split(".", 2);
    decimal = parseFloat(splitted[1]);
    spoonBrake = parseInt(splitted[0]);
    
    for(var i = 0; i < 5; i++)
    {
        var spoon = document.createElement('div');
        if(i == spoonBrake && decimal == 5)
        {
            spoon.id = "spoonHalf";
        }
        else if(i < spoonBrake)
        {
            spoon.id = "spoonFull";
        }
        else
        {
            spoon.id = "spoonEmpty";
        }
        votingContent.appendChild(spoon);
    }
    
    voting = voting.toString().replace(".", ",");
    if(voting == "0")
    {
        voting = "0,0";
    }
    
    var textContent = document.createElement('div');
    textContent.id = "votingTextContent";
    votingContent.appendChild(textContent);
    
    var votingText = document.createElement('div');
    votingText.id = "votingText";
    votingText.appendChild(document.createTextNode(voting));
    textContent.appendChild(votingText);
    
    var totalVotesText = document.createElement('div');
    totalVotesText.id = "totalVotesText";
    totalVotesText.appendChild(document.createTextNode(getNodeValue(recipes[0], 'votes') + " votes"));
    textContent.appendChild(totalVotesText);
}

function renderAssessment(recipeId)
{
    var votingLayer = document.createElement('div');
    votingLayer.id = "votingLayer";
    document.getElementById('recipeHeaderContent').appendChild(votingLayer);   
    
    
    var votingAssessmentZero = document.createElement('div');
    votingAssessmentZero.className = "assessment";
    votingAssessmentZero.onclick = Function("onAssessmentClick(" + recipeId + ", " + 0 + ");");
    votingLayer.appendChild(votingAssessmentZero);
    
    var imageZero = document.createElement('div');
    imageZero.className = "assessmentImage";
    imageZero.id = "assessmentZero";
    votingAssessmentZero.appendChild(imageZero);
    
    var textZero = document.createElement('div');
    textZero.appendChild(document.createTextNode("0 spoons"));
    textZero.className = "assessmentText";
    votingAssessmentZero.appendChild(textZero);
    
    
    var votingAssessmentOne = document.createElement('div');
    votingAssessmentOne.className = "assessment";
    votingAssessmentOne.onclick = Function("onAssessmentClick(" + recipeId + ", " + 1 + ");");
    votingLayer.appendChild(votingAssessmentOne);
    
    var imageOne = document.createElement('div');
    imageOne.className = "assessmentImage";
    imageOne.id = "assessmentOne";
    votingAssessmentOne.appendChild(imageOne);
    
    var textOne = document.createElement("div");
    textOne.appendChild(document.createTextNode("1 spoon"));
    textOne.className = "assessmentText";
    votingAssessmentOne.appendChild(textOne);
    

    var votingAssessmentTwo = document.createElement('div');
    votingAssessmentTwo.className = "assessment";
    votingAssessmentTwo.onclick = Function("onAssessmentClick(" + recipeId + ", " + 2 + ");");
    votingLayer.appendChild(votingAssessmentTwo);
    
    var imageTwo = document.createElement('div');
    imageTwo.className = "assessmentImage";
    imageTwo.id = "assessmentTwo";
    votingAssessmentTwo.appendChild(imageTwo);
    
    var textTwo = document.createElement('div');
    textTwo.appendChild(document.createTextNode("2 spoons"));
    textTwo.className = "assessmentText";
    votingAssessmentTwo.appendChild(textTwo);
    
    
    var votingAssessmentThree = document.createElement('div');
    votingAssessmentThree.className = "assessment";
    votingAssessmentThree.onclick = Function("onAssessmentClick(" + recipeId + ", " + 3 + ");");
    votingLayer.appendChild(votingAssessmentThree);
    
    var imageThree = document.createElement('div');
    imageThree.className = "assessmentImage";
    imageThree.id = "assessmentThree";
    votingAssessmentThree.appendChild(imageThree);
    
    var textThree = document.createElement('div');
    textThree.appendChild(document.createTextNode("3 spoons"));
    textThree.className = "assessmentText";
    votingAssessmentThree.appendChild(textThree);
    
    
    var votingAssessmentFour = document.createElement('div');
    votingAssessmentFour.className = "assessment";
    votingAssessmentFour.onclick = Function("onAssessmentClick(" + recipeId + ", " + 4 + ");");
    votingLayer.appendChild(votingAssessmentFour);
    
    var imageFour = document.createElement('div');
    imageFour.className = "assessmentImage";
    imageFour.id = "assessmentFour";
    votingAssessmentFour.appendChild(imageFour);
    
    var textFour = document.createElement('div');
    textFour.appendChild(document.createTextNode("4 spoons"));
    textFour.className = "assessmentText";
    votingAssessmentFour.appendChild(textFour);
    
    
    var votingAssessmentFive = document.createElement('div');
    votingAssessmentFive.className = "assessment";
    votingAssessmentFive.onclick = Function("onAssessmentClick(" + recipeId + ", " + 5 + ");");
    votingLayer.appendChild(votingAssessmentFive);
    
    var imageFive = document.createElement('div');
    imageFive.className = "assessmentImage";
    imageFive.id = "assessmentFive";
    votingAssessmentFive.appendChild(imageFive);
    
    var textFive = document.createElement('div');
    textFive.appendChild(document.createTextNode("5 spoons"));
    textFive.className = "assessmentText";
    votingAssessmentFive.appendChild(textFive);
}

function renderIngredients(xml)
{
    var ingredients = xml.getElementsByTagName("ingredient");
    var quantityContainer = document.getElementById("quantityContainer");
    var unitContainer = document.getElementById("unitContainer");
    var nameContainer = document.getElementById("nameContainer");
    quantityContainer.innerHTML = "";
    unitContainer.innerHTML = "";
    nameContainer.innerHTML = "";
    
    for(var i = 0; i < ingredients.length; i++)
    {   
        var quantity = document.createElement('div');
        quantity.className = "recipeQuantity";
        var text = getNodeValue(ingredients[i],'quantity').replace("NULL", "");
        var value = parseFloat(text);
        if(!isNaN(value))
        {
            value = roundFloat(value, 2);
            text = value.toString().replace(".", ",");
        }
        quantity.appendChild(document.createTextNode(text));
        quantityContainer.appendChild(quantity);
            
        var unit = document.createElement('div');
        unit.className = "recipeUnit";
        text = getNodeValue(ingredients[i],'unit').replace("NULL", "");
        unit.appendChild(document.createTextNode(text));
        unitContainer.appendChild(unit);
            
        var name = document.createElement('div');
        name.className = "recipeName";
        name.appendChild(document.createTextNode(getNodeValue(ingredients[i],'name')));
        nameContainer.appendChild(name);
    }
}

function handleConvertResponse(xml)
{
    renderIngredients(xml);
    hideLoader();
}

function roundFloat(value,n){
    var factor = Math.pow(10,n);
    return(Math.round(value * factor) / factor);
}

function handleGetCommentsResponse(xml)
{
    var comments = xml.getElementsByTagName("comment");
    var commentsRoot = xml.getElementsByTagName("comments");
    var recipeId = getNodeValue(commentsRoot[0],'recipeId');
    
    var commentContent = document.createElement('div');
    commentContent.id = "commentContent";
    document.getElementById('content').appendChild(commentContent);
    
    if(comments.length > 0)
    {
        for(var i = 0; i < comments.length; i++)
        {
            var singleComment = document.createElement('div');
            singleComment.id = "singleComment";
            commentContent.appendChild(singleComment);
            
            var commentHeadText = getNodeValue(comments[i],'logon') + " posted on " + getNodeValue(comments[i],'timestamp') + ":";
            var head = document.createElement('div');
            head.id = "commentHead";
            head.appendChild(document.createTextNode(commentHeadText));
            singleComment.appendChild(head);
            
            var commentText = document.createElement('div');
            commentText.id = "commentText";
            commentText.appendChild(document.createTextNode(getNodeValue(comments[i],'copy')));
            singleComment.appendChild(commentText);
            
            
            if(getNodeValue(comments[i],'isOwner') == 0)
            {
                var deleteButton = document.createElement('input');
                deleteButton.className = "smallGrayButton";
                deleteButton.id = "deleteCommentButton";
                deleteButton.setAttribute('value', 'delete');
                deleteButton.setAttribute('type', 'submit');
                singleComment.appendChild(deleteButton);
                deleteButton.onclick = Function("onCommentDeleteClick(" + getNodeValue(comments[i],'commentId') + "," + recipeId + ");");
            }
            
            var seperator = document.createElement('div');
            seperator.id = "commentSeperator";
            singleComment.appendChild(seperator);
        }
    }
    
    var commentAddContainer = document.createElement('div');
    commentAddContainer.id = "commentAddContainer";
    commentContent.appendChild(commentAddContainer);
    
    var inputField = document.createElement('input');
    inputField.className = "inputField";
    inputField.id = "commentInput";
    inputField.setAttribute("maxlength", 500);
    commentAddContainer.appendChild(inputField);
    
    var submitButton = document.createElement('input');
    submitButton.className = "greenButton";
    submitButton.id = "commentSubmitButton";
    submitButton.setAttribute('value', 'comment');
    submitButton.setAttribute('type', 'submit');
    submitButton.onclick = Function("onCommentSubmitClick(" + recipeId + ");");
    commentAddContainer.appendChild(submitButton);
    
    hideLoader();
}

function handleAddCommentResponse(xml)
{
    var comments = xml.getElementsByTagName("comments");
    var status = getNodeValue(comments[0],'status');
    
    if (status == "okay" && document.getElementById("commentContent")) {
        reloadComments(getNodeValue(comments[0],'recipeId'));
    }
    else
    {
    //Fehlerbehandlung bei nicht erfolgreichem insert des comments
    }
}

function handleDeleteCommentResponse(xml)
{
    var comments = xml.getElementsByTagName("comments");
    var status = getNodeValue(comments[0],'status');
    
    if (status == "okay" && document.getElementById("commentContent")) {
        reloadComments(getNodeValue(comments[0],'recipeId'));
    }
    else
    {
    //Fehlerbehandlung bei nicht erfolgreichem insert des comments
    }
}

function reloadComments(recipeId)
{
    showLoader();
    document.getElementById("content").removeChild(document.getElementById("commentContent"));
    requestComments(recipeId);
}

function handleLoginResponse(xml)
{
    var session = xml.getElementsByTagName("session");
    
    if(session.length > 0)
    {
        var registered = getNodeValue(session[0],'registered');
        var activated = getNodeValue(session[0],'activate');
 
        if(registered == 1 && activated == 1)
        {
        
            var id = getNodeValue(session[0],'userId');
            var logon = getNodeValue(session[0],'logonName');
                
            if(id != 0 && logon != "")
            {
                loggedIn = true;
                
                hideLoginForm();
                toggleLoginButton();
                
                if(currentRecipeId != 0)
                {
                    onRecipeClick(currentRecipeId);
                }
                else if(currentFilter != "" || currentKeyword != "")
                {
                        
                }
                else
                {
                    document.getElementById('content').innerHTML = "";
                    gotoAccount();
                }
               
            }
            
        }
        else if(registered == 1 && activated == 0)
        {
            gotoNotActivated();
        }
        else
        {
            gotoOverview();
        }
    }
    else
    {
        var text = document.createElement('div');
        text.appendChild(document.createTextNode("Unknown Error."));
        document.getElementById('content').appendChild(text);
    }
    hideLoader();
}
  
function handleAddAssessmentResponse(xml)
{
    hideVotingLayer();
    var recipe = xml.getElementsByTagName("recipe");
    var status = getNodeValue(recipe[0],'status');
    if (status == "okay" && document.getElementById("votingContent")) {
        document.getElementById("votingContent").innerHTML = "";
        renderVoting(xml, true);
    }
    else
    {
    //Fehlerbehandlung bei nicht erfolgreichem insert des comments
    }
    hideLoader();
}

function handleEditRecipeValuesResponse(xml)
{
    var recipes = xml.getElementsByTagName("recipe");
    if(recipes.length == 1)
    {
        document.getElementById("recipeTitle").value = getNodeValue(recipes[0], 'title');
        document.getElementById("abstract").value = getNodeValue(recipes[0], 'abstract');
        document.getElementById("numberPeople").value = getNodeValue(recipes[0], 'servings');
        document.getElementById("preparation").value = getNodeValue(recipes[0], 'preparation');
        document.getElementById("prepTime").value = getNodeValue(recipes[0], 'cookingTime');
        document.getElementById("videoUrl").value = getNodeValue(recipes[0], 'videoUrl');
        
        var ingredients = xml.getElementsByTagName("ingredient");
        for(var i = 0; i < ingredients.length; i++)
        {
            var quantity = getNodeValue(ingredients[i], 'quantity').replace("NULL", "");
            var value = parseFloat(quantity);
            if(!isNaN(value))
            {
                value = roundFloat(value, 2);
                quantity = value.toString().replace(".", ",");
            }
            var unitId = getNodeValue(ingredients[i], 'unitId');
            var name = getNodeValue(ingredients[i], 'name');
            addIngredient(quantity, unitId, name);
        }
        
        var categories = xml.getElementsByTagName('category');
        for(var j = 0; j < categories.length; j++)
        {
            var element = document.getElementById(getNodeValue(categories[j], 'name'));
            element.classList.add("sel");
        }
    }
    
    if(loggedIn)
    {
        showAccountButton();
    }
    hideLoader();
}

function handleRegisterResponse(xml)
{
    var user = xml.getElementsByTagName("user");
    var hasUser = getNodeValue(user[0],'hasUser');
    
    if(hasUser == 1)
    {
        var error = document.createElement('div');
        error.appendChild(document.createTextNode("The logon name exists. Please choose an other!"));
        document.getElementById('errors').appendChild(error);
    }
    else
    {
        document.getElementById('content').innerHTML = "";
        if(registerUserWithJavaEE == true)
        {
            registerUserWithJavaEE = false;           
        }
        gotoRegisterSuccess();
    }
    hideLoader();
}

function handleForgotPWResponse(xml)
{
    var eMail = xml.getElementsByTagName("forgotMail");
    var hasEmail = getNodeValue(eMail[0],'hasEmail');
    var mailSend = getNodeValue(eMail[0], 'mailSend');
    
    if(hasEmail == 0)
    {
        var errorPW = document.createElement('div');
        errorPW.appendChild(document.createTextNode("The email does not exists in database!"));
        document.getElementById('errorsPW').appendChild(errorPW);
    }else if (hasEmail == 1 && mailSend == 0)
    {
        var errorMail = document.createElement('div');
        errorMail.appendChild(document.createTextNode("The email exists but server error!"));
        document.getElementById('errorsPW').appendChild(errorMail);
    }else if(hasEmail == 1 && mailSend == 1)
    {
        document.getElementById('content').innerHTML = "";
        gotoPasswordResetSuccess();
    }
    hideLoader();
}

function handleActivationResponse(xml)
{
    
    var activation = xml.getElementsByTagName("activation");
    var activate = getNodeValue(activation[0],'activate');
    
    if(activate == "true")
    {
        var exists = getNodeValue(activation[0], 'activateExists');
        if(exists == "true")
            gotoActivateExists();
        else
        {
            gotoActivateSuccess();
        }
    }
    else
    {
        gotoActivateFailed();
    }
    hideLoader();
}

function handleUpdateUserResponse(xml)
{
    var user = xml.getElementsByTagName("user");
    var success = getNodeValue(user[0],'success');
    
    if(success == "true")
    {
        showSuccessLayer();
        
        if(updateUserWithJavaEE == true)
        {
            updateUserWithJavaEE = false;
            var forenameValue = getNodeValue(user[0],'forename');
            var surenameValue = getNodeValue(user[0],'surename');
            var streetValue = getNodeValue(user[0], 'street');
            var housenumberValue = getNodeValue(user[0], 'housenumber');
            var zipValue = getNodeValue(user[0], 'zip');
            var cityValue = getNodeValue(user[0], 'city');
            var phoneValue = getNodeValue(user[0], 'phone');
            
            $.post('web/php/SetSessionVariable.php', {
                forename: forenameValue,
                surename: surenameValue,
                street: streetValue,
                house_number: housenumberValue,
                zip: zipValue,
                city: cityValue,
                phone_number: phoneValue
            });
        }
    }
    else
    {
        showFailLayer();
    }
    hideLoader();
}

function handleDeleteRecipeResponse(xml)
{
    var recipe = xml.getElementsByTagName("recipe");
    var status = getNodeValue(recipe[0],'status');
    
    if (status == "okay" && document.getElementById("commentContent")) {
        gotoWelcome();
    }
    else
    {
    //Fehlerbehandlung bei nicht erfolgreichem insert des comments
    }
    hideLoader();
}

function handleAddFavoriteResponse(xml)
{
    var favorite = xml.getElementsByTagName("favorite");
    var status = getNodeValue(favorite[0],'status');
    
    if (status == "okay" && document.getElementById("recipeContent")) 
    {
        document.getElementById("buttonFavorite").style.visibility = "hidden";
        document.getElementById("buttonFavorite").style.display = "none";
        document.getElementById("buttonDeleteFavorite").style.visibility = "visible";
        document.getElementById("buttonDeleteFavorite").style.display = "inline";
    }
    else
    {
    //Fehlerbehandlung bei nicht erfolgreichem insert des comments
    }
    hideLoader();
}

function handleDeleteFavoriteResponse(xml)
{
    var favorite = xml.getElementsByTagName("favorite");
    var status = getNodeValue(favorite[0],'status');
    
    if (status == "okay" && document.getElementById("recipeContent")) 
    {
        document.getElementById("buttonDeleteFavorite").style.visibility = "hidden";
        document.getElementById("buttonDeleteFavorite").style.display = "none";
        document.getElementById("buttonFavorite").style.visibility = "visible";
        document.getElementById("buttonFavorite").style.display = "inline";
    }
    else
    {
    //Fehlerbehandlung bei nicht erfolgreichem insert des comments
    }
    hideLoader();
}

function handleLogoutResponse(xml)
{
    hideAccountButton();
    gotoWelcome();
}

function requestComments(recipeId)
{
    showLoader();
    var url = "web/php/GetComments.php?recipeId=" + recipeId;
    request("GET", url, true, "getComments");
}

function onRecipeClick(recipeId)
{
    showLoader();
    var url = "web/php/Recipe.php?recipeId=" + recipeId;
    request("GET", url, true, "showRecipeById");
}

function onFavoriteClick(recipeId)
{
    if(loggedIn)
    {
        showLoader();
        var url = "web/php/AddFavorite.php?recipeId=" + recipeId;
        request("GET", url, true, "addFavorite");
    }
    else
    {
        showLoginForm();
    }
}

function onFavoriteDeleteClick(recipeId)
{
    if(loggedIn)
    {
        showLoader();
        var url = "web/php/DeleteFavorite.php?recipeId=" + recipeId;
        request("GET", url, true, "deleteFavorite");
    }
    else
    {
        showLoginForm();
    }
}

function onPrintRecipeClick(recipeId)
{
    window.open("printPage.php?recipeId=" + recipeId,"Printversion");
}

function onFavoriteClick(recipeId)
{
    if(loggedIn)
    {
        showLoader();
        var url = "web/php/AddFavorite.php?recipeId=" + recipeId;
        request("GET", url, true, "addFavorite");
    }
    else
    {
        showLoginForm();
    }
}

function onCommentSubmitClick(recipeId)
{
    if(loggedIn)
    {
        showLoader();
        var commentText = document.getElementById("commentInput").value;
        var url = "web/php/AddComment.php?recipeId=" + recipeId + "&comment=" + commentText;
        request("GET", url, true, "addComment");
    }
    else
    {
        showLoginForm();
    }
}

function onCommentDeleteClick(commentId, recipeId)
{
    if(loggedIn)
    {
        showLoader();
        var url = "web/php/DeleteComment.php?commentId=" + commentId + "&recipeId=" + recipeId;
        request("GET", url, true, "deleteComment");
    }
    else
    {
        showLoginForm();
    }
}

function onRecipeEditClick(recipeId)
{
    if(loggedIn)
    {
        gotoEditRecipe(recipeId);
    }
}

function onRecipeDeleteClick(recipeId)
{
    if(loggedIn)
    {
        showLoader();
        var url = "web/php/DeleteRecipe.php?recipeId=" + recipeId;
        request("GET", url, true, "deleteRecipe");
    }
}

function onConvertClick(recipeId, from)
{
    showLoader();
    var to = parseInt(document.getElementById("servingInput").value);
    if(to <= 0 || to >= 100)
    {
        to = 1;
        document.getElementById("servingInput").value = to;
    }
    orderIngredientsFrom = from;
    orderIngredientsTo = to;
    var url = "web/php/ConvertIngredients.php?recipeId=" + recipeId + "&from=" + from + "&to=" + to;
    request("GET", url, true, "convertIngredients");
}

function onVoteClick()
{
    if(loggedIn)
    {
        showVotingLayer();
    }
    else
    {
        showLoginForm();
    }
}
function onAssessmentClick(recipeId, assessment)
{
    showLoader();
    var url = "?recipeId=" + recipeId + "&assessment=" + assessment;
    request("GET", "web/php/AddAssessment.php" + url, true, "addAssessment");
}

function showVotingLayer()
{
    document.getElementById("votingLayer").style.visibility = "visible";
}

function hideVotingLayer()
{
    document.getElementById("votingLayer").style.visibility = "hidden";
}

function getNodeValue(obj,tag)
{
    try
    {
        return obj.getElementsByTagName(tag)[0].firstChild.nodeValue;
    }
    catch(error)
    {
        return "";
    }
}

function handleGetUnitsResponse(xmlDoc)
{
    var options = xmlDoc.getElementsByTagName("option");
    unitArray = new Array();
    unitArray[0] = "choose";
    for (var i = 0; i < options.length; i++)
    {
        unitArray[i+1] = getNodeValue(options[i], 'unitName');       
    }
    hideLoader();
}

function onOrderClick(recipeId)
{
    if(loggedIn)
    {
        orderRecipeId = recipeId;
        var to = parseInt(document.getElementById("servingInput").value);
        if(to <= 0 || to >= 100)
        {
            to = orderIngredientsFrom;
            document.getElementById("servingInput").value = to;
        }
        orderIngredientsTo = to;
        gotoOrderIngredientsForm();
        showLoader();
    }
    else
    {
        showLoginForm();
    }
}

function handleEditOrderResponse(xml)
{
    var ingredients = xml.getElementsByTagName("ingredient");
    document.getElementById("ingredientsHeadline").innerHTML = "The ingredients for " + orderIngredientsTo + " servings / people:";
    if(ingredients.length > 0)
    {
        for(var i = 0; i < ingredients.length; i++)
        { 
            var quantity = getNodeValue(ingredients[i], 'quantity').replace("NULL", "");
            var value = parseFloat(quantity);
            if(!isNaN(value))
            {
                value = roundFloat(value, 2);
                quantity = value.toString().replace(".", ",");
            }

            var unitId = getNodeValue(ingredients[i], 'unitId');
            var name = getNodeValue(ingredients[i], 'name');
            addIngredient(quantity, unitId, name);
        }
    }
    else
    {
        addIngredient('', '', '');
    }
    
    hideLoader();
}


function onShareRecipeClick(recipeId, title, description, tinyImageUrl)
{ 
    url = encodeURIComponent("http://sfsuswe.com/~f12g22/?recipeId=" + recipeId);
    title = encodeURIComponent("Cooking Place - " + title );
    description = encodeURIComponent(description);
 
    window.open("http://www.facebook.com/sharer.php?s=100&p[title]="+title+
        "&p[summary]="+description+"&p[url]="+url+
        "&p[images][0]="+tinyImageUrl,'sharer','toolbar=0,status=0,width=650,height=450');
}

function handleGetRandomRecipeResponse(xml)
{
    var recipe = xml.getElementsByTagName("recipe");
    if(recipe.length == 1)
    {
        var content = document.getElementById("randomRecipeContent");
        content.innerHTML = "";
        content.onclick = Function("onRecipeClick(" + getNodeValue(recipe[0], 'id') + ");");
        
        var image = document.createElement('div');
        image.id = "randomRecipeImageContainer";
        content.appendChild(image);
        
        var recipeImage = document.createElement('img');
        var imageUrl = getNodeValue(recipe[0], 'url') != "default" ? imageLocation + getNodeValue(recipe[0], 'url') : imageLocation + "web/img/default.jpg";
        recipeImage.src = imageUrl;
        recipeImage.id="randomRecipeImage";
        image.appendChild(recipeImage);
        
        var randomTeaser = document.createElement('div');
        randomTeaser.id = "randomTeaser";
        image.appendChild(randomTeaser);
        
        var infos = document.createElement('div');
        infos.id = "randomRecipeInfos";
        content.appendChild(infos);
        
        var title = document.createElement('div');
        title.id = "randomRecipeTitle";
        title.appendChild(document.createTextNode(getNodeValue(recipe[0],'title')));
        infos.appendChild(title);
        
        var votingContent = document.createElement('div');
        votingContent.id = "randomRecipeVotingContent";
        content.appendChild(votingContent);
        
        renderVoting(xml, false);
    }
}

function handleCreateEventResponse(xml)
{    
    var event = xml.getElementsByTagName("events");
    var created = getNodeValue(event[0],'created');
    if(created == 1)
    {
        showEvent(getNodeValue(event[0],'eventId'));
    }
    else
    {
        gotoGeneralError();
    }
    hideLoader();
}

function showEvent(eventId)
{
    showLoader();
    request("Get", "web/php/Event.php?eventId=" + eventId, true, "showEvent");
}

function onEventCellClick(eventId)
{
    //set current event ID for getting back to the search results;
    showEvent(eventId);
}

function handleShowEventResponse(xml)
{   
    var content = document.getElementById('content');
    content.innerHTML = '';
    
    var event = xml.getElementsByTagName("events");
    
    if(event.length == 1)
    {
        var container = document.createElement('div');
        container.id = "event";
        content.appendChild(container);
        
        var eventHeader = document.createElement('div');
        eventHeader.id = "eventHeaderContent";
        container.appendChild(eventHeader);
        
        var map = document.createElement('div');
        map.id = "googleMap";
        eventHeader.appendChild(map);
          
        var address = getNodeValue(event[0], 'zip') + ", " + 
        getNodeValue(event[0], 'city') + ", " + 
        getNodeValue(event[0], 'street') + ", " + 
        getNodeValue(event[0], 'houseNumber');
        initializeMap(address,getNodeValue(event[0], 'title') );
         
        var eventinfos = document.createElement('div');
        eventinfos.id = "eventInfos";
        eventHeader.appendChild(eventinfos);
        
        var title = document.createElement('div');
        title.id = "eventHeadline";
        title.appendChild(document.createTextNode(getNodeValue(event[0], 'title')));
        eventinfos.appendChild(title);
        
        var owner = document.createElement('div');
        owner.id = "eventOwner";
        owner.appendChild(document.createTextNode(getNodeValue(event[0], 'logon_name')));
        eventinfos.appendChild(owner);
        
        var description = document.createElement('div');
        description.id = "eventAbstract";
        description.appendChild(document.createTextNode(getNodeValue(event[0],'abstract')));
        eventinfos.appendChild(description);
        
        //Container for Buttons
        var buttonContainer = document.createElement('div');
        buttonContainer.id = "eventHeaderButtonsContent";
        eventHeader.appendChild(buttonContainer);
                 
        if(getNodeValue(event[0],'isOwner') == 0)
        {
            //Button for editing an event
            var editButton = document.createElement('input');
            editButton.className = "greenButton";
            editButton.id = "editEventButton";
            editButton.setAttribute('value', 'edit');
            editButton.setAttribute('type', 'submit');       
            editButton.onclick = Function("onEditEventClick(" + getNodeValue(event[0],'id') + ");");
            buttonContainer.appendChild(editButton);
        
            //Button for deleting an event
            var deleteButton = document.createElement('input');
            deleteButton.className = "redButton";
            deleteButton.id = "deleteEventButton";
            deleteButton.setAttribute('value', 'delete');
            deleteButton.setAttribute('type', 'submit');       
            deleteButton.onclick = Function("onDeleteEventClick(" + getNodeValue(event[0],'id') + ");");
            buttonContainer.appendChild(deleteButton);
        }
        else if(getNodeValue(event[0],'isParticipant') == 1)
        {           
            //Button for leaving an event
            var leaveButton = document.createElement('input');
            leaveButton.className = "redButton";
            leaveButton.id = "leaveEventButton";
            leaveButton.setAttribute('value', 'leave');
            leaveButton.setAttribute('type', 'submit');       
            leaveButton.onclick = Function("onLeaveEventClick(" + getNodeValue(event[0],'id') + ");");
            buttonContainer.appendChild(leaveButton); 
        }
        else if(getNodeValue(event[0],'freePlaces')>0)
        {
            //Button for joining an event
            var joinButton = document.createElement('input');
            joinButton.className = "greenButton";
            joinButton.id = "joinEventButton";
            joinButton.setAttribute('value', 'join');
            joinButton.setAttribute('type', 'submit');       
            joinButton.onclick = Function("onJoinEventClick(" + getNodeValue(event[0],'id') + ");");
            buttonContainer.appendChild(joinButton); 
        }
        var eventContent = document.createElement('div');
        eventContent.id = "eventContent";
        container.appendChild(eventContent);
        
        var eventData = document.createElement('div');
        eventData.id = "eventData";
        eventContent.appendChild(eventData);
        
        var dataHeadline = document.createElement('div');
        dataHeadline.className = "prepContentHeadline";
        dataHeadline.appendChild(document.createTextNode("General Information"));
        eventData.appendChild(dataHeadline);
        
        //container for information categories
        var eventUnitContainer = document.createElement('div');
        eventUnitContainer.id = "eventUnitContainer";
        eventData.appendChild(eventUnitContainer);
        
        //container for the data
        var eventNameContainer = document.createElement('div');
        eventNameContainer.id = "eventNameContainer";
        eventData.appendChild(eventNameContainer);
        
        //add place of event
        var where = document.createElement('div');
        where.className = "eventUnit";
        where.appendChild(document.createTextNode("where"));
        eventUnitContainer.appendChild(where);
        
        var whereData = document.createElement('div');
        whereData.className = "eventName";
        whereData.appendChild(document.createTextNode(getNodeValue(event[0],'street')+" "+getNodeValue(event[0],'houseNumber')+", "+getNodeValue(event[0],'zip')+" "+getNodeValue(event[0],'city')));
        eventNameContainer.appendChild(whereData);
        
        //add date and time of event        
        var time = document.createElement('div');
        time.className = "eventUnit";
        time.appendChild(document.createTextNode("when"));
        eventUnitContainer.appendChild(time);
        
        var whenData = document.createElement('div');
        whenData.className = "eventName";
        whenData.appendChild(document.createTextNode(getNodeValue(event[0],'timestamp')));
        eventNameContainer.appendChild(whenData);
        
        //add number of People (max)
        var maxPeople = document.createElement('div');
        maxPeople.className = "eventUnit";
        maxPeople.appendChild(document.createTextNode("number of people [max.]"));
        eventUnitContainer.appendChild(maxPeople);
        
        var maxPeopleData = document.createElement('div');
        maxPeopleData.className = "eventName";
        maxPeopleData.appendChild(document.createTextNode(getNodeValue(event[0],'maxParticipants')));
        eventNameContainer.appendChild(maxPeopleData);
        
        //add number of free places
        var freePlaces = document.createElement('div');
        freePlaces.className = "eventUnit";
        freePlaces.appendChild(document.createTextNode("free places"));
        eventUnitContainer.appendChild(freePlaces);
        
        var freePlacesData = document.createElement('div');
        freePlacesData.className = "eventName";
        freePlacesData.appendChild(document.createTextNode(getNodeValue(event[0],'freePlaces')));
        eventNameContainer.appendChild(freePlacesData);
        
        //add cost
        var cost = document.createElement('div');
        cost.className = "eventUnit";
        cost.appendChild(document.createTextNode("total cost"));
        eventUnitContainer.appendChild(cost);
                               
        var costData = document.createElement('div');
        costData.className = "eventName";
        costData.appendChild(document.createTextNode(getNodeValue(event[0],'cost')+" Euro"));
        eventNameContainer.appendChild(costData);
        
        //add participants names (logon_name)       
        var participants = xml.getElementsByTagName("participant");
        for(var i = 0; i < 4; i++)
        //   for(var i = 0; i < participants.length; i++)
        {
            var participant = document.createElement('div');
            participant.className = "eventUnit";
            if(i==0)
            {
                participant.appendChild(document.createTextNode("participating people"));
            }
            else
            {
                participant.appendChild(document.createTextNode(""));
            }
            eventUnitContainer.appendChild(participant);

            var participantName = document.createElement('div');
            participantName.className = "eventName";
            participantName.appendChild(document.createTextNode(getNodeValue(participants[i],'name')));
            eventNameContainer.appendChild(participantName);
        }
 
    }
    hideLoader();
}

function handleSearchEventResponse(xml){
    hideLoader();
    var events = xml.getElementsByTagName("event");

    var content = document.getElementById('searchForEventsResults');
    content.innerHTML = "";

    for( var i = 0; i<events.length; i++)
    {          
        //create event
        var listCell = document.createElement('div');
        listCell.className = "eventListCell";
        listCell.onclick = Function("onEventCellClick(" + getNodeValue(events[i], 'id') + ");");
        content.appendChild(listCell);
        
        var listCellContent = document.createElement('div');
        listCellContent.className = "eventListCellContent";
        listCell.appendChild(listCellContent);
        
        //create zipcode of event
        var eventZipcode = document.createElement('div');
        eventZipcode.className = "cellZip";
        eventZipcode.appendChild(document.createTextNode(getNodeValue(events[i], 'zip')));
        listCellContent.appendChild(eventZipcode);
        
        //create zipcode of event
        var eventCity = document.createElement('div');
        eventCity.className = "cellCity";
        eventCity.appendChild(document.createTextNode(getNodeValue(events[i], 'city')));
        listCellContent.appendChild(eventCity);        
            
        //create abstract of event
        var timestamp = getNodeValue(events[i], 'timestamp');
        var date = new Date(timestamp * 1000);

        var eventDate = document.createElement('div');
        eventDate.className = "cellDate";
        eventDate.appendChild(document.createTextNode(date.getDate() + "." + (date.getMonth() + 1) + "." + date.getFullYear()));   
        listCellContent.appendChild(eventDate);
    
        //create title of event
        var eventTitle = document.createElement('div');
        eventTitle.className = "cellTitle";
        eventTitle.appendChild(document.createTextNode(getNodeValue(events[i], 'title')));   
        listCellContent.appendChild(eventTitle);  
        
        var seperator = document.createElement('div');
        seperator.className = "cellSeperator";
        listCell.appendChild(seperator);

    }
    hideLoader();
}

function onEditEventClick(eventId)
{
    if(loggedIn)
    {
        gotoEditEvent(eventId);
    }
}

function onDeleteEventClick(eventId)
{
    if(loggedIn)
    {
        showLoader();
        var url = "web/php/DeleteEvent.php?eventId=" + eventId;
        request("GET", url, true, "deleteEvent");
    }
}

function onJoinEventClick(eventId)
{
    if(loggedIn)
    {
        showLoader();
        var url = "web/php/JoinEvent.php?eventId=" + eventId;
        request("GET", url, true, "joinEvent");
    }
}

function onLeaveEventClick(eventId)
{
    if(loggedIn)
    {
        showLoader();
        var url = "web/php/LeaveEvent.php?eventId=" + eventId;
        request("GET", url, true, "leaveEvent");
    }
}

function handleEditEventValuesResponse(xml)
{
    var event = xml.getElementsByTagName("event");
    if(event.length == 1)
    {
        document.getElementById("eventTitle").value = getNodeValue(event[0], 'title');
        document.getElementById("description").value = getNodeValue(event[0], 'abstract');
        document.getElementById("maxPersons").value = getNodeValue(event[0], 'maxParticipants');
        document.getElementById("cost").value = getNodeValue(event[0], 'cost');
        document.getElementById("street").value = getNodeValue(event[0], 'street');
        document.getElementById("zipcode").value = getNodeValue(event[0],'zip');
        document.getElementById("city").value = getNodeValue(event[0],'city');
        document.getElementById("housenumber").value = getNodeValue(event[0], 'houseNumber');
        document.getElementById("hour").value = getNodeValue(event[0], 'hour');
        document.getElementById("minute").value = getNodeValue(event[0], 'minute');
        document.getElementById("datepicker").value = getNodeValue(event[0], 'date');
    }
    hideLoader();
}

function handleDeleteEventResponse(xml)
{
    var event = xml.getElementsByTagName("event");
    var status = getNodeValue(event[0],'status');
    
    if (status == "okay" ) {
        gotoWelcome();
    }
    hideLoader();
}

function handleJoinEventResponse(xml)
{
    var event = xml.getElementsByTagName("event");
    showEvent(getNodeValue(event[0],"eventId"));
}

function handleLeaveEventResponse(xml)
{
    var event = xml.getElementsByTagName("event");
    showEvent(getNodeValue(event[0],"eventId"));
}

function initializeMap(address,title) {
    
    var myOptions = {
        zoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP
    };
    var map = new google.maps.Map(document.getElementById("googleMap"), myOptions);
    
    var geocoder = new google.maps.Geocoder();
    geocoder.geocode( {
        'address': address
    }, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            map.setCenter(results[0].geometry.location);
            var marker = new google.maps.Marker({
                map: map,
                position: results[0].geometry.location,
                title: title
            }); 
            google.maps.event.addListener(marker, 'click', function() {
                map.setZoom(15);
                map.setCenter(marker.getPosition());
            });
        }
        else {
            alert("Geocode was not successful for the following reason: " + status);
        }
    });     
}

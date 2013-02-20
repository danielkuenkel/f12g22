
/*
 *Autor: Daniel, Künkel
 * Datum: 10.11.2012
 * Version: v1.0
 * --- Dateinbeschreibung ---
 * 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 * 
 * 
 */

var isFilterVisible = false;
var isLoginVisible = false;
var isLoggedIn = false;

function toggleFilterHeader()
{
    isFilterVisible = !isFilterVisible;
    
    if(isFilterVisible)
    {
        document.getElementById("filterHeader").style.visibility = "visible";
        document.getElementById("content").style.top = "514px";
        document.getElementById("toggleButton").style.backgroundImage = "url(./web/img/toggle-off.png)";
    }
    else
    {
        resetFilter();
        document.getElementById("filterHeader").style.visibility = "hidden";
        document.getElementById("content").style.top = "200px";
        document.getElementById("toggleButton").style.backgroundImage = "url(./web/img/toggle-on.png)";
    }
}

function toggleLoginForm()
{
    if(!isLoginVisible)
    {
        showLoginForm();
    }
    else
    {
        hideLoginForm();
    }
}

function showLoginForm()
{
    isLoginVisible = true;
    document.getElementById("login").style.visibility = "visible";
}

function hideLoginForm()
{
    isLoginVisible = false;
    document.getElementById("login").style.visibility = "hidden";
}

function toggleLoginButton()
{
    if(!isLoggedIn)
    {
        showAccountButton();
    }
    else
    {
        hideAccountButton();
    }
}

function showAccountButton()
{
    isLoggedIn = true;
    document.getElementById("loginButton").style.display = "none";
    document.getElementById("loginButton").style.visibility = "hidden";
    document.getElementById("accountButton").style.display = "inline";
    document.getElementById("accountButton").style.visibility = "visible";
}

function hideAccountButton()
{
    isLoggedIn = false;
    document.getElementById("loginButton").style.display = "inline";
    document.getElementById("loginButton").style.visibility = "visible";
    document.getElementById("accountButton").style.display = "none";
    document.getElementById("accountButton").style.visibility = "hidden";
}
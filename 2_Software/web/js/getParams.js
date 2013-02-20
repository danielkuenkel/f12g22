/*
 * Autor: Daniel, Künkel
 * Datum: 18.11.2012
 * Version: v1.0
 *-Dateinbeschreibung- 
 * Java Script datei 
*/



function getURLParameters( name )
{
    name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");  
    var regexS = "[\\?&]"+name+"=([^&#]*)";  
    var regex = new RegExp( regexS );  
    var results = regex.exec( window.location.href ); 
    if( results == null )
    {
        return "";  
    }    
    else{
        return results[1];
    }
}
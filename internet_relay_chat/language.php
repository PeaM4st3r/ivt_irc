<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

require getLanguageFile();
function getLanguageFile(){
    if(isset($_GET["lang"])){
        $_SESSION["lang"] = $_GET["lang"];
    }
    if(!isset($_SESSION["lang"])){
        $_SESSION["lang"] = "cs";
    }

    return "languages\\" . $_SESSION["lang"] . ".php";
}

/**
 * Returns a text string corresponding to the input language token from the currently active language file.
 * @param string $languageToken
 * @return string
 */
function getLan($languageToken) {
    global $langArray;
    if(isset($langArray[$languageToken])){
        return $langArray[$languageToken];
    }
    return $languageToken;
}

/**
 * Returns a text string corresponding to the input language token from the currently active language file.
 * Adds the $append parameter to the end of the resulting value.
 * @param string $languageToken
 * @param string $append
 * @return string
 */
function getLanA($languageToken, $append) {
    return getLan($languageToken) . $append;
}

?>
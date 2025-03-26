<?php
if(session_status() == PHP_SESSION_NONE){
    session_start();
}

require getLanguageFile();

/** Returns the path to the proper <lang>.php file. Updates the language in `$_SESSION` as well.
 * @return string the path to the <lang>.php file based on the currently selected language.
 */
function getLanguageFile(): string{
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
 * @return string the selected string from the currently loaded <lang>.php file,
 * or `$languageToken` if the specified token doesn't exist. 
 */
function getLan(string $languageToken): string {
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
 * @param string $append A string to the append to the end of the language token.
 * @return string the same as `getLan()` with the `$append` string concatenated to the end of the language string.
 */
function getLanA(string $languageToken, string $append): string {
    return getLan($languageToken) . $append;
}

?>
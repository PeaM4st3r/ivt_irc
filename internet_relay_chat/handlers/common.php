<?php
require getLanguageFile();

/** Returns the path to the proper <lang>.php file. Updates the language in `$_SESSION` as well.
 * @return string the path to the <lang>.php file based on the currently selected language.
 */
function getLanguageFile(): string {
    if(session_status() == PHP_SESSION_NONE){
        session_start();
    }

    if(isset($_GET["lang"])){
        $_SESSION["lang"] = $_GET["lang"];
    }
    if(!isset($_SESSION["lang"])){
        $_SESSION["lang"] = "cs";
    }

    session_write_close();
    return __DIR__ . "\\..\\languages\\" . $_SESSION["lang"] . ".php";
}

/** Returns a text string corresponding to the input language token from the currently active language file.
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

/** Returns a text string corresponding to the input language token from the currently active language file.
 * Adds the $append parameter to the end of the resulting value.
 * @param string $languageToken
 * @param string $append A string to the append to the end of the language token.
 * @return string the same as `getLan()` with the `$append` string concatenated to the end of the language string.
 */
function getLanA(string $languageToken, string $append): string {
    return getLan($languageToken) . $append;
}


/**Provides a consistent way for returning request statuses to the clients. */
final class RequestStatus {
    private $_ok = false;
    private $_message = "";
    private $_data = null;

    private function __construct(bool $state, string $message) {
        $this->_ok = $state;
        $this->_message = $message;
    }

    /** Attaches data to the request status.
     * @param mixed $data The data to attach to the response.
     */
    public function setData(mixed $data) {
        $this->_data = $data;
    }

    /**
     * @return array containing the current request status.
     * If custom data has been set using `setData()`, it will be added in this array.
     */
    public function getArray() {
        if ($this->_data == null) {
            return array(
                "ok" => $this->_ok,
                "message" => $this->_message
            );
        }

        return array(
            "ok" => $this->_ok,
            "message" => $this->_message,
            "data" => $this->_data
        );
    }


    /** Creates a new successful request status with a custom message string.
     * @param string $message The message stating the success of the request.
     * @return RequestStatus
     */
    public static function makeNewSuccess(string $message) {
        return new RequestStatus(true, $message);
    }

    /** Creates a new error request status with a custom message string.
     * @param string $message The message stating the reason for the error.
     * @return RequestStatus
     */
    public static function makeNewError(string $message) {
        return new RequestStatus(false, $message);
    }

    /** Creates a new successful request status. Uses a language token to set the message string.
     * @param string $langToken The token to use for the response message.
     * @return RequestStatus
     */
    public static function makeNewLanSuccess(string $langToken) {
        return new RequestStatus(true, getLan($langToken));
    }

    /** Creates a new error request status. Uses a language token to set the message string.
     * @param string $langToken The token to use for the error message.
     * @return RequestStatus
     */
    public static function makeNewLanError(string $langToken) {
        return new RequestStatus(false, getLan($langToken));
    }
}
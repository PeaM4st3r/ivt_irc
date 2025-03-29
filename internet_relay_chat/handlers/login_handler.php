<?php
namespace USRH;
use PDO;
use RequestStatus;

require ".\\db_handler.php";

/** Tries to create a new account with the provided username and password.
 * @param PDO &$pdo A reference to the PDO object.
 * @param string $username The username to set for the new account.
 * @param string $password The raw password to use for hashing.
 * @return RequestStatus containing data about the operation's success/failure.
 */
function createAccount(PDO &$pdo, string $username, string $password) {
    $userExists = \DBH\findUsername($pdo, $username);
    if ($userExists) return RequestStatus::makeNewLanError("error_create_acc_username_exists");

    $newPassHash = password_hash($password, null);

    if (!\DBH\createNewUser($pdo, $username, $newPassHash)) {
        return RequestStatus::makeNewLanError("error_create_acc_failed");
    }

    return RequestStatus::makeNewLanSuccess("succes_create_acc");
}

/** Tries to log into a user account using the provided credentials.
 * @param PDO &$pdo A reference to the PDO object.
 * @param string $username The account's username.
 * @param string $password The account's password.
 * @return RequestStatus containing the status of the operation.
 */
function logIntoAccount(PDO &$pdo, string $username, string $password) {
    if (session_status() == PHP_SESSION_NONE && !session_start()) {
        return RequestStatus::makeNewLanError("error_internal_failure");
    }

    $loginVerified = \DBH\testUserCredentials($pdo, $username, $password);
    if (!$loginVerified) {
        return RequestStatus::makeNewLanError("error_login_invalid_credentials");
    }

    $userID = \DBH\findUsername($pdo, $username, true);
    if (!$userID) {
        session_write_close();
        return RequestStatus::makeNewLanError("error_internal_failure");
    }

    $_SESSION["user_id"] = $userID;
    session_write_close();
    return RequestStatus::makeNewLanSuccess("succes_login");
}


function logOut() {
    if (session_status() == PHP_SESSION_NONE && !session_start()) {
        return RequestStatus::makeNewLanError("error_internal_failure");
    }

    unset($_SESSION["user_id"]);
    if (isset($_SESSION["user_id"])) {
        session_write_close();
        return RequestStatus::makeNewLanError("error_internal_failure");
    }

    session_write_close();
    return RequestStatus::makeNewLanSuccess("succes_logout");
}

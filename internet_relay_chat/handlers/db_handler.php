<?php
namespace DBH;
use PDO;
use PDOException;
use RequestStatus;

// MARK: Constants

/** Queries messages in the given channel, sorted from OLDEST to NEWEST and limited by the message count. */
const QGET_MESSAGES_IN_CHANNEL = "SELECT users.username, messages.msg_text, messages.time_sent
    FROM (messages INNER JOIN users ON (messages.fk_author = users.pk_id))
    WHERE messages.fk_channel = (SELECT channels.pk_id FROM channels WHERE channels.channel_name = :channelName)
    ORDER BY time_sent ASC
    LIMIT :msgOffset, :msgCount";

/** Returns the msg_text and time_sent attributes from the newest message in the provided channel. */
const QGET_LATEST_MESSAGE_IN_CHANNEL = "SELECT messages.msg_text, messages.time_sent FROM messages
    WHERE messages.fk_channel = (SELECT channels.pk_id FROM channels WHERE channels.channel_name = :channelName)
    ORDER BY messages.time_sent DESC
    LIMIT 1";

/** Sends a message from the specified user to the specified channel with the sent-time being the current timestamp */
const QSEND_MESSAGE_IN_CHANNEL = "INSERT INTO `messages` (`pk_id`, `time_sent`, `msg_text`, `fk_author`, `fk_channel`)
    VALUES (NULL, current_timestamp(), :queryMessage, :queryAuthorPK,
        (SELECT channels.pk_id FROM channels WHERE channels.channel_name = :channelName)
    )";

const QGET_PK_FROM_USERNAME = "SELECT users.username FROM users WHERE users.pk_id = :queryUserPK";
const QGET_USERNAME_PK = "SELECT users.pk_id FROM users WHERE users.username = :queryUsername";

const QINSERT_NEW_USER = "INSERT INTO users (`pk_id`, `username`, `password_hash`) 
    VALUES (NULL, :queryUsername, :queryPassHash)";

const QGET_USER_PASS = "SELECT users.password_hash FROM users WHERE users.username = :queryUsername";



// MARK: Function declarations
/** Attempts to connect to a database.
 * @param string $dbServer The server address to connect to
 * @param string $dbName The name of the database to connect to
 * @param string $dbUser The username of the account under which you wish to access the database
 * @param ?string $dbPass The account password
 * @return PDO Upon successful connection, return a new PHP Database Object
 * @return false When the connection fails, logs the error to the log file and returns false
*/
function connectToDB(string $dbServer, string $dbName, string $dbUser, ?string $dbPass): PDO|false
{
    try {
        $pdo = new PDO("mysql:host=$dbServer; dbname=$dbName", $dbUser, $dbPass);
    } catch (PDOException $exception) {
        error_log("Unable to establish connection to the IRC database for the following reason:" . $exception->getMessage());
        return false;
    }

    return $pdo;
}

/** Gets the newest message in the current channel and returns its hash.
 * @param PDO &$pdo A reference to the PDO object.
 * @param string $channelName The name of the channel to get the signature from. This name must be exact!
 * @return string an sha256 hash upon success, otherwise returns an empty string.
 */
function getChannelSignHash(PDO &$pdo, string $channelName): RequestStatus {
    $statement = $pdo->prepare(QGET_LATEST_MESSAGE_IN_CHANNEL);
    if(!$statement) return RequestStatus::makeNewLanError();
    $statement->bindValue(":channelName", $channelName);

    if(!$statement->execute()) return RequestStatus::makeNewLanError();
    $queryData = $statement->fetchAll(PDO::FETCH_ASSOC);
    if(!$messageData = $queryData[0]) return RequestStatus::makeNewLanError();

    $sign = hash("sha256", sprintf("%s/%s", $messageData["time_sent"], $messageData["msg_text"]));
    $response = RequestStatus::makeNewSuccess("Current channel signature.");
    $response->setData($sign);
    return $response;
}

/** Gets the messages in the provided chat channel. Can set a message limit and message offset to load older messages.
 * @param PDO &$pdo A reference to the PDO object (see `connectToDB()`).
 * @param string $channelName The chat channel to query the messages from.
 * @param int $messageCount The number of messages to retrieve in the query. 50 by default.
 * @param int $messageOffset By default, the query begins with the newest message. This lets you start from older messages.
 * @return array a multi-dimensional 0-indexed array where each sub-array contains associative message information.
 *  The keys are: `username`, `msg_text`, `time_sent`. The messages are ordered from OLDEST to NEWEST.
 */
function getChatMessages(PDO &$pdo, string $channelName, int $messageOffset = 0, int $messageCount = 50): array
{
    $msgCountNormalized = max($messageCount - 1, 1);

    $statement = $pdo->prepare(QGET_MESSAGES_IN_CHANNEL);
    if (!$statement) return array();
    $statement->bindValue(":msgOffset", (int)$messageOffset, PDO::PARAM_INT);
    $statement->bindValue(":msgCount", (int)$msgCountNormalized, PDO::PARAM_INT);
    $statement->bindValue(":channelName", $channelName);
    
    if (!$statement->execute()) {
        return array();
    }

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

/** Sends a chat message to the specified channel from a specified author.
 * @param PDO &$pdo The PDO.
 * @param string $channelName The name of the channel to send the message to.
 * @param int $author The message author's primary key.
 * @param string $message The contents of the message.
 * @return RequestStatus with the status of the operation.
 */
function sendChatMessage(PDO &$pdo, string $channelName, int $author, string $message): RequestStatus {
    // Verifying author PK
    $test_author_pk_statement = $pdo->prepare("SELECT users.username FROM users WHERE users.pk_id = :authorPK");
    if (!$test_author_pk_statement) return RequestStatus::makeNewLanError("error_internal_failure");
    $test_author_pk_statement->bindValue(":authorPK", (int)$author, PDO::PARAM_INT);

    if (!$author || !$test_author_pk_statement->execute() || !$test_author_pk_statement->fetchColumn()) {
        return RequestStatus::makeNewLanError("error_send_invalid_author");
    }

    // Sending message
    $statement = $pdo->prepare(QSEND_MESSAGE_IN_CHANNEL);
    if (!$statement) return RequestStatus::makeNewLanError("error_internal_failure");
    $statement->bindValue(":queryMessage", $message);
    $statement->bindValue(":queryAuthorPK", (int)$author, PDO::PARAM_INT);
    $statement->bindValue(":channelName", $channelName);

    if (!$statement->execute()) return RequestStatus::makeNewLanError("error_send_invalid_channel");
    return RequestStatus::makeNewLanSuccess("succes_send_message");
}

/** Returns true if the username exists within the 'users' table, otherwise returns false.
 * @param PDO &$pdo A reference to the PDO.
 * @param string $username The username to search for. This is case sensitive.
 * @return bool|int true when the username exists in the database, otherwise returns false. This returns false even when
 * the query fails.
 */
function findUsername(PDO &$pdo, string $username, bool $returnPK = false) {
    $statement = $pdo->prepare(QGET_USERNAME_PK);
    if (!$statement) return false; // This could be problematic in the future... who cares!
    $statement->bindValue(":queryUsername", $username);

    if (!$statement->execute()) { // Same thing with the execute() method here
        return false;
    }

    return ($returnPK ? (int)$statement->fetchAll(PDO::FETCH_ASSOC)[0]["pk_id"] : ($statement->fetchColumn() ? true : false));
}

/** Tests if the password for the provided username checks out with the password set in the database.
 * @param PDO &$pdo A reference to the PDO object.
 * @param string $username for which to test the password.
 * @param string $password to test the validity of.
 * @return bool true when the password checks out, otherwise false.
 */
function testUserCredentials(PDO &$pdo, string $username, string $password) {
    if (!findUsername($pdo, $username)) {
        return false;
    }

    $statement = $pdo->prepare(QGET_USER_PASS);
    if (!$statement) return false;
    $statement->bindValue(":queryUsername", $username);

    if (!$statement->execute()) {
        return false;
    }

    $databasePassHash = $statement->fetchAll(PDO::FETCH_ASSOC)[0]["password_hash"];
    if (!$databasePassHash) return false;
    return password_verify($password, $databasePassHash);
}

/** Creates a new user in the database.
 * @param PDO &$pdo database object reference.
 * @param string $username of the new user account.
 * @param string $passwordHash of the new user's password. This MUST be a hash generated by password_hash() [returns false otherwise].
 * @return bool true when the creation succeeds, otherwise false.
 */
function createNewUser(PDO &$pdo, string $username, string $passwordHash) {
    $statement = $pdo->prepare(QINSERT_NEW_USER);
    if (!$statement) return false;
    if (password_get_info($passwordHash)["algoName"] == "unknown") return false;
    $statement->bindValue(":queryUsername", $username);
    $statement->bindValue(":queryPassHash", $passwordHash);

    if (!$statement->execute()) {
        return false;
    }

    return true;
}

/** Returns the username from the 'users' table that's associated with the supplied primary key.
 * @param PDO &$pdo The PDO.
 * @param int $userPrimaryKey The primary key to search for.
 * @return string|false the username on success or FALSE when the query fails or no username is found.
 */
function getPrimaryKeyUsername(PDO &$pdo, int $userPrimaryKey) {
    $statement = $pdo->prepare(QGET_PK_FROM_USERNAME);
    if (!$statement) return false;
    $statement->bindValue(":queryUserPK", $userPrimaryKey);

    if (!$statement->execute()) return false;
    return $statement->fetch(PDO::FETCH_ASSOC)["username"];
}
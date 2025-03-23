<?php
namespace DBH;
use PDO;
use PDOException;


// MARK: Constants
const QUERY_GET_MESSAGE = "SELECT users.username, messages.msg_text, messages.time_sent
    FROM (messages INNER JOIN users ON (messages.fk_author = users.pk_id))
    ORDER BY time_sent ASC
    LIMIT :msgOffset, :msgCount";

const QGET_MESSAGES_IN_CHANNEL = "SELECT users.username, messages.msg_text";

/** Returns the msg_text and time_sent attributes from the newest message in the provided channel.
 */
const QGET_LATEST_MESSAGE_IN_CHANNEL = "SELECT messages.msg_text, messages.time_sent FROM messages
    WHERE messages.fk_channel = (SELECT channels.pk_id FROM channels WHERE channels.channel_name = :channelName)
    ORDER BY messages.time_sent DESC
    LIMIT 1";



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
 * @return string Returns an sha256 hash upon success, otherwise returns an empty string.
 */
function getChannelSignHash(PDO &$pdo, string $channelName): string {
    $statement = $pdo->prepare(QGET_LATEST_MESSAGE_IN_CHANNEL);
    if(!$statement) return "";
    $statement->bindValue(":channelName", $channelName);

    if(!$statement->execute() || !$statement->columnCount()) return "";
    $queryData = $statement->fetchAll(PDO::FETCH_ASSOC);
    if(!$messageData = $queryData[0]) return "";

    $sign = hash("sha256", sprintf("%s/%s", $messageData["time_sent"], $messageData["msg_text"]));
    return $sign;
}

/** Gets a range of chat messages sorted by date in ascending order.
 * @param PDO &$pdo A reference to the PDO object (see `connectToDB()`)
 * @param int $messageCount The number of messages to retrieve in the query. 50 by default.
 * @param int $messageOffset By default, the query begins with the newest message. This lets you start from older messages.
 * @return array Returns a multi-dimensional 0-indexed array where each sub-array contains associative message information.
 *  The keys are: `username`, `msg_text`, `time_sent`
 */
function getChatMessages(PDO &$pdo, int $messageCount = 20, int $messageOffset = 0): array
{
    $msgCountNormalized = max($messageCount - 1, 1);

    $statement = $pdo->prepare(QUERY_GET_MESSAGE);
    if (!$statement) return array();
    $statement->bindValue(":msgOffset", (int)$messageOffset, PDO::PARAM_INT);
    $statement->bindValue(":msgCount", (int)$msgCountNormalized, PDO::PARAM_INT);
    
    if (!$statement->execute() || !$statement->columnCount()) {
        return array();
    }

    return $statement->fetchAll(PDO::FETCH_ASSOC);
}

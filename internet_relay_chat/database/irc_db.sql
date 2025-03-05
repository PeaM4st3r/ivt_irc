-- Initialization file for the IRC database.

-- WARNING - this will DROP the entire database contents when ran!

-- Table definition: "users"
DROP TABLE IF EXISTS users;
CREATE TABLE IF NOT EXISTS users (
    pk_id int NOT NULL AUTO_INCREMENT,
    username VARCHAR(32) COLLATE utf8_czech_ci NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    PRIMARY KEY (pk_id)
);

-- Table definition: "messages"
DROP TABLE IF EXISTS messages;
CREATE TABLE IF NOT EXISTS messages (
    pk_id int NOT NULL AUTO_INCREMENT,
    time_sent TIMESTAMP NOT NULL,
    fk_author int NOT NULL,
    msg_text TEXT,
    PRIMARY KEY (pk_id)
);


-- Table definition: "channels"
DROP TABLE IF EXISTS channels;
CREATE TABLE IF NOT EXISTS channels (
    pk_id int NOT NULL AUTO_INCREMENT,
    channel_name VARCHAR(48) NOT NULL,
    fk_creator int NOT NULL,
    PRIMARY KEY (pk_id)
);


-- Table definition: "message_links"
DROP TABLE IF EXISTS message_links;
CREATE TABLE IF NOT EXISTS message_links (
    fk_channel int NOT NULL,
    fk_message int NOT NULL
);



-- Constraints
ALTER TABLE messages
ADD FOREIGN KEY (fk_author) REFERENCES users(pk_id);

ALTER TABLE channels
ADD FOREIGN KEY (fk_creator) REFERENCES users(pk_id);

ALTER TABLE message_links
ADD FOREIGN KEY (fk_channel) REFERENCES channels(pk_id),
ADD FOREIGN KEY (fk_message) REFERENCES messages(pk_id);
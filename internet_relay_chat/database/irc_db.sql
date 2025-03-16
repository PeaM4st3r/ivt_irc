-- Initialization file for the IRC database.

-- Table definition: "users"
CREATE TABLE IF NOT EXISTS users (
    pk_id int NOT NULL AUTO_INCREMENT,
    username VARCHAR(32) COLLATE utf8_czech_ci NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    PRIMARY KEY (pk_id)
);

-- Table definition: "messages"
CREATE TABLE IF NOT EXISTS messages (
    pk_id int NOT NULL AUTO_INCREMENT,
    time_sent TIMESTAMP NOT NULL,
    msg_text TEXT,
    fk_author int NOT NULL,
    fk_channel int NOT NULL,
    PRIMARY KEY (pk_id)
);


-- Table definition: "channels"
CREATE TABLE IF NOT EXISTS channels (
    pk_id int NOT NULL AUTO_INCREMENT,
    channel_name VARCHAR(48) NOT NULL,
    fk_creator int NOT NULL,
    PRIMARY KEY (pk_id)
);


-- Constraints
ALTER TABLE messages
ADD FOREIGN KEY (fk_author) REFERENCES users(pk_id),
ADD FOREIGN KEY (fk_channel) REFERENCES channels(pk_id);

ALTER TABLE channels
ADD FOREIGN KEY (fk_creator) REFERENCES users(pk_id);
TRUNCATE messages;

DELETE FROM channels WHERE `channels`.`pk_id` = 1;


-- Adding users

REPLACE INTO `users` (`pk_id`, `username`, `password_hash`) VALUES
    (1, 'jelimán_001', '$2y$10$.3Qk2tURm3Ew5W/qS7mOsObXm5CURVHGU2nMGKg70gDZEKxWE0o86');

REPLACE INTO `users` (`pk_id`, `username`, `password_hash`) VALUES
    (2, 'škoda_superb_1.9tdi', '$2y$10$.3Qk2tURm3Ew5W/qS7mOsObXm5CURVHGU2nMGKg70gDZEKxWE0o86');

REPLACE INTO `users` (`pk_id`, `username`, `password_hash`) VALUES
    (3, 'šv3jk0v1n4', '$2y$10$.3Qk2tURm3Ew5W/qS7mOsObXm5CURVHGU2nMGKg70gDZEKxWE0o86');


-- Adding channels
REPLACE INTO `channels` (`pk_id`, `channel_name`, `fk_creator`) VALUES
    (1, "institut", (SELECT users.pk_id FROM users WHERE users.username = "škoda_superb_1.9tdi"));


-- Adding messages
INSERT INTO `messages` (`pk_id`, `time_sent`, `msg_text`, `fk_author`, `fk_channel`) VALUES
    (1, DATE_ADD(NOW(6), INTERVAL 0 SECOND), 'vítejte lampy :-)', '2', '1');

INSERT INTO `messages` (`pk_id`, `time_sent`, `msg_text`, `fk_author`, `fk_channel`) VALUES
    (2, DATE_ADD(NOW(6), INTERVAL 2 SECOND), 'nenadávej mi!', '1', '1');

INSERT INTO `messages` (`pk_id`, `time_sent`, `msg_text`, `fk_author`, `fk_channel`) VALUES
    (3, DATE_ADD(NOW(6), INTERVAL 6 SECOND), 'zavři zobák nebo tě zabanuju', '2', '1');

INSERT INTO `messages` (`pk_id`, `time_sent`, `msg_text`, `fk_author`, `fk_channel`) VALUES
    (4, DATE_ADD(NOW(6), INTERVAL 10 SECOND), 'Cožpak se tady musíme hádat?', '3', '1');

INSERT INTO `messages` (`pk_id`, `time_sent`, `msg_text`, `fk_author`, `fk_channel`) VALUES
    (5, DATE_ADD(NOW(6), INTERVAL 15 SECOND), 'no švejk tady ještě chyběl ty vole', '2', '1');
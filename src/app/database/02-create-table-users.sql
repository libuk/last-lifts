CREATE TABLE IF NOT EXISTS `lastlifts`.`users` (
  `user_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing user_id of each user, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s name, unique',
  `user_password_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'user''s password in salted and hashed format',
  `user_email` varchar(254) COLLATE utf8_unicode_ci NOT NULL COMMENT 'user''s email, unique',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `user_name` (`user_name`),
  UNIQUE KEY `user_email` (`user_email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='user data';

INSERT INTO `lastlifts`.`users` (`user_id`, `user_name`, `user_password_hash`, `user_email`) VALUES
  (1, 'demo', '$2y$11$WTorv3w2aDkQKB7t1CvQ5u7LNkSEI.SMygrsFLSreOkAwaAp/SJXK', 'demo@demo.com'),
  (2, 'demo2', '$2y$11$Jo1nuWvg/t2NCNXSwaPzxO1R7bsWcOu0cvdBR0Eb3QCnz1lsD0gLK', 'demo2@demo.com');

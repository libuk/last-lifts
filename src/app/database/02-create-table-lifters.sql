CREATE TABLE IF NOT EXISTS `lastlifts`.`lifters` (
  `id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'auto incrementing id of each lifter, unique index',
  `user_name` varchar(64) COLLATE utf8_unicode_ci NOT NULL COMMENT 'lifter''s user_name',
  `password_hash` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT 'lifters''s password in salted and hashed format',
  `email` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT 'lifters''s email, unique',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='lifter data';

INSERT INTO `lastlifts`.`lifters` (`user_name`, `password_hash`, `email`) VALUES
  ('demo', '$2y$11$WTorv3w2aDkQKB7t1CvQ5u7LNkSEI.SMygrsFLSreOkAwaAp/SJXK', 'demo@demo.com'),
  ('demo2', '$2y$11$Jo1nuWvg/t2NCNXSwaPzxO1R7bsWcOu0cvdBR0Eb3QCnz1lsD0gLK', 'demo2@demo.com');

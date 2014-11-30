CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `email_hash` varchar(32) NOT NULL,
  `password_hash` varchar(32) NOT NULL,
  `role` tinyint(4) NOT NULL,
  `balance` decimal(18,2) NOT NULL,
  `order_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_by_email_pwd_hash_idx` (`email_hash`,`password_hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

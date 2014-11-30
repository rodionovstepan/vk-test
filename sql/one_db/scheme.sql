CREATE TABLE IF NOT EXISTS `orders` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `content` text NOT NULL,
  `price` decimal(18,2) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `customer_name` text NOT NULL,
  `is_completed` bit(1) NOT NULL,
  `is_deleted` bit(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `orders_by_customer_idx` (`customer_id`,`is_deleted`,`is_completed`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=115 ;

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` text NOT NULL,
  `email` text NOT NULL,
  `email_hash` varchar(32) NOT NULL,
  `password_hash` varchar(32) NOT NULL,
  `role` tinyint(4) NOT NULL,
  `balance` decimal(17,2) NOT NULL,
  `order_count` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `users_by_email_pwd_hash_idx` (`email_hash`,`password_hash`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=24 ;

ALTER TABLE `orders`
  ADD CONSTRAINT `fk_customer_id` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`);

CREATE TABLE IF NOT EXISTS `events` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `order_id` int(11) NOT NULL,
  `type` tinyint(1) NOT NULL,
  `profit` decimal(18,2) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=cp1251 AUTO_INCREMENT=18 ;

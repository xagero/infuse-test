CREATE TABLE `statcounter` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip_address` text,
  `user_agent` text,
  `view_date` int(11) DEFAULT NULL,
  `page_url` text,
  `views_count` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
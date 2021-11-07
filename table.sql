CREATE TABLE `access_token` (
  `app` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  PRIMARY KEY (`app`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

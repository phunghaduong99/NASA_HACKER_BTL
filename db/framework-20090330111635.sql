

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";

--
-- Database: `framework`
--

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(40) unsigned NOT NULL auto_increment,
  `username` varchar(45) default NULL,
  `email` varchar(45) NOT NULL,
  `password` varchar(255) NOT  NULL,
  `profile_title` varchar(255) default NULL ,
  `profile_description` varchar(255) default NULL,
  `profile_url` longblob default NULL,
  `images_users_id` int(40) unsigned default NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  ;

--
-- Dumping data for table `users`
--

INSERT INTO `users` VALUES(1, "Phùng Hà Dương", 'phunghaduong99@gmail.com', 'sdfsdasdff', null, null, null,null,null, null);
INSERT INTO `users` VALUES(2, "Ahihi do ngok", 'mothai@gmail.com', 'sdfsdasdff', null, null, null,null,null, null);
INSERT INTO `users` VALUES(6, "Diem Tien", 'acac@gmail.com', 'sdfsdasdff', null, null, null,null,null, null);

-- --------------------------------------------------------

--
-- Table structure for table `posts`
--

CREATE TABLE `posts` (
  `id` int(40) unsigned NOT NULL auto_increment,
  `user_id` int(40) unsigned NOT NULL,
  `post_description` varchar(255) NOT NULL ,
  `post_image_id` int(40) unsigned NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
  updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  ;



-- Dumping data for table `posts`
--

INSERT INTO `posts` VALUES(3, 1, 'Product A', '34', null, null);
INSERT INTO `posts` VALUES(4, 1, 'Product B', '35', null, null);
INSERT INTO `posts` VALUES(5, 1, 'Product C', '36', null, null);
INSERT INTO `posts` VALUES(2, 6, 'Product C', '32', null, null);
-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `images_users` (
  `id` int(40) unsigned NOT NULL auto_increment,
  `user_id` int(40) unsigned NOT NULL,
  `content` longblob NOT NULL,
  PRIMARY KEY  (`id`, `user_id`)
) ENGINE=InnoDB CHARSET=utf8mb4 ;

--
-- Dumping data for table `image`
--

-- ADD FOREIGN KEY
-- ALTER TABLE users
--     ADD CONSTRAINT fk_img_user
--         FOREIGN KEY (profile_image_id)
--             REFERENCES images (id);
-- ALTER TABLE users
-- DROP FOREIGN KEY fk_img_user;
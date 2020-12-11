

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
  `profile_url` varchar(255) default NULL,
  `profile_image_id` int(40) default NULL,
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

CREATE TABLE `image` (
  `img_id` int(40) unsigned NOT NULL auto_increment,
  `type` varchar(255)  NOT NULL,
  `type_id` int(40) unsigned NOT NULL,
  `content` varchar(255) unsigned NOT NULL,
  PRIMARY KEY  (`img_id`)
) ENGINE=InnoDBDEFAULT CHARSET=utf8mb4 ;

--
-- Dumping data for table `image`
--


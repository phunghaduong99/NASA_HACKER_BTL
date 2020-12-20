

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
  `image_id` int(40) unsigned default NULL,
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
  `image_id` int(40) unsigned NOT NULL,
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

CREATE TABLE `images` (
  `id` int(40) unsigned NOT NULL auto_increment,
  `content` longblob NOT NULL,
  PRIMARY KEY  (`id`)
) ENGINE=InnoDB CHARSET=utf8mb4 ;


CREATE TABLE `follows` (
    `id` int(40) unsigned NOT NULL auto_increment,
    `user_id` int(40) unsigned NOT NULL,
    `follower_id` int(40) unsigned NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  ;

INSERT INTO `follows` (user_id, follower_id) VALUES(1, 2)
INSERT INTO `follows` (user_id, follower_id) VALUES(1, 6)
    INSERT INTO `follows` (user_id, follower_id) VALUES(6, 1)



CREATE TABLE `loves` (
    `id` int(40) unsigned NOT NULL auto_increment,
    `post_id` int(40) unsigned NOT NULL,
    `user_id` int(40) unsigned NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ,
    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY  (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4  ;

--
-- Dumping data for table `image`
--


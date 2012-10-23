CREATE TABLE `mf_system_relation_type` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `relation_name` varchar(255) DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `mf_system_relation` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `object_id` int(11) unsigned NOT NULL,
  `parent_object_id` int(11) unsigned NOT NULL,
  `relation_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `relation_to_relationtype` (`relation_id`),
  KEY `related_by_parent` (`parent_object_id`,`relation_id`),
  KEY `related_by_object` (`object_id`,`relation_id`),
  CONSTRAINT `parent_to_object` FOREIGN KEY (`parent_object_id`) REFERENCES `mf_system_object` (`id`),
  CONSTRAINT `relation_to_relationtype` FOREIGN KEY (`relation_id`) REFERENCES `mf_system_relation_type` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;




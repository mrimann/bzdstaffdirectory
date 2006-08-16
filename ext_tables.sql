#
# Table structure for table 'tx_bzdstaffdirectory_persons_usergroups_mm'
# 
#
CREATE TABLE tx_bzdstaffdirectory_persons_usergroups_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);


#
# Table structure for table 'tx_bzdstaffdirectory_groups_teamleaders_mm'
# 
#
CREATE TABLE tx_bzdstaffdirectory_groups_teamleaders_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);


#
# Table structure for table 'tx_bzdstaffdirectory_pages_persons_mm'
# 
#
CREATE TABLE tx_bzdstaffdirectory_pages_persons_mm (
  uid_local int(11) DEFAULT '0' NOT NULL,
  uid_foreign int(11) DEFAULT '0' NOT NULL,
  tablenames varchar(30) DEFAULT '' NOT NULL,
  sorting int(11) DEFAULT '0' NOT NULL,
  KEY uid_local (uid_local),
  KEY uid_foreign (uid_foreign)
);


#
# Table structure for table 'tx_bzdstaffdirectory_persons'
#
CREATE TABLE tx_bzdstaffdirectory_persons (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	sys_language_uid int(11) DEFAULT '0' NOT NULL,
	l18n_parent int(11) DEFAULT '0' NOT NULL,
	l18n_diffsource mediumblob NOT NULL,
	last_name tinytext NOT NULL,
	first_name tinytext NOT NULL,
	title tinytext NOT NULL,
	image blob NOT NULL,
	usergroups int(11) DEFAULT '0' NOT NULL,
	gender tinytext NOT NULL,
	date_birthdate int(11) DEFAULT '0' NOT NULL,
	date_incompany int(11) DEFAULT '0' NOT NULL,
	function tinytext NOT NULL,
	email tinytext NOT NULL,
	tasks text NOT NULL,
	opinion text NOT NULL,
	location tinytext NOT NULL,
	room tinytext NOT NULL,
	phone tinytext NOT NULL,
	officehours tinytext NOT NULL,
	files blob NOT NULL,
	universal_field_1 tinytext NOT NULL,
	universal_field_2 tinytext NOT NULL,
	universal_field_3 tinytext NOT NULL,
	universal_field_4 tinytext NOT NULL,
	universal_field_5 tinytext NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'tx_bzdstaffdirectory_groups'
#
CREATE TABLE tx_bzdstaffdirectory_groups (
	uid int(11) NOT NULL auto_increment,
	pid int(11) DEFAULT '0' NOT NULL,
	tstamp int(11) DEFAULT '0' NOT NULL,
	crdate int(11) DEFAULT '0' NOT NULL,
	cruser_id int(11) DEFAULT '0' NOT NULL,
	sorting int(10) DEFAULT '0' NOT NULL,
	deleted tinyint(4) DEFAULT '0' NOT NULL,
	hidden tinyint(4) DEFAULT '0' NOT NULL,
	group_name tinytext NOT NULL,
	group_leaders int(11) DEFAULT '0' NOT NULL,
	
	PRIMARY KEY (uid),
	KEY parent (pid)
);



#
# Table structure for table 'pages'
#
CREATE TABLE pages (
	tx_bzdstaffdirectory_bzd_contact_person int(11) DEFAULT '0' NOT NULL
);

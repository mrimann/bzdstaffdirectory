<?php

########################################################################
# Extension Manager/Repository config file for ext: "bzdstaffdirectory"
#
# Auto generated 22-06-2009 12:44
#
# Manual updates:
# Only the data in the array - anything else is removed by next write.
# "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'BZD Staff Directory',
	'description' => 'An extension to show your staff or club-members in different output styles (single persons, lists).',
	'category' => 'plugin',
	'shy' => 0,
	'dependencies' => 'cms,oelib',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => 'pages',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author' => 'Mario Rimann',
	'author_email' => 'mario@screenteam.com',
	'author_company' => 'Screenteam GmbH',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.8.2',
	'_md5_values_when_last_written' => 'a:40:{s:9:"ChangeLog";s:4:"0d16";s:20:"class.ext_update.php";s:4:"727f";s:21:"ext_conf_template.txt";s:4:"de2e";s:12:"ext_icon.gif";s:4:"15b9";s:17:"ext_localconf.php";s:4:"4730";s:14:"ext_tables.php";s:4:"ca00";s:14:"ext_tables.sql";s:4:"15dd";s:24:"ext_typoscript_setup.txt";s:4:"9d0c";s:15:"flexform_ds.xml";s:4:"b09e";s:36:"icon_tx_bzdstaffdirectory_groups.gif";s:4:"401f";s:39:"icon_tx_bzdstaffdirectory_locations.gif";s:4:"934b";s:37:"icon_tx_bzdstaffdirectory_persons.gif";s:4:"78eb";s:13:"locallang.xml";s:4:"0c99";s:16:"locallang_db.xml";s:4:"f0b9";s:7:"tca.php";s:4:"c279";s:8:"todo.txt";s:4:"ff41";s:14:"doc/manual.sxw";s:4:"5fae";s:53:"Mapper/class.tx_bzdstaffdirectory_Mapper_Location.php";s:4:"3558";s:51:"Mapper/class.tx_bzdstaffdirectory_Mapper_Person.php";s:4:"0b22";s:49:"Mapper/class.tx_bzdstaffdirectory_Mapper_Team.php";s:4:"3dc3";s:29:"media/bzd_staff_directory.css";s:4:"728a";s:27:"media/bzdstaff_template.htm";s:4:"66b5";s:28:"media/dummyPictureFemale.jpg";s:4:"1361";s:26:"media/dummyPictureMale.jpg";s:4:"f1f4";s:15:"media/noimg.jpg";s:4:"4d3f";s:51:"Model/class.tx_bzdstaffdirectory_Model_Location.php";s:4:"2e0c";s:49:"Model/class.tx_bzdstaffdirectory_Model_Person.php";s:4:"27ed";s:47:"Model/class.tx_bzdstaffdirectory_Model_Team.php";s:4:"2ee8";s:14:"pi1/ce_wiz.gif";s:4:"9322";s:38:"pi1/class.tx_bzdstaffdirectory_pi1.php";s:4:"2dc5";s:57:"pi1/class.tx_bzdstaffdirectory_pi1_frontEndDetailView.php";s:4:"8bf8";s:51:"pi1/class.tx_bzdstaffdirectory_pi1_frontEndView.php";s:4:"3aec";s:46:"pi1/class.tx_bzdstaffdirectory_pi1_wizicon.php";s:4:"54fd";s:13:"pi1/clear.gif";s:4:"cc11";s:17:"pi1/locallang.xml";s:4:"c3ad";s:24:"pi1/static/editorcfg.txt";s:4:"92fe";s:54:"tests/tx_bzdstaffdirectory_Model_Location_testcase.php";s:4:"6e87";s:52:"tests/tx_bzdstaffdirectory_Model_Person_testcase.php";s:4:"1e3b";s:50:"tests/tx_bzdstaffdirectory_Model_Team_testcase.php";s:4:"f878";s:62:"tests/tx_bzdstaffdirectory_pi1_frontEndDetailView_testcase.php";s:4:"df6d";}',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'php' => '5.1.0-0.0.0',
			'typo3' => '4.2.0-4.3.99',
			'oelib' => '0.6.0-0.6.99',
		),
		'conflicts' => array(
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
);

?>
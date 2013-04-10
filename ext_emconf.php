<?php

########################################################################
# Extension Manager/Repository config file for ext "bzdstaffdirectory".
#
# Auto generated 21-05-2012 23:27
#
# Manual updates:
# Only the data in the array - everything else is removed by next
# writing. "version" and "dependencies" must not be touched!
########################################################################

$EM_CONF[$_EXTKEY] = array(
	'title' => 'BZD Staff Directory',
	'description' => 'An extension to show your staff or club-members in different output styles (single persons, lists).',
	'category' => 'plugin',
	'shy' => 0,
	'dependencies' => 'cms,oelib',
	'conflicts' => 'dbal',
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
	'author_email' => 'typo3-coding@rimann.org',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.10.0-dev',
	'_md5_values_when_last_written' => 'a:47:{s:9:"ChangeLog";s:4:"c90a";s:20:"class.ext_update.php";s:4:"727f";s:21:"ext_conf_template.txt";s:4:"de2e";s:12:"ext_icon.gif";s:4:"15b9";s:17:"ext_localconf.php";s:4:"7816";s:14:"ext_tables.php";s:4:"1936";s:14:"ext_tables.sql";s:4:"75f8";s:24:"ext_typoscript_setup.txt";s:4:"0fb6";s:15:"flexform_ds.xml";s:4:"aa46";s:39:"icon_tx_bzdstaffdirectory_functions.gif";s:4:"46f5";s:36:"icon_tx_bzdstaffdirectory_groups.gif";s:4:"401f";s:39:"icon_tx_bzdstaffdirectory_locations.gif";s:4:"934b";s:37:"icon_tx_bzdstaffdirectory_persons.gif";s:4:"78eb";s:13:"locallang.xml";s:4:"0c99";s:16:"locallang_db.xml";s:4:"afa2";s:7:"tca.php";s:4:"14e4";s:8:"todo.txt";s:4:"ff41";s:53:"Mapper/class.tx_bzdstaffdirectory_Mapper_Function.php";s:4:"645d";s:53:"Mapper/class.tx_bzdstaffdirectory_Mapper_Location.php";s:4:"3558";s:51:"Mapper/class.tx_bzdstaffdirectory_Mapper_Person.php";s:4:"0b22";s:49:"Mapper/class.tx_bzdstaffdirectory_Mapper_Team.php";s:4:"3dc3";s:51:"Model/class.tx_bzdstaffdirectory_Model_Function.php";s:4:"761a";s:51:"Model/class.tx_bzdstaffdirectory_Model_Location.php";s:4:"267e";s:49:"Model/class.tx_bzdstaffdirectory_Model_Person.php";s:4:"ed71";s:47:"Model/class.tx_bzdstaffdirectory_Model_Team.php";s:4:"2ee8";s:14:"doc/manual.sxw";s:4:"7fd0";s:29:"media/bzd_staff_directory.css";s:4:"d986";s:27:"media/bzdstaff_template.htm";s:4:"1c5a";s:28:"media/dummyPictureFemale.jpg";s:4:"1361";s:26:"media/dummyPictureMale.jpg";s:4:"f1f4";s:18:"media/icon_vcf.gif";s:4:"0fb8";s:15:"media/noimg.jpg";s:4:"4d3f";s:14:"pi1/ce_wiz.gif";s:4:"9322";s:38:"pi1/class.tx_bzdstaffdirectory_pi1.php";s:4:"1d9f";s:57:"pi1/class.tx_bzdstaffdirectory_pi1_frontEndDetailView.php";s:4:"5643";s:54:"pi1/class.tx_bzdstaffdirectory_pi1_frontEndVcfView.php";s:4:"b8b8";s:51:"pi1/class.tx_bzdstaffdirectory_pi1_frontEndView.php";s:4:"3aec";s:46:"pi1/class.tx_bzdstaffdirectory_pi1_wizicon.php";s:4:"54fd";s:13:"pi1/clear.gif";s:4:"cc11";s:17:"pi1/locallang.xml";s:4:"c478";s:24:"pi1/static/editorcfg.txt";s:4:"92fe";s:54:"tests/tx_bzdstaffdirectory_Model_Function_testcase.php";s:4:"a0f6";s:54:"tests/tx_bzdstaffdirectory_Model_Location_testcase.php";s:4:"5af1";s:52:"tests/tx_bzdstaffdirectory_Model_Person_testcase.php";s:4:"7a18";s:50:"tests/tx_bzdstaffdirectory_Model_Team_testcase.php";s:4:"f878";s:62:"tests/tx_bzdstaffdirectory_pi1_frontEndDetailView_testcase.php";s:4:"d609";s:59:"tests/tx_bzdstaffdirectory_pi1_frontEndVcfView_testcase.php";s:4:"c684";}',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'php' => '5.3.0-5.4.99',
			'typo3' => '6.0.0-6.1.99',
			'oelib' => '0.6.0-0.7.99',
		),
		'conflicts' => array(
			'dbal' => '',
		),
		'suggests' => array(
		),
	),
	'suggests' => array(
	),
);

?>
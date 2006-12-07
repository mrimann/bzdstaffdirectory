<?php

########################################################################
# Extension Manager/Repository config file for ext: "bzd_staff_directory"
#
# Auto generated 07-12-2006 20:33
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
	'dependencies' => 'cms,bidirectional',
	'conflicts' => '',
	'priority' => '',
	'loadOrder' => '',
	'module' => '',
	'state' => 'beta',
	'internal' => '',
	'uploadfolder' => 1,
	'createDirs' => '',
	'modify_tables' => '',
	'clearCacheOnLoad' => 0,
	'lockType' => '',
	'author' => 'Mario Rimann',
	'author_email' => 'typo3-coding@rimann.li',
	'author_company' => '',
	'CGLcompliance' => '',
	'CGLcompliance_note' => '',
	'version' => '0.3.1',
	'_md5_values_when_last_written' => 'a:143:{s:8:".project";s:4:"0a51";s:9:"ChangeLog";s:4:"52e1";s:10:"README.txt";s:4:"ee2d";s:21:"ext_conf_template.txt";s:4:"0bb0";s:12:"ext_icon.gif";s:4:"15b9";s:17:"ext_localconf.php";s:4:"4730";s:14:"ext_tables.php";s:4:"93b7";s:14:"ext_tables.sql";s:4:"4020";s:24:"ext_typoscript_setup.txt";s:4:"3c42";s:15:"flexform_ds.xml";s:4:"8942";s:36:"icon_tx_bzdstaffdirectory_groups.gif";s:4:"401f";s:37:"icon_tx_bzdstaffdirectory_persons.gif";s:4:"78eb";s:13:"locallang.xml";s:4:"5a5c";s:16:"locallang_db.xml";s:4:"28cd";s:7:"tca.php";s:4:"173c";s:8:"todo.txt";s:4:"b6f5";s:15:".svn/README.txt";s:4:"cf1a";s:15:".svn/empty-file";s:4:"d41d";s:12:".svn/entries";s:4:"8b9a";s:11:".svn/format";s:4:"48a2";s:28:".svn/props/.project.svn-work";s:4:"685f";s:29:".svn/props/ChangeLog.svn-work";s:4:"3c71";s:30:".svn/props/README.txt.svn-work";s:4:"3c71";s:41:".svn/props/ext_conf_template.txt.svn-work";s:4:"3c71";s:34:".svn/props/ext_emconf.php.svn-work";s:4:"3c71";s:32:".svn/props/ext_icon.gif.svn-work";s:4:"945a";s:37:".svn/props/ext_localconf.php.svn-work";s:4:"3c71";s:34:".svn/props/ext_tables.php.svn-work";s:4:"3c71";s:34:".svn/props/ext_tables.sql.svn-work";s:4:"3c71";s:44:".svn/props/ext_typoscript_setup.txt.svn-work";s:4:"3c71";s:35:".svn/props/flexform_ds.xml.svn-work";s:4:"3c71";s:56:".svn/props/icon_tx_bzdstaffdirectory_groups.gif.svn-work";s:4:"945a";s:57:".svn/props/icon_tx_bzdstaffdirectory_persons.gif.svn-work";s:4:"945a";s:33:".svn/props/locallang.xml.svn-work";s:4:"3c71";s:36:".svn/props/locallang_db.xml.svn-work";s:4:"3c71";s:27:".svn/props/tca.php.svn-work";s:4:"3c71";s:28:".svn/props/todo.txt.svn-work";s:4:"685f";s:32:".svn/prop-base/.project.svn-base";s:4:"685f";s:33:".svn/prop-base/ChangeLog.svn-base";s:4:"3c71";s:34:".svn/prop-base/README.txt.svn-base";s:4:"3c71";s:45:".svn/prop-base/ext_conf_template.txt.svn-base";s:4:"3c71";s:38:".svn/prop-base/ext_emconf.php.svn-base";s:4:"3c71";s:36:".svn/prop-base/ext_icon.gif.svn-base";s:4:"945a";s:41:".svn/prop-base/ext_localconf.php.svn-base";s:4:"3c71";s:38:".svn/prop-base/ext_tables.php.svn-base";s:4:"3c71";s:38:".svn/prop-base/ext_tables.sql.svn-base";s:4:"3c71";s:48:".svn/prop-base/ext_typoscript_setup.txt.svn-base";s:4:"3c71";s:39:".svn/prop-base/flexform_ds.xml.svn-base";s:4:"3c71";s:60:".svn/prop-base/icon_tx_bzdstaffdirectory_groups.gif.svn-base";s:4:"945a";s:61:".svn/prop-base/icon_tx_bzdstaffdirectory_persons.gif.svn-base";s:4:"945a";s:37:".svn/prop-base/locallang.xml.svn-base";s:4:"3c71";s:40:".svn/prop-base/locallang_db.xml.svn-base";s:4:"3c71";s:31:".svn/prop-base/tca.php.svn-base";s:4:"3c71";s:32:".svn/prop-base/todo.txt.svn-base";s:4:"685f";s:32:".svn/text-base/.project.svn-base";s:4:"0a51";s:33:".svn/text-base/ChangeLog.svn-base";s:4:"52e1";s:34:".svn/text-base/README.txt.svn-base";s:4:"ee2d";s:45:".svn/text-base/ext_conf_template.txt.svn-base";s:4:"0bb0";s:38:".svn/text-base/ext_emconf.php.svn-base";s:4:"f876";s:36:".svn/text-base/ext_icon.gif.svn-base";s:4:"15b9";s:41:".svn/text-base/ext_localconf.php.svn-base";s:4:"4730";s:38:".svn/text-base/ext_tables.php.svn-base";s:4:"93b7";s:38:".svn/text-base/ext_tables.sql.svn-base";s:4:"4020";s:48:".svn/text-base/ext_typoscript_setup.txt.svn-base";s:4:"3c42";s:39:".svn/text-base/flexform_ds.xml.svn-base";s:4:"8942";s:60:".svn/text-base/icon_tx_bzdstaffdirectory_groups.gif.svn-base";s:4:"401f";s:61:".svn/text-base/icon_tx_bzdstaffdirectory_persons.gif.svn-base";s:4:"78eb";s:37:".svn/text-base/locallang.xml.svn-base";s:4:"5a5c";s:40:".svn/text-base/locallang_db.xml.svn-base";s:4:"28cd";s:31:".svn/text-base/tca.php.svn-base";s:4:"173c";s:32:".svn/text-base/todo.txt.svn-base";s:4:"b6f5";s:29:"media/bzd_staff_directory.css";s:4:"728a";s:27:"media/bzdstaff_template.htm";s:4:"356b";s:28:"media/dummyPictureFemale.jpg";s:4:"1361";s:26:"media/dummyPictureMale.jpg";s:4:"f1f4";s:15:"media/noimg.jpg";s:4:"4d3f";s:21:"media/.svn/README.txt";s:4:"cf1a";s:21:"media/.svn/empty-file";s:4:"d41d";s:18:"media/.svn/entries";s:4:"e573";s:17:"media/.svn/format";s:4:"48a2";s:49:"media/.svn/props/bzd_staff_directory.css.svn-work";s:4:"3c71";s:47:"media/.svn/props/bzdstaff_template.htm.svn-work";s:4:"3c71";s:48:"media/.svn/props/dummyPictureFemale.jpg.svn-work";s:4:"1131";s:46:"media/.svn/props/dummyPictureMale.jpg.svn-work";s:4:"1131";s:35:"media/.svn/props/noimg.jpg.svn-work";s:4:"945a";s:53:"media/.svn/prop-base/bzd_staff_directory.css.svn-base";s:4:"3c71";s:51:"media/.svn/prop-base/bzdstaff_template.htm.svn-base";s:4:"3c71";s:52:"media/.svn/prop-base/dummyPictureFemale.jpg.svn-base";s:4:"1131";s:50:"media/.svn/prop-base/dummyPictureMale.jpg.svn-base";s:4:"1131";s:39:"media/.svn/prop-base/noimg.jpg.svn-base";s:4:"945a";s:53:"media/.svn/text-base/bzd_staff_directory.css.svn-base";s:4:"728a";s:51:"media/.svn/text-base/bzdstaff_template.htm.svn-base";s:4:"356b";s:52:"media/.svn/text-base/dummyPictureFemale.jpg.svn-base";s:4:"1361";s:50:"media/.svn/text-base/dummyPictureMale.jpg.svn-base";s:4:"f1f4";s:39:"media/.svn/text-base/noimg.jpg.svn-base";s:4:"4d3f";s:14:"pi1/ce_wiz.gif";s:4:"9322";s:38:"pi1/class.tx_bzdstaffdirectory_pi1.php";s:4:"c935";s:46:"pi1/class.tx_bzdstaffdirectory_pi1_wizicon.php";s:4:"1d43";s:13:"pi1/clear.gif";s:4:"cc11";s:17:"pi1/locallang.xml";s:4:"f7e9";s:19:"pi1/.svn/README.txt";s:4:"cf1a";s:19:"pi1/.svn/empty-file";s:4:"d41d";s:16:"pi1/.svn/entries";s:4:"2587";s:15:"pi1/.svn/format";s:4:"48a2";s:34:"pi1/.svn/props/ce_wiz.gif.svn-work";s:4:"945a";s:58:"pi1/.svn/props/class.tx_bzdstaffdirectory_pi1.php.svn-work";s:4:"3c71";s:66:"pi1/.svn/props/class.tx_bzdstaffdirectory_pi1_wizicon.php.svn-work";s:4:"3c71";s:33:"pi1/.svn/props/clear.gif.svn-work";s:4:"945a";s:37:"pi1/.svn/props/locallang.xml.svn-work";s:4:"3c71";s:38:"pi1/.svn/prop-base/ce_wiz.gif.svn-base";s:4:"945a";s:62:"pi1/.svn/prop-base/class.tx_bzdstaffdirectory_pi1.php.svn-base";s:4:"3c71";s:70:"pi1/.svn/prop-base/class.tx_bzdstaffdirectory_pi1_wizicon.php.svn-base";s:4:"3c71";s:37:"pi1/.svn/prop-base/clear.gif.svn-base";s:4:"945a";s:41:"pi1/.svn/prop-base/locallang.xml.svn-base";s:4:"3c71";s:38:"pi1/.svn/text-base/ce_wiz.gif.svn-base";s:4:"9322";s:62:"pi1/.svn/text-base/class.tx_bzdstaffdirectory_pi1.php.svn-base";s:4:"c935";s:70:"pi1/.svn/text-base/class.tx_bzdstaffdirectory_pi1_wizicon.php.svn-base";s:4:"1d43";s:37:"pi1/.svn/text-base/clear.gif.svn-base";s:4:"cc11";s:41:"pi1/.svn/text-base/locallang.xml.svn-base";s:4:"f7e9";s:24:"pi1/static/editorcfg.txt";s:4:"92fe";s:26:"pi1/static/.svn/README.txt";s:4:"cf1a";s:26:"pi1/static/.svn/empty-file";s:4:"d41d";s:23:"pi1/static/.svn/entries";s:4:"9efb";s:22:"pi1/static/.svn/format";s:4:"48a2";s:44:"pi1/static/.svn/props/editorcfg.txt.svn-work";s:4:"3c71";s:48:"pi1/static/.svn/prop-base/editorcfg.txt.svn-base";s:4:"3c71";s:48:"pi1/static/.svn/text-base/editorcfg.txt.svn-base";s:4:"92fe";s:14:"doc/manual.sxw";s:4:"93d4";s:19:"doc/wizard_form.dat";s:4:"9116";s:20:"doc/wizard_form.html";s:4:"78fb";s:19:"doc/.svn/README.txt";s:4:"cf1a";s:19:"doc/.svn/empty-file";s:4:"d41d";s:16:"doc/.svn/entries";s:4:"c4a1";s:15:"doc/.svn/format";s:4:"48a2";s:34:"doc/.svn/props/manual.sxw.svn-work";s:4:"945a";s:39:"doc/.svn/props/wizard_form.dat.svn-work";s:4:"3c71";s:40:"doc/.svn/props/wizard_form.html.svn-work";s:4:"3c71";s:38:"doc/.svn/prop-base/manual.sxw.svn-base";s:4:"945a";s:43:"doc/.svn/prop-base/wizard_form.dat.svn-base";s:4:"3c71";s:44:"doc/.svn/prop-base/wizard_form.html.svn-base";s:4:"3c71";s:38:"doc/.svn/text-base/manual.sxw.svn-base";s:4:"93d4";s:43:"doc/.svn/text-base/wizard_form.dat.svn-base";s:4:"9116";s:44:"doc/.svn/text-base/wizard_form.html.svn-base";s:4:"78fb";}',
	'constraints' => array(
		'depends' => array(
			'cms' => '',
			'bidirectional' => '1.1.0',
			'php' => '3.0.0-',
			'typo3' => '3.5.0-',
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
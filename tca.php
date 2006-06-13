<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

$TCA["tx_bzdstaffdirectory_persons"] = Array (
	"ctrl" => $TCA["tx_bzdstaffdirectory_persons"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,last_name,first_name,image,usergroups,tasks"
	),
	"feInterface" => $TCA["tx_bzdstaffdirectory_persons"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"last_name" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.last_name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"first_name" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.first_name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
				"eval" => "required",
			)
		),
		"image" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.image",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 1000,	
				"uploadfolder" => "uploads/tx_bzdstaffdirectory",
				"show_thumbs" => 1,	
				"size" => 1,	
				"minitems" => 0,
				"maxitems" => 1,
			)
		),
		"usergroups" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.usergroups",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_bzdstaffdirectory_groups",	
				"foreign_table_where" => "ORDER BY tx_bzdstaffdirectory_groups.uid",	
				"size" => 4,	
				"minitems" => 0,
				"maxitems" => 20,	
				"MM" => "tx_bzdstaffdirectory_persons_usergroups_mm",	
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"list" => Array(
						"type" => "script",
						"title" => "List",
						"icon" => "list.gif",
						"params" => Array(
							"table"=>"tx_bzdstaffdirectory_groups",
							"pid" => "###CURRENT_PID###",
						),
						"script" => "wizard_list.php",
					),
				),
			)
		),
		"function" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.function",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"email" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.email",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"tasks" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.tasks",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",
			)
		),
		"opinion" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.opinion",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",
			)
		),
		"location" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.location",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
			)
		),
		"room" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.room",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
			)
		),
		"phone" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.phone",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
			)
		),
		"officehours" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.officehours",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
			)
		),
		"files" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.files",		
			"config" => Array (
				"type" => "group",
				"internal_type" => "file",
				"allowed" => $GLOBALS["TYPO3_CONF_VARS"]["GFX"]["imagefile_ext"],	
				"max_size" => 1500,	
				"uploadfolder" => "uploads/tx_bzdstaffdirectory",
				"show_thumbs" => 1,	
				"size" => 5,	
				"minitems" => 0,
				"maxitems" => 5,
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, last_name, first_name, email, phone, function, image, usergroups, location, room, officehours, tasks, opinion, files")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);



$TCA["tx_bzdstaffdirectory_groups"] = Array (
	"ctrl" => $TCA["tx_bzdstaffdirectory_groups"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,group_name"
	),
	"feInterface" => $TCA["tx_bzdstaffdirectory_groups"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (		
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"group_name" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_groups.group_name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"group_leaders" => Array (		
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_groups.group_leaders",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_bzdstaffdirectory_persons",	
				"foreign_table_where" => "ORDER BY tx_bzdstaffdirectory_persons.uid",	
				"size" => 4,	
				"minitems" => 0,
				"maxitems" => 20,	
				"MM" => "tx_bzdstaffdirectory_groups_teamleaders_mm",	
				"wizards" => Array(
					"_PADDING" => 2,
					"_VERTICAL" => 1,
					"list" => Array(
						"type" => "script",
						"title" => "List",
						"icon" => "list.gif",
						"params" => Array(
							"table"=>"tx_bzdstaffdirectory_persons",
							"pid" => "###CURRENT_PID###",
						),
						"script" => "wizard_list.php",
					),
				),
			)
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, group_name, group_leaders")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);
?>

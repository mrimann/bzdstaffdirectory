<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');

// get extension confArr
$confArr = unserialize($GLOBALS['TYPO3_CONF_VARS']['EXT']['extConf']['bzd_staff_directory']);

// l10n_mode for text fields
$l10n_mode = ($confArr['l10n_mode_prefixLangTitle']?'prefixLangTitle':'');

// l10n_mode for text fields that probably won't be translated (like the name, phone number and so on)
$l10n_mode_merge = '';//($confArr['l10n_mode_prefixLangTitle']?'mergeIfNotBlank':'');

// l10n_mode for the image field
$l10n_mode_image = ($confArr['l10n_mode_imageExclude']?'exclude':'mergeIfNotBlank');

// hide new localizations
$hideNewLocalizations = ($confArr['hideNewLocalizations']?'mergeIfNotBlank':'');


$TCA["tx_bzdstaffdirectory_persons"] = Array (
	"ctrl" => $TCA["tx_bzdstaffdirectory_persons"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,last_name,first_name,image,usergroups,tasks"
	),
	"feInterface" => $TCA["tx_bzdstaffdirectory_persons"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			'l10n_mode' => $hideNewLocalizations,
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"last_name" => Array (
			'l10n_mode' => $l10n_mode_merge,
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.last_name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"first_name" => Array (
			'l10n_mode' => $l10n_mode_merge,
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.first_name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
				"eval" => "required",
			)
		),
		"title" => Array (
			'l10n_mode' => $l10n_mode,	
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.title",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"image" => Array (
			'l10n_mode' => $l10n_mode_image,
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
			'l10n_mode' => 'exclude',
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.usergroups",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_bzdstaffdirectory_groups",	
				"foreign_table_where" => "ORDER BY tx_bzdstaffdirectory_groups.uid",	
				"size" => 4,	
				"minitems" => 0,
				"maxitems" => 99,	
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
		'gender' => Array (
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 0,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.gender',		
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array ('LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.gender.notSet', 0),
					Array ('LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.gender.male', 1),
					Array ('LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.gender.female', 2)
				),
			)
		),
		'date_incompany' => Array (
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 0,
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.date_incompany',
			'config' => Array (
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'date',
				'default' => '0'
			)
		),
		'date_birthdate' => Array (
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 0,
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.date_birthdate',
			'config' => Array (
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'date',
				'default' => '0'
			)
		),
		"function" => Array (
			'l10n_mode' => $l10n_mode,
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.function",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"email" => Array (
			'l10n_mode' => $l10n_mode_merge,
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.email",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
				"eval" => "required",
			)
		),
		"tasks" => Array (
			'l10n_mode' => $l10n_mode,
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.tasks",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",
			)
		),
		"opinion" => Array (
			'l10n_mode' => $l10n_mode,
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.opinion",		
			"config" => Array (
				"type" => "text",
				"cols" => "30",	
				"rows" => "5",
			)
		),
		"location" => Array (
			'l10n_mode' => $l10n_mode,
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.location",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
			)
		),
		"room" => Array (
			'l10n_mode' => $l10n_mode_merge,
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.room",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
			)
		),
		"phone" => Array (
			'l10n_mode' => $l10n_mode_merge,
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.phone",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
			)
		),
		"officehours" => Array (
			'l10n_mode' => $l10n_mode,
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.officehours",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",	
			)
		),
		"files" => Array (
			'l10n_mode' => $l10n_mode_merge,
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
		'sys_language_uid' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => Array (
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
				'foreign_table' => 'tx_bzdstaffdirectory_persons',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_persons.uid=###CURRENT_PID### AND tx_bzdstaffdirectory_persons.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => Array(
			'config'=>array(
				'type'=>'passthrough')
		),
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, last_name, first_name, title, email, phone, function, gender, date_birthdate, date_incompany, image, usergroups, location, room, officehours, tasks, opinion, files")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);

// add the universal fields to the TCA Array if they are configured
if ($confArr['useUniversalField_1'] && !empty($confArr['fieldNameUniversalField_1'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_1';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_1'] = Array (
		'l10n_mode' => $l10n_mode,
		'exclude' => 0,
		'label' => $confArr['fieldNameUniversalField_1'],		
		'config' => Array (
			'type' => 'input',	
			'size' => '30',	
		)
	);
}

if ($confArr['useUniversalField_2'] && !empty($confArr['fieldNameUniversalField_2'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_2';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_2'] = Array (
		'l10n_mode' => $l10n_mode,
		'exclude' => 0,
		'label' => $confArr['fieldNameUniversalField_2'],		
		'config' => Array (
			'type' => 'input',	
			'size' => '30',	
		)
	);
}

if ($confArr['useUniversalField_3'] && !empty($confArr['fieldNameUniversalField_3'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_3';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_3'] = Array (
		'l10n_mode' => $l10n_mode,
		'exclude' => 0,
		'label' => $confArr['fieldNameUniversalField_3'],		
		'config' => Array (
			'type' => 'input',	
			'size' => '30',	
		)
	);
}

if ($confArr['useUniversalField_4'] && !empty($confArr['fieldNameUniversalField_4'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_4';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_4'] = Array (
		'l10n_mode' => $l10n_mode,
		'exclude' => 0,
		'label' => $confArr['fieldNameUniversalField_4'],		
		'config' => Array (
			'type' => 'input',	
			'size' => '30',	
		)
	);
}

if ($confArr['useUniversalField_5'] && !empty($confArr['fieldNameUniversalField_5'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_5';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_5'] = Array (
		'l10n_mode' => $l10n_mode,
		'exclude' => 0,
		'label' => $confArr['fieldNameUniversalField_5'],		
		'config' => Array (
			'type' => 'input',	
			'size' => '30',	
		)
	);
}

$TCA["tx_bzdstaffdirectory_groups"] = Array (
	"ctrl" => $TCA["tx_bzdstaffdirectory_groups"]["ctrl"],
	"interface" => Array (
		"showRecordFieldList" => "hidden,group_name"
	),
	"feInterface" => $TCA["tx_bzdstaffdirectory_groups"]["feInterface"],
	"columns" => Array (
		"hidden" => Array (
			'l10n_mode' => $hideNewLocalizations,
			"exclude" => 1,
			"label" => "LLL:EXT:lang/locallang_general.php:LGL.hidden",
			"config" => Array (
				"type" => "check",
				"default" => "0"
			)
		),
		"group_name" => Array (
			'l10n_mode' => $l10n_mode,
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_groups.group_name",		
			"config" => Array (
				"type" => "input",	
				"size" => "30",
			)
		),
		"group_leaders" => Array (
			'l10n_mode' => 'exclude',
			"exclude" => 0,		
			"label" => "LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_groups.group_leaders",		
			"config" => Array (
				"type" => "select",	
				"foreign_table" => "tx_bzdstaffdirectory_persons",	
				"foreign_table_where" => "ORDER BY tx_bzdstaffdirectory_persons.uid",	
				"size" => 4,	
				"minitems" => 0,
				"maxitems" => 99,	
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
		'team_members' => Array (
			'l10n_mode' => 'exclude',
			'exclude' => 0,
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_groups.group_members',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'tx_bzdstaffdirectory_persons',
				'foreign_table_where' => 'ORDER BY tx_bzdstaffdirectory_persons.last_name',
				'size' => 4,
				'minitems' => 0,
				'maxitems' => 9999,
				'MM' => 'tx_bzdstaffdirectory_persons_usergroups_mm | foreign',
				'wizards' => Array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'list' => Array(
						'type' => 'script',
						'title' => 'List',
						'icon' => 'list.gif',
						'params' => Array(
							'table' => 'tx_bzdstaffdirectory_persons',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					)
				)
			)
		),
		'sys_language_uid' => Array (
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => Array (
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => Array(
					Array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					Array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => Array (
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => Array (
				'type' => 'select',
				'items' => Array (
					Array('', 0),
				),
				'foreign_table' => 'tx_bzdstaffdirectory_groups',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_groups.uid=###CURRENT_PID### AND tx_bzdstaffdirectory_groups.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => Array(
			'config'=>array(
				'type'=>'passthrough')
		)
	),
	"types" => Array (
		"0" => Array("showitem" => "hidden;;1;;1-1-1, group_name, group_leaders, team_members")
	),
	"palettes" => Array (
		"1" => Array("showitem" => "")
	)
);
?>

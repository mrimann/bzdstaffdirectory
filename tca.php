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



/*
 * This is the default Table Configuration Array for the persons table.
 */
$TCA['tx_bzdstaffdirectory_persons'] = Array (
	'ctrl' => $TCA['tx_bzdstaffdirectory_persons']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,last_name,first_name,image,usergroups,tasks'
	),
	'feInterface' => $TCA['tx_bzdstaffdirectory_persons']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'l10n_mode' => $hideNewLocalizations,
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'last_name' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.last_name',		
			'config' => array(
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required'
			)
		),
		'first_name' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.first_name',		
			'config' => array(
				'type' => 'input',	
				'size' => '30',
				'eval' => 'required'
			)
		),
		'title' => array(
			'l10n_mode' => $l10n_mode,	
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.title',		
			'config' => array(
				'type' => 'input',	
				'size' => '30'
			)
		),
		'image' => array(
			'l10n_mode' => $l10n_mode_image,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.image',		
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],	
				'max_size' => 1000,	
				'uploadfolder' => 'uploads/tx_bzdstaffdirectory',
				'show_thumbs' => 1,	
				'size' => 1,	
				'minitems' => 0,
				'maxitems' => 1
			)
		),
		'usergroups' => array(
			'l10n_mode' => 'exclude',
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.usergroups',		
			'config' => array(
				'type' => 'select',	
				'foreign_table' => 'tx_bzdstaffdirectory_groups',	
				'foreign_table_where' => 'ORDER BY tx_bzdstaffdirectory_groups.uid',	
				'size' => 4,	
				'minitems' => 0,
				'maxitems' => 99,	
				'MM' => 'tx_bzdstaffdirectory_persons_usergroups_mm',	
				'wizards' => array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'list' => array(
						'type' => 'script',
						'title' => 'List',
						'icon' => 'list.gif',
						'params' => array(
							'table'=>'tx_bzdstaffdirectory_groups',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php'
					)
				)
			)
		),
		'gender' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.gender',		
			'config' => array(
				'type' => 'select',
				'items' => array(
					array('LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.gender.notSet', 0),
					array('LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.gender.male', 1),
					array('LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.gender.female', 2)
				)
			)
		),
		'date_incompany' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.date_incompany',
			'config' => array(
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'date',
				'default' => '0'
			)
		),
		'date_birthdate' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.date_birthdate',
			'config' => array(
				'type' => 'input',
				'size' => '12',
				'max' => '20',
				'eval' => 'date',
				'default' => '0'
			)
		),
		'function' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.function',		
			'config' => array(
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required'
			)
		),
		'email' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.email',		
			'config' => array(
				'type' => 'input',	
				'size' => '30',	
				'eval' => 'required'
			)
		),
		'tasks' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.tasks',		
			'config' => array(
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5'
			)
		),
		'opinion' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.opinion',		
			'config' => array(
				'type' => 'text',
				'cols' => '30',	
				'rows' => '5'
			)
		),
		'location' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.location',		
			'config' => array(
				'type' => 'input',	
				'size' => '30'
			)
		),
		'room' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.room',		
			'config' => array(
				'type' => 'input',	
				'size' => '30'
			)
		),
		'phone' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.phone',		
			'config' => array(
				'type' => 'input',	
				'size' => '30'
			)
		),
		'officehours' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.officehours',		
			'config' => array(
				'type' => 'input',	
				'size' => '30'
			)
		),
		'xing_profile_url' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.xing_profile_url',
			'config' => array(
				'type' => 'input',	
				'size' => '30'
			)
		),
		'files' => array(
			'l10n_mode' => $l10n_mode_merge,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_persons.files',		
			'config' => array(
				'type' => 'group',
				'internal_type' => 'file',
				'allowed' => $GLOBALS['TYPO3_CONF_VARS']['GFX']['imagefile_ext'],	
				'max_size' => 1500,	
				'uploadfolder' => 'uploads/tx_bzdstaffdirectory',
				'show_thumbs' => 1,	
				'size' => 5,	
				'minitems' => 0,
				'maxitems' => 5
			)
		),
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array(
						'',
						0
					)
				),
				'foreign_table' => 'tx_bzdstaffdirectory_persons',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_persons.uid=###CURRENT_PID### AND tx_bzdstaffdirectory_persons.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array(
			'config'=>array(
				'type'=>'passthrough')
		)
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, last_name, first_name, title, email, phone, function, gender, date_birthdate, date_incompany, image, usergroups, location, room, officehours, xing_profile_url, tasks, opinion;;;richtext[paste|bold|italic|formatblock|class|left|center|right|orderedlist|unorderedlist|outdent|indent|link|image]:rte_transform[mode=ts_css], files')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);

// add the universal fields to the TCA Array if they are configured
if ($confArr['useUniversalField_1'] && !empty($confArr['fieldNameUniversalField_1'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_1';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_1'] = array(
		'l10n_mode' => $l10n_mode,
		'exclude' => 1,
		'label' => $confArr['fieldNameUniversalField_1'],
		'config' => array(
			'type' => 'input',
			'size' => '30'
		)
	);
}

if ($confArr['useUniversalField_2'] && !empty($confArr['fieldNameUniversalField_2'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_2';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_2'] = array(
		'l10n_mode' => $l10n_mode,
		'exclude' => 1,
		'label' => $confArr['fieldNameUniversalField_2'],
		'config' => array(
			'type' => 'input',
			'size' => '30'
		)
	);
}

if ($confArr['useUniversalField_3'] && !empty($confArr['fieldNameUniversalField_3'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_3';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_3'] = array(
		'l10n_mode' => $l10n_mode,
		'exclude' => 1,
		'label' => $confArr['fieldNameUniversalField_3'],
		'config' => array(
			'type' => 'input',
			'size' => '30'
		)
	);
}

if ($confArr['useUniversalField_4'] && !empty($confArr['fieldNameUniversalField_4'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_4';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_4'] = array(
		'l10n_mode' => $l10n_mode,
		'exclude' => 1,
		'label' => $confArr['fieldNameUniversalField_4'],
		'config' => array(
			'type' => 'input',
			'size' => '30'
		)
	);
}

if ($confArr['useUniversalField_5'] && !empty($confArr['fieldNameUniversalField_5'])) {
	$TCA['tx_bzdstaffdirectory_persons']['types']['0']['showitem'] .= ', universal_field_5';
	$TCA['tx_bzdstaffdirectory_persons']['columns']['universal_field_5'] = array(
		'l10n_mode' => $l10n_mode,
		'exclude' => 1,
		'label' => $confArr['fieldNameUniversalField_5'],
		'config' => array(
			'type' => 'input',
			'size' => '30'
		)
	);
}



/*
 * This is the default Table Configuration Array for the teams table.
 */
$TCA['tx_bzdstaffdirectory_groups'] = array(
	'ctrl' => $TCA['tx_bzdstaffdirectory_groups']['ctrl'],
	'interface' => array(
		'showRecordFieldList' => 'hidden,group_name'
	),
	'feInterface' => $TCA['tx_bzdstaffdirectory_groups']['feInterface'],
	'columns' => array(
		'hidden' => array(
			'l10n_mode' => $hideNewLocalizations,
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.hidden',
			'config' => array(
				'type' => 'check',
				'default' => '0'
			)
		),
		'group_name' => array(
			'l10n_mode' => $l10n_mode,
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_groups.group_name',		
			'config' => array(
				'type' => 'input',	
				'size' => '30',
			)
		),
		'group_leaders' => array(
			'l10n_mode' => 'exclude',
			'exclude' => 1,		
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_groups.group_leaders',		
			'config' => array(
				'type' => 'select',	
				'foreign_table' => 'tx_bzdstaffdirectory_persons',	
				'foreign_table_where' => 'ORDER BY tx_bzdstaffdirectory_persons.uid',	
				'size' => 4,	
				'minitems' => 0,
				'maxitems' => 99,	
				'MM' => 'tx_bzdstaffdirectory_groups_teamleaders_mm',	
				'wizards' => array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'list' => array(
						'type' => 'script',
						'title' => 'List',
						'icon' => 'list.gif',
						'params' => array(
							'table'=>'tx_bzdstaffdirectory_persons',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					)
				)
			)
		),
		'team_members' => array(
			'l10n_mode' => 'exclude',
			'exclude' => 1,
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_groups.group_members',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'tx_bzdstaffdirectory_persons',
				'foreign_table_where' => 'ORDER BY tx_bzdstaffdirectory_persons.last_name',
				'size' => 4,
				'minitems' => 0,
				'maxitems' => 9999,
				'MM' => 'tx_bzdstaffdirectory_persons_usergroups_mm',
				'MM_foreign_select' => 1,
				'MM_opposite_field' => 'uid_local',
				'wizards' => array(
					'_PADDING' => 2,
					'_VERTICAL' => 1,
					'list' => array(
						'type' => 'script',
						'title' => 'List',
						'icon' => 'list.gif',
						'params' => array(
							'table' => 'tx_bzdstaffdirectory_persons',
							'pid' => '###CURRENT_PID###',
						),
						'script' => 'wizard_list.php',
					)
				)
			)
		),

		'infopage' => array(
			'l10n_mode' => 'exclude',
			'exclude' => 1,
			'label' => 'LLL:EXT:bzd_staff_directory/locallang_db.php:tx_bzdstaffdirectory_groups.infopage',
			'config' => array(
				'type' => 'group',
				'internal_type' => 'db',
				'allowed' => 'pages',
				'size' => 1,
				'minitems' => 0,
				'maxitems' => 1,
				'show_thumbs' => 1
			)
		),
		'sys_language_uid' => array(
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.language',
			'config' => array(
				'type' => 'select',
				'foreign_table' => 'sys_language',
				'foreign_table_where' => 'ORDER BY sys_language.title',
				'items' => array(
					array('LLL:EXT:lang/locallang_general.php:LGL.allLanguages',-1),
					array('LLL:EXT:lang/locallang_general.php:LGL.default_value',0)
				)
			)
		),
		'l18n_parent' => array(
			'displayCond' => 'FIELD:sys_language_uid:>:0',
			'exclude' => 1,
			'label' => 'LLL:EXT:lang/locallang_general.php:LGL.l18n_parent',
			'config' => array(
				'type' => 'select',
				'items' => array(
					array(
						'',
						0
					),
				),
				'foreign_table' => 'tx_bzdstaffdirectory_groups',
				'foreign_table_where' => 'AND tx_bzdstaffdirectory_groups.uid=###CURRENT_PID### AND tx_bzdstaffdirectory_groups.sys_language_uid IN (-1,0)',
			)
		),
		'l18n_diffsource' => array(
			'config'=>array(
				'type'=>'passthrough')
		)
	),
	'types' => array(
		'0' => array('showitem' => 'hidden;;1;;1-1-1, group_name, group_leaders, team_members, infopage')
	),
	'palettes' => array(
		'1' => array('showitem' => '')
	)
);
?>

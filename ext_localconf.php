<?php
if (!defined ('TYPO3_MODE')) 	die ('Access denied.');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_bzdstaffdirectory_persons=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_bzdstaffdirectory_groups=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_bzdstaffdirectory_locations=1
');
t3lib_extMgm::addUserTSConfig('
	options.saveDocNew.tx_bzdstaffdirectory_functions=1
');

  ## Extending TypoScript from static template uid=43 to set up userdefined tag:
t3lib_extMgm::addTypoScript($_EXTKEY,'editorcfg','
	tt_content.CSS_editor.ch.tx_bzdstaffdirectory_pi1 = < plugin.tx_bzdstaffdirectory_pi1.CSS_editor
',43);


t3lib_extMgm::addPItoST43($_EXTKEY,'pi1/class.tx_bzdstaffdirectory_pi1.php','_pi1','list_type',1);


$TYPO3_CONF_VARS['FE']['eID_include']['tx_bzdstaffdirectory_vcf'] = 'EXT:bzdstaffdirectory/class.tx_bzdstaffdirectory_eID.php';

?>
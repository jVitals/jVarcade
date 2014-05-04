<?php
/**
 * @package		jVArcade
 * @version		2.11
 * @date		2014-01-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.folder');
jimport('joomla.filesystem.file');
jimport('joomla.archive.archive');

class pkg_jvarcadeInstallerScript {
	
	
	function install($parent){
		$db = JFactory::getDbo();
     	$query = 'INSERT INTO '. $db->quoteName('#__postinstall_messages') .
              ' ( `extension_id`, 
                  `title_key`, 
                  `description_key`, 
                  `action_key`, 
                  `language_extension`, 
                  `language_client_id`, 
                  `type`, 
                  `action_file`, 
                  `action`, 
                  `condition_file`, 
                  `condition_method`, 
                  `version_introduced`, 
                  `enabled`) VALUES '
              .'( 700,
               "COM_JVARCADE_POST_INSTALL_TITLE", 
               "COM_JVARCADE_POST_INSTALL_BODY", 
               "COM_JVARCADE_POST_INSTALL_ACTION",
               "com_jvarcade",
                1,
               "action", 
               "admin://components/com_jvarcade/install/postinstall.php",
               "jvaPostinstallAction", 
               "admin://components/com_jvarcade/install/postinstall.php", 
               "jvaPostinstallCondition", 
               "2.11", 
               1)';
		$db->setQuery($query);
     	$db->execute();
	}
	
	function postflight($parent) {
		$errnum = 0;
		$install = '<div style="align:left;">';
		require_once (JPATH_ROOT . '/components/com_jvarcade/include/define.php');
        $table = '#__jvarcade_settings';
		$db = JFactory::getDBO();
		?>
			<center><h1>Installation of jVArcade <?php echo JVA_VERSION; ?> </h1></center>
		<?php
			$db->setQuery('SELECT COUNT(*) FROM ' . $db->quoteName($table));
			$records_exist = @$db->loadResult();
			if ($records_exist = 53) {
				$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'tick.png" align="absmiddle"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_COLUMNS_OK') .'<br />';
			} else {
				$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'red_x.png" align="absmiddle"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_DEFAULT_FAILED') .'<br />';
				$errnum++;
			}
			
			if (JFolder::exists(JPATH_ROOT . '/images/jvarcade') && JFolder::exists(JPATH_ROOT . '/arcade')) {
				$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'tick.png" align="absmiddle"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_MOVEFOLDERS_OK') .'<br />';
			} else {
				$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'red_x.png" align="absmiddle"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_MOVEFOLDERS_FAILED') .'<br />';
				$errnum++;
			}
			
			if (JFile::exists(JPATH_ROOT . '/newscore.php') && JFile::exists(JPATH_ROOT . '/arcade.php') && JFile::exists(JPATH_ROOT . '/crossdomain.xml')) {
				$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'tick.png"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_FILECOPY') .'<br />';
			} else {
				$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'red_x.png"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_FILECOPY_FAILED') .'<br />';
				$errnum++;
			}
			
			if ($errnum == 0){
				$install .= '<br /><h6><img src="'. JVA_IMAGES_SITEPATH. 'tick.png"/>' . JText::_('COM_JVARCADE_INSTALLER_SUCCESS') . '<br /></h6>';
			}
			
		echo $install;
		echo "</div><br /><br /><br />";
	}
}
?>
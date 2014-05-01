<?php
/**
 * @package		jVArcade
 * @version		2.1
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
		
class com_jvarcadeInstallerScript {		
		
		function preflight($type, $parent) {
			
	
			
			
			
		}//End Preflight
		
		
		function install($parent) {
			$install = '<div style="align:left;">';
			require_once (JPATH_ROOT . '/components/com_jvarcade/include/define.php');
			$backendPath = JPATH_ROOT . '/administrator/components/com_jvarcade/';
			$frontendPath = JPATH_ROOT . '/components/com_jvarcade/';
			$table = '#__jvarcade_settings';
			$db = JFactory::getDBO();
			?>
			<center><h1>Installation of jVArcade <?php echo JVA_VERSION; ?> </h1></center>
			<?php
			$db->setQuery('SELECT COALESCE(COUNT(*), 0) FROM ' . $db->quoteName($table));
			$records_exist = @$db->loadResult();
			if (!(int)$records_exist) {
					$query = file_get_contents(JPATH_ADMINISTRATOR . '/components/com_jvarcade/install/sql/install.defaults.sql');
        			$queries = $db->splitSql($query);
        				foreach ($queries as $querie) { 
            			$db->setQuery($querie);
            			$db->execute();
            			$error = $db->getErrorNum();
                    		
						}
						if ($error) {
							JFactory::getApplication()->enqueueMessage(JText::_('COM_JVARCADE_INSTALLER_UPGRADE_DEFAULT_FAILED'), 'error');
						} else {
							$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'tick.png" align="absmiddle"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_DEFAULT_OK') .'<br />';
						}
        	} else {
        		$result = $db->setQuery("SHOW COLUMNS FROM `#__jvarcade_games` LIKE 'gsafe'");
        		$db->execute($result);
        		$exists = $db->getNumRows();
        		if ($exists == 0){
        			$query = file_get_contents(JPATH_ADMINISTRATOR . '/components/com_jvarcade/install/sql/update.tables.sql');
        			$queries = $db->splitSql($query);
        			foreach ($queries as $querie) {
        				$db->setQuery($querie);
        				try {
        					$db->execute();
        				} catch (RuntimeException $e) {
        					$ec = $e->getCode();
        				}
            			
        			}		
        			if($ec == 1062){
        				$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'tick.png" align="absmiddle"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_COLUMNS_OK') .'<br />';
        			}else{
        				$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'tick.png" align="absmiddle"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_COLUMNS_OK') .'<br />';
        			}
        		} elseif ($exists == 1) {
        			$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'tick.png" align="absmiddle"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADED_COLUMNS_OK') .'<br />';
        		}
        			
        		
			}
		// Create /arcade/gamedata directory. This prevents menu item with alias arcade being created which breaks gamedata rewrite rules
		
		if (!JFolder::exists(JPATH_ROOT . '/arcade')) {
			@JFolder::create(JPATH_ROOT . '/arcade', 0775);
			@JFolder::create(JPATH_ROOT . '/arcade/gamedata', 0775);
		}
		// ONLY FOR FRESH INSTALL - MOVE FOLDERS
		
		if (!JFolder::exists(JPATH_ROOT . '/images/jvarcade')) {

			$movefolders = array(
				array($frontendPath . 'images', JPATH_ROOT . '/images/jvarcade/images'),
				array($frontendPath . 'games', JPATH_ROOT . '/images/jvarcade/games'),
			);
			JFolder::create(JPATH_ROOT . '/images/jvarcade');
			foreach ($movefolders as $folder) {
				if (!JFolder::exists($folder[1])) {
					$mvres = JFolder::move($folder[0], $folder[1]);
				}
			}
			if ($mvres === true) {
				$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'tick.png" align="absmiddle"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_MOVEFOLDERS_OK') .'<br />';
			} else {
				$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'red_x.png" align="absmiddle"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_MOVEFOLDERS_FAILED') .'<br />';
			}
		}
		
		// (RE)CREATE THE CATCH FILES IN THE JOOMLA ROOT
		
		$install .= '<img src="'. JVA_IMAGES_SITEPATH. 'tick.png"/>' . JText::_('COM_JVARCADE_INSTALLER_UPGRADE_FILECOPY') .'<br />';
		$copyfiles = array(
			JPATH_ROOT . '/arcade/index.html' => '<html><body bgcolor="#FFFFFF"></body></html>',
			JPATH_ROOT . '/arcade/gamedata/index.html' => '<html><body bgcolor="#FFFFFF"></body></html>',
			JPATH_ROOT . '/newscore.php' => '<?php require_once \'./index.php\';',
			JPATH_ROOT . '/arcade.php' => '<?php require_once \'./index.php\';',
			JPATH_ROOT . '/crossdomain.xml' => '<?xml version="1.0"?>' . "\n" . '<!DOCTYPE cross-domain-policy SYSTEM "http://www.macromedia.com/xml/dtds/cross-domain-policy.dtd">' . "\n" . '<cross-domain-policy>' . "\n\t" . '<allow-access-from domain="www.mochiads.com" />' . "\n\t" . '<allow-access-from domain="x.mochiads.com" />' . "\n\t" . '<allow-access-from domain="xs.mochiads.com" />' . "\n" . '</cross-domain-policy>' . "\n",
		);
		foreach ($copyfiles as $filename => $content) {
			if(JFile::exists($filename)) @JFile::delete($filename);
			file_put_contents($filename, $content);
		}
		
		echo $install;
		echo "</div><br /><br /><br />";
	}//end install function
	
	function uninstall($parent) {
		
		
		
		
	}//end uninstall function
	
	
	function postflight ($type, $parent) {
		
		
		
		
		
	}//end postflight function



}
?>
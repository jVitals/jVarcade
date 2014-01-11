<?php
/**
* This file is part of the jVArcade distribution. 
* Detailed copyright and licensing information can be found
* in the gpl-3.0.txt file which should be included in the distribution.
* 
* @version		2.01 2013-07-29 nuclear-head
* @copyright	2011-2013 jVitals
* @license		GPLv3 Open Source
* @link			http://jvitals.com
* @since		File available since initial release
*/

// no direct access
defined('_JEXEC') or die('Restricted access');


		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		jimport('joomla.archive.archive');
		
class com_jvarcadeInstallerScript {		
		
		function preflight($type, $parent){
			
	
			
			
			
		}//End Preflight
		
		
		function install($parent){
			$backendPath = JPATH_ROOT . '/administrator/components/com_jvarcade/';
			$frontendPath = JPATH_ROOT . '/components/com_jvarcade/';
			
			$db = JFactory::getDBO();
        $query = file_get_contents(JPATH_ADMINISTRATOR . DIRECTORY_SEPARATOR . 'components' . DIRECTORY_SEPARATOR . 'com_jvarcade' . DIRECTORY_SEPARATOR . 'install' . DIRECTORY_SEPARATOR . 'sql' . DIRECTORY_SEPARATOR . 'install_default.sql');
        $queries = $db->splitSql($query);
        foreach ($queries as $querie) { 
            $db->setQuery($querie);
            $db->query();
            $error = $db->getErrorNum();
                    if ($error) { 
                      JFactory::getApplication()->enqueueMessage(JText::_('COM_JVARCADE_INSTALLER_UPGRADE_DEFAULT_FAILED'), 'error');
				} else {
					JFactory::getApplication()->enqueueMessage(JText::_('COM_JVARCADE_INSTALLER_UPGRADE_DEFAULT_OK'), 'message');
				}
                    
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
					if ($mvres === true) {
						JFactory::getApplication()->enqueueMessage(JText::sprintf('COM_JVARCADE_INSTALLER_UPGRADE_MOVEFOLDERS_OK', $folder[0], $folder[1]), 'message');
					} else {
						JFactory::getApplication()->enqueueMessage(JText::_('COM_JVARCADE_INSTALLER_UPGRADE_MOVEFOLDERS_FAILED'), 'error');
							}
				}
			}
		}
		
		
		// (RE)CREATE THE CATCH FILES IN THE JOOMLA ROOT
		
		JFactory::getApplication()->enqueueMessage(JText::_('COM_JVARCADE_INSTALLER_UPGRADE_FILECOPY'), 'message');
		$copyfiles = array(
			JPATH_ROOT . '/newscore.php' => '<?php require_once \'./index.php\';',
			JPATH_ROOT . '/arcade.php' => '<?php require_once \'./index.php\';',
			JPATH_ROOT . '/crossdomain.xml' => '<?xml version="1.0"?>' . "\n" . '<!DOCTYPE cross-domain-policy SYSTEM "http://www.macromedia.com/xml/dtds/cross-domain-policy.dtd">' . "\n" . '<cross-domain-policy>' . "\n\t" . '<allow-access-from domain="www.mochiads.com" />' . "\n\t" . '<allow-access-from domain="x.mochiads.com" />' . "\n\t" . '<allow-access-from domain="xs.mochiads.com" />' . "\n" . '</cross-domain-policy>' . "\n",
		);
		foreach ($copyfiles as $filename => $content) {
			if(JFile::exists($filename)) @JFile::delete($filename);
			file_put_contents($filename, $content);
		}
		
		
		
	}//end install function
	
	function uninstall($parent){
		
		
		
		
	}//end uninstall function
	
	
	function postflight ($type, $parent){
		
		
		
		
	}//end postflight function



}
?>
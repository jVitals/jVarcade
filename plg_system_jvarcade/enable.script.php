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

class plgSystemJvarcadeInstallerScript { 

	function update($parent) { 
		$this->install($parent);
	}
 
	function install($parent) { 
		// I activate the plugin
		$db = JFactory::getDbo();
		$tableExtensions = $db->quoteName("#__extensions");
		$columnElement = $db->quoteName("element");
		$columnType = $db->quoteName("type");
		$columnEnabled = $db->quoteName("enabled");
		$columnFolder = $db->quoteName("folder");
     
		// Enable plugin
		$db->setQuery("UPDATE $tableExtensions SET $columnEnabled=1 WHERE $columnElement='jvarcade' AND $columnType='plugin' AND $columnFolder='system'");
		$db->execute();
       
	} 
}
?>
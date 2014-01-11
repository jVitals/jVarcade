<?php
/**
* This file is part of the jVArcade distribution. 
*
*
*
*
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

class plgSearchJvarcadeInstallerScript { 

	function update($parent) { 
		$this->install($parent);
	}
 
	function install($parent) { 
		// I activate the plugin
		$db = JFactory::getDbo();
		$tableExtensions = $db->quoteName("#__extensions");
		$columnElement   = $db->quoteName("element");
		$columnType      = $db->quoteName("type");
		$columnEnabled   = $db->quoteName("enabled");
		$columnFolder	  = $db->quoteName("folder");
     
	 	// Enable plugin
		$db->setQuery("UPDATE $tableExtensions SET $columnEnabled=1 WHERE $columnElement='jvarcade' AND $columnType='plugin' AND $columnFolder='search'");
		$db->query();
       
	} 
}
?>
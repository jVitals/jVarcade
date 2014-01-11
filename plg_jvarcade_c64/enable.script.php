<?php
/**
* This file is part of the jVArcade distribution. 
* Enables playing of Commodore 64 games using jac64
* http://sourceforge.net/projects/jac64/
*
*
*/

// no direct access
defined('_JEXEC') or die('Restricted access');
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

class plgJvarcadeC64InstallerScript
{ 

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
     
     // Enable plugin
     $db->setQuery("UPDATE $tableExtensions SET $columnEnabled=1 WHERE $columnElement='c64' AND $columnType='plugin'");
     $db->query();
       
  } 
}
?>

?>
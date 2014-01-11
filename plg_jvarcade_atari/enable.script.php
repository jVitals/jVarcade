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
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

class plgJvarcadeAtariInstallerScript
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
     $db->setQuery("UPDATE $tableExtensions SET $columnEnabled=1 WHERE $columnElement='atari' AND $columnType='plugin'");
     $db->query();
       
  } 
}
?>

?>
<?php
/**
 * @package		jVArcade
* @version		2.13
* @date		2016-02-18
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

	function jvaPostinstallCondition()
{
	$db = JFactory::getDbo();
	$query = $db->getQuery(true)
		->select('*')
		->from($db->qn('#__extensions'))
		->where($db->qn('type') . ' = ' . $db->q('plugin'))
		->where($db->qn('enabled') . ' = ' . $db->q('1'))
		->where($db->qn('folder') . ' = ' . $db->q('system'))
		->where($db->qn('element') . ' = ' . $db->q('jvfixscript'));
	$db->setQuery($query);
	$enabled_plugins = $db->loadObjectList();
	return count($enabled_plugins) == 0;
}
	
  function jvaPostinstallAction(){
 // Enable the plugin
 $db = JFactory::getDbo();
   $query = $db->getQuery(true)
 	->select('*')
 	->from($db->qn('#__extensions'))
 	->where($db->qn('type') . ' = ' . $db->q('plugin'))
 	->where($db->qn('enabled') . ' = ' . $db->q('0'))
 	->where($db->qn('folder') . ' = ' . $db->q('system'))
 	->where($db->qn('element') . ' = ' . $db->q('jvfixscript'));
 $db->setQuery($query);
 $enabled_plugins = $db->loadObjectList();
 
 $query = $db->getQuery(true)
 	->update($db->qn('#__extensions'))
 	->set($db->qn('enabled') . ' = ' . $db->q(1))
 	->where($db->qn('type') . ' = ' . $db->q('plugin'))
 	->where($db->qn('folder') . ' = ' . $db->q('system'))
 	->where($db->qn('element') . ' = ' . $db->q('jvfixscript'));
 $db->setQuery($query);
 $db->execute();
  //Redirect the user to the plugin configuration page
 $url = 'index.php?option=com_plugins&task=plugin.edit&extension_id='
           .$enabled_plugins[0]->extension_id ;
 JFactory::getApplication()->redirect($url);
}
?>
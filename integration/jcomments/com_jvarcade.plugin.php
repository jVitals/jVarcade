<?php
/**
* JComments plugin for jVArcade component
* Detailed copyright and licensing information can be found
* in the gpl-3.0.txt file which should be included in the distribution.
* 
* @version	  $Id: 1.0.01 2011-05-11 nuclear-head $
* @copyright  2011 jVitals
* @license    GPLv3 Open Source
* @link       http://jvitals.com
* @package	  jVArcade
*/

class jc_com_jvarcade extends JCommentsPlugin {
	function getObjectInfo($id, $language = null) {
		$db = JFactory::getDbo();
		$query = "SELECT title, id FROM #__jvarcade_games WHERE id = " . $id;
		$db->setQuery($query);
		$row = $db->loadObject();
		
		$info = new JCommentsObjectInfo();
		
		if (!empty($row)) {
			$db->setQuery("SELECT id FROM #__menu WHERE link = 'index.php?option=com_jvarcade&view=home' and published = 1");
			$Itemid = $db->loadResult();
		
			if (!$Itemid) {
				$Itemid = self::getItemid('com_jvarcade');
			}
		
		$Itemid = $Itemid > 0 ? '&amp;Itemid='.$Itemid : '';
		
		$info->title = $row->title;
		$info->id = $row->id;
		$info->link = JRoute::_('index.php?option=com_jvarcade'.$Itemid.'&task=game&id=' . $id);
		}
		return $info;
	}
	
	
}

?>

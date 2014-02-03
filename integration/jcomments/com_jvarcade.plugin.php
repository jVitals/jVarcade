<?php
/**
 * @plugin		JComments plugin for jVArcade
 * @package		jVArcade
 * @version		2.1
 * @date		2014-01-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
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

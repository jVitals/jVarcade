<?php 
/**
 * CComment plugin for jVArcade component
 * @package		jVArcade
 * @version		2.11
 * @date		2014-05-04
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */

class ccommentComponentjvarcadeSettings extends ccommentComponentSettings {
	
	/**
	 * categories option list used to display the include/exclude folders list in setting
	 * must return an array of objects (id,title)
	 *
	 * @return array() - associative array (id, title)
	 */
	
	function getCategories() {
		$db = JFactory::getDBO();
	
		$query = $db->getQuery(true);
		$query->select('id, name AS title');
		$query->from('#__jvarcade_folders');
		$query->where('published = 1');
		$query->order('title ASC');
				
		$db->setQuery( $query );
		$catoptions = $db->loadObjectList();
				
		return $catoptions;
	}
	
}


?>
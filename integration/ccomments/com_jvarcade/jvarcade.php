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

class ccommentComponentJvarcadePlugin extends ccommentComponentPlugin {

	/**
	 * With this function we determine if the comment system should be executed for this
	 * content Item
	 * @return bool
	 */
	public function isEnabled()
	{
		$config = ccommentConfig::getConfig('com_jvarcade');
		$row = $this->row;
	
		$contentIds = $config->get('basic.exclude_content_items', array());
		$categories = $config->get('basic.categories', array());
		$include = $config->get('basic.include_categories', 0);
	
		/* content ids */
		if (count($contentIds) > 0)
		{
			$result = in_array((($row->id == 0) ? -1 : $row->id), $contentIds);
			if ($include && $result)
			{
				return true; /* include and selected */
			}
			if (!$include && $result)
			{
				return false; /* exclude and selected */
			}
		}
	
		/* categories */
		$result = in_array((($row->folderid == 0) ? -1 : $row->folderid), $categories);
		if ($include && $result)
		{
			return true; /* include and selected */
		}
		if (!$include && $result)
		{
			return false; /* exclude and selected */
		}
	
		if (!$include)
		{
			return true; /* was not excluded */
		}
	
		return false;
	}
	
	/**
	 * This function decides whether to show the comments
	 * in an article/item or to show the readmore link
	 *
	 * If it returns true - the comments are shown
	 * If it returns false - the setShowReadon function will be called
	 *
	 * @return boolean
	 */
	public function isSingleView()
	{
		$input = JFactory::getApplication()->input;
		$option = $input->getCmd('option', '');
		$view = $input->getCmd('task', '');
	
		return ($option == 'com_jvarcade' && $view == 'game'
		);
	}
	
	/**
	 * This function determines whether to show the comment count or not
	 *
	 * @return bool
	 */
	public function showReadOn()
	{
		$config = ccommentConfig::getConfig('com_jvarcade');
	
		return $config->get('layout.show_readon');
	}
	
	/**
	 * Creates a link to a game
	 *
	 * @param   int   $contentId  - the item id
	 * @param   int   $commentId  - the comment id
	 * @param   bool  $xhtml      - should we generate a xhtml link?
	 *
	 * @return string
	 */
	public function getLink($contentId, $commentId = 0, $xhtml = true)
	{
		$add = '';
		$menuid = $this->getItemid();
		if ($commentId)
		{
			$add = "#!/ccomment-comment=$commentId";
		}
		$url = JURI::root() . "index.php?option=com_jvarcade&task=game&id=$contentId"
		. ( $menuid ? "&Itemid=$menuid" : "" );
		;
		$url .= $add;
		$url = JRoute::_($url, $xhtml);
	
		return $url;
	}
	
	/*
	 *  getItemid
	*/
	
	function getItemid($component='com_jvarcade') {
		static $ids;
		if (!isset($ids)) {
			$ids = array();
		}
		if (!isset($ids[$component])) {
			$database = & JFactory::getDBO();
			$query = "SELECT id FROM #__menu"
					. "\n WHERE link LIKE '%option=$component%'"
					. "\n AND type = 'component'"
					. "\n AND published = 1 LIMIT 1";
						$database->setQuery($query);
						$ids[$component] = $database->loadResult();
		}
		return $ids[$component];
	}
	
	/**
	 * Returns the id of the author of an item
	 *
	 * @param   int  $contentId  - the content id
	 *
	 * @return mixed
	 */
	public function getAuthorId($contentId)
	{
	
		return false;
	}
	
	/**
	 * Gets Item titles
	 *
	 * @param   array  $ids  - array with record ids
	 *
	 * @return mixed
	 */
	public function getItemTitles($ids)
	{
		$db = JFactory::getDbo();
		$query = $db->getQuery(true);
		$query->select('id,title')->from('#__jvarcade_games')
		->where('id IN (' . implode(',', $ids) . ')');
	
		$db->setQuery($query);
	
		return $db->loadObjectList('id');
	}
}

?>
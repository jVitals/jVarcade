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
if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

if(file_exists(JPATH_ROOT . DS . 'components' . DS . 'com_jvarcade' . DS . 'include' . DS . 'define.php')) {
	require_once (JPATH_ROOT . DS . 'components' . DS . 'com_jvarcade' . DS . 'include' . DS . 'define.php');
	require_once (JPATH_ROOT . DS . 'components' . DS . 'com_jvarcade' . DS . 'helpers' . DS . 'helper.php');
	jimport('joomla.plugin.plugin');
	
	class plgSearchJvarcade extends JPlugin {
	
		public function __construct(& $subject, $config) {
			parent::__construct($subject, $config);
			$this->loadLanguage();
		}
	
		function onContentSearchAreas()	{
			static $areas = array(
				'games' => 'PLG_SEARCH_JVARCADE_GAMES',
				'folders' => 'PLG_SEARCH_JVARCADE_FOLDERS',
				'tags' => 'PLG_SEARCH_JVARCADE_TAGS'
			);
			return $areas;
		}
		
		function folderPerms(&$user, $perms) {
			return (jvaHelper::isSuperAdmin($user) || jvaHelper::checkPerms(jvaHelper::userGroups($user), explode(',', $perms)));
		}
	
		function onContentSearch($text, $phrase = '', $ordering = '', $areas = null) {
			$db = JFactory::getDbo();
			$user = JFactory::getUser();
			$return = array();
			$Itemid = (int)$this->params->get('Itemid', 0);
	
			if (is_array($areas)) {
				$areas = array_intersect($areas, array_keys($this->onContentSearchAreas()));
			} else {
				$areas = array_keys($this->onContentSearchAreas());
			}
			
			foreach($areas as $area) {
				switch(true) {
				
					case $area == 'games':
						
						// main query
						$query = 'SELECT g.id as game_id, g.title, g.gamename, g.numplayed, g.description, f.id as folder_id, 
										f.name as folder_name, f.viewpermissions 
								 FROM #__jvarcade_games as g 
									LEFT JOIN #__jvarcade_folders f ON g.folderid = f.id 
								 WHERE g.' . $db->quoteName('published') . ' = ' . $db->Quote(1) .
									' AND f.' . $db->quoteName('published') . ' = ' . $db->Quote(1);
						
						// keywords
						switch(true) {
							case $phrase == 'exact' :
								$searchText = strtolower($db->Quote('%' . $db->escape($text, true) . '%', false));
								$query .= ' AND (LOWER(g.title) LIKE ' . $searchText . ' OR LOWER(g.gamename) LIKE ' . $searchText . ' OR LOWER(g.description) LIKE ' . $searchText . ')';
								break;
								
							case $phrase == 'all' :
							case $phrase == 'any' :
							default :
								$words = explode(' ', $text);
								$wheres = array();
								foreach ($words as $w) {
									$word = strtolower($db->Quote('%' . $db->escape($w, true) . '%', false));
									$wheres2 = 'LOWER(g.title) LIKE ' . $word;
									$wheres2 .= ' OR LOWER(g.gamename) LIKE ' . $word;
									$wheres2 .= ' OR LOWER(g.description) LIKE ' . $word;
									$wheres[] = '( ' . $wheres2 . ' )';
								}
								$query .= ' AND (' . implode(($phrase == 'all' ? ') AND (' : ' OR '), $wheres ) . ')';
								break;
						}
						
						// ordering
						switch ($ordering) {
							case 'alpha':
								$query .= ' ORDER BY g.title ASC';
								break;
							case 'category':
								$query .= ' ORDER BY g.title ASC';
								break;
							case 'popular':
								$query .= ' ORDER BY g.numplayed DESC';
								break;
							case 'newest':
								$query .= ' ORDER BY g.id DESC';
								break;
							case 'oldest':
								$query .= ' ORDER BY g.id ASC';
								break;
							default:
								$query .= ' ORDER BY g.title ASC';
								break;
						}
						
						// execute the query
						$db->setQuery($query, 0, (int)$this->params->get('search_limit_games', 50));
						$rows = $db->loadObjectList();
						
						// create the search results object
						foreach($rows as $r) {
							if ($this->folderPerms($user, $r->viewpermissions)) {
								$item = new stdClass;
								$desc = array();
								$folder_url = JRoute::_('index.php?option=com_jvarcade&task=folder&id=' . (int)$r->folder_id . ($Itemid > 0 ? '&Itemid=' . $Itemid : ''), false);
								$game_url = JRoute::_('index.php?option=com_jvarcade&task=game&id=' . (int)$r->game_id . ($Itemid > 0 ? '&Itemid=' . $Itemid : ''), false);
								$item->title = htmlspecialchars(stripslashes($r->title));
								if ($r->folder_name) $desc[] = htmlspecialchars(stripslashes(JText::_('PLG_SEARCH_JVARCADE_FOLDER') . ': ' . $r->folder_name));
								$desc[] = htmlspecialchars(stripslashes(JText::_('PLG_SEARCH_JVARCADE_NUMPLAYED') . ': ' . $r->numplayed));
								if ($r->description) $desc[] = htmlspecialchars(stripslashes($r->description));
								$item->text = implode(' | ',  $desc);
								$item->section = JText::_('PLG_SEARCH_JVARCADE_GAMES');
								$item->href = $game_url;
								$item->browsernav = 0;
								$return[] = $item;
							}
						}
						break;
				
					case $area == 'folders':
						
						// main query
						$query = 'SELECT id, name, description, viewpermissions FROM #__jvarcade_folders WHERE ' . $db->quoteName('published') . ' = ' . $db->Quote(1);
						
						// keywords
						switch(true) {
							case $phrase == 'exact' :
								$searchText = strtolower($db->Quote('%' . $db->escape($text, true) . '%', false));
								$query .= ' AND (LOWER(name) LIKE ' . $searchText . ' OR LOWER(description) LIKE ' . $searchText . ')';
								break;
								
							case $phrase == 'all' :
							case $phrase == 'any' :
							default :
								$words = explode(' ', $text);
								$wheres = array();
								foreach ($words as $w) {
									$word = strtolower($db->Quote('%' . $db->escape($w, true) . '%', false));
									$wheres2 = 'LOWER(name) LIKE ' . $word;
									$wheres2 .= ' OR LOWER(description) LIKE ' . $word;
									$wheres[] = '( ' . $wheres2 . ' )';
								}
								$query .= ' AND (' . implode(($phrase == 'all' ? ') AND (' : ' OR '), $wheres ) . ')';
								break;
						}
						
						// ordering
						switch ($ordering) {
							case 'alpha':
								$query .= ' ORDER BY name ASC';
								break;
							case 'category':
								$query .= ' ORDER BY name ASC';
								break;
							case 'popular':
								$query .= ' ORDER BY id DESC';
								break;
							case 'newest':
								$query .= ' ORDER BY id DESC';
								break;
							case 'oldest':
								$query .= ' ORDER BY id ASC';
								break;
							default:
								$query .= ' ORDER BY name ASC';
								break;
						}
						
						// execute the query
						$db->setQuery($query, 0, (int)$this->params->get('search_limit_folders', 50));
						$rows = $db->loadObjectList();
						
						// create the search results object
						foreach($rows as $r) {
							if ($this->folderPerms($user, $r->viewpermissions)) {
								$item = new stdClass;
								$folder_url = JRoute::_('index.php?option=com_jvarcade&task=folder&id=' . (int)$r->id . ($Itemid > 0 ? '&Itemid=' . $Itemid : ''), false);
								$item->title = htmlspecialchars(stripslashes($r->name));
								$item->text = htmlspecialchars(stripslashes($r->description));
								$item->section = JText::_('PLG_SEARCH_JVARCADE_FOLDERS');
								$item->href = $folder_url;
								$item->browsernav = 0;
								$return[] = $item;
							}
						}
						break;
				
					case $area == 'tags':
						
						// main query
						$query = 'SELECT DISTINCT id, tag FROM #__jvarcade_tags';
						
						// keywords
						switch(true) {
							case $phrase == 'exact' :
								$searchText = strtolower($db->Quote('%' . $db->escape($text, true) . '%', false));
								$query .= ' WHERE (LOWER(tag) LIKE ' . $searchText . ')';
								break;
								
							case $phrase == 'all' :
							case $phrase == 'any' :
							default :
								$words = explode(' ', $text);
								$wheres = array();
								foreach ($words as $w) {
									$word = strtolower($db->Quote('%' . $db->escape($w, true) . '%', false));
									$wheres[] = ' LOWER(tag) LIKE ' . $word;
								}
								$query .= ' WHERE (' . implode(($phrase == 'all' ? ') AND (' : ' OR '), $wheres ) . ')';
								break;
						}
						
						// ordering
						switch ($ordering) {
							case 'alpha':
								$query .= ' ORDER BY tag ASC';
								break;
							case 'category':
								$query .= ' ORDER BY tag ASC';
								break;
							case 'popular':
								$query .= ' ORDER BY tag DESC';
								break;
							case 'newest':
								$query .= ' ORDER BY id DESC';
								break;
							case 'oldest':
								$query .= ' ORDER BY id ASC';
								break;
							default:
								$query .= ' ORDER BY tag ASC';
								break;
						}
						
						// execute the query
						$db->setQuery($query, 0, (int)$this->params->get('search_limit_tags', 50));
						$rows = $db->loadObjectList();
						
						// create the search results object
						foreach($rows as $r) {
							$item = new stdClass;
							$tag_url = JRoute::_('index.php?option=com_jvarcade&task=showtag&tag=' . $r->tag . ($Itemid > 0 ? '&Itemid=' . $Itemid : ''), false);
							$item->title = htmlspecialchars(stripslashes($r->tag));
							$item->section = JText::_('PLG_SEARCH_JVARCADE_TAGS');
							$item->href = $tag_url;
							$item->browsernav = 0;
							$return[] = $item;
						}
						break;
				}
			}
			
			return $return;
		}
	}
}

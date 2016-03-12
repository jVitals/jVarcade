<?php
/**
 * @package		jVArcade
 * @version		2.14
 * @date		2016-03-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.model');

class jvarcadeModelGames extends jvarcadeModelCommon {

	private $folders = null;
	private $games = null;
	private $games_count = null;
	private $parents = array();
	
	
	// FOLDERS RELATED
	
	public function getFolders($parent_id = null) {
		if (!$this->folders) {
			$this->_loadFolders($parent_id);
		}
		return $this->folders;
	}
	
	private function _loadFolders($parent_id) {	
		if (!$this->folders) {
			$and = !is_null($parent_id) ? ' AND ' . $this->dbo->quoteName('parentid') . ' = ' . $this->dbo->Quote((int)$parent_id) : '';
			$this->dbo->setQuery('SELECT * FROM #__jvarcade_folders ' . 
								'WHERE ' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) .
								$and .
								' ORDER BY id DESC');
			$this->folders = $this->dbo->loadAssocList('id');
			return (boolean)$this->folders;
		}
		return true;
	}
	
	public function getFoldersHome() {
		$conf = $this->getConf();
		$order = (int)$conf->homepage_order == 1 ? 'name' : 'id' ;
		$dir = (int)$conf->homepage_order_dir == 1 ? 'ASC' : 'DESC' ;
		$this->dbo->setQuery('SELECT * FROM #__jvarcade_folders WHERE ' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) . ' ORDER BY ' . $order . ' ' . $dir);
		$folders = $this->dbo->loadAssocList('id');
		return $folders;
	}
	
	public function getFolder($folder_id) {
		$this->dbo->setQuery('SELECT id, name, parentid FROM #__jvarcade_folders WHERE id = ' . (int)$folder_id);
		return $this->dbo->loadAssoc();
	}
	
	public function getParents($folder_id) {
		$this->loadParents($folder_id);
		ksort($this->parents);
		return $this->parents;
	}
	
	public function loadParents($folder_id) {
		if ($folder_id) {
			$result = $this->getFolder($folder_id);
			$this->parents[$result['id']] = $result;
			if ($result && is_array($result) && count($result)) {
				$this->getParents((int)$result['parentid']);
			}
		}
	}
	
	// VARIOUS LISTINGS

	public function getAllGames() {
		if (!$this->games) {
			$this->_loadAllGames();
		}
		return $this->games;
	}	

	private function _loadAllGames() {
		if (!$this->games) {

			if ($this->orderby) {
				$orderby = ' ORDER BY ' .  $this->orderby  . ' ' .  ($this->orderdir ? $this->orderdir : '');
			} else {
				$orderby = ' ORDER BY game_id DESC';
			}
			
			$sql = 'SELECT SQL_CALC_FOUND_ROWS g.*, g.id as game_id, g.description as game_desc, c.imagename as rating_image, c.name as rating_name, c.description as rating_desc ' .
					' FROM #__jvarcade_games g ' .
					' LEFT JOIN #__jvarcade_contentrating c ' . 
					'	ON g.contentratingid = c.id ' . 
					' WHERE g.' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) .
					$orderby;
			$this->dbo->setQuery($sql, $this->getState('limitstart'), $this->getState('limit'));
			$this->games = $this->dbo->loadAssocList();
			return (boolean)$this->games;
		}
		return true;
	}
	
	public function getRandomGames($folder_ids) {
		if (!$this->games && is_array($folder_ids) && count($folder_ids)) {
			$this->_loadRandomGames($folder_ids);
		}
		return $this->games;
	}	

	private function _loadRandomGames($folder_ids) {	
		if (!$this->games && is_array($folder_ids) && count($folder_ids)) {
			$statements = array();
			foreach ($folder_ids as $id) {
				$statements[] = '(SELECT * FROM #__jvarcade_games ' . 
								' WHERE ' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) .
								' AND ' . $this->dbo->quoteName('folderid') . ' = ' . $this->dbo->Quote($id) . 
								' ORDER BY rand() LIMIT ' . $this->config->randgamecount . ')';
			}
			if (count($statements)) {
				$sql = implode(' UNION  ', $statements);
				$this->dbo->setQuery($sql);
				$this->games = $this->dbo->loadAssocList();
				return (boolean)$this->games;
			} else {
				return false;
			}
		}
		return true;
	}

	public function getNewestGames() {
		if (!$this->games) {
			$this->_loadNewestGames();
		}
		return $this->games;
	}	

	private function _loadNewestGames() {
		if (!$this->games) {

			if ($this->orderby) {
				$orderby = ' ORDER BY ' .  $this->orderby  . ' ' .  ($this->orderdir ? $this->orderdir : '');
			} else {
				$orderby = ' ORDER BY game_id DESC';
			}
			
			$sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT g.*, g.id as game_id, g.description as game_desc, c.imagename as rating_image, c.name as rating_name, c.description as rating_desc ' .
					' FROM #__jvarcade_games g ' .
					' LEFT JOIN #__jvarcade_contentrating c' . 
					'	ON g.contentratingid = c.id' . 
					' WHERE g.' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) .
					' ORDER BY game_id DESC ' .
					'  LIMIT 0,20 ) a ' . 
					$orderby;
			$this->dbo->setQuery($sql, $this->getState('limitstart'), $this->getState('limit'));
			$this->games = $this->dbo->loadAssocList();
			return (boolean)$this->games;
		}
		return true;
	}

	public function getPopularGames() {
		if (!$this->games) {
			$this->_loadPopularGames();
		}
		return $this->games;
	}	

	private function _loadPopularGames() {	
		if (!$this->games) {

			if ($this->orderby) {
				$orderby = ' ORDER BY ' .  $this->orderby  . ' ' .  ($this->orderdir ? $this->orderdir : '');
			} else {
				$orderby = ' ORDER BY numplayed DESC';
			}
			
			$sql = 'SELECT SQL_CALC_FOUND_ROWS * FROM (SELECT g.*, g.id as game_id, g.description as game_desc, c.imagename as rating_image, c.name as rating_name, c.description as rating_desc ' .
					' FROM #__jvarcade_games g ' .
					' LEFT JOIN #__jvarcade_contentrating c' . 
					'	ON g.contentratingid = c.id' . 
					' WHERE g.' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) .
					' ORDER BY numplayed DESC ' .
					'  LIMIT 0,20) a ' . 
					$orderby;
			$this->dbo->setQuery($sql, $this->getState('limitstart'), $this->getState('limit'));
			$this->games = $this->dbo->loadAssocList();
			return (boolean)$this->games;
		}
		return true;
	}

    public function getPagedRandomGames($folder_ids) { 
        if (!$this->games && is_array($folder_ids) && count($folder_ids)) { 
            $this->_getPagedRandomGames($folder_ids); 
        } 
        return $this->games; 
    }    

    private function _getPagedRandomGames($folder_ids) {    
        if (!$this->games && is_array($folder_ids) && count($folder_ids)) { 
            $statements = array(); 
            foreach ($folder_ids as $id) { 
                $statements[] = '(SELECT g.*, g.id as game_id, g.description as game_desc, c.imagename as rating_image, c.name as rating_name, c.description as rating_desc ' .
								' FROM #__jvarcade_games g ' . 
								' LEFT JOIN #__jvarcade_contentrating c' . 
								'	ON g.contentratingid = c.id' . 
                                ' WHERE g.' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) . 
                                ' AND g.' . $this->dbo->quoteName('folderid') . ' = ' . $this->dbo->Quote($id) . 
                                ' ORDER BY rand() LIMIT ' . $this->config->randgamecount . ')';
            } 
            if (count($statements)) { 
                $sql = implode(' UNION  ', $statements); 
                $this->dbo->setQuery($sql, $this->getState('limitstart'), $this->getState('limit')); 
                $this->games = $this->dbo->loadAssocList();
                return (boolean)$this->games; 
            } else { 
                return false; 
            } 
        } 
        return true; 
    }

	public function getFavouriteGames() {
		if (!$this->games) {
			$this->_loadFavouriteGames();
		}
		return $this->games;
	}	

	private function _loadFavouriteGames() {	
		if (!$this->games) {

			if ($this->orderby) {
				$orderby = ' ORDER BY ' .  $this->orderby  . ' ' .  ($this->orderdir ? $this->orderdir : '');
			} else {
				$orderby = ' ORDER BY game_id DESC';
			}
			
			$sql = 'SELECT SQL_CALC_FOUND_ROWS g.*, g.id as game_id, g.description as game_desc, c.imagename as rating_image, c.name as rating_name, c.description as rating_desc ' .
					' FROM #__jvarcade_games g ' .
					' LEFT JOIN #__jvarcade_contentrating c' . 
					'	ON g.contentratingid = c.id' . 
					' JOIN #__jvarcade_faves f' . 
					'	ON g.id = f.gid AND f.userid = ' . $this->dbo->Quote($this->user->get('id')) . 
					' WHERE g.' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) .
					$orderby;
			$this->dbo->setQuery($sql, $this->getState('limitstart'), $this->getState('limit'));
			$this->games = $this->dbo->loadAssocList();
			return (boolean)$this->games;
		}
		return true;
	}

	public function getFolderGames($folder_id) {

		if ($this->orderby) {
			$orderby = ' ORDER BY '. $this->orderby . ' ' . ($this->orderdir ? $this->orderdir : '');
		} else {
			$orderby = ' ORDER BY game_id DESC';
		}
		
		$sql = 'SELECT SQL_CALC_FOUND_ROWS g.*, g.id as game_id, g.description as game_desc, c.imagename as rating_image, c.name as rating_name, c.description as rating_desc, f.name as folder_name, f.viewpermissions, f.description ' .
				' FROM #__jvarcade_games as g ' .
				' LEFT JOIN #__jvarcade_contentrating c' . 
				'	ON g.contentratingid = c.id' . 
				' LEFT JOIN #__jvarcade_folders f' . 
				'	ON g.folderid = f.id ' . 
				' WHERE g.' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) .
				'	AND f.' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) . 
				'	AND f.' . $this->dbo->quoteName('id') . ' = ' . $this->dbo->Quote((int)$folder_id) . 
				$orderby;
		$this->dbo->setQuery($sql, $this->getState('limitstart'), $this->getState('limit'));
		return $this->dbo->loadAssocList();
	}

	public function getGamesByTag($tag) {

		if ($this->orderby) {
			$orderby = ' ORDER BY ' .  $this->orderby  . ' ' .  ($this->orderdir ? $this->orderdir : '');
		} else {
			$orderby = 'ORDER BY game_id DESC';
		}
		
		$sql = 'SELECT SQL_CALC_FOUND_ROWS g.*, g.id as game_id, g.description as game_desc, c.imagename as rating_image, c.name as rating_name, c.description as rating_desc ' .
				' FROM #__jvarcade_games g ' .
				' LEFT JOIN #__jvarcade_contentrating c' . 
				'	ON g.contentratingid = c.id' . 
				' JOIN #__jvarcade_tags t' . 
				'	ON g.id = t.gameid ' . 
				' WHERE g.' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) .
				'	AND t.' . $this->dbo->quoteName('tag') . ' = ' . $this->dbo->Quote($tag) . 
				$orderby;
		$this->dbo->setQuery($sql, $this->getState('limitstart'), $this->getState('limit'));
		return $this->dbo->loadAssocList();
	}

	// GAME DATA

	public function getGame($game_id) {
		$sql = 'SELECT g.*, c.imagename as rating_image, c.name as rating_name, c.description as rating_desc, c.warningrequired, ' . 
				' 		COALESCE(r.total_votes, 0) as total_votes, COALESCE(r.total_value, 0) as total_value, ' .
				' 		f.viewpermissions ' .
				' FROM #__jvarcade_games g ' .
				' LEFT JOIN #__jvarcade_contentrating c ON g.contentratingid = c.id ' . 
				' LEFT JOIN #__jvarcade_ratings r ON r.gameid = g.id ' . 
				' LEFT JOIN #__jvarcade_folders f ON g.folderid = f.id ' . 
				' WHERE g.' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) .
				'	AND g.' . $this->dbo->quoteName('id') . ' = ' . $this->dbo->Quote((int)$game_id);
		$this->dbo->setQuery($sql);
		return $this->dbo->loadAssoc();
	}
	
	public function getGameByName($game_name) {
		$sql = 'SELECT g.*, c.imagename as rating_image, c.name as rating_name, c.description as rating_desc, ' . 
				' 		COALESCE(r.total_votes, 0) as total_votes, COALESCE(r.total_value, 0) as total_value ' .
				' FROM #__jvarcade_games g ' .
				' LEFT JOIN #__jvarcade_contentrating c ON g.contentratingid = c.id' . 
				' LEFT JOIN #__jvarcade_ratings r ON r.gameid = g.id' . 
				' WHERE g.' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) .
				'	AND lower(g.' . $this->dbo->quoteName('gamename') . ') = ' . $this->dbo->Quote($game_name);
		$this->dbo->setQuery($sql);
		return $this->dbo->loadAssoc();
	}

	public function getGamesCountByFolder() {
		if (!$this->games_count) {
			$this->_loadGamesCountByFolder();
		}
		return $this->games_count;
	}
	
	private function _loadGamesCountByFolder() {	
		if (!$this->games_count) {
			$this->dbo->setQuery('SELECT folderid, COUNT(*) as count FROM #__jvarcade_games WHERE ' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) . ' GROUP BY folderid');
			$this->games_count = $this->dbo->loadAssocList('folderid');
			return (boolean)$this->games_count;
		}
		return true;
	}

	public function getHighestScore($game_id, $reverse, $userid = null) {
		$order = $reverse ? 'ASC' : 'DESC' ;
		$this->dbo->setQuery('SELECT p.id, p.score, p.userid, u.name, u.username' . 
							' FROM #__jvarcade p' .  
								' LEFT JOIN #__users u ON p.userid = u.id' .
							' WHERE ' . $this->dbo->quoteName('gameid') . ' = ' . $this->dbo->Quote($game_id) . 
								(!is_null($userid) ? ' AND ' . $this->dbo->quoteName('userid') . ' = ' . $this->dbo->Quote((int)$userid) : '') . 
								' AND ' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) . 
							' ORDER BY p.score ' . $order .
							' LIMIT 1');
		return $this->dbo->loadAssoc();
	}
	
	public function increaseNumplayed($game_id) {
		$query = 'UPDATE #__jvarcade_games SET ' . $this->dbo->quoteName('numplayed') . ' = ' . $this->dbo->quoteName('numplayed') . '+1 WHERE id = ' . $this->dbo->Quote($game_id);
		$this->dbo->setQuery($query);
		$this->dbo->execute();
	}
	
	
	// FAVOURITES RELATED
	
	public function getGameFavCount($game_id, $user_id = 0) {
		$query = 'SELECT count(*) as count FROM #__jvarcade_faves WHERE gid = ' . $this->dbo->Quote((int)$game_id) . ((int)$user_id ? ' AND userid = ' . $this->dbo->Quote((int)$user_id) : '');
		$this->dbo->setQuery($query );
		return $this->dbo->loadResult();
	}
	
	public function getMyFavCount($user_id) {
		$query = 'SELECT count(*) as count FROM #__jvarcade_faves WHERE userid = ' . $this->dbo->Quote((int)$user_id);
		$this->dbo->setQuery($query );
		return $this->dbo->loadResult();
	}
	
	public function saveFavourite($game_id, $user_id) {
		$query = 'INSERT INTO #__jvarcade_faves (userid, gid) VALUES (' . $this->dbo->Quote((int)$user_id) . ',' . $this->dbo->Quote((int)$game_id) . ')';
		$this->dbo->setQuery($query);
		$res = $this->dbo->execute();
		return $res;
	}

	public function delFavourite($game_id, $user_id) {
		$query = 'DELETE FROM #__jvarcade_faves WHERE userid = ' . $this->dbo->Quote((int)$user_id) . ' AND gid = ' . $this->dbo->Quote((int)$game_id);
		$this->dbo->setQuery($query);
		$res = $this->dbo->execute();
		return $res;
	}
	
	// TAG DATA

	public function getTagData($id) {
		$this->dbo->setQuery('SELECT tag,count FROM #__jvarcade_tags WHERE gameid = ' . $this->dbo->Quote((int)$id) . ' ORDER BY RAND() LIMIT 40');
		return $this->dbo->loadObjectList();
	}
	
	public function getTagCount($gameid, $tag) {
		$query = 'SELECT coalesce(' . $this->dbo->quoteName('count') . ', 0) FROM #__jvarcade_tags WHERE gameid = ' . $this->dbo->Quote($gameid) . ' AND tag = ' . $this->dbo->Quote($tag);
		$this->dbo->setQuery($query);
		return $this->dbo->loadResult();
	}
	
	public function updateTagCount($gameid, $tag) {
		$query = 'UPDATE #__jvarcade_tags SET ' . $this->dbo->quoteName('count') . ' = ' . $this->dbo->quoteName('count') . '+1 WHERE gameid = ' . $this->dbo->Quote($gameid) . ' AND tag = ' . $this->dbo->Quote($tag);
		$this->dbo->setQuery($query);
		$this->dbo->execute();
	}

	public function addTag($gameid, $tag) {
		$query = 'INSERT INTO #__jvarcade_tags (gameid, tag, ' . $this->dbo->quoteName('count') . ') VALUES (' . $this->dbo->Quote($gameid) . ', ' . $this->dbo->Quote($tag) . ', 1)';
		$this->dbo->setQuery($query);
		$this->dbo->execute();
		return $this->dbo->insertid();
	}
	
	// PERMISSIONS
	
	public function canTagPerms(&$user) {
		return (jvaHelper::isSuperAdmin($user) || jvaHelper::checkPerms(jvaHelper::userGroups($user), explode(',', $this->config->TagPerms)));
	}
	
	public function canDloadPerms(&$user) {
		return (jvaHelper::isSuperAdmin($user) || jvaHelper::checkPerms(jvaHelper::userGroups($user), explode(',', $this->config->DloadPerms)));
	}
	
	public function folderPerms(&$user, $perms) {
		return (jvaHelper::isSuperAdmin($user) || jvaHelper::checkPerms(jvaHelper::userGroups($user), explode(',', $perms)));
	}

}

?>

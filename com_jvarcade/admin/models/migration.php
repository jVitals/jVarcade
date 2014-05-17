<?php
/**
 * @package		jVArcade
 * @version		2.12
 * @date		2014-05-17
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */


//This file MUST remain in package for future reference by pgkerr76

// no direct access
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.model');

class jvarcadeModelMigration extends JModelLegacy {
	private $dbo;
	private $return_arr;
	private $old_imagepath;
	private $old_fileimagepath;
	private $new_imagepath;
	private $old_gamepath;
	private $new_gamepath;
	
	public function __construct() {
		parent::__construct();
		$this->dbo = JFactory::getDBO();
		$app = JFactory::getApplication('site');
		$this->return_arr = array('errnum' => 0, 'msg' => '');
		
		$this->dbo->setQuery('SELECT games_dir, games_images_dir FROM #__puarcade_config LIMIT 1');
		$config = $this->dbo->loadAssoc();
		
		$this->old_imagepath = JPATH_ROOT . '/components/com_puarcade/images/';
		$this->old_fileimagepath = JPATH_ROOT . ((is_array($config) && count($config) && isset($config['games_images_dir'])) ? $config['games_images_dir'] : '/components/images/');
		$this->new_imagepath = JVA_IMAGES_INCPATH;
		$this->old_gamepath = JPATH_ROOT . ((is_array($config) && count($config) && isset($config['games_dir'])) ? $config['games_dir'] : '/components/flash/');
		$this->new_gamepath = JVA_GAMES_INCPATH;
	}
	
	public function setMsg($msg, $type) {
		switch($type) {
			case 'error':
				$error = 1;
				$color = 'red';
				break;
			case 'warn':
				$error = 0;
				$color = 'orange';
				break;
			case 'none':
				$error = 0;
				$color = 'black';
				break;
			case 'info':
			default:
				$error = 0;
				$color = 'green';
				break;
		}
		$this->return_arr['errnum'] += $error;
		$this->return_arr['msg'] .= '<p style="color:' . $color . '">' . $msg . '</p>';
	}
	
	public function getTemp($table) {
		$return = array();
		$this->dbo->setQuery('SELECT * FROM #__tmp_' . $table);
		$results = $this->dbo->loadObjectList();
		if ($this->dbo->getErrorMsg()) {
			$this->setMsg($this->dbo->getErrorMsg(), 'error');
		} elseif (is_array($results) && count($results)) {
			foreach($results as $result) {
				$return[$result->pua_id] = $result->jva_id;
			}
		} else {
			$this->setMsg(JText::sprintf('COM_JVARCADE_MAINTENANCE_MIGRATION_TEMP_EMPTY', '#__tmp_' . $table), 'error');
		}
		return $return;
		
	}
	
	public function doMigration($step) {
		$step = (int)$step;
		$this->setMsg($step . '.' . JText::_('COM_JVARCADE_MAINTENANCE_MIGRATION_STEP_' . $step), 'none');
		switch($step) {
			case 1:
				$this->migrateContentRatings();
				break;
			case 2:
				$this->migrateContests();
				break;
			case 3:
				$this->migrateFolders();
				break;
			case 4:
				$this->migrateGames();
				break;
			case 5:
				$this->migrateFavs();
				break;
			case 6:
				$this->migrateTags();
				break;
			case 7:
				$this->migrateRatings();
				break;
			case 8:
				$this->migrateScores();
				break;
			case 9:
				$this->migrateContestGame();
				break;
			case 10:
				$this->migrateContestMember();
				break;
			case 11:
				$this->migrateContestScore();
				break;
			case 12:
				$this->migrateConfig();
				break;
			default:
				break;
		}
		$msg = $this->return_arr['errnum'] < 1 ? JText::_('COM_JVARCADE_MAINTENANCE_MIGRATION_SUCCESS') : JText::_('COM_JVARCADE_MAINTENANCE_MIGRATION_FAILED') ;
		$type = $this->return_arr['errnum'] < 1 ? 'info' : 'error';
		$this->setMsg($msg, $type);
		if ($step == 12) $this->setMsg(JText::_('COM_JVARCADE_MAINTENANCE_MIGRATION_FINISHED'), 'info');
		return $this->return_arr;
	}
	
	public function migrateContentRatings() {
		$this->dbo->setQuery('SELECT * FROM #__puarcade_contentrating');
		$results = $this->dbo->loadObjectList();
		
		if ($this->dbo->getErrorMsg()) {
			$this->setMsg($this->dbo->getErrorMsg(), 'error');
		} else {
			// create the temp table
			$query = 'DROP TABLE IF EXISTS `#__tmp_contentrating`';
			$this->dbo->setQuery($query);
			$this->dbo->execute();
			
			$query =
			'CREATE TABLE `#__tmp_contentrating` (
			`pua_id` int(11) unsigned NOT NULL,
			`jva_id` int(11) unsigned NOT NULL,
			PRIMARY KEY(`pua_id`),
			UNIQUE(`jva_id`)
			) Type=MyISAM';
			$this->dbo->setQuery($query);
			$this->dbo->execute();
			
			if (is_array($results) && count($results)) {
				foreach ($results as $result) {
					// insert the new item
					$this->dbo->setQuery(
					'INSERT INTO #__jvarcade_contentrating(name, description, warningrequired, published) VALUES (' 
					. $this->dbo->Quote($result->name) . ', ' 
					. $this->dbo->Quote($result->description) . ', '
					. $this->dbo->Quote($result->warningrequired) . ', '
					. $this->dbo->Quote($result->published) . ')');
					$this->dbo->execute();
					if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
					
					// update and copy the image
					$jva_id = $this->dbo->insertid();
					if ($result->imagename && file_exists($this->old_imagepath . $result->imagename)) {
						$imagename = $jva_id . '_' . $result->imagename;
						$this->dbo->setQuery('UPDATE #__jvarcade_contentrating SET imagename = ' . $this->dbo->Quote($imagename) . ' WHERE id = ' . (int)$jva_id);
						$this->dbo->execute();
						if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
						@copy($this->old_imagepath . $result->imagename, $this->new_imagepath . 'contentrating' . '/' . $imagename);
					}
					
					// insert in the temp table
					$this->dbo->setQuery('INSERT INTO #__tmp_contentrating (pua_id, jva_id) VALUES(' . (int)$result->id . ', ' . (int)$jva_id . ')');
					$this->dbo->execute();
					if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
				}
			}
		}
	}
	
	public function migrateContests() {
		$this->dbo->setQuery('SELECT * FROM #__puarcade_contest');
		$results = $this->dbo->loadObjectList();
		
		if ($this->dbo->getErrorMsg()) {
			$this->setMsg($this->dbo->getErrorMsg(), 'error');
		} else {
			// create the temp table
			$query = 'DROP TABLE IF EXISTS `#__tmp_contest`';
			$this->dbo->setQuery($query);
			$this->dbo->execute();
			
			$query =
			'CREATE TABLE `#__tmp_contest` (
			`pua_id` int(11) unsigned NOT NULL,
			`jva_id` int(11) unsigned NOT NULL,
			PRIMARY KEY(`pua_id`),
			UNIQUE(`jva_id`)
			) Type=MyISAM';
			$this->dbo->setQuery($query);
			$this->dbo->execute();
			
			if (is_array($results) && count($results)) {
				foreach ($results as $result) {
					// insert the new item
					$this->dbo->setQuery(
					'INSERT INTO #__jvarcade_contest(name, description, startdatetime, enddatetime, islimitedtoslots, 
					minaccesslevelrequired, published, hasadvertisedstarted, hasadvertisedended, maxplaycount) VALUES (' 
					. $this->dbo->Quote($result->name) . ', ' 
					. $this->dbo->Quote($result->description) . ', '
					. $this->dbo->Quote($result->startdatetime) . ', '
					. $this->dbo->Quote($result->enddatetime) . ', '
					. $this->dbo->Quote($result->islimitedtoslots) . ', '
					. $this->dbo->Quote($result->minaccesslevelrequired) . ', '
					. $this->dbo->Quote($result->published) . ', '
					. $this->dbo->Quote($result->hasadvertisedstarted) . ', '
					. $this->dbo->Quote($result->hasadvertisedended) . ', '
					. $this->dbo->Quote($result->maxplaycount) . ')');
					$this->dbo->execute();
					if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
					
					// update and copy the image
					$jva_id = $this->dbo->insertid();
					if ($result->imagename && file_exists($this->old_imagepath . $result->imagename)) {					
						$imagename = $jva_id . '_' . $result->imagename;
						$this->dbo->setQuery('UPDATE #__jvarcade_contest SET imagename = ' . $this->dbo->Quote($imagename) . ' WHERE id = ' . (int)$jva_id);
						$this->dbo->execute();
						if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
						@copy($this->old_imagepath . $result->imagename, $this->new_imagepath . 'contests' . '/' . $imagename);
					}
					
					// insert in the temp table
					$this->dbo->setQuery('INSERT INTO #__tmp_contest (pua_id, jva_id) VALUES(' . (int)$result->id . ', ' . (int)$jva_id . ')');
					$this->dbo->execute();
					if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
				}
			}
		}
	}
	
	public function migrateFolders() {
		$folders = array();
		
		$this->dbo->setQuery('SELECT * FROM #__puarcade_folders ORDER BY id ASC');
		$results = $this->dbo->loadObjectList();
		
		if ($this->dbo->getErrorMsg()) {
			$this->setMsg($this->dbo->getErrorMsg(), 'error');
		} else {
			// create the temp table
			$query = 'DROP TABLE IF EXISTS `#__tmp_folders`';
			$this->dbo->setQuery($query);
			$this->dbo->execute();
			
			$query =
			'CREATE TABLE `#__tmp_folders` (
			`pua_id` int(11) unsigned NOT NULL,
			`jva_id` int(11) unsigned NOT NULL,
			PRIMARY KEY(`pua_id`),
			UNIQUE(`jva_id`)
			) Type=MyISAM';
			$this->dbo->setQuery($query);
			$this->dbo->execute();
			
			if (is_array($results) && count($results)) {
				foreach ($results as $result) {
					
					// check if folder with the same name already exists
					$this->dbo->setQuery('SELECT id FROM #__jvarcade_folders WHERE name = ' . $this->dbo->Quote($result->name) . ' LIMIT 1');
					$folder_exists = (int)$this->dbo->loadResult();
					$pua_id = (int)$result->id;
					$jva_id = $folder_exists;
					
					if (!$folder_exists) {
						// insert the new item
						$parentid = (is_array($folders) && count($folders) && isset($folders[(int)$result->parentid])) ? $folders[(int)$result->parentid] : (int)$result->parentid;
						$this->dbo->setQuery(
						'INSERT INTO #__jvarcade_folders(name, description, published, parentid, viewpermissions) VALUES (' 
						. $this->dbo->Quote($result->name) . ', ' 
						. $this->dbo->Quote($result->description) . ', '
						. $this->dbo->Quote($result->published) . ', '
						. $this->dbo->Quote((int)$parentid) . ', '
						. $this->dbo->Quote($result->viewpermissions) . ')');
						$this->dbo->execute();
						if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
						
						// update and copy the image
						$jva_id = $this->dbo->insertid();
						if ($result->imagename && file_exists($this->old_imagepath . $result->imagename)) {
							$imagename = $jva_id . '_' . $result->imagename;
							$this->dbo->setQuery('UPDATE #__jvarcade_folders SET imagename = ' . $this->dbo->Quote($imagename) . ' WHERE id = ' . (int)$jva_id);
							$this->dbo->execute();
							if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
							@copy($this->old_imagepath . $result->imagename, $this->new_imagepath . 'folders/' . $imagename);
						}
					}
					
					// insert in the temp table
					$folders[(int)$pua_id] = (int)$jva_id;
					$this->dbo->setQuery('INSERT INTO #__tmp_folders (pua_id, jva_id) VALUES(' . (int)$pua_id . ', ' . (int)$jva_id . ')');
					$this->dbo->execute();
					if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
				}
			}
		}
	}

	public function migrateGames() {
		
		$this->dbo->setQuery('SELECT * FROM #__puarcade_games');
		$results = $this->dbo->loadObjectList();
		if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'error');

		$folders = $this->getTemp('folders');
		$contentratings = $this->getTemp('contentrating');

		if ($this->return_arr['errnum'] < 1) {
			
			// create the temp table
			$query = 'DROP TABLE IF EXISTS `#__tmp_games`';
			$this->dbo->setQuery($query);
			$this->dbo->execute();
			
			$query =
			'CREATE TABLE `#__tmp_games` (
			`pua_id` int(11) unsigned NOT NULL,
			`jva_id` int(11) unsigned NOT NULL,
			PRIMARY KEY(`pua_id`),
			UNIQUE(`jva_id`)
			) Type=MyISAM';
			$this->dbo->setQuery($query);
			$this->dbo->execute();
			
			if (is_array($results) && count($results)) {
				foreach ($results as $i => $result) {
					
					// check if game with the same filename and gamename already exists
					$this->dbo->setQuery('SELECT id FROM #__jvarcade_games WHERE filename = ' . $this->dbo->Quote($result->filename) 
																			. ' OR gamename = ' . $this->dbo->Quote($result->gamename) . ' LIMIT 1');
					$game_exists = (int)$this->dbo->loadResult();
					$pua_id = (int)$result->id;
					$jva_id = $game_exists;
					
					if (!$game_exists) {
						// insert the new item
						$folderid = (is_array($folders) && count($folders) && isset($folders[(int)$result->folderid])) ? $folders[(int)$result->folderid] : (int)$result->folderid;
						$contentratingid = (is_array($contentratings) && count($contentratings) && isset($contentratings[(int)$result->contentratingid])) 
											? $contentratings[(int)$result->contentratingid] : (int)$result->contentratingid;
						$this->dbo->setQuery(
						'INSERT INTO #__jvarcade_games(gamename, title, height, width, description, numplayed, filename, background, published, 
														reverse_score, scoring, folderid, window, contentratingid, mochi, author) VALUES (' 
						. $this->dbo->Quote($result->gamename) . ', ' 
						. $this->dbo->Quote($result->title) . ', ' 
						. $this->dbo->Quote($result->height) . ', ' 
						. $this->dbo->Quote($result->width) . ', ' 
						. $this->dbo->Quote($result->description) . ', ' 
						. $this->dbo->Quote($result->numplayed) . ', ' 
						. $this->dbo->Quote($result->filename) . ', ' 
						. $this->dbo->Quote($result->background) . ', ' 
						. $this->dbo->Quote($result->published) . ', ' 
						. $this->dbo->Quote($result->reverse_score) . ', ' 
						. $this->dbo->Quote($result->scoring) . ', ' 
						. $this->dbo->Quote($folderid) . ', ' 
						. $this->dbo->Quote($result->window) . ', ' 
						. $this->dbo->Quote($contentratingid) . ', ' 
						. $this->dbo->Quote($result->mochi) . ', ' 
						. $this->dbo->Quote($result->author) . ')');
						$this->dbo->execute();
						if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
						
						// update and copy the image
						$jva_id = $this->dbo->insertid();
						if ($result->imagename && file_exists($this->old_fileimagepath . $result->imagename)) {
							$imagename = $jva_id . '_' . $result->imagename;
							$this->dbo->setQuery('UPDATE #__jvarcade_games SET imagename = ' . $this->dbo->Quote($imagename) . ' WHERE id = ' . (int)$jva_id);
							$this->dbo->execute();
							if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
							@copy($this->old_fileimagepath . $result->imagename, $this->new_imagepath . 'games' . '/' . $imagename);
						}
						// copy the game file
						if (file_exists($this->old_gamepath . $result->filename)) @copy($this->old_gamepath . $result->filename, $this->new_gamepath . $result->filename);
					}
					
					// insert in the temp table
					$folders[(int)$pua_id] = (int)$jva_id;
					$this->dbo->setQuery('INSERT INTO #__tmp_games (pua_id, jva_id) VALUES(' . (int)$pua_id . ', ' . (int)$jva_id . ')');
					$this->dbo->execute();
					if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
					
					if ($i%500 == 0) set_time_limit(30);
				}
			}
		}
	}

	public function migrateFavs() {
		
		$this->dbo->setQuery('SELECT * FROM #__puarcade_faves');
		$results = $this->dbo->loadObjectList();
		if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'error');
		$games = $this->getTemp('games');

		if ($this->return_arr['errnum'] < 1) {
			if (is_array($results) && count($results)) {
				foreach ($results as $i => $result) {
					$gameid = (is_array($games) && count($games) && isset($games[(int)$result->gid])) ? $games[(int)$result->gid] : (int)$result->gid;
					// check if record for this game and user already exists
					$this->dbo->setQuery('SELECT id FROM #__jvarcade_faves WHERE gid = ' . $this->dbo->Quote($gameid) . ' AND userid = ' . $this->dbo->Quote($result->userid) . ' LIMIT 1');
					$fav_exists = (int)$this->dbo->loadResult();
					if (!$fav_exists) {
						// insert the record
						$this->dbo->setQuery('INSERT INTO #__jvarcade_faves(userid, gid) VALUES (' . $this->dbo->Quote($result->userid) . ', ' . $this->dbo->Quote($gameid) . ')');
						$this->dbo->execute();
						if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
					}
					if ($i%500 == 0) set_time_limit(30);
				}
			}
		}
	}

	public function migrateTags() {
		
		$this->dbo->setQuery('SELECT * FROM #__puarcade_tags');
		$results = $this->dbo->loadObjectList();
		if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'error');
		$games = $this->getTemp('games');

		if ($this->return_arr['errnum'] < 1) {
			if (is_array($results) && count($results)) {
				foreach ($results as $i => $result) {
					$gameid = (is_array($games) && count($games) && isset($games[(int)$result->gameid])) ? $games[(int)$result->gameid] : (int)$result->gameid;
					// check if record for this game and user already exists
					$this->dbo->setQuery('SELECT id FROM #__jvarcade_tags WHERE gameid = ' . $this->dbo->Quote($gameid) . ' AND tag = ' . $this->dbo->Quote($result->tag) . ' LIMIT 1');
					$tag_exists = (int)$this->dbo->loadResult();
					if (!$tag_exists) {
						// insert the record
						$this->dbo->setQuery('INSERT INTO #__jvarcade_tags(tag, count, gameid) VALUES (' . $this->dbo->Quote($result->tag) . ', ' . $this->dbo->Quote($result->count) . ', ' . $this->dbo->Quote($gameid) . ')');
						$this->dbo->execute();
						if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
					}
					if ($i%500 == 0) set_time_limit(30);
				}
			}
		}
	}

	public function migrateRatings() {
		
		$this->dbo->setQuery('SELECT * FROM #__puarcade_ratings');
		$results = $this->dbo->loadObjectList();
		if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'error');
		$games = $this->getTemp('games');

		if ($this->return_arr['errnum'] < 1) {
			if (is_array($results) && count($results)) {
				foreach ($results as $i => $result) {
					$gameid = (is_array($games) && count($games) && isset($games[(int)$result->gameid])) ? $games[(int)$result->gameid] : (int)$result->gameid;
					// check if record for this game and user already exists
					$this->dbo->setQuery('SELECT id FROM #__jvarcade_ratings WHERE gameid = ' . $this->dbo->Quote($gameid) . ' LIMIT 1');
					$id_exists = (int)$this->dbo->loadResult();
					if (!$id_exists) {
						// insert the record
						$this->dbo->setQuery('INSERT INTO #__jvarcade_ratings(total_votes, total_value, gameid, used_ids) VALUES (' . $this->dbo->Quote($result->total_votes) . ', ' . $this->dbo->Quote($result->total_value) . ', ' . $this->dbo->Quote($gameid) . ', ' . $this->dbo->Quote($result->used_ids) . ')');
					} else {
						// update
						$this->dbo->setQuery('UPDATE #__jvarcade_ratings SET ' 
												. 'total_votes = ' . $this->dbo->Quote($result->total_votes) . ', '
												. 'total_value = ' . $this->dbo->Quote($result->total_value) . ', '
												. 'used_ids = ' . $this->dbo->Quote($result->used_ids)
											. ' WHERE gameid = ' . $this->dbo->Quote($gameid));
					}
					$this->dbo->execute();
					if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
					if ($i%500 == 0) set_time_limit(30);
				}
			}
		}
	}

	public function migrateScores() {
		
		$this->dbo->setQuery('SELECT * FROM #__puarcade');
		$results = $this->dbo->loadObjectList();
		if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'error');
		$games = $this->getTemp('games');

		if ($this->return_arr['errnum'] < 1) {
			if (is_array($results) && count($results)) {
				foreach ($results as $i => $result) {
					$gameid = (is_array($games) && count($games) && isset($games[(int)$result->gameid])) ? $games[(int)$result->gameid] : (int)$result->gameid;
					// check if record for this game and user already exists
					$this->dbo->setQuery('SELECT id FROM #__jvarcade WHERE gameid = ' . $this->dbo->Quote($gameid) . ' AND userid = ' . $this->dbo->Quote($result->userid) . ' LIMIT 1');
					$id_exists = (int)$this->dbo->loadResult();
					if (!$id_exists) {
						// insert the record
						$this->dbo->setQuery('INSERT INTO #__jvarcade (userid, score, ip, gameid, date, published) VALUES (' . $this->dbo->Quote($result->userid) . ', ' . $this->dbo->Quote($result->score) . ', ' . $this->dbo->Quote($result->ip) . ', ' . $this->dbo->Quote($gameid) . ', ' . $this->dbo->Quote($result->date) . ', ' . $this->dbo->Quote($result->published) . ')');
					} else {
						// update
						$this->dbo->setQuery('UPDATE #__jvarcade SET ' 
												. 'score = ' . $this->dbo->Quote($result->score) . ', '
												. 'ip = ' . $this->dbo->Quote($result->ip) . ', '
												. 'date = ' . $this->dbo->Quote($result->date) . ', '
												. 'published = ' . $this->dbo->Quote($result->published)
											. ' WHERE gameid = ' . $this->dbo->Quote($gameid)
											. ' AND userid = ' . $this->dbo->Quote($result->userid));
					}
					$this->dbo->execute();
					if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
					if ($i%500 == 0) set_time_limit(30);
				}
			}
		}
	}

	public function migrateContestGame() {
		
		$this->dbo->setQuery('SELECT * FROM #__puarcade_contestgame');
		$results = $this->dbo->loadObjectList();
		if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'error');
		$games = $this->getTemp('games');
		$contests = $this->getTemp('contest');

		if ($this->return_arr['errnum'] < 1) {
			if (is_array($results) && count($results)) {
				foreach ($results as $i => $result) {
					$gameid = (is_array($games) && count($games) && isset($games[(int)$result->gameid])) ? $games[(int)$result->gameid] : (int)$result->gameid;
					$contestid = (is_array($contests) && count($contests) && isset($contests[(int)$result->contestid])) ? $contests[(int)$result->contestid] : (int)$result->contestid;
					// check if record for this game and user already exists
					$this->dbo->setQuery('SELECT count(*) FROM #__jvarcade_contestgame WHERE gameid = ' . $this->dbo->Quote($gameid) . ' AND contestid = ' . $this->dbo->Quote($contestid) . ' LIMIT 1');
					$id_exists = (int)$this->dbo->loadResult();
					if (!$id_exists) {
						// insert the record
						$this->dbo->setQuery('INSERT INTO #__jvarcade_contestgame (contestid, gameid) VALUES (' . $this->dbo->Quote($contestid) . ', ' . $this->dbo->Quote($gameid) . ')');
						$this->dbo->execute();
						if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
					}
					if ($i%500 == 0) set_time_limit(30);
				}
			}
		}
	}

	public function migrateContestMember() {
		
		$this->dbo->setQuery('SELECT * FROM #__puarcade_contestmember');
		$results = $this->dbo->loadObjectList();
		if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'error');
		$contests = $this->getTemp('contest');

		if ($this->return_arr['errnum'] < 1) {
			if (is_array($results) && count($results)) {
				foreach ($results as $i => $result) {
					$contestid = (is_array($contests) && count($contests) && isset($contests[(int)$result->contestid])) ? $contests[(int)$result->contestid] : (int)$result->contestid;
					// check if record for this game and user already exists
					$this->dbo->setQuery('SELECT count(*) FROM #__jvarcade_contestmember WHERE userid = ' . $this->dbo->Quote($result->userid) . ' AND contestid = ' . $this->dbo->Quote($contestid) . ' LIMIT 1');
					$id_exists = (int)$this->dbo->loadResult();
					if (!$id_exists) {
						// insert the record
						$this->dbo->setQuery('INSERT INTO #__jvarcade_contestmember (contestid, userid, dateregistered) VALUES (' . $this->dbo->Quote($contestid) . ', ' . $this->dbo->Quote($result->userid) . ', ' . $this->dbo->Quote($result->dateregistered) . ')');
						$this->dbo->execute();
						if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
					}
					if ($i%500 == 0) set_time_limit(30);
				}
			}
		}
	}

	public function migrateContestScore() {
		
		$this->dbo->setQuery('SELECT * FROM #__puarcade_contestscore');
		$results = $this->dbo->loadObjectList();
		if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'error');
		$games = $this->getTemp('games');
		$contests = $this->getTemp('contest');

		if ($this->return_arr['errnum'] < 1) {
			if (is_array($results) && count($results)) {
				foreach ($results as $i => $result) {
					$gameid = (is_array($games) && count($games) && isset($games[(int)$result->gameid])) ? $games[(int)$result->gameid] : (int)$result->gameid;
					$contestid = (is_array($contests) && count($contests) && isset($contests[(int)$result->contestid])) ? $contests[(int)$result->contestid] : (int)$result->contestid;
					// check if record for this game and user already exists
					$this->dbo->setQuery('SELECT id FROM #__jvarcade_contestscore WHERE gameid = ' . $this->dbo->Quote($gameid) . ' AND userid = ' . $this->dbo->Quote($result->userid) . ' AND contestid = ' . $this->dbo->Quote($contestid) .  ' LIMIT 1');
					$id_exists = (int)$this->dbo->loadResult();
					if (!$id_exists) {
						// insert the record
						$this->dbo->setQuery('INSERT INTO #__jvarcade_contestscore (userid, score, ip, gameid, date, published, contestid, attemptnum) VALUES (' . $this->dbo->Quote($result->userid) . ', ' . $this->dbo->Quote($result->score) . ', ' . $this->dbo->Quote($result->ip) . ', ' . $this->dbo->Quote($gameid) . ', ' . $this->dbo->Quote($result->date) . ', ' . $this->dbo->Quote($result->published) . ', ' . $this->dbo->Quote($contestid) . ', ' . $this->dbo->Quote($result->attemptnum) . ')');
					} else {
						// update
						$this->dbo->setQuery('UPDATE #__jvarcade_contestscore SET ' 
												. 'score = ' . $this->dbo->Quote($result->score) . ', '
												. 'ip = ' . $this->dbo->Quote($result->ip) . ', '
												. 'date = ' . $this->dbo->Quote($result->date) . ', '
												. 'attemptnum = ' . $this->dbo->Quote($result->attemptnum) . ', '
												. 'published = ' . $this->dbo->Quote($result->published)
											. ' WHERE gameid = ' . $this->dbo->Quote($gameid)
											. ' AND userid = ' . $this->dbo->Quote($result->userid)
											. ' AND contestid = ' . $this->dbo->Quote($contestid));
					}
					$this->dbo->execute();
					if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
					if ($i%500 == 0) set_time_limit(30);
				}
			}
		}
	}
	
	public function migrateConfig() {
		$changes = array(
			'pu_scorelink' => 'scorelink',
			'pufoldergames' => 'foldergames',
			'pu_avatar' => 'avatar',
			'pufoldercols' => 'foldercols',
		);
		$this->dbo->setQuery('SELECT * FROM #__puarcade_config LIMIT 1');
		$results = $this->dbo->loadAssoc();
		if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'error');
		
		if ($this->return_arr['errnum'] < 1) {
			foreach ($results as $name => $value) {
				if(in_array($name, array('games_dir', 'games_images_dir', 'comments', 'installed_version', 'TagPerms'))) continue;
				$optname = isset($changes[$name]) ? $changes[$name] : $name;
				$this->dbo->setQuery('UPDATE #__jvarcade_settings SET value = ' . $this->dbo->Quote($value) . ' WHERE optname = ' . $this->dbo->Quote($optname));
				$this->dbo->execute();
				if ($this->dbo->getErrorMsg()) $this->setMsg($this->dbo->getErrorMsg(), 'warn');
			}
		}
	}

}
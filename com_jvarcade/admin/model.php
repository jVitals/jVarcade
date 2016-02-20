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


class jvarcadeModelCommon extends JModelLegacy {
	protected $dbo;
	protected $filterobj = null;
	protected $conf = null;
	protected $confobj = null;
	
	public function __construct() {
		parent::__construct();
		$this->dbo = JFactory::getDBO();
		$app = JFactory::getApplication('site');
		global $option;
		
		// Get pagination request variables
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->input->getInt('limitstart', 0);

		// In case limit has been changed, adjust it
		$limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

		$this->setState('limit', $limit);
		$this->setState('limitstart', $limitstart);
		
		$this->filterobj = new JFilterInput(null, null, 1, 1);
	}
	
	public function getDBerr() {
		$app = JFactory::getApplication('site');
		$app->enqueueMessage($this->dbo->getErrorMsg(), 'error');
	}
	
	public function getTotal(){
		$this->dbo->setQuery('SELECT FOUND_ROWS();');
		$result = $this->dbo->loadResult();
		return $result;
	}
	
	public function getConf() {
		if (!$this->conf) {
			$this->_loadConf();
		}
		return $this->conf;
	}
	
	private function _loadConf() {
		if (!$this->conf) {
			$this->dbo->setQuery("SELECT * FROM #__jvarcade_settings ORDER BY " . $this->dbo->quoteName('group') . ", " . $this->dbo->quoteName('ord') . "");
			$this->conf = $this->dbo->loadAssocList();
			return (boolean)$this->conf;
		}
		return true;
	}
	
	public function getConfObj() {
		if (!$this->confobj) {
			$this->_loadConfObj();
		}
		return $this->confobj;
	}
	
	private function _loadConfObj() {
		static $loadedconf;
		
		if (!$loadedconf) {
			$my = JFactory::getUser();
			$app = JFactory::getApplication();
			$this->dbo->setQuery("SELECT * FROM #__jvarcade_settings ORDER BY " . $this->dbo->quoteName('group') . ", " . $this->dbo->quoteName('ord') . "");
			$res = $this->dbo->loadObjectList();
			$obj = new stdClass();
			if (count($res)) {
				foreach ($res as $row) {
					$optname = $row->optname;
					$obj->$optname = $row->value;
				}
			}
			
			// TIMEZONE - if user is logged in we use the user timezone, if guest - we use timezone in global settings
			$obj->timezone = ((int)$my->guest ? $app->getCfg('offset') : $my->getParam('timezone', $app->getCfg('offset')));
			
			// DIFF BETWEEN SERVER AND USER TIMEZONE - date already contains the server timezone offset so we subtract it
			if (JVA_COMPATIBLE_MODE == '15') {
				$obj->tz_diff = ($obj->timezone*3600 - date('Z'))/3600;
			} else {
				$dateTimeZone = new DateTimeZone($obj->timezone);
				$obj->tz_diff = ($dateTimeZone->getOffset(new DateTime("now", $dateTimeZone)) - (int)date('Z'))/3600;
			}
			
			$this->confobj = $loadedconf = $obj;
			return (boolean)$this->confobj;
		} else {
			$this->confobj = $loadedconf;
		}
		return true;
	}
	
	public function configSave() {
		$app = JFactory::getApplication('site');
		$config_save = $app->input->getInt('config_save', 0);
		if ($config_save) {
			$confdb = $this->getConf();
			$conf = array();
			foreach ($confdb as $obj) {
				$confvalue = $app->input->get($obj['optname'], '', 'raw');
				if ($obj['optname'] == 'TagPerms' && is_array($confvalue)) $confvalue = implode(',', $confvalue);
				if ($obj['optname'] == 'DloadPerms' && is_array($confvalue)) $confvalue = implode(',', $confvalue);
				if (strpos($obj['optname'],'alias') !== false) $confvalue = str_replace(array(' '), array(''), trim($confvalue));
				if (strlen(trim($confvalue))) {
					$conf[$obj['optname']] = $this->filterobj->clean(trim($confvalue), 'html');
				} else {
					$conf[$obj['optname']] = $obj['value'];
				}
			}
			
			foreach ($conf as $optname => $value) {
				$this->dbo->setQuery("UPDATE #__jvarcade_settings SET " . $this->dbo->quoteName('value') . " = " . $this->dbo->Quote($value) . " 
					WHERE " . $this->dbo->quoteName('optname') . " = " . $this->dbo->Quote($optname) . "");
				$this->dbo->execute();
			}
			$app->redirect('index.php?option=com_jvarcade&task=settings');
			exit;
		}
	}
	
	public function getContentRatingList() {
		$this->dbo->setQuery('SELECT id, name FROM #__jvarcade_contentrating ORDER BY id');
		return $this->dbo->loadObjectList();
	}
	
	public function getAcl() {
		$query = 'SELECT id, title as name FROM #__usergroups';
		$this->dbo->setQuery($query);
		return $this->dbo->loadAssocList('id');
	}
	
	public function getGamesCount() {
		$this->dbo->setQuery('SELECT count(*) as count FROM #__jvarcade_games');
		return $this->dbo->loadResult();
	}

	public function getScoresCount() {
		$this->dbo->setQuery('SELECT count(*) as count FROM #__jvarcade');
		return $this->dbo->loadResult();
	}

	public function getScores() {
	
		if ($this->orderby) {
			$orderby = ' ORDER BY ' . $this->orderby . ' ' . ($this->orderdir ? $this->orderdir : '');
		} else {
			$orderby = 'ORDER BY p.date DESC';
		}
		
		$where = array();
		$wherestr = '';
		
		if (isset($this->searchfields) && is_array($this->searchfields) && count($this->searchfields) > 0) {
			foreach ($this->searchfields as $name => $value) {
				if ($value != '') {
					$escaped = $this->dbo->Quote( '%'.$this->dbo->escape($value, true ).'%', false );
					$where[] = $name . ' LIKE ' . $escaped;
				}
			}
		}
		$wherestr = (count($where) ? ' WHERE ( ' . implode( ' ) AND ( ', $where ) . ' )' : '' );
		
		$query = "SELECT SQL_CALC_FOUND_ROWS p.*, u.username, g.title FROM #__jvarcade p " . 
						"LEFT JOIN #__users u ON u.id = p.userid " . 
						"JOIN #__jvarcade_games g ON g.id = p.gameid " . 
					$wherestr . ' ' . 
					$orderby;
		$this->dbo->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		return $this->dbo->loadObjectList();
	}

	public function getLatestScores() {
		$query = "SELECT p.userid, p.score, u.username, g.title " . 
					"FROM #__jvarcade p " . 
						"JOIN #__jvarcade_games g ON g.id = p.gameid " . 
						"LEFT JOIN #__users u ON u.id = p.userid " . 
				"ORDER by p.date DESC LIMIT 5 ";
		$this->dbo->setQuery($query);
		return $this->dbo->loadObjectList();
	}

	public function getLatestGames() {
		$query = "SELECT title, numplayed FROM #__jvarcade_games ORDER by id DESC LIMIT 5 ";
		$this->dbo->setQuery($query);
		return $this->dbo->loadObjectList();
	}

	public function getGameTitles($id = array()) {
		if (is_array($id) && count($id)) {
			$query = 'SELECT title FROM #__jvarcade_games WHERE id IN (' . implode(',', $id) . ') ORDER BY id DESC';
			$this->dbo->setQuery($query);
			$return = $this->dbo->loadColumn();
			return is_array($return) && count($return) ? $return : array() ;
		}
		return array();
	}

	public function getGameIdTitles() {
		$query = 'SELECT id, title FROM #__jvarcade_games ORDER BY id DESC';
		$this->dbo->setQuery($query);
		$return = $this->dbo->loadObjectList();
		return is_array($return) && count($return) ? $return : array() ;

	}
	
	public function getContests($id = 0) {
	
	
		$where = array();
		$wherestr = '';
	
		if ((int)$id) $where[] = 'id = ' . (int)$id;
	
		if (isset($this->searchfields) && is_array($this->searchfields) && count($this->searchfields) > 0) {
			foreach ($this->searchfields as $name => $value) {
				if ($value != '') {
					$escaped = $this->dbo->Quote( '%'.$this->dbo->escape($value, true ).'%', true );
					$where[] = $name . ' LIKE ' . $escaped;
				}
			}
		}
		$wherestr = (count($where) ? ' WHERE ( ' . implode( ' ) AND ( ', $where ) . ' )' : '' );
	
		$query = "SELECT SQL_CALC_FOUND_ROWS * " .
				"FROM #__jvarcade_contest " .
				$wherestr . ' ';
		$this->dbo->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		$return = $this->dbo->loadObjectList();
		return $return;
	}
	
	public function addGameToContest($game_ids = array(), $contest_ids = array()) {
		if (is_array($game_ids) && count($game_ids) && is_array($contest_ids) && count($contest_ids)) {
			$query = 'INSERT INTO #__jvarcade_contestgame (' . $this->dbo->quoteName('gameid') . ', ' . $this->dbo->quoteName('contestid') . ') VALUES ';
			$q = array();
			foreach ($game_ids as $game_id) {
				foreach ($contest_ids as $contest_id) {
					$this->dbo->setQuery('SELECT gameid FROM #__jvarcade_contestgame WHERE ' . $this->dbo->quoteName('gameid') . ' = ' . (int)$game_id . ' AND ' . $this->dbo->quoteName('contestid') . ' = ' . (int)$contest_id);
					if (!(int)$this->dbo->loadResult()) {
						$q[] = '(' . (int)$game_id . ',' . (int)$contest_id . ')';
					}
				}
			}
			if (!count($q)) {
				return true;
			}
			$query .= implode(', ',$q);
			$this->dbo->setQuery($query);
			if($this->dbo->execute()) {
				return true;
			}
		}
		return false;
	}
	
	public function getContestGames($contest_id) {
		$this->dbo->setQuery('SELECT g.id, g.title, g.numplayed ' . 
							' FROM #__jvarcade_contestgame cg ' .
							'	LEFT JOIN #__jvarcade_games g ON cg.gameid = g.id ' .
							' WHERE cg.' . $this->dbo->quoteName('contestid') . ' = ' . (int)$contest_id . 
							' ORDER BY g.id DESC'
							);
		return $this->dbo->loadObjectList();

	}
	
	public function getGameContests($game_id) {
		$this->dbo->setQuery('SELECT c.* ' . 
							' FROM #__jvarcade_contestgame cg ' .
							'	LEFT JOIN #__jvarcade_contest c ON cg.contestid = c.id ' .
							' WHERE cg.' . $this->dbo->quoteName('gameid') . ' = ' . (int)$game_id . 
							' ORDER BY c.startdatetime DESC'
							);
		return $this->dbo->loadObjectList();

	}
	
	public function deleteGameFromContest($game_ids = array(), $contest_ids = array()) {
		$return = true;
		if (is_array($game_ids) && count($game_ids) && is_array($contest_ids) && count($contest_ids)) {
			foreach ($game_ids as $game_id) {
				foreach ($contest_ids as $contest_id) {
					$this->dbo->setQuery('DELETE FROM #__jvarcade_contestgame WHERE ' . $this->dbo->quoteName('gameid') . ' = ' . (int)$game_id . ' AND ' . $this->dbo->quoteName('contestid') . ' = ' . (int)$contest_id);
					if (!(int)$this->dbo->execute()) {
						$return = false;
					}
				}
			}
		} else {
			$return = false;
		}
		return $return;
	}
	
	public function regenerateLeaderBoard($contest_id = 0) {
		//First clear out the old data
		$query = 'DELETE FROM #__jvarcade_leaderboard WHERE ' . $this->dbo->quoteName('contestid') . ' = ' . (int)$contest_id;
		$this->dbo->setQuery($query);
		if (!$this->dbo->execute()){
			return false;
		}
		
		// Setup our point values
		$points = array(
			1 => 20,
			2 => 19,
			3 => 18,
			4 => 17,
			5 => 16,
			6 => 15,
			7 => 14,
			8 => 13,
			9 => 12,
			10 => 11,
			11 => 10,
			12 => 9,
			13 => 8,
			14 => 7,
			15 => 6,
			16 => 5,
			17 => 4,
			18 => 3,
			19 => 2,
			20 => 1,
		);

		$table  = (int)$contest_id ? ' #__jvarcade_contestscore ' : ' #__jvarcade ' ;
		$where  = (int)$contest_id ? ' WHERE contestid = ' . $contest_id . ' ' : '' ;

		$this->dbo->setQuery('SELECT * FROM ' . $table . $where . ' ORDER BY gameid DESC, score DESC, date ASC ');
		$scores = $this->dbo->loadObjectList();
		
		$all_scores = array();
		$user_score = array();
		$tmp = array();
		$pos = 0;
		
		// first calculate the placement
		$i = 0;
		for($i = 0; $i < count($scores); $i++) {
			if(!array_key_exists($scores[$i]->gameid, $tmp)) {
				$tmp[$scores[$i]->gameid] = $scores[$i]->gameid; 
				$pos = 1;
			} else {
				$pos++;
			}
			if ($pos <= 20) {
				// here we give + 1 point classement
				$all_scores[] = array('points' => $points[$pos]+1, 'uid' => $scores[$i]->userid);
			}
		}
		
		// calculate per user points
		$i = 0;
		for($i = 0; $i < count($all_scores); $i++) {
			if(!array_key_exists($all_scores[$i]['uid'], $user_score)) {
				$user_score[$all_scores[$i]['uid']] = $all_scores[$i]['points'];
			} else {
				$user_score[$all_scores[$i]['uid']] = $user_score[$all_scores[$i]['uid']] + $all_scores[$i]['points'];
			}
		}
		arsort($user_score);
		
		$qarr = array();
		foreach($user_score as $key => $value) {
			$qarr[] = '(' . $this->dbo->Quote((int)$contest_id) . ', ' . $this->dbo->Quote((int)$key) . ', ' . $this->dbo->Quote((int)$value) . ')';
		}

		$this->dbo->setQuery('INSERT INTO #__jvarcade_leaderboard(' . $this->dbo->quoteName('contestid') . ', ' . $this->dbo->quoteName('userid') . ', ' . $this->dbo->quoteName('points') . ') VALUES ' . implode(',', $qarr));
		if (!count($qarr) || $this->dbo->execute()) {
			$globalconf = JFactory::getConfig();
			$path = $globalconf->get('tmp_path') . '/' . 'lb_' . $contest_id . '.txt';
			if (file_exists($path)) unlink($path);
			return true;
		} else {
			return false;
		}
	}
	
	public function showDiagnostics() {
		$msg = array();
		$conf = $this->getConfObj();
		$safemode = (@ini_get('safe_mode') ? JText::_('COM_JVARCADE_MAINTENANCE_PHPSAFEMODE_YES') : JText::_('COM_JVARCADE_MAINTENANCE_PHPSAFEMODE_NO'));
		
		$tables = array(
			'#__jvarcade_contentrating',
			'#__jvarcade_contest',
			'#__jvarcade_contestgame',
			'#__jvarcade_contestmember',
			'#__jvarcade_contestscore',
			'#__jvarcade_faves',
			'#__jvarcade_folders',
			'#__jvarcade_gamedata',
			'#__jvarcade_games',
			'#__jvarcade_lastvisited',
			'#__jvarcade_leaderboard',
			'#__jvarcade_ratings',
			'#__jvarcade_settings',
			'#__jvarcade_tags'
		);

		$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_JVAVERSION', JVA_VERSION);
		$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_JOOMLAVERSION', JVA_JOOMLA_VERSION);
		$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_PHPVERSION', phpversion());
		$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_PHPINTERFACE', php_sapi_name());
		$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_PHPSAFEMODE',  $safemode);
		$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_DBVERSION', $this->dbo->getVersion());
		
		$msg[] = '<br><strong>' . JText::_('COM_JVARCADE_MAINTENANCE_SCOREFILES') . '</strong><br/>';
		
		$filelist = array ('newscore.php', 'arcade.php');
		foreach($filelist as $file) {
			$filename = JPATH_SITE . '/' . $file ;
			if (file_exists($filename)) {
				$permresult = substr(sprintf('%o', fileperms($filename)), -4);
				$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_FILEHASPERMS', $filename, $permresult);
			} else {
				$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_FILENOTEXISTS', $filename);
			}
		}
		
		$msg[] = '<br><strong>' . JText::_('COM_JVARCADE_MAINTENANCE_ANALYZE') . '</strong><br/>';
		
		foreach ($tables as $table) {
			$this->dbo->setQuery('ANALYZE TABLE ' . $table);
			$result = $this->dbo->loadObject();
			$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_ANALYZETABLE', $table, $result->Msg_text);
		}
		
		$msg[] = '<br><strong>' . JText::_('COM_JVARCADE_MAINTENANCE_OPTIMIZE') . '</strong><br/>';
		
		foreach ($tables as $table) {
			$this->dbo->setQuery('OPTIMIZE TABLE ' . $table);
			$result = $this->dbo->loadObject();
			$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_OPTIMIZETABLE', $table, $result->Msg_text);
		}
		
		$msg[] = '<br><strong>' . JText::_('COM_JVARCADE_MAINTENANCE_PLUGINS') . '</strong><br/>';

		if (JPluginHelper::isEnabled('system', 'jvarcade')) {
			$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_PLUGIN', 'jVArcade System Plugin', JText::_('COM_JVARCADE_MAINTENANCE_PLUGIN_ENABLED'));
		}
		$plugins = JPluginHelper::getPlugin('puarcade');
		foreach($plugins as $plugin) {
			$enabled = JPluginHelper::isEnabled('puarcade', $plugin->name) ? JText::_('COM_JVARCADE_MAINTENANCE_PLUGIN_ENABLED') : JText::_('COM_JVARCADE_MAINTENANCE_PLUGIN_DISABLED');
			$msg[] = JText::sprintf('COM_JVARCADE_MAINTENANCE_PLUGIN', $plugin->name, $enabled);
		}

		if (count($msg)) {
			return implode('<br/>', $msg); 
		}
		return '';
	}
	
	public function doMaintenance($service, $context, $gameid, $contestid) {
		$result = -1;
		$message = '';
		$sql = '';
		$where = '';
		$and = '';
		$langstr = '';
		$table = '';
		
		if ($context == 'game') {
			$where = ' WHERE ' . $this->dbo->quoteName('gameid') . ' = ' . (int)$gameid;
			$and = ' AND ' . $this->dbo->quoteName('gameid') . ' = ' . (int)$gameid;
			$langstr = 'GAME_';
		} elseif ($context == 'contest') {
			$where = ' WHERE ' . $this->dbo->quoteName('contestid') . ' = ' . (int)$contestid;
			$and = ' AND ' . $this->dbo->quoteName('contestid') . ' = ' . (int)$contestid;
			$langstr = 'CONTEST_';
			$table = '_contestscore';
		}
		switch ($service) {
			case 'deleteallscores':
				$sql = 'DELETE FROM #__jvarcade' . $table . ' ' . $where;
				break;
			case 'deleteguestscores':
				$sql = 'DELETE FROM #__jvarcade' . $table . ' WHERE ' . $this->dbo->quoteName('userid') . ' = 0 ' . $and;
				break;
			case 'deletezeroscores':
				$sql = 'DELETE FROM #__jvarcade' . $table . ' WHERE ' . $this->dbo->quoteName('score') . ' = 0 ' . $and;
				break;
			case 'deleteblankscores':
				$sql = 'DELETE FROM #__jvarcade' . $table . ' WHERE (' . $this->dbo->quoteName('score') . ' = \'\' OR ' . $this->dbo->quoteName('score') . ' IS NULL) ' . $and;
				break;
			case 'clearallratings':
				$sql = 'DELETE FROM #__jvarcade_ratings ' . $where;
				break;
			case 'deletealltags':
				$sql = 'DELETE FROM #__jvarcade_tags ' . $where;
				break;
		}
		if ($sql) {
			$this->dbo->setDebug(0);
			$this->dbo->setQuery($sql);
			if ($this->dbo->execute()) {
				$result = 1;
			}
		}
		if ($service == 'recalculateleaderboard' && $this->regenerateLeaderBoard((int)$contestid)) {
			$result = 1;
		}
		
		if ($result == 1) {
			//~ $message = '<span style="color: green;">' . JText::_('COM_JVARCADE_MAINTENANCE_' . $langstr . strtoupper($service) . '_SUCCESS') . '</span>';
			$message = '<span style="color: green;">' . JText::_('COM_JVARCADE_MAINTENANCE_' . $langstr . strtoupper($service) . '_SUCCESS') . '<br/>' . $sql . '</span>';
		} elseif ($result == -1) {
			$message = JText::_('COM_JVARCADE_MAINTENANCE_' . $langstr . strtoupper($service) . '_FAILED');
			if ($this->dbo->getErrorMsg()) {
				$message .= '<br>' . $this->dbo->getErrorMsg();
			}
			$message = '<span style="color: red;">' . $message . '</span>';
		}
		
		if ($service == 'supportdiagnostics') {
			$result = 1;
			$message = $this->showDiagnostics();
		}
		
		return array('status' => $result, 'msg' => $message);
	}

	// method to get the changelog file
	public function getChangeLog() {
		//jimport('joomla.utilities.simplexml');
		

		// Load changelog
		$xmlfile = $this->loadChangelogFile();
		$xml = simplexml_load_file($xmlfile);
		
		$output = '<dl class="changelog">';
		foreach ($xml->version as $version) {
			$attr = $version->attributes();
			
			$output .= '<dt>';
			$output .= '<h4>' . JText::_('COM_JVARCADE_CHANGELOG_VERSION') . ': ' . $attr['number'] . '</h4>';
			$output .= '<b>' . JText::_('COM_JVARCADE_CHANGELOG_DATE') . ':</b> ' . $version->date[0] . '<br/>';
			$output .= '<b>' . JText::_('COM_JVARCADE_CHANGELOG_DESCRIPTION') . ':</b> ' . $version->description[0];
			$output .= '</dt>';
			$output .= '<dd><ul>';
			/*if(isset($version->list) && is_array($version->list) && is_array($version->list[0]->item)) {*/
				foreach ($version->list[0]->item as $item) {
					$itemAttr = $item->attributes();
					$output .= '<li><span class="' . $itemAttr['type'] . '">' . $itemAttr['type'] . '</span> ' . $item . '</li>';
				}
			//}
			$output .= '</ul></dd>';
		}
		$output .= '</dl>';

		return $output;
	}
	
	public function loadChangelogFile() {
		$config = JFactory::getConfig();
		$tmp_path = $config->get('tmp_path');
		$filename = 'jvarcade-changelog.xml';
		$tmpfile = $tmp_path . '/' . $filename;
		$default_file = JPATH_ROOT . '/administrator/components/com_jvarcade/changelog.xml';
		
		$dorequest = false;
		$filefound = false;
		
		if (is_file($tmpfile)) {
			$filefound = true;
			if ((filemtime($tmpfile) + (60 * 60 * 24)) < time()) {
				// only once per day
				$dorequest = true;
			}
		}
		
		if (!$filefound) $dorequest = true;
		
		if ($dorequest) {
			
			$http = JHttpFactory::getHttp();
			$response = $http->get('https://rawgit.com/jVitals/jVarcade/master/com_jvarcade/admin/changelog.xml', array(), 90);
			$response = $response->body;
			
			
			
			$fp = @fopen($tmpfile, "wb");
			if ($fp) {
				@flock($fp, LOCK_EX);
				$len = strlen($response);
				@fwrite($fp, $response, $len);
				@flock($fp, LOCK_UN);
				@fclose($fp);
				$written = true;
			}
			// Data integrity check
			if ($written && (file_get_contents($tmpfile))) {
				// nothing to do
			} else {
				unlink($tmpfile);
			}
		}
		
		return (is_file($tmpfile) ? $tmpfile : $default_file);
	}

}

?>

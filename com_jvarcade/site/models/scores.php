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

class jvarcadeModelScores extends jvarcadeModelCommon {
	private $contests = null;
	
	// SCORES
	
	public function gameScoreCount($game_id) {

		$query = 'SELECT COUNT(*) as count from #__jvarcade WHERE gameid = ' . $this->dbo->Quote((int)$game_id) . ' AND published = ' . $this->dbo->Quote(1);
		$this->dbo->setQuery($query );
		return  $this->dbo->loadResult();
	}
	
	public function getScores($game_id, $reverse) {
		
		$def_ord = $reverse ? 'ASC' : 'DESC' ;
		
		if ($this->orderby) {
			$orderby = ' ORDER BY ' . $this->orderby . ' ' . ($this->orderdir ? $this->orderdir : '');
		} else {
			$orderby = ' ORDER BY p.score ' . $def_ord;
		}
		
		$query = 'SELECT SQL_CALC_FOUND_ROWS p.*, g.gamename, g.title, u.id as userid, u.username, u.name FROM #__jvarcade p ' .
				'	LEFT JOIN #__jvarcade_games g ON p.gameid = g.id ' .
				'	LEFT JOIN #__users u ON u.id = p.userid ' .
				' WHERE p.gameid = ' . $this->dbo->Quote($game_id) . 
				'	AND p.published = ' . $this->dbo->Quote(1) . 
				$orderby;
		$this->dbo->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		return  $this->dbo->loadAssocList();
	}
	
	public function getLatestScores() {
		if ($this->orderby) {
			$orderby = ' ORDER BY ' . $this->orderby . ' ' . ($this->orderdir ? $this->orderdir : '');
		} else {
			$orderby = ' ORDER BY p.date DESC ';
		}
		
		$query = 'SELECT SQL_CALC_FOUND_ROWS p.*, g.id as gameid, g.gamename, g.title, g.imagename, g.scoring, g.reverse_score, u.id as userid, u.username, u.name 
				FROM #__jvarcade p 
					LEFT JOIN #__jvarcade_games g ON p.gameid = g.id 
					LEFT JOIN #__users u ON u.id = p.userid 
				WHERE p.published = ' . $this->dbo->Quote(1) . 
				$orderby;
		$this->dbo->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		return  $this->dbo->loadAssocList();
	}
	
	public function deleteScore($id) {
		$this->dbo->setQuery('DELETE FROM #__jvarcade WHERE ' . $this->dbo->quoteName('id') . ' = ' . $this->dbo->Quote($id));
		return $this->dbo->execute();
	}
	
	public function saveScore($game_id, $game_title, $userid, $username, $score, $highestscore, $trigger) {
		$app = JFactory::getApplication();
		$player_ip = $app->input->server->get('REMOTE_ADDR', '0.0.0.0', 'raw');
		$res = 0;
		
		if ($highestscore != 0) {
			$this->dbo->setQuery('SELECT id FROM #__jvarcade ' . 
								' WHERE ' . $this->dbo->quoteName('gameid') . ' = ' . $this->dbo->Quote($game_id) . 
								'	AND ' . $this->dbo->quoteName('userid') . ' = ' . $this->dbo->Quote($userid));
			$scoreid = (int)$this->dbo->loadResult();
			$this->dbo->setQuery('UPDATE #__jvarcade SET ' . 
									$this->dbo->quoteName('score') . ' = ' . $this->dbo->Quote($score) . ', ' . 
									$this->dbo->quoteName('ip') . ' = ' . $this->dbo->Quote($player_ip) . ', ' . 
									$this->dbo->quoteName('date') . ' = ' . $this->dbo->Quote(date('Y-m-d H:i:s')) . 
								' WHERE ' . $this->dbo->quoteName('id') . ' = ' . $this->dbo->Quote($scoreid));
			if (!$scoreid || !$this->dbo->execute()) {
				$res = 0;
			} else {
				$res = 1;
			}
		} elseif ($highestscore == 0 || $highestscore == false) {
			$this->dbo->setQuery('INSERT INTO #__jvarcade (' . $this->dbo->quoteName('userid') . ', ' . $this->dbo->quoteName('score') . ', ' .
									$this->dbo->quoteName('ip') . ', ' . $this->dbo->quoteName('gameid') . ', ' . $this->dbo->quoteName('date') . ') ' . 
								' VALUES (' . $this->dbo->Quote($userid) . ',' . $this->dbo->Quote($score) . ',' . $this->dbo->Quote($player_ip) . ',' . 
											$this->dbo->Quote($game_id) . ',' . $this->dbo->Quote(date('Y-m-d H:i:s')) . ')');
			if (!$this->dbo->execute()) {
				$res = 0;
			} else {
				$scoreid = $this->dbo->insertid();
				$res = 1;
			}
		} else {
			$res = 1;
		}
		
		if ($res == 1) {
			$this->setUpdateLeaderBoard();
			if ($trigger) {
				$dispatcher = JEventDispatcher::getInstance();
				// trigger the contest score event
				$dispatcher->trigger('onPUAScoreSaved', array($game_id, $game_title, $userid, $username, $score));
			}
		}
		
		return $res;
	}
	
	public function savePNGame($game_id, $userid, $gameData) {

		$this->dbo->setQuery('SELECT count(*) as count FROM #__jvarcade_gamedata WHERE ' . 
							$this->dbo->quoteName('userid') . ' =' . $this->dbo->Quote($userid) . ' AND ' . 
							$this->dbo->quoteName('gameid') . ' =' . $this->dbo->Quote($game_id));
		$count = $this->dbo->loadResult();
		
		if (!(int)$count) {
			//No rows found, this user has not stored a high score for this game yet
			$sql = 'INSERT INTO #__jvarcade_gamedata (' . $this->dbo->quoteName('gameid') . ', ' . $this->dbo->quoteName('userid') . ', ' . $this->dbo->quoteName('gamedata') . ') VALUES (' . 
					$this->dbo->Quote($game_id) . ',' . $this->dbo->Quote($userid) . ',' . $this->dbo->Quote($gameData) . ')';
		} else {
			//old gameData found so replace it with the new one.
			$sql = 'UPDATE #__jvarcade_gamedata SET ' . 
						$this->dbo->quoteName('gamedata') . ' =' . $this->dbo->Quote($gameData) . 
					' WHERE  ' . 
						$this->dbo->quoteName('userid') . ' = ' . $this->dbo->Quote($userid) . ' AND ' . 
						$this->dbo->quoteName('gameid') . ' = ' . $this->dbo->Quote($game_id);
		}
		$this->dbo->setquery($sql);

		if (!$this->dbo->execute()) {
			return false;
		}
		return true;
	}

	public function loadPNGame($game_id, $userid) {
		$this->dbo->setquery('SELECT gamedata FROM #__jvarcade_gamedata WHERE ' . 
							$this->dbo->quoteName('gameid') . ' = ' . $this->dbo->Quote($game_id) . ' AND ' . 
							$this->dbo->quoteName('userid') . ' = ' . $this->dbo->Quote($userid));
		$gameData = $this->dbo->loadResult();
		if ($gameData == null) {
			return '';
		}
		//Flash will unencode the data automatically
		return urlencode($gameData);
	}
	
	public function GetScoreXML($game_id, $order = 'DESC', $limit = 10) {

		$query = 'SELECT j.score, j.date, u.username ' . 
				' FROM #__jvarcade j ' . 
				'	LEFT JOIN #__jvarcade_games g ON j.gameid = g.id ' . 
				'	LEFT JOIN #__users u ON u.id = j.userid ' . 
				' WHERE ' . $this->dbo->quoteName('j.gameid') . ' = ' . $this->dbo->Quote($game_id) . 
				'	AND ' . $this->dbo->quoteName('j.published') . ' = ' . $this->dbo->Quote(1) . ' ' . 
				' ORDER BY score ' . $order . ' ' . 
				' LIMIT ' . $limit;
		$this->dbo->setQuery($query);
		$scores = $this->dbo->loadObjectList();
		
		$i = 1;
		$scorexml = '<scorelist>';
		if (count($scores) > 0) {
			foreach ($scores as $score) {
				$scorexml .= "<score rank='" . $i . "' score='" . round($score->score, 2) . "' player='" . $score->username . "' date='" . $score->date . "' />";
				$i = $i + 1;
			}
		}
		$scorexml .= '</scorelist>';
		$scorexml = urlencode($scorexml);
		
		return $scorexml;
	}

	// CONTESTS
	
	public function getContestsByGame($game_id) {
		
		$level = (JVA_COMPATIBLE_MODE == '15') ? $this->user->gid  : implode(',', $this->user->groups);
		
		$sql = 'SELECT c.*, COALESCE(cs.attemptnum,0) as attemptnum, COALESCE(cs.score,0) as score, COALESCE(cs.id,0) as has_score, COALESCE(cm.userid,0) AS userid ' .
				' FROM #__jvarcade_contest as c' . 
				' JOIN #__jvarcade_contestgame as cg ON c.id = cg.contestid' . 
				' LEFT JOIN #__jvarcade_contestmember as cm ON c.id = cm.contestid AND cm.' . $this->dbo->quoteName('userid') . ' = ' . $this->dbo->Quote($this->user->id) . 
				' LEFT JOIN #__jvarcade_contestscore as cs ON c.id = cs.contestid ' . 
				'	AND cs.' . $this->dbo->quoteName('userid') . ' = ' . $this->dbo->Quote($this->user->id) . 
				'	AND cs.' . $this->dbo->quoteName('gameid') . ' = ' . $this->dbo->Quote($game_id) .
				' WHERE  cg.' . $this->dbo->quoteName('gameid') . ' = ' . $this->dbo->Quote($game_id) .
				//~ '	AND c.' . $this->dbo->quoteName('minaccesslevelrequired') . ' IN (' . $this->dbo->Quote($level) . ')' . 
				'	AND c.' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) . 
				'	AND current_timestamp between date_format(startdatetime, \'%Y-%m-%d %H:%i:%s\') and date_format(enddatetime, \'%Y-%m-%d %H:%i:%s\') ' ;
		$this->dbo->setQuery($sql);
		$result = $this->dbo->loadObjectList();
		return $result;

	}
	
	public function getAllContests() {
		$this->dbo->setQuery('SELECT c.id, c.name, c.description, c.imagename, c.minaccesslevelrequired,' .
								' c.startdatetime as startdatetime , c.enddatetime as enddatetime' .
							' FROM #__jvarcade_contest c' . 
								' LEFT JOIN  #__jvarcade_contestmember m ON m.contestid = c.id' . 
								' LEFT JOIN #__users u ON u.id = m.userid' . 
							' WHERE c.published = ' . $this->dbo->Quote(1) . 
								' AND ((c.startdatetime <= now() AND c.enddatetime >= now()) OR (c.enddatetime <= now()))' . 
								' AND u.id = ' . $this->dbo->Quote($this->user->get('id'))
								//~ . ' AND u.gid >= c.minaccesslevelrequired'
								);
		return $this->dbo->loadAssocList('id');
	}

	//~ public function getAllContestsCount() {
		//~ $this->dbo->setQuery('SELECT COUNT(*) as count ' .
							//~ ' FROM #__jvarcade_contest c' . 
								//~ ' LEFT JOIN  #__jvarcade_contestmember m ON m.contestid = c.id' . 
								//~ ' LEFT JOIN #__users u ON u.id = m.userid' . 
							//~ ' WHERE c.published = ' . $this->dbo->Quote(1) . 
								//~ ' AND ((c.startdatetime <= now() AND c.enddatetime >= now()) OR (c.enddatetime <= now()))' . 
								//~ ' AND u.id = ' . $this->dbo->Quote($this->user->get('id'))
								//~ . ' AND u.gid >= c.minaccesslevelrequired'
								//~ );
		//~ return $this->dbo->loadResult();
	//~ }

	public function getAllContestsCount() {
		$this->dbo->setQuery('SELECT COUNT(*) as count ' .
							' FROM #__jvarcade_contest ' . 
							' WHERE published = ' . $this->dbo->Quote(1) . 
							' ORDER BY startdatetime DESC ');
		return $this->dbo->loadResult();
	}

	public function getContests() {
		if (!$this->contests) {
			$this->_loadContests();
		}
		return $this->contests;
	}	

	private function _loadContests() {	
		if (!$this->contests) {

			if ($this->orderby) {
				$orderby = ' ORDER BY ' .  $this->orderby  . ' ' .  ($this->orderdir ? $this->orderdir : '');
			} else {
				$orderby = ' ORDER BY id DESC';
			}
			
			$sql = 'SELECT SQL_CALC_FOUND_ROWS * , ' . 
					'		(CASE islimitedtoslots WHEN 0 THEN 0 ELSE 1 END) as registration, ' .
					'		(CASE ' .
					'			WHEN date_format(startdatetime, \'%Y-%m-%d %H:%i:%s\') < current_timestamp AND date_format(enddatetime, \'%Y-%m-%d %H:%i:%s\') < current_timestamp THEN 1 ' .
					'			WHEN current_timestamp between date_format(startdatetime, \'%Y-%m-%d %H:%i:%s\') and date_format(enddatetime, \'%Y-%m-%d %H:%i:%s\') THEN 2 ' .
					'			WHEN (date_format(startdatetime, \'%Y-%m-%d %H:%i:%s\') > current_timestamp AND date_format(enddatetime, \'%Y-%m-%d %H:%i:%s\') > current_timestamp) THEN 3 ' .
					'			ELSE 0 ' .
					'		END) as status ' .
					' FROM #__jvarcade_contest ' .
					' WHERE ' . $this->dbo->quoteName('published') . ' = ' . $this->dbo->Quote(1) .
					$orderby;
			$this->dbo->setQuery($sql, $this->getState('limitstart'), $this->getState('limit'));
			$this->contests = $this->dbo->loadAssocList();
			return (boolean)$this->contests;
		}
		return true;
	}
	
	
	public function getContest($contest_id) {
		$sql = 'SELECT * FROM #__jvarcade_contest WHERE id = ' . (int)$contest_id;
		$this->dbo->setQuery($sql);
		return $this->dbo->loadObject();
	}

	public function getContestGames($contest_id) {
		$sql = 'SELECT g.* FROM #__jvarcade_games g JOIN #__jvarcade_contestgame cg ON g.id = cg.gameid WHERE cg.contestid = ' . (int)$contest_id . ' ORDER BY g.id DESC';
		$this->dbo->setQuery($sql);
		return $this->dbo->loadObjectList();
	}

	public function getContestMembers($contest_id) {
		$sql = 'SELECT cm.userid, cm.dateregistered, u.name, u.username FROM #__jvarcade_contestmember cm JOIN #__users u ON cm.userid = u.id WHERE cm.contestid = ' . (int)$contest_id;
		$this->dbo->setQuery($sql);
		return $this->dbo->loadObjectList('userid');
	}
	
	public function ContestMembership($contest_id, $user_id, $type) {
		if ($type) {
			// register
			$sql = 'SELECT userid FROM #__jvarcade_contestmember WHERE contestid = ' . (int)$contest_id . ' AND userid = ' . (int) $user_id;
			$this->dbo->setQuery($sql);
			if (!(int)$this->dbo->loadResult()) {
				$sql = 'INSERT INTO #__jvarcade_contestmember (contestid, userid) VALUES (' . (int)$contest_id . ', ' . (int) $user_id . ')';
				$this->dbo->setQuery($sql);
				$this->dbo->execute();
			}
		} else {
			// unregister
			$sql = 'DELETE FROM #__jvarcade_contestmember WHERE contestid = ' . (int)$contest_id . ' AND userid = ' . (int) $user_id;
			$this->dbo->setQuery($sql);
			$this->dbo->execute();
		}
	}
	
	public function registerScore($game_id, $game_title, $userid, $username, $score, $reverse) {
		$app = JFactory::getApplication();
		$player_ip = $app->input->server->get('REMOTE_ADDR', '0.0.0.0', 'raw');
		$dispatcher = JDispatcher::getInstance();
		$contests = $this->getContestsByGame($game_id);
		$current_score = $score;
		
		if (is_array($contests) && count($contests)) {
			foreach($contests as $contest) {
				
				// FIRST LET'S CHECK IF THE RESULT CAN BE STORED AT ALL FOR THIS CONTEST:
				// We are good to go if there is no registration or there is and we are registered 
				// AND there is no max play count or there is but it's still not reached
				if ((!$contest->islimitedtoslots || $contest->userid) && (!$contest->maxplaycount || ($contest->maxplaycount > $contest->attemptnum))) {
				
					$contest_id = (int)$contest->id;
					$contest_name = $contest->name;
					$attemptnum = (int)$contest->attemptnum;
					
					// check if this score is better than users's previous... or it's first score for the game
					$oldscore = (int)$contest->score;
					$has_score = (int)$contest->has_score;
					$goodscore = (!$has_score ||!$oldscore || ($reverse ? $score < $oldscore : $score > $oldscore));
					// if the result is not better we will still have to insert or update in order to track the attemptnum
					$score = ($goodscore ? $score : 0);
					
					if (!$has_score) {
						// there's no previous score by this user for the game and the contest (it's a first) so we insert
						$sql = 'INSERT INTO #__jvarcade_contestscore (' . $this->dbo->quoteName('userid') . ', ' . $this->dbo->quoteName('score') . ', ' .
										$this->dbo->quoteName('ip') . ', ' . $this->dbo->quoteName('gameid') . ', ' . $this->dbo->quoteName('date') . ', ' . 
										$this->dbo->quoteName('contestid') . ') ' . 
								' VALUES (' . $this->dbo->Quote($userid) . ',' . $this->dbo->Quote($score) . ',' . $this->dbo->Quote($player_ip) . ',' . 
										$this->dbo->Quote($game_id) . ',' . $this->dbo->Quote(date('Y-m-d H:i:s')) . ',' . $this->dbo->Quote($contest_id) . ')';
						$this->dbo->setQuery($sql);
					} else {
						// there's a previous score, we just update
						$sql = 'UPDATE #__jvarcade_contestscore SET ' . 
									($goodscore ? $this->dbo->quoteName('score') . ' = ' . $this->dbo->Quote($score) . ', ' : '') . 
									$this->dbo->quoteName('ip') . ' = ' . $this->dbo->Quote($player_ip) . ', ' . 
									$this->dbo->quoteName('attemptnum') . ' = ' . $this->dbo->Quote(($attemptnum + 1)) . ', ' . 
									$this->dbo->quoteName('date') . ' = ' . $this->dbo->Quote(date('Y-m-d H:i:s')) . 
								' WHERE ' . $this->dbo->quoteName('id') . ' = ' . $this->dbo->Quote($has_score);
						$this->dbo->setQuery($sql);
					}
					
					if ($this->dbo->execute() && $goodscore) {
						$dispatcher->trigger('onPUAScoreSaved', array($game_id, $game_title, $userid, $username, $score, $contest_id, $contest_name));
						$this->setUpdateLeaderBoard($contest_id);
						// ADD TO MSG QUEUE INFO THAT SCORE WAS SAVED FOR THIS CONTEST
						$app->enqueueMessage(JText::sprintf('COM_JVARCADE_SCORE_SAVED_FOR_CONTEST', $contest_name, $current_score));
					} elseif (!$goodscore) {
						$app->enqueueMessage(JText::sprintf('COM_JVARCADE_SCORE_NOTGOOD_FOR_CONTEST', $contest_name, $oldscore, $current_score));
					} else {
						$app->enqueueMessage(JText::sprintf('COM_JVARCADE_SCORE_TECHPROBLEM_CONTEST', $contest_name, $current_score));
					}
				}
			}
		}

	}
	
	// LEADERBOARD

	public function getLeaderBoard($contest_id = 0) {
		$sql = 'SELECT l.*, u.name, u.username FROM #__jvarcade_leaderboard l LEFT JOIN #__users u ON l.userid = u.id 
				WHERE l.contestid = ' . (int)$contest_id . ' 
					AND ((l.userid IS NOT NULL AND u.id IS NOT NULL)
						OR (l.userid = 0 AND u.id IS NULL))
				ORDER BY l.points DESC';
		$this->dbo->setQuery($sql);
		return $this->dbo->loadObjectList();
	}
	
	public function setUpdateLeaderBoard($contest_id = 0) {
		$path = $this->global_conf->get('tmp_path') . '/' . 'lb_' . $contest_id . '.txt';
		if (!is_file($path)) {
			touch($path);
			file_put_contents($path, mktime()); 
		}
	}
	
	public function checkUpdateLeaderBoard($contest_id = 0) {
		$path = $this->global_conf->get('tmp_path') . '/' . 'lb_' . $contest_id . '.txt';
		if (is_file($path) && (((int)file_get_contents($path) + ((int)$this->config->updatelb*60)) < mktime())) {
			return true;
		}
		return false;
		
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
			$path = $this->global_conf->get('tmp_path') . '/' . 'lb_' . $contest_id . '.txt';
			unlink($path);
			return true;
		} else {
			return false;
		}
	}

}

?>

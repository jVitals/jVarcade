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

class jvarcadeModelProfile extends jvarcadeModelCommon {
	
	
	public function saveAchievement($user_id, $gid, $gtitle, $achtitle, $achdesc, $achicon, $pts) {
		$query = 'INSERT IGNORE INTO #__jvarcade_achievements (userid, gameid, gametitle, title, description, icon_url, points) VALUES ('. $this->dbo->Quote((int)$user_id) .','. $this->dbo->Quote((int)$gid) .',
				'. $this->dbo->Quote($gtitle) .','. $this->dbo->Quote($achtitle) .','. $this->dbo->Quote($achdesc) .','. $this->dbo->Quote($achicon) .','. $this->dbo->Quote((int)$pts) .')';
		$this->dbo->setQuery($query);
		$this->dbo->execute();
		$dispatcher = JEventDispatcher::getInstance();
		// trigger the achievement saved event
		$dispatcher->trigger('onJVAAchievementSaved', array($user_id, $gid, $gtitle, $achtitle, $achdesc, $achicon, $pts));
	}
	
	public function getUserAchievements($user_id) {
		$query = 'SELECT * FROM #__jvarcade_achievements WHERE userid =' . $user_id . ' ORDER BY id DESC LIMIT 5';
		$this->dbo->setQuery($query);
		$user_achieve = $this->dbo->loadAssocList();
		return $user_achieve;
	}
	
	public function getUserScores($user_id, $limit='') {
		$query = 'SELECT b.score, b.date, c.title, c.imagename, c.description, c.id FROM #__jvarcade AS b, #__jvarcade_games AS c WHERE b.userid ='
				 . $user_id .' AND b.gameid = c.id ORDER BY b.date DESC ' . $limit;
		$this->dbo->setQuery($query);
		$user_scores = $this->dbo->loadAssocList();
		return $user_scores;
	}
	
	public function getLatestScores() {
		$query = 'SELECT SQL_CALC_FOUND_ROWS p.*, g.id as gameid, g.gamename, g.title, g.imagename, g.scoring, g.reverse_score, u.id as userid, u.username, u.name
				FROM #__jvarcade p
					LEFT JOIN #__jvarcade_games g ON p.gameid = g.id
					LEFT JOIN #__users u ON u.id = p.userid
				WHERE p.published = ' . $this->dbo->Quote(1);
		$this->dbo->setQuery($query);
		return  $this->dbo->loadAssocList();
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
	
	public function checkOnline($user_id) {
		$query = 'SELECT userid FROM #__session WHERE client_id = 0 AND userid = ' . $user_id;
		$this->dbo->setQuery($query);
		return $this->dbo->loadRow();
	}
	

}

?>
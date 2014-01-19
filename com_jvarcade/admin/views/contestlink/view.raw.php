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

//jimport('joomla.application.component.view');

class jvarcadeViewContestlink extends JViewLegacy {

	function display($tpl = null) {

		$model = $this->getModel();
		
		$task = JRequest::getVar('task', 'showcontestgames');
		$this->assignRef('task', $task);
		
		if ($task == 'showcontestgames') {
		
			$contest_id = JRequest::getVar('contest_id', 0);
			$games = $model->getContestGames($contest_id);
			$this->assignRef('contest_id', $contest_id);
			$this->assignRef('games', $games);

		} else if ($task == 'showgamecontests') {
		
			$game_id = JRequest::getVar('game_id', 0);
			$contests = $model->getGameContests($game_id);
			$this->assignRef('game_id', $game_id);
			$this->assignRef('contests', $contests);

		}
		
		parent::display($tpl);
	}
}
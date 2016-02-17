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

//jimport('joomla.application.component.view');

class jvarcadeViewContestlink extends JViewLegacy {

	function display($tpl = null) {

		$model = $this->getModel();
		$app = JFactory::getApplication();
		$task = $app->input->getWord('task', 'showcontestgames');
		$this->task = $task;
		
		if ($task == 'showcontestgames') {
		
			$contest_id = $app->input->getInt('contest_id', 0);
			$games = $model->getContestGames($contest_id);
			$this->contest_id = $contest_id;
			$this->games = $games;

		} else if ($task == 'showgamecontests') {
		
			$game_id = $app->input->getInt('game_id', 0);
			$contests = $model->getGameContests($game_id);
			$this->game_id = $game_id;
			$this->contests = $contests;

		}
		
		parent::display($tpl);
	}
}

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
		$task = $app->input->get('task', 'addgametocontest');
		$this->task = $task;
		
		if ($task == 'addgametocontest') {
			
			$game_id = array_unique($app->input->get('cid', array(), 'array'));
			JArrayHelper::toInteger($game_id);
			$game_titles = $model->getGameTitles($game_id);
			$game_titles = implode(',', $game_titles);
			$this->game_titles = $game_titles;
			$this->game_ids = implode(',', $game_id);
			
			
			$contestobj = $model->getContests();
			foreach($contestobj as $obj ) {
				$contests[] = JHTML::_('select.option', $obj->id, $obj->name);
			}
			$contestlist = JHTML::_('select.genericlist', $contests, 'contestlist', 'size="9" multiple', 'value', 'text');
			$this->contestlist = $contestlist;

		} else if ($task == 'addcontestgames') {
			
			$contest_id = array_unique($app->input->get('cid', array(), 'array'));
			JArrayHelper::toInteger($contest_id);
			$this->contest_id = implode(',', $contest_id);
			
			$gamesobj = $model->getGameIdTitles();
			foreach($gamesobj as $obj ) {
				$games[] = JHTML::_('select.option', $obj->id, $obj->title);
			}
			$gameslist = JHTML::_('select.genericlist', $games, 'gameslist', 'size="9" multiple', 'value', 'text');
			$this->gameslist = $gameslist;
		}
		
		parent::display($tpl);
	}
}

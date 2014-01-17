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
		$mainframe = JFactory::getApplication();
		$task = $mainframe->input->get('task', 'addgametocontest');
		$this->assignRef('task', $task);
		
		if ($task == 'addgametocontest') {
		
			$game_id = JRequest::getVar('cid', 'contests');
			if (!is_array($game_id)) $game_id = array($game_id);
			JArrayHelper::toInteger($game_id, array(0));
			$game_titles = $model->getGameTitles($game_id);
			$game_titles = implode(',', $game_titles);
			$this->assignRef('game_titles', $game_titles);
			$this->assignRef('game_ids', implode(',', $game_id));
			
			
			$contestobj = $model->getContests();
			foreach($contestobj as $obj ) {
				$contests[] = JHTML::_('select.option', $obj->id, $obj->name);
			}
			$contestlist = JHTML::_('select.genericlist', $contests, 'contestlist', 'size="9" multiple', 'value', 'text');
			$this->assignRef('contestlist', $contestlist);

		} else if ($task == 'addcontestgames') {
		
			$contest_id = JRequest::getVar('cid', 'contests');
			if (!is_array($contest_id)) $contest_id = array($contest_id);
			JArrayHelper::toInteger($contest_id, array(0));
			$this->assignRef('contest_id', implode(',', $contest_id));
			
			$gamesobj = $model->getGameIdTitles();
			foreach($gamesobj as $obj ) {
				$games[] = JHTML::_('select.option', $obj->id, $obj->title);
			}
			$gameslist = JHTML::_('select.genericlist', $games, 'gameslist', 'size="9" multiple', 'value', 'text');
			$this->assignRef('gameslist', $gameslist);
		}
		
		parent::display($tpl);
	}
}

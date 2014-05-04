<?php
/**
 * @package		jVArcade
 * @version		2.10
 * @date		2014-05-04
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class jvarcadeViewContestdetail extends JViewLegacy {

	function display($tpl = null) {
		
		$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();
		$doc = JFactory::getDocument();
		$task = $mainframe->input->get('task');
		$this->assignRef('task', $task);
		$Itemid = $mainframe->input->get('Itemid');
		$this->assignRef('Itemid', $Itemid);
		$model = $this->getModel();
		$contest_id = $mainframe->input->get('id', 0);
		$slotsleft = 0;	
		
		// Get actual data
		
		$contest = $model->getContest($contest_id);
		$this->assignRef('contest', $contest);
		
		if ($contest_id && $contest) {
			
			$slotsleft = $this->contest->islimitedtoslots;
			
			// Get contest games and members (if registration)
			$games = $model->getContestGames($contest_id);
			$this->assignRef('games', $games);
			if ($contest->islimitedtoslots) {
				$members = $model->getContestMembers($contest_id);
				$this->assignRef('members', $members);
				if (is_array($members) && count($members)) {
					$slotsleft = ($this->contest->islimitedtoslots - count($members));
				}
			}
			
			// Get Leaderboard
			if ($model->checkUpdateLeaderBoard($contest_id)) {
				$model->regenerateLeaderBoard($contest_id);
			}
			$leaderboard = $model->getleaderBoard($contest_id);
			$this->assignRef('leaderboard', $leaderboard);
			
			$contests_title = JText::_('COM_JVARCADE_CONTESTS');
			$title = $contest->name;
			
			$pathway->addItem($contests_title, JRoute::_('index.php?option=com_jvarcade&task=contests', false));
			$pathway->addItem($title);
			$doc->setTitle(($this->config->title ? $this->config->title . ' - ' : '') . $contests_title . ' - ' . $title);
			$doc->setDescription(strip_tags($contest->description));
			$this->assignRef('tabletitle', $title);
		
		}
		
		$this->assignRef('slotsleft', $slotsleft);

		$user = JFactory::getUser();
		$this->assignRef('user', $user);
		
		parent::display($tpl);
	}
}

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
defined('_JEXEC') or die;

class jvarcadeViewLeaderboard extends JViewLegacy {

	function display($tpl = null) {
		$mainframe = JFactory::getApplication();
		
		$pathway = $mainframe->getPathway();
		$doc = JFactory::getDocument();
		$task = $mainframe->input->get('task');
		$this->task = $task;
		$Itemid = $mainframe->input->get('Itemid');
		$this->Itemid = $Itemid;
		$model = $this->getModel();
		
		// Get Leaderboard
		if ($model->checkUpdateLeaderBoard(0)) {
			$model->regenerateLeaderBoard(0);
		}
		$leaderboard = $model->getleaderBoard(0);
		$this->leaderboard = $leaderboard;
		
		$title = JText::_('COM_JVARCADE_LEADERBOARD');
		$pathway->addItem($title);
		$doc->setTitle(($this->config->title ? $this->config->title . ' - ' : '') . $title);
		$this->tabletitle = $title;

		$user = JFactory::getUser();
		$this->user = $user;
		
		parent::display($tpl);
	}
}

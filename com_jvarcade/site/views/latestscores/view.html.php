<?php
/**
 * @package		jVArcade
 * @version		2.11
 * @date		2014-05-04
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class jvarcadeViewLatestscores extends JViewLegacy {
	
	function display($tpl = null) {
		
		$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();
		$doc = JFactory::getDocument();
		$user = JFactory::getUser();
		$task = $mainframe->input->get('task');
		$this->assignRef('task', $task);
		$Itemid = $mainframe->input->get('Itemid');
		$this->assignRef('Itemid', $Itemid);
		$model = $this->getModel();
		$sort_url = 'index.php?option=com_jvarcade&task=' . $task;
		$subfolders = 1;
		
		// Table ordering
		$filter_order = $mainframe->input->get('filter_order', 'p.date');
		$filter_order_Dir = $mainframe->input->get('filter_order_Dir', 'DESC');
		$filter_order_Dir = $filter_order_Dir ? $filter_order_Dir : 'DESC';
		
		// ensure filter_order has a valid value.
		if (!in_array($filter_order, array('p.score', 'p.date', 'g.title', 'u.username', 'u.name'))) {
			$filter_order = 'p.date';
		}
		$model->setOrderBy($filter_order);
		$model->setOrderDir($filter_order_Dir);
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order'] = $filter_order;
		$this->assignRef('lists', $lists);		
		
		// Get actual data
		$scores = $model->getLatestScores();
		$title = JText::_('COM_JVARCADE_LATEST_SCORES');
		
		// Pagination
		$pageNav = $model->getPagination();
		$this->assignRef('pageNav', $pageNav);
		
		// Highest Scores
		$highscores = array();
		foreach ($scores as $score) {
			if (!isset($highscores[$score['gameid']])) $highscores[$score['gameid']] = array();
			// get high scores
			if (!count($highscores[$score['gameid']])) {
				if ($score['scoring']) {
					$highscores[$score['gameid']] = $this->games_model->getHighestScore($score['gameid'], $score['reverse_score']);
					$highscores[$score['gameid']]['score'] =  round($highscores[$score['gameid']]['score'], 2);
					if (!isset($highscores[$score['gameid']]['userid']) || !(int)$highscores[$score['gameid']]['userid']) {
						$highscores[$score['gameid']]['username'] = $this->config->guest_name;
					} elseif(!(int)$this->config->show_usernames) {
						$highscores[$score['gameid']]['username'] = $highscores[$score['gameid']]['name'];
					}
				}
			}
		}
		$this->assignRef('highscores', $highscores);
		
		$pathway->addItem($title);
		$doc->setTitle(($this->config->title ? $this->config->title . ' - ' : '') . $title);
		
		$this->assignRef('scores', $scores);
		$this->assignRef('tabletitle', $title);
		$this->assignRef('sort_url', $sort_url);
		$this->assignRef('user', $user);
		$this->assignRef('subfolders', $subfolders);
		$this->assignRef('config', $this->config);
		
		parent::display($tpl);
	}
}

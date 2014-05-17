<?php
/**
 * @package		jVArcade
 * @version		2.12
 * @date		2014-05-17
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.view');
//jimport('joomla.html.pagination');

class jvarcadeViewScores extends JViewLegacy {
	
	function display($tpl = null) {
		
		$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();
		$doc = JFactory::getDocument();
		$user = JFactory::getUser();
		$this->assignRef('user', $user);
		$Itemid = $mainframe->input->get('Itemid');
		$this->assignRef('Itemid', $Itemid);
		
		$game_id = (int)$mainframe->input->get('id');
		$this->assignRef('game_id', $game_id);
		$sort_url = !$this->table_only ? 'index.php?option=com_jvarcade&task=scores&id=' . $game_id : 'index.php?option=com_jvarcade&task=game&id=' . $game_id;
		
		$model = $this->getModel();
		
		// Get game
		$game = $this->games_model->getGame($game_id);
		$reverse = $game['reverse_score'];
		
		// Table ordering
		
		$filter_order = $mainframe->input->get('filter_order', 'p.score');
		$ord = (($filter_order == 'p.score') ? ($reverse ? 'ASC' : 'DESC') : 'DESC');
		$filter_order_Dir = $mainframe->input->get('filter_order_Dir', $ord);
		$filter_order_Dir = $filter_order_Dir ? $filter_order_Dir : 'DESC';
		
		// ensure filter_order has a valid value
		if (!in_array($filter_order, array('p.date', 'p.score', 'u.username', 'u.name'))) {
			$filter_order = 'p.date';
		}
		$model->setOrderBy($filter_order);
		$model->setOrderDir($filter_order_Dir);
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order'] = $filter_order;
		$this->assignRef('lists', $lists);
		
		// Get Scores and inititiate Pagination
		
		$scores = $model->getScores($game_id, $reverse);
		$pageNav = $model->getPagination();
		$this->assignRef('pageNav', $pageNav);
		$this->assignRef('scores', $scores);
		$this->assignRef('sort_url', $sort_url);
		
		// Game details
		$game['current_vote'] = ($game['total_value'] > 0 && $game['total_votes'] > 0) ? round($game['total_value']/$game['total_votes'], 1) : 0;
		$this->assignRef('game', $game);
		if(!(int)$this->table_only) {
			$pathway->addItem($game['title'], JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $game['id']));
			$pathway->addItem(JText::_('COM_JVARCADE_SCORES'));
			$doc->setTitle(($this->config->title ? $this->config->title . ' - ' : '') . $game['title'] . ' ' . JText::_('COM_JVARCADE_SCORES'));
			$doc->setDescription(strip_tags($game['description']));
		}
		
		parent::display($tpl);
	}
}

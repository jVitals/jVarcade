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

class jvarcadeViewManage_scores extends JViewLegacy {

	function display($tpl = null) {
	
		$mainframe = JFactory::getApplication('site');
		
		$task = $mainframe->input->get('task', 'manage_scores');
		$this->assignRef('task', $task);
		
		$filter_order = $mainframe->getUserStateFromRequest('com_jvarcade.manage_scores.filter_order', 'filter_order', 'p.date', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest('com_jvarcade.manage_scores.filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$filter_order_Dir = $filter_order_Dir ? $filter_order_Dir : 'DESC';
		// ensure filter_order has a valid value.
		if (!in_array($filter_order, array('g.title', 'u.username', 'p.ip', 'p.score', 'p.date', 'p.published'))) {
			$filter_order = 'p.date';
		}

		$model = $this->getModel();
		$model->setOrderBy($filter_order);
		$model->setOrderDir($filter_order_Dir);
		
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order'] = $filter_order;

		$this->assignRef('lists', $lists);
		
		$scores = $model->getScores();
		$pagination = $model->getPagination();
		$this->assignRef('pagination', $pagination);
		$this->assignRef('scores', $scores);
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_MANAGE_SCORES'), 'jvascores');
		JToolBarHelper::deleteList(JText::_('COM_JVARCADE_SCORES_ASK_DELETE'), 'deletescore', JText::_('COM_JVARCADE_SCORES_DELETE'));
		JToolBarHelper::publishList('scorePublish', JText::_('COM_JVARCADE_SCORES_PUBLISH'));
		JToolBarHelper::unpublishList('scoreUnPublish', JText::_('COM_JVARCADE_SCORES_UNPUBLISH'));
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('manage_scores');
		parent::display($tpl);
	}
	
	protected function addSidebar() {
		$this->sidebar = JHtmlSidebar::render();
	}
}

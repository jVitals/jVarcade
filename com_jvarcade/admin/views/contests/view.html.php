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

class jvarcadeViewContests extends JViewLegacy {
	

	function display($tpl = null) {
		$app = JFactory::getApplication('site');
		$task = $app->input->get('task', 'contests');
		$this->task = $task;
		
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->filter_order = $app->getUserStateFromRequest('jvarcade.contests.filter_order', 'filter_order', 'id', 'cmd' );
		$this->filter_order_Dir = $app->getUserStateFromRequest('jvarcade.contests.filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
		
			return false;
		}
		
		
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_CONTESTS'), 'jvacontests');
		JToolBarHelper::editList('contests.editcontest', JText::_('COM_JVARCADE_CONTESTS_EDIT'));
		JToolBarHelper::addNew('add_contest', JText::_('COM_JVARCADE_CONTESTS_ADD'));
		JToolBarHelper::deleteList(JText::_('COM_JVARCADE_CONTESTS_ASK_DELETE'), 'deleteContest', JText::_('COM_JVARCADE_CONTESTS_DELETE'));
		JToolBarHelper::publishList('contests.contestPublish', JText::_('COM_JVARCADE_CONTESTS_PUBLISH'));
		JToolBarHelper::unpublishList('contests.contestUnpublish', JText::_('COM_JVARCADE_CONTESTS_UNPUBLISH'));
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('contests');
		
		parent::display($tpl);
	}
	protected function addSidebar() {
		$this->sidebar = JHtmlSidebar::render();
	}
	
}

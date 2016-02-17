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

class jvarcadeViewManage_scores extends JViewLegacy {

	function display($tpl = null) {
	
		$app = JFactory::getApplication();
		
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->filter_order = $app->getUserStateFromRequest('jvarcade.manage_scores.filter_order', 'filter_order', 'p.date', 'cmd' );
		$this->filter_order_Dir = $app->getUserStateFromRequest('jvarcade.manage_scores.filter_order_Dir', 'filter_order_Dir', 'desc', 'word' );
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters 	= $this->get('ActiveFilters');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
		
			return false;
		}
		
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_MANAGE_SCORES'), 'jvascores');
		JToolBarHelper::deleteList(JText::_('COM_JVARCADE_SCORES_ASK_DELETE'), 'manage_scores.deletescore', JText::_('COM_JVARCADE_SCORES_DELETE'));
		JToolBarHelper::publishList('manage_scores.scorePublish', JText::_('COM_JVARCADE_SCORES_PUBLISH'));
		JToolBarHelper::unpublishList('manage_scores.scoreUnPublish', JText::_('COM_JVARCADE_SCORES_UNPUBLISH'));
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('manage_scores');
		parent::display($tpl);
	}
	
	protected function addSidebar() {
		$this->sidebar = JHtmlSidebar::render();
	}
}

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
defined('_JEXEC') or die();


class jvarcadeViewManage_games extends JViewLegacy {
	var $permnames = array();

	function display($tpl = null) {
	
		$app = JFactory::getApplication();
		
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->filter_order = $app->getUserStateFromRequest('jvarcade.manage_games.filter_order', 'filter_order', 'g.id', 'cmd' );
		$this->filter_order_Dir = $app->getUserStateFromRequest('jvarcade.manage_games.filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
		$this->filterForm = $this->get('FilterForm');
		$this->activeFilters 	= $this->get('ActiveFilters');
		
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
		
			return false;
		}
		
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_MANAGE_GAMES'), 'jvagames');
		JToolBarHelper::editList('manage_games.editGame', JText::_('COM_JVARCADE_GAMES_EDIT'));
		JToolBarHelper::addNew('add_game', JText::_('COM_JVARCADE_GAMES_ADD'));
		JToolBarHelper::deleteList(JText::_('COM_JVARCADE_GAMES_ASK_DELETE'), 'manage_games.deletegame', JText::_('COM_JVARCADE_GAMES_DELETE'));
		JToolBarHelper::publishList('manage_games.gamePublish', JText::_('COM_JVARCADE_GAMES_PUBLISH'));
		JToolBarHelper::unpublishList('manage_games.gameUnPublish', JText::_('COM_JVARCADE_GAMES_UNPUBLISH'));
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('manage_games');

		parent::display($tpl);
	}
	protected function addSidebar() {
		
		$this->sidebar = JHtmlSidebar::render();
	}
}

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

class jvarcadeViewContent_ratings extends JViewLegacy {

	function display($tpl = null) {

		$app = JFactory::getApplication();
		
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->filter_order = $app->getUserStateFromRequest('jvarcade.content_ratings.filter_order', 'filter_order', 'id', 'cmd' );
		$this->filter_order_Dir = $app->getUserStateFromRequest('jvarcade.content_ratings.filter_order_Dir', 'filter_order_Dir', 'asc', 'word' );
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
		
			return false;
		}
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_CONTENT_RATINGS'), 'jvacontent');
		JToolBarHelper::editList('content_ratings.editcontentrating', JText::_('COM_JVARCADE_CONTENT_RATINGS_EDIT'));
		JToolBarHelper::addNew('add_contentrating', JText::_('COM_JVARCADE_CONTENT_RATINGS_ADD'));
		JToolBarHelper::deleteList(JText::_('COM_JVARCADE_CONTENT_RATINGS_ASK_DELETE'), 'content_ratings.deletecontentrating', JText::_('COM_JVARCADE_CONTENT_RATINGS_DELETE'));
		JToolBarHelper::publishList('content_ratings.contentratingPublish', JText::_('COM_JVARCADE_CONTENT_RATINGS_PUBLISH'));
		JToolBarHelper::unpublishList('content_ratings.contentratingUnpublish', JText::_('COM_JVARCADE_CONTENT_RATINGS_UNPUBLISH'));
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('content_ratings');
		
		parent::display($tpl);
	}
	protected function addSidebar() {
		$this->sidebar = JHtmlSidebar::render();
	}
}

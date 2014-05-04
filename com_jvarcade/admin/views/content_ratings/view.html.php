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

class jvarcadeViewContent_ratings extends JViewLegacy {

	function display($tpl = null) {
	
		$mainframe = JFactory::getApplication('site');
		
		$task = $mainframe->input->get('task', 'contentratings');
		$this->assignRef('task', $task);
		$lists = array();
		
		$search = '';
		$searchfields = array('title', 'name');
		
		$filter_order = $mainframe->getUserStateFromRequest('com_jvarcade.content_ratings.filter_order', 'filter_order', 'id', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest('com_jvarcade.content_ratings.filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$filter_order_Dir = $filter_order_Dir ? $filter_order_Dir : 'DESC';
		// ensure filter_order has a valid value.
		if (!in_array($filter_order, array('id', 'name', 'warningrequired', 'published'))) {
			$filter_order = 'id';
		}

		$model = $this->getModel();
		$model->setOrderBy($filter_order);
		$model->setOrderDir($filter_order_Dir);
		
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order'] = $filter_order;

		$this->assignRef('lists', $lists);
		
		$ratings = $model->getContentRatings();
		$pagination = $model->getPagination();
		$this->assignRef('pagination', $pagination);
		$this->assignRef('ratings', $ratings);
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_CONTENT_RATINGS'), 'jvacontent');
		JToolBarHelper::editList('editcontentrating', JText::_('COM_JVARCADE_CONTENT_RATINGS_EDIT'));
		JToolBarHelper::addNew('addcontentrating', JText::_('COM_JVARCADE_CONTENT_RATINGS_ADD'));
		JToolBarHelper::deleteList(JText::_('COM_JVARCADE_CONTENT_RATINGS_ASK_DELETE'), 'deletecontentrating', JText::_('COM_JVARCADE_CONTENT_RATINGS_DELETE'));
		JToolBarHelper::publishList('contentratingPublishYes', JText::_('COM_JVARCADE_CONTENT_RATINGS_PUBLISH'));
		JToolBarHelper::unpublishList('contentratingPublishNo', JText::_('COM_JVARCADE_CONTENT_RATINGS_UNPUBLISH'));
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('content_ratings');
		
		parent::display($tpl);
	}
	protected function addSidebar() {
		$this->sidebar = JHtmlSidebar::render();
	}
}

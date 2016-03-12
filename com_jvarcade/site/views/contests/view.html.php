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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class jvarcadeViewContests extends JViewLegacy {
	
	function display($tpl = null) {
		
		$mainframe = JFactory::getApplication();
		$pathway = $mainframe->getPathway();
		$doc = JFactory::getDocument();
		$task = $mainframe->input->get('task');
		$this->task = $task;
		$Itemid = $mainframe->input->get('Itemid');
		$this->Itemid = $Itemid;
		$model = $this->getModel();
		$sort_url = 'index.php?option=com_jvarcade&task=' . $task;
		
		// Table ordering
		
		$filter_order = $mainframe->getUserStateFromRequest('com_jvarcade.contests.filter_order', 'filter_order', 'startdatetime', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest('com_jvarcade.contests.filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$filter_order_Dir = $filter_order_Dir ? $filter_order_Dir : 'DESC';
		// ensure filter_order has a valid value.
		if (!in_array($filter_order, array('name', 'startdatetime', 'enddatetime', 'registration', 'status'))) {
			$filter_order = 'startdatetime';
		}
		$model->setOrderBy($filter_order);
		$model->setOrderDir($filter_order_Dir);
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order'] = $filter_order;
		$this->lists = $lists;		
		
		// Get actual data
		
		$contests = $model->getContests();
		$title = JText::_('COM_JVARCADE_CONTESTS');
		
		// Pagination
		
		$pageNav = $model->getPagination();
		$this->pageNav = $pageNav;
		$this->contests = $contests;
		
		$pathway->addItem($title);
		$doc->setTitle(($this->config->title ? $this->config->title . ' - ' : '') . $title);
		$this->tabletitle = $title;
		$this->sort_url = $sort_url;

		$user = JFactory::getUser();
		$this->user = $user;
		
		parent::display($tpl);
	}
	
	function showRegistration($registration, $slots) {
		if ($registration) {
			return JText::sprintf('COM_JVARCADE_CONTESTS_REGISTRATION_REQUIRED', $slots);
		} else {
			return JText::_('COM_JVARCADE_CONTESTS_REGISTRATION_NOTREQUIRED');
		}
	}
	
	function showStatus($status) {
		$return = JText::_('COM_JVARCADE_CONTESTS_STATUS_UNKNOWN');
		if ($status == 1) {
			$return = JText::_('COM_JVARCADE_CONTESTS_STATUS_FINISHED');
		} elseif ($status == 2) {
			$return = JText::_('COM_JVARCADE_CONTESTS_STATUS_NOW');
		} elseif ($status == 3) {
			$return = JText::_('COM_JVARCADE_CONTESTS_STATUS_UPCOMING');
		}
		return $return;
	}
}

<?php
/**
 * @package		jVArcade
 * @version		2.1
 * @date		2014-01-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.application.component.view');

class jvarcadeViewContests extends JViewLegacy {
	var $permnames = array();

	function display($tpl = null) {
		$mainframe = JFactory::getApplication('site');
		
		$acl = JFactory::getACL();
		
		$task = $mainframe->input->get('task', 'contests');
		$this->assignRef('task', $task);
		
		$filter_order = $mainframe->getUserStateFromRequest('com_jvarcade.contests.filter_order', 'filter_order', 'startdatetime', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest('com_jvarcade.contests.filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$filter_order_Dir = $filter_order_Dir ? $filter_order_Dir : 'DESC';
		// ensure filter_order has a valid value.
		if (!in_array($filter_order, array('id', 'name', 'startdatetime', 'enddatetime', 'maxplaycount', 'published'))) {
			$filter_order = 'startdatetime';
		}

		$model = $this->getModel();
		$model->setOrderBy($filter_order);
		$model->setOrderDir($filter_order_Dir);
		
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order'] = $filter_order;

		$this->assignRef('lists', $lists);
		
		$contests = $model->getContests();
		$pagination = $model->getPagination();
		$this->assignRef('pagination', $pagination);
		$this->assignRef('contests', $contests);
		
		//~ $this->permnames = $model->getAcl();
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_CONTESTS'), 'jvacontests');
		JToolBarHelper::editList('editcontest', JText::_('COM_JVARCADE_CONTESTS_EDIT'));
		JToolBarHelper::addNew('addcontest', JText::_('COM_JVARCADE_CONTESTS_ADD'));
		JToolBarHelper::deleteList(JText::_('COM_JVARCADE_CONTESTS_ASK_DELETE'), 'deletecontest', JText::_('COM_JVARCADE_CONTESTS_DELETE'));
		JToolBarHelper::publishList('folderPublishYes', JText::_('COM_JVARCADE_CONTESTS_PUBLISH'));
		JToolBarHelper::unpublishList('folderPublishNo', JText::_('COM_JVARCADE_CONTESTS_UNPUBLISH'));
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('contests');
		
		parent::display($tpl);
	}
	protected function addSidebar() {
		$this->sidebar = JHtmlSidebar::render();
	}
	
	function showPerms($perms) {
		$permsarr = explode(',', $perms);
		$result = '';
		foreach ($permsarr as $perm) {
			if(array_key_exists($perm, $this->permnames)) {
				$result .= $this->permnames[$perm]['name'] . ', ';
			}
			
		}
		$result = rtrim($result,', ');
		return $result;
	}
}

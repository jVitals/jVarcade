<?php
/**
 * @package		jVArcade
 * @version		2.10
 * @date		2014-05-04
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.view');

class jvarcadeViewManage_folders extends JViewLegacy {
	var $permnames = array();

	function display($tpl = null) {
	
		$mainframe = JFactory::getApplication('site');
		
		$acl = JFactory::getACL();
		
		$task = $mainframe->input->get('task', 'manage_folders');
		$this->assignRef('task', $task);
		
		$filter_order = $mainframe->getUserStateFromRequest('com_jvarcade.manage_folders.filter_order', 'filter_order', 'f.name', 'cmd' );
		$filter_order_Dir = $mainframe->getUserStateFromRequest('com_jvarcade.manage_folders.filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$filter_order_Dir = $filter_order_Dir ? $filter_order_Dir : 'DESC';
		// ensure filter_order has a valid value.
		if (!in_array($filter_order, array('f.name', 'f.viewpermissions', 'parentname', 'f.published'))) {
			$filter_order = 'f.name';
		}

		$model = $this->getModel();
		$model->setOrderBy($filter_order);
		$model->setOrderDir($filter_order_Dir);
		
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order'] = $filter_order;

		$this->assignRef('lists', $lists);
		
		$folders = $model->getFolders();
		$pagination = $model->getPagination();
		$this->assignRef('pagination', $pagination);
		$this->assignRef('folders', $folders);
		
		$this->permnames = $model->getAcl();
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_MANAGE_FOLDERS'), 'jvafolders');
		JToolBarHelper::editList('editfolder', JText::_('COM_JVARCADE_FOLDERS_EDIT'));
		JToolBarHelper::addNew('addfolder', JText::_('COM_JVARCADE_FOLDERS_ADD'));
		JToolBarHelper::deleteList(JText::_('COM_JVARCADE_FOLDERS_ASK_DELETE'), 'deletefolder', JText::_('COM_JVARCADE_FOLDERS_DELETE'));
		JToolBarHelper::publishList('folderPublish', JText::_('COM_JVARCADE_FOLDERS_PUBLISH'));
		JToolBarHelper::unpublishList('folderUnPublish', JText::_('COM_JVARCADE_FOLDERS_UNPUBLISH'));
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('manage_folders');
		
		parent::display($tpl);
	}
	protected function addSidebar() {
		$this->sidebar = JHtmlSidebar::render();
	}
	
	function showPerms($perms) {
		$permsarr = explode(',', $perms);
		$result = '';
		foreach ($permsarr as $perm) {
			if ((int)$perm == 0) $result .= 'Guest, ';
			if(array_key_exists($perm, $this->permnames)) {
				$result .= $this->permnames[$perm]['name'] . ', ';
			}
			
		}
		$result = rtrim($result,', ');
		return $result;
	}
}

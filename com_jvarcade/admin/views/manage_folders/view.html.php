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

class jvarcadeViewManage_folders extends JViewLegacy {
	var $permnames = array();

	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		
		$this->items = $this->get('Items');
		$this->pagination = $this->get('Pagination');
		$this->state = $this->get('State');
		$this->filter_order = $app->getUserStateFromRequest('jvarcade.manage_folders.filter_order', 'filter_order', 'f.name', 'cmd' );
		$this->filter_order_Dir = $app->getUserStateFromRequest('jvarcade.manage_folders.filter_order_Dir', 'filter_order_Dir', 'desc', 'word' );
		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
		
			return false;
		}
		
		$acl = JFactory::getACL();
		$model = $this->getModel();
		$this->permnames = $model->getAcl();
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_MANAGE_FOLDERS'), 'jvafolders');
		JToolBarHelper::editList('manage_folders.editFolder', JText::_('COM_JVARCADE_FOLDERS_EDIT'));
		JToolBarHelper::addNew('add_folder', JText::_('COM_JVARCADE_FOLDERS_ADD'));
		JToolBarHelper::deleteList(JText::_('COM_JVARCADE_FOLDERS_ASK_DELETE'), 'manage_folders.deletefolder', JText::_('COM_JVARCADE_FOLDERS_DELETE'));
		JToolBarHelper::publishList('manage_folders.folderPublish', JText::_('COM_JVARCADE_FOLDERS_PUBLISH'));
		JToolBarHelper::unpublishList('manage_folders.folderUnPublish', JText::_('COM_JVARCADE_FOLDERS_UNPUBLISH'));
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

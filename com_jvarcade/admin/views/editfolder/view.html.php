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

//jimport('joomla.application.component.view');

class jvarcadeViewEditfolder extends JViewLegacy {
	var $acl;
	var $gtree;
	var $folderlist;

	function display($tpl = null) {
		
		$model = $this->getModel();
		$this->acl = JFactory::getACL();
		$this->gtree = JVA_COMPATIBLE_MODE == '15' ? $this->acl->get_group_children_tree(null, 'USERS', false) : array();
		$mainframe = JFactory::getApplication();
		$task = $mainframe->input->get('task', 'editfolder');
		$this->assignRef('task', $task);

		$folderid = $mainframe->input->get('id', 'folder');
		if (is_array($folderid)) $folderid = $folderid[0];
		
		if ($task == 'addfolder') {
			$folder = new stdClass();
			$folder->id = 0;
			$folder->parentid = 0;
			$folder->name = '';
			$folder->alias = '';
			$folder->description = '';
			$folder->imagename = '';
			$folder->viewpermissions = '';
			$folder->published = 0;
		} else {
			$folder = $model->getFolders((int)$folderid);
			if (is_array($folder)) $folder = $folder[0];
		}
		$this->assignRef('folder', $folder);
		
		$this->folderlist = $model->getFolderList();
		
		$editor = JFactory::getEditor();
		$this->assignRef('editor', $editor);
		
		$editor_params = array('mode' => 'advanced');
		$this->assignRef('editor_params', $editor_params);
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_FOLDERS_FOLDER') . ': ' . $folder->name, 'jvafolders');
		JToolBarHelper::custom('manage_folders', 'cancel.png', 'cancel.png', JText::_('COM_JVARCADE_FOLDERS_CANCEL'), false, false);
		JToolBarHelper::save('savefolder', JText::_('COM_JVARCADE_FOLDERS_SAVE'));			
		JToolBarHelper::apply('applyfolder', JText::_('COM_JVARCADE_FOLDERS_APPLY'));			
		
		
		parent::display($tpl);
	}
	
	function list_folders($active = '') {
		$list = '<select name="parentid">';
		$list .= '<option value="0">ROOT</option>';
		foreach($this->folderlist as $folder) {
			$list .= '<option value="' . $folder->id . '"' . ($active == $folder->id? ' selected ' : '') . '>' . $folder->name . '</option>';
		}
		$list .= '</select>';
		return $list;
	}
	
}

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

//jimport('joomla.application.component.view');

class jvarcadeViewEdit_folder extends JViewLegacy {
	var $acl;
	var $gtree;
	var $folderlist;

	function display($tpl = null) {
		
		$model = $this->getModel();
		$this->app = JFactory::getApplication();
		$task = $this->app->input->get('task', 'edit_folder');
		$this->task = $task;

		$folderid = $this->app->input->get('id', 'folder');
		if (is_array($folderid)) $folderid = $folderid[0];
		
		if ($task == 'add_folder') {
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
		$this->folder = $folder;
		
		$this->folderlist = $model->getFolderList();
		
		$editor = JFactory::getEditor();
		$this->editor = $editor;
		
		$editor_params = array('mode' => 'advanced');
		$this->editor_params = $editor_params;
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_FOLDERS_FOLDER') . ': ' . $folder->name, 'jvafolders');
		JToolBarHelper::custom('manage_folders', 'cancel.png', 'cancel.png', JText::_('COM_JVARCADE_FOLDERS_CANCEL'), false, false);
		JToolBarHelper::save('edit_folder.savefolder', JText::_('COM_JVARCADE_FOLDERS_SAVE'));			
		JToolBarHelper::apply('edit_folder.applyfolder', JText::_('COM_JVARCADE_FOLDERS_APPLY'));			
		
		
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

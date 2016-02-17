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
defined('_JEXEC') or die;


class jvarcadeModelEdit_folder extends JModelLegacy {
	protected $filterobj = null;
	
	public function __construct() {
		parent::__construct();
		$this->dbo = JFactory::getDBO();
		$this->app = JFactory::getApplication();
		global $option;
	
	
		$this->filterobj = new JFilterInput(null, null, 1, 1);
	}
	
	public function getFolders($id = 0) {
		
		$query = "SELECT SQL_CALC_FOUND_ROWS f.*, p.name as parentname " . 
					"FROM #__jvarcade_folders f " . 
						"LEFT JOIN #__jvarcade_folders p ON p.id = f.parentid WHERE f.id = " . (int)$id;
		$this->dbo->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		$return  = $this->dbo->loadObjectList();

		return $return;
	}
	
	public function getFolderList() {
		$this->dbo->setQuery('SELECT id, name FROM #__jvarcade_folders ORDER BY id');
		return $this->dbo->loadObjectList();
	}
	
	public function saveFolder() {
		$task = $this->app->input->get('task');
		$post = $this->app->input->getArray(array(
				'id' => 'int',
				'name' => 'string',
				'alias' => 'string',
				'description' => 'raw',
				'viewpermissions' => 'array',
				'published' => 'int',
				'parentid' => 'int',
		));

		$post['description'] = $this->filterobj->clean((string)$post['description'], 'html');
		Joomla\Utilities\ArrayHelper::toInteger($post['viewpermissions']);
		$imgfile = $this->app->input->files->get('image');
		$uploaderr = '';
		$post['alias'] = isset($post['alias']) && $post['alias'] ? $post['alias'] : $post['name'];
		$post['alias'] = str_replace(array(' '), array(''), trim($post['alias']));
	
		// Process data
	
		if ((int)$post['id']) {
			$folderid = (int)$post['id'];
			$query = "UPDATE #__jvarcade_folders SET
				" . $this->dbo->quoteName('name') . " = " . $this->dbo->Quote($post['name']) . ",
				" . $this->dbo->quoteName('alias') . " = " . $this->dbo->Quote($post['alias']) . ",
				" . $this->dbo->quoteName('description') . " = " . $this->dbo->Quote($post['description']) . ",
				" . $this->dbo->quoteName('published') . " = " . $this->dbo->Quote((int)$post['published']) . ",
				" . $this->dbo->quoteName('parentid') . " = " . $this->dbo->Quote((int)$post['parentid']) . ",
				" . $this->dbo->quoteName('viewpermissions') . " = " . $this->dbo->Quote(implode(',', $post['viewpermissions'])) . "
			WHERE " . $this->dbo->quoteName('id') . " = " . (int)$post['id'];
			$this->dbo->setQuery($query);
			$this->dbo->execute();
		} else {
			$query = "INSERT INTO #__jvarcade_folders " .
					"(" . $this->dbo->quoteName('name') . ", " . $this->dbo->quoteName('alias') . ", " . $this->dbo->quoteName('description') . ", " . $this->dbo->quoteName('published') . ", " .
					$this->dbo->quoteName('parentid') . ", " . $this->dbo->quoteName('viewpermissions') . ") " .
					"VALUES (" . $this->dbo->Quote($post['name']) . "," . $this->dbo->Quote($post['alias']) . "," . $this->dbo->Quote($post['description']) . "," . $this->dbo->Quote((int)$post['published']) . "," .
					$this->dbo->Quote((int)$post['parentid']) . "," . $this->dbo->Quote(implode(',', $post['viewpermissions'])) . ")";
			$this->dbo->setQuery($query);
			$this->dbo->execute();
			$folderid = (int)$this->dbo->insertid();
		}
	
		// Process folder image upload
		if ((int)$folderid && is_array($imgfile) && $imgfile['size'] > 0) {
	
			$imgext = substr($imgfile['name'], strrpos($imgfile['name'], '.'));
			list($imgwith, $imgheight) = @getimagesize($imgfile['tmp_name']);
	
			if (!$uploaderr && $imgfile['error']) {
				$uploaderr = JText::sprintf('COM_JVARCADE_UPLOAD_ERROR', $imgfile['name']);
			}
			if (!$uploaderr && (strpos($imgfile['type'], 'image') === false)) {
				$uploaderr = JText::sprintf('COM_JVARCADE_UPLOAD_NOT_IMAGE', $imgfile['name']);
			}
			if (!$uploaderr && ($imgwith > 64 || $imgheight > 64)) {
				$uploaderr = JText::_('COM_JVARCADE_UPLOAD_BIGGER_DIMS');
			}
			if (!$uploaderr) {
				jimport('joomla.filesystem.file');
				$uploaded = JFile::upload($imgfile['tmp_name'], JVA_IMAGES_INCPATH . 'folders/' . $folderid . $imgext);
				if ($uploaded) {
					$this->dbo->setQuery('UPDATE #__jvarcade_folders SET ' .
							$this->dbo->quoteName('imagename') . ' = ' . $this->dbo->Quote($folderid . $imgext) .
							' WHERE ' . $this->dbo->quoteName('id') . ' = ' . (int)$folderid);
					$this->dbo->execute();
				} else {
					$uploaderr = JText::sprintf('COM_JVARCADE_UPLOAD_ERROR_MOVING', $imgfile['name']);
				}
			}
			if ($uploaderr) $this->app->enqueueMessage($uploaderr, 'notice');
		}
	
		if ($task == 'applyfolder') {
			$url = 'index.php?option=com_jvarcade&task=edit_folder&id=' . (int)$folderid;
		} else {
			$url = 'index.php?option=com_jvarcade&task=manage_folders';
		}
	
		$this->app->enqueueMessage(JText::_('COM_JVARCADE_FOLDERS_SAVE_SUCCESS'));
		$this->app->redirect($url);
	}
}
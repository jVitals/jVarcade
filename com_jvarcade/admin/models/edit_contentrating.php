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


class jvarcadeModelEdit_contentrating extends JModelLegacy {
	
	public function __construct() {
		parent::__construct();
		$this->dbo = JFactory::getDBO();
		$this->app = JFactory::getApplication();
		global $option;
	
		$this->filterobj = new JFilterInput(null, null, 1, 1);
	}
	
	public function getContentRatings($id = 0) {
	
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM #__jvarcade_contentrating WHERE id =" . (int)$id;
		$this->dbo->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		$return = $this->dbo->loadObjectList();
		return $return;
	}
	
	public function saveContentRating() {

		$task = $this->app->input->getWord('task', '');
		$post = $this->app->input->getArray(array(
				'id' => 'int',
				'name' => 'string',
				'description' => 'raw',
				'warningrequired' => 'int',
				'published' => 'int',
		));
		$post['description'] = $this->filterobj->clean((string)$post['description'], 'html');
		$imgfile = $this->app->input->files->get('image');
		$uploaderr = '';
	
		// Process data
	
		if ((int)$post['id']) {
			$contentratingid = (int)$post['id'];
			$query = "UPDATE #__jvarcade_contentrating SET
				" . $this->dbo->quoteName('name') . " = " . $this->dbo->Quote($post['name']) . ",
				" . $this->dbo->quoteName('description') . " = " . $this->dbo->Quote($post['description']) . ",
				" . $this->dbo->quoteName('warningrequired') . " = " . $this->dbo->Quote((int)$post['warningrequired']) . ",
				" . $this->dbo->quoteName('published') . " = " . $this->dbo->Quote((int)$post['published']) . "
			WHERE " . $this->dbo->quoteName('id') . " = " . $contentratingid;
			$this->dbo->setQuery($query);
			$this->dbo->execute();
		} else {
			$query = "INSERT INTO #__jvarcade_contentrating " .
					"(" . $this->dbo->quoteName('name') . ", " . $this->dbo->quoteName('description') . ", " .
					$this->dbo->quoteName('warningrequired') . ", " . $this->dbo->quoteName('published') . ") " .
					"VALUES (" . $this->dbo->Quote($post['name']) . "," . $this->dbo->Quote($post['description']) . "," .
					$this->dbo->Quote((int)$post['warningrequired']) . "," . $this->dbo->Quote((int)$post['published']) . ")";
			$this->dbo->setQuery($query);
			$this->dbo->execute();
			$contentratingid = (int)$this->dbo->insertid();
		}
	
		// Process image upload
		if ((int)$contentratingid && is_array($imgfile) && $imgfile['size'] > 0) {
	
			list($imgwith, $imgheight) = @getimagesize($imgfile['tmp_name']);
	
			if (!$uploaderr && $imgfile['error']) {
				$uploaderr = JText::sprintf('COM_JVARCADE_UPLOAD_ERROR', $imgfile['name']);
			}
			if (!$uploaderr && (strpos($imgfile['type'], 'image') === false)) {
				$uploaderr = JText::sprintf('COM_JVARCADE_UPLOAD_NOT_IMAGE', $imgfile['name']);
			}
			if (!$uploaderr && ($imgwith > 150 || $imgheight > 150)) {
				$uploaderr = JText::_('COM_JVARCADE_UPLOAD_BIGGER_DIMS3');
			}
			if (!$uploaderr) {
				jimport('joomla.filesystem.file');
				$uploaded = JFile::upload($imgfile['tmp_name'], JVA_IMAGES_INCPATH . 'contentrating/' . $contentratingid . '_' . $imgfile['name']);
				if ($uploaded) {
					$this->dbo->setQuery('UPDATE #__jvarcade_contentrating SET ' .
							$this->dbo->quoteName('imagename') . ' = ' . $this->dbo->Quote($contentratingid . '_' . $imgfile['name']) .
							' WHERE ' . $this->dbo->quoteName('id') . ' = ' . (int)$contentratingid);
					$this->dbo->execute();
				} else {
					$uploaderr = JText::sprintf('COM_JVARCADE_UPLOAD_ERROR_MOVING', $imgfile['name']);
				}
			}
			if ($uploaderr) $this->app->enqueueMessage($uploaderr, 'notice');
		}
	
		if ($task == 'applycontentrating') {
			$url = 'index.php?option=com_jvarcade&task=edit_contentrating&id=' . (int)$contentratingid;
		} else {
			$url = 'index.php?option=com_jvarcade&task=content_ratings';
		}
	
		$this->app->enqueueMessage(JText::_('COM_JVARCADE_CONTENT_RATINGS_SAVE_SUCCESS'));
		$this->app->redirect($url);
	}
	
	
}

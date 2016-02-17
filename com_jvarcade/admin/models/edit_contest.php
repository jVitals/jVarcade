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


class jvarcadeModelEdit_contest extends JModelLegacy {
	
	public function __construct() {
		parent::__construct();
		$this->dbo = JFactory::getDBO();
		$this->app = JFactory::getApplication();
		global $option;
	
	
		$this->filterobj = new JFilterInput(null, null, 1, 1);
	}
	
	public function getContests($id = 0) {
	
	
		$query = "SELECT SQL_CALC_FOUND_ROWS * FROM #__jvarcade_contest WHERE id =" . (int)$id;
		$this->dbo->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		$return = $this->dbo->loadObjectList();
		return $return;
	}

	public function saveContest() {
		$task = $this->app->input->getWord('task', '');
		$post = $this->app->input->getArray(array(
			'id' => 'int',
			'name' => 'string',
			'description' => 'raw',
			'startdatetime' => 'string',
			'enddatetime' => 'string',
			'islimitedtoslots' => 'int',
			'hasadvertisedstarted' => 'int',
			'hasadvertisedended' => 'int',
			'maxplaycount' => 'int',
			'published' => 'int',
		));
		$post['description'] = $this->filterobj->clean((string)$post['description'], 'html');
		$imgfile = $this->app->input->files->get('image');
		$uploaderr = '';

		// Process data

		if ((int)$post['id']) {
			$contestid = (int)$post['id'];
			$query = "UPDATE #__jvarcade_contest SET
					" . $this->dbo->quoteName('name') . " = " . $this->dbo->Quote($post['name']) . ",
					" . $this->dbo->quoteName('description') . " = " . $this->dbo->Quote($post['description']) . ",
					" . $this->dbo->quoteName('startdatetime') . " = " . $this->dbo->Quote($post['startdatetime']) . ",
					" . $this->dbo->quoteName('enddatetime') . " = " . $this->dbo->Quote($post['enddatetime']) . ",
					" . $this->dbo->quoteName('islimitedtoslots') . " = " . $this->dbo->Quote((int)$post['islimitedtoslots']) . ",
					" . $this->dbo->quoteName('published') . " = " . $this->dbo->Quote((int)$post['published']) . ",
					" . $this->dbo->quoteName('hasadvertisedstarted') . " = " . $this->dbo->Quote((int)$post['hasadvertisedstarted']) . ",
					" . $this->dbo->quoteName('hasadvertisedended') . " = " . $this->dbo->Quote((int)$post['hasadvertisedended']) . ",
					" . $this->dbo->quoteName('maxplaycount') . " = " . $this->dbo->Quote((int)$post['maxplaycount']) . "
				WHERE " . $this->dbo->quoteName('id') . " = " . (int)$post['id'];
			$this->dbo->setQuery($query);
			$this->dbo->execute();
		} else {
			$query = "INSERT INTO #__jvarcade_contest " .
					"(" . $this->dbo->quoteName('name') . ", " . $this->dbo->quoteName('description') . ", " . $this->dbo->quoteName('startdatetime') . ", " .
						$this->dbo->quoteName('enddatetime') . ", " . $this->dbo->quoteName('islimitedtoslots') . ", " . $this->dbo->quoteName('published') . ", " .
					$this->dbo->quoteName('hasadvertisedstarted') . ", " . $this->dbo->quoteName('hasadvertisedended') . ", " . $this->dbo->quoteName('maxplaycount') . ") " .
					"VALUES (" . $this->dbo->Quote($post['name']) . "," . $this->dbo->Quote($post['description']) . "," . $this->dbo->Quote($post['startdatetime']) . "," .
					$this->dbo->Quote($post['enddatetime']) . "," . $this->dbo->Quote((int)$post['islimitedtoslots']) . "," . $this->dbo->Quote((int)$post['published']) . "," .
					$this->dbo->Quote((int)$post['hasadvertisedstarted']) . "," . $this->dbo->Quote((int)$post['hasadvertisedended']) . "," . $this->dbo->Quote((int)$post['maxplaycount']) . ")";
			$this->dbo->setQuery($query);
			$this->dbo->execute();
			$contestid = (int)$this->dbo->insertid();
		}
	
		// Process contet image upload
		if ((int)$contestid && is_array($imgfile) && $imgfile['size'] > 0) {
	
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
				$uploaded = JFile::upload($imgfile['tmp_name'], JVA_IMAGES_INCPATH . 'contests/' . $contestid . '_' . $imgfile['name']);
				if ($uploaded) {
					$this->dbo->setQuery('UPDATE #__jvarcade_contest SET ' .
							$this->dbo->quoteName('imagename') . ' = ' . $this->dbo->Quote($contestid . '_' . $imgfile['name']) .
							' WHERE ' . $this->dbo->quoteName('id') . ' = ' . (int)$contestid);
					$this->dbo->execute();
				} else {
					$uploaderr = JText::sprintf('COM_JVARCADE_UPLOAD_ERROR_MOVING', $imgfile['name']);
				}
			}
			if ($uploaderr) $app->enqueueMessage($uploaderr, 'notice');
		}
	
		if ($task == 'applycontest') {
			$url = 'index.php?option=com_jvarcade&task=edit_contest&id=' . (int)$contestid;
		} else {
			$url = 'index.php?option=com_jvarcade&task=contests';
		}
	
		$this->app->enqueueMessage(JText::_('COM_JVARCADE_CONTESTS_SAVE_SUCCESS'));
		$this->app->redirect($url);
	}

}
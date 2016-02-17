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


class jvarcadeModelEdit_game extends JModelLegacy {
	
	protected $filterobj = null;
	
	public function __construct() {
		parent::__construct();
		$this->dbo = JFactory::getDBO();
		$this->app = JFactory::getApplication();
		global $option;
	
	
		$this->filterobj = new JFilterInput(null, null, 1, 1);
	}
	
	public function getGames($id = 0) {
		
		$query = "SELECT SQL_CALC_FOUND_ROWS g.*, f.name " .
				"FROM #__jvarcade_games g " .
				"LEFT JOIN #__jvarcade_folders f ON f.id = g.folderid WHERE g.id = " . (int)$id;
		
		$this->dbo->setQuery($query, $this->getState('limitstart'), $this->getState('limit'));
		$return = $this->dbo->loadObjectList();
		
		return $return;
	}
	
	public function getFolderList() {
		$this->dbo->setQuery('SELECT id, name FROM #__jvarcade_folders ORDER BY id');
		return $this->dbo->loadObjectList();
	}
	
	public function getContentRatingList() {
		$this->dbo->setQuery('SELECT id, name FROM #__jvarcade_contentrating ORDER BY id');
		return $this->dbo->loadObjectList();
	}
	
	public function saveGame() {
		$task = $this->app->input->getWord('task', '');
		$post = $this->app->input->getArray(array(
				'id' => 'int',
				'title' => 'string',
				'gamename' => 'string',
				'description' => 'raw',
				'width' => 'int',
				'height' => 'int',
				'numplayed' => 'int',
				'background' => 'string',
				'reverse_score' => 'int',
				'scoring' => 'int',
				'folderid' => 'int',
				'window' => 'int',
				'contentratingid' => 'int',
				'ajaxscore' => 'int',
				'gsafe' => 'int',
				'published' => 'int',
		));
		$post['description'] = $this->filterobj->clean((string)$post['description'], 'html');
		$imgfile = $this->app->input->files->get('image');
		$gamefile = $this->app->input->files->get('file');
		$uploaderr = '';
		$uploaderr2 = '';
	
		// Process data
	
		if ((int)$post['id']) {
			$gameid = (int)$post['id'];
			$query = "UPDATE #__jvarcade_games SET
				" . $this->dbo->quoteName('title') . " = " . $this->dbo->Quote($post['title']) . ",
				" . $this->dbo->quoteName('description') . " = " . $this->dbo->Quote($post['description']) . ",
				" . $this->dbo->quoteName('height') . " = " . $this->dbo->Quote((int)$post['height']) . ",
				" . $this->dbo->quoteName('width') . " = " . $this->dbo->Quote((int)$post['width']) . ",
				" . $this->dbo->quoteName('numplayed') . " = " . $this->dbo->Quote((int)$post['numplayed']) . ",
				" . $this->dbo->quoteName('background') . " = " . $this->dbo->Quote($post['background']) . ",
				" . $this->dbo->quoteName('published') . " = " . $this->dbo->Quote((int)$post['published']) . ",
				" . $this->dbo->quoteName('reverse_score') . " = " . $this->dbo->Quote((int)$post['reverse_score']) . ",
				" . $this->dbo->quoteName('scoring') . " = " . $this->dbo->Quote((int)$post['scoring']) . ",
				" . $this->dbo->quoteName('folderid') . " = " . $this->dbo->Quote((int)$post['folderid']) . ",
				" . $this->dbo->quoteName('window') . " = " . $this->dbo->Quote((int)$post['window']) . ",
				" . $this->dbo->quoteName('contentratingid') . " = " . $this->dbo->Quote((int)$post['contentratingid']) . ",
				" . $this->dbo->quoteName('ajaxscore') . " = " . $this->dbo->Quote((int)$post['ajaxscore']) . ",
				" . $this->dbo->quoteName('gsafe') . " = " . $this->dbo->Quote((int)$post['gsafe']) . "
			WHERE " . $this->dbo->quoteName('id') . " = " . (int)$post['id'];
			$this->dbo->setQuery($query);
			if (!$this->dbo->execute()) $this->getDBerr();
		} else {
			$query = "INSERT INTO #__jvarcade_games " .
					"(" . $this->dbo->quoteName('gamename') . ", " . $this->dbo->quoteName('title') . ", " . $this->dbo->quoteName('description') . ", " .
					$this->dbo->quoteName('height') . ", " . $this->dbo->quoteName('width') . ", " . $this->dbo->quoteName('numplayed') . ", " .
					$this->dbo->quoteName('background') . ", " . $this->dbo->quoteName('published') . ", " . $this->dbo->quoteName('reverse_score') . ", " .
					$this->dbo->quoteName('scoring') . ", " . $this->dbo->quoteName('folderid') . ", " . $this->dbo->quoteName('window') . ", " .
					$this->dbo->quoteName('contentratingid') . ", " . $this->dbo->quoteName('ajaxscore') . ", " . $this->dbo->quoteName('gsafe'). ") " .
					"VALUES (" . $this->dbo->Quote($post['gamename']) . "," . $this->dbo->Quote($post['title']) . "," . $this->dbo->Quote($post['description']) . "," .
					$this->dbo->Quote((int)$post['height']) . "," . $this->dbo->Quote((int)$post['width']) . "," . $this->dbo->Quote((int)$post['numplayed']) . "," .
					$this->dbo->Quote($post['background']) . "," . $this->dbo->Quote((int)$post['published']) . "," . $this->dbo->Quote((int)$post['reverse_score']) . "," .
					$this->dbo->Quote((int)$post['scoring']) . "," . $this->dbo->Quote((int)$post['folderid']) . "," . $this->dbo->Quote((int)$post['window']) . "," .
					$this->dbo->Quote((int)$post['contentratingid']) . "," . $this->dbo->Quote((int)$post['ajaxscore']) . "," . $this->dbo->Quote((int)$post['gsafe']) . ")";
			$this->dbo->setQuery($query);
			if (!$this->dbo->execute()) $this->getDBerr();
			$gameid = (int)$this->dbo->insertid();
		}
	
		// Process game image upload
		if ((int)$gameid && is_array($imgfile) && $imgfile['size'] > 0) {
	
			list($imgwith, $imgheight) = @getimagesize($imgfile['tmp_name']);
	
			if (!$uploaderr && $imgfile['error']) {
				$uploaderr = JText::sprintf('COM_JVARCADE_UPLOAD_ERROR', $imgfile['name']);
			}
			if (!$uploaderr && (strpos($imgfile['type'], 'image') === false)) {
				$uploaderr = JText::sprintf('COM_JVARCADE_UPLOAD_NOT_IMAGE', $imgfile['name']);
			}
			if (!$uploaderr && ($imgwith > 50 || $imgheight > 50)) {
				$uploaderr = JText::_('COM_JVARCADE_UPLOAD_BIGGER_DIMS2');
			}
			if (!$uploaderr) {
				jimport('joomla.filesystem.file');
				$uploaded = JFile::upload($imgfile['tmp_name'], JVA_IMAGES_INCPATH . 'games/' . $gameid . '_' . $imgfile['name']);
				if ($uploaded) {
					$this->dbo->setQuery('UPDATE #__jvarcade_games SET ' .
							$this->dbo->quoteName('imagename') . ' = ' . $this->dbo->Quote($gameid . '_' . $imgfile['name']) .
							' WHERE ' . $this->dbo->quoteName('id') . ' = ' . (int)$gameid);
					if (!$this->dbo->execute()) $this->getDBerr();
				} else {
					$uploaderr = JText::sprintf('COM_JVARCADE_UPLOAD_ERROR_MOVING', $imgfile['name']);
				}
			}
			if ($uploaderr) $this->app->enqueueMessage($uploaderr, 'notice');
		}
	
		// Process game file upload
		if ((int)$gameid && is_array($gamefile) && $gamefile['size'] > 0) {
	
			if (!$uploaderr2 && $gamefile['error']) {
				$uploaderr2 = JText::sprintf('COM_JVARCADE_UPLOAD_ERROR', $gamefile['name']);
			}
			if (!$uploaderr2) {
				jimport('joomla.filesystem.file');
				$uploaded = JFile::upload($gamefile['tmp_name'], JVA_GAMES_INCPATH . $gamefile['name']);
				if ($uploaded) {
					$this->dbo->setQuery('UPDATE #__jvarcade_games SET ' .
							$this->dbo->quoteName('filename') . ' = ' . $this->dbo->Quote($gamefile['name']) .
							' WHERE ' . $this->dbo->quoteName('id') . ' = ' . (int)$gameid);
					if (!$this->dbo->execute()) $this->getDBerr();
				} else {
					$uploaderr2 = JText::sprintf('COM_JVARCADE_UPLOAD_ERROR_MOVING', $gamefile['name']);
				}
			}
			if ($uploaderr2) $this->app->enqueueMessage($uploaderr2, 'notice');
		}
	
		if ($task == 'applygame') {
			$url = 'index.php?option=com_jvarcade&task=edit_game&id=' . (int)$gameid;
		} else {
			$url = 'index.php?option=com_jvarcade&task=manage_games';
		}
	
		$this->app->enqueueMessage(JText::_('COM_JVARCADE_GAMES_SAVE_SUCCESS'));
		$this->app->redirect($url);
	}
}
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

class jvarcadeModelManage_games extends JModelList {
	
	public function __construct($config = array()) {
	
		$this->dbo = JFactory::getDBO();
		$this->app = JFactory::getApplication();
	
		if(empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
					'g.id',
					'g.title',
					'g.numplayed',
					'g.scoring',
					'f.name',
					'g.published'
			);
		}
	
		parent::__construct($config);
	}
	
	protected function getListQuery(){
		// Initialize variables
		$query = $this->dbo->getQuery(true);
		// Create the base statement
		$query->select(array('SQL_CALC_FOUND_ROWS g.*', $this->dbo->quoteName('f.name')))
		->from($this->dbo->quoteName('#__jvarcade_games', 'g'))
		->leftJoin($this->dbo->quoteName('#__jvarcade_folders', 'f') . 'ON f.id = g.folderid');
		
		//Filter by Search
		$search = $this->getState('filter.search');
		
		if (!empty($search))
		{
			$like = $this->dbo->quote('%' . $search . '%');
			$query->where('g.title LIKE ' . $like);
		}
		
		//Filter by folders
		if ($folderId = $this->getState('filter.folders'))
		{
			$query->where('f.id = ' . $this->dbo->quote($folderId));
		}
		
		$orderCol = $this->state->get('list.ordering', 'g.id');
		$orderDirn = $this->state->get('list.direction', 'asc');
		
		$query->order($this->dbo->escape($orderCol) . ' ' . $this->dbo->escape($orderDirn));
		
		return $query;
		
	}
	
	public function gamePublish($published) {
		$id = array_unique($this->app->input->get('cid', array(0), 'array'));
		Joomla\Utilities\ArrayHelper::toInteger($id);
		$query = "UPDATE #__jvarcade_games SET " . $this->dbo->quoteName('published') . " = " . $this->dbo->Quote((int)$published) . "
			WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $id) . ")";
		$this->dbo->setQuery($query);
		$this->dbo->execute();
		$this->app->redirect('index.php?option=com_jvarcade&c&task=manage_games');
	
	}
	
	public function deleteGame() {
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		$id = array_unique($this->app->input->get('cid', array(0), 'array'));
		Joomla\Utilities\ArrayHelper::toInteger($id);
	
		// Delete the game file and image as well as the gamedata folder if exists
		$this->dbo->setQuery('SELECT filename, imagename, gamename FROM #__jvarcade_games WHERE ' . $this->dbo->quoteName('id') . ' IN (' . implode(',', $id) . ')');
		$games = $this->dbo->loadObjectList();
		foreach($games as $game) {
			if (JFile::exists(JVA_GAMES_INCPATH . '/' . $game->filename)) @JFile::delete(JVA_GAMES_INCPATH . $game->filename);
			if (JFile::exists(JVA_IMAGES_INCPATH . 'games/' . $game->imagename)) @JFile::delete(JVA_IMAGES_INCPATH . 'games/' . $game->imagename);
			if (JFolder::exists(JPATH_SITE . '/arcade/gamedata/' . $game->gamename)) {
				@JFolder::delete(JPATH_SITE . '/arcade/gamedata/' . $game->gamename);
			}
		}
	
		$query = "DELETE FROM #__jvarcade_games WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $id) . ")";
		$this->dbo->setQuery($query);
	
		if ($this->dbo->execute()) {
			$this->dbo->setQuery("DELETE FROM #__jvarcade WHERE " . $this->dbo->quoteName('gameid') . " IN (" . implode(',', $id) . ")");
			$this->dbo->execute();
			$this->dbo->setQuery("DELETE FROM #__jvarcade_contestgame WHERE " . $this->dbo->quoteName('gameid') . " IN (" . implode(',', $id) . ")");
			$this->dbo->execute();
			$this->dbo->setQuery("DELETE FROM #__jvarcade_contestscore WHERE " . $this->dbo->quoteName('gameid') . " IN (" . implode(',', $id) . ")");
			$this->dbo->execute();
			$this->dbo->setQuery("DELETE FROM #__jvarcade_faves WHERE " . $this->dbo->quoteName('gid') . " IN (" . implode(',', $id) . ")");
			$this->dbo->execute();
			$this->dbo->setQuery("DELETE FROM #__jvarcade_gamedata WHERE " . $this->dbo->quoteName('gameid') . " IN (" . implode(',', $id) . ")");
			$this->dbo->execute();
			$this->dbo->setQuery("DELETE FROM #__jvarcade_lastvisited WHERE " . $this->dbo->quoteName('gameid') . " IN (" . implode(',', $id) . ")");
			$this->dbo->execute();
			$this->dbo->setQuery("DELETE FROM #__jvarcade_ratings WHERE " . $this->dbo->quoteName('gameid') . " IN (" . implode(',', $id) . ")");
			$this->dbo->execute();
			$this->dbo->setQuery("DELETE FROM #__jvarcade_tags WHERE " . $this->dbo->quoteName('gameid') . " IN (" . implode(',', $id) . ")");
			$this->dbo->execute();
	
		}
	
		$this->app->redirect('index.php?option=com_jvarcade&c&task=manage_games');
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
	
	public function editGame() {
		$id = $this->app->input->get('cid', null, 'games', array());
		if (!is_array($id)) $id = array($id);
		Joomla\Utilities\ArrayHelper::toInteger($id, array(0));
		$this->app->redirect('index.php?option=com_jvarcade&task=edit_game&id='. implode(',', $id));
	}
}
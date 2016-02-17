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
defined('_JEXEC') or die();

class jvarcadeModelContests extends JModelList {
	
	public function __construct($config = array()) {
	
		$this->dbo = JFactory::getDBO();
		$this->app = JFactory::getApplication();
	
		if(empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
					'id',
					'name',
					'startdatetime',
					'enddatetime',
					'maxplaycount',
					'published'
			);
		}
	
		parent::__construct($config);
	}

	protected function getListQuery(){
		// Initialize variables
		$query = $this->dbo->getQuery(true);
		// Create the base statement
		$query->select('SQL_CALC_FOUND_ROWS *')
		->from('#__jvarcade_contest');
	
		// Add the list ordering clause
		$orderCol = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'asc');
	
		$query->order($this->dbo->escape($orderCol) . ' ' . $this->dbo->escape($orderDirn));
	
		return $query;
	}
	
	
	
	public function deleteContest() {
		$id = array_unique($this->app->input->get('cid', array(0), 'array'));
		Joomla\Utilities\ArrayHelper::toInteger($id);
	
		$query = "DELETE FROM #__jvarcade_contest WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $id) . ")";
		$this->dbo->setQuery($query);
	
		if ($this->dbo->execute()) {
			$this->dbo->setQuery("DELETE FROM #__jvarcade_contestgame WHERE " . $this->dbo->quoteName('contestid') . " IN (" . implode(',', $id) . ")");
			$this->dbo->execute();
			$this->dbo->setQuery("DELETE FROM #__jvarcade_contestmember WHERE " . $this->dbo->quoteName('contestid') . " IN (" . implode(',', $id) . ")");
			$this->dbo->execute();
			$this->dbo->setQuery("DELETE FROM #__jvarcade_contestscore WHERE " . $this->dbo->quoteName('contestid') . " IN (" . implode(',', $id) . ")");
			$this->dbo->execute();
				
			$this->dbo->setQuery("SELECT id FROM #__jvarcade_leaderboard WHERE " . $this->dbo->quoteName('contestid') . " IN (" . implode(',', $id) . ")");
			$ids = $this->dbo->loadColumn();
			if (is_array($ids) && count($ids)) {
				$this->dbo->setQuery("DELETE FROM #__jvarcade_leaderboard WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $ids) . ")");
				$this->dbo->execute();
				$this->dbo->setQuery("DELETE FROM #__jvarcade_leaderboarddetail WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $ids) . ")");
				$this->dbo->execute();
			}
		}
	
		$this->app->redirect('index.php?option=com_jvarcade&task=contests');
	}
	
	public function contestPublish($published) {
		$id = array_unique($this->app->input->get('cid', array(0), 'array'));
		Joomla\Utilities\ArrayHelper::toInteger($id);
		$query = "UPDATE #__jvarcade_contest SET " . $this->dbo->quoteName('published') . " = " . $this->dbo->Quote((int)$published) . "
			WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $id) . ")";
		$this->dbo->setQuery($query);
		$this->dbo->execute();
		$this->app->redirect('index.php?option=com_jvarcade&task=contests');
	
	}
	
	public function editContest() {
		$id = $this->app->input->get('cid', null, 'contests', array());
		if (!is_array($id)) $id = array($id);
		Joomla\Utilities\ArrayHelper::toInteger($id, array(0));
		$this->app->redirect('index.php?option=com_jvarcade&task=edit_contest&id='. implode(',', $id));
	}
}

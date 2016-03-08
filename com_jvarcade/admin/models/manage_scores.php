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

class jvarcadeModelManage_scores extends JModelList {
	
	public function __construct($config = array()) {
		
		$this->dbo = JFactory::getDBO();
		$this->app = JFactory::getApplication();
		
		if(empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
					'g.title',
					'u.username',
					'p.ip',
					'p.score',
					'p.date',
					'p.published'
			);
		}
		
		parent::__construct($config);
	}
	
	protected function getListQuery(){
		// Initialize variables
		$query = $this->dbo->getQuery(true);
		// Create the base statement
		$query->select(array('SQL_CALC_FOUND_ROWS p.*', 'u.username', 'g.title'))
			->from($this->dbo->quoteName('#__jvarcade', 'p'))
				->leftJoin('#__users u ON u.id = p.userid')
					->join('', '#__jvarcade_games g ON g.id = p.gameid');
		
		// Filter: like / search
		$search = $this->getState('filter.search');
		
		if (!empty($search))
		{
			$like = $this->dbo->quote('%' . $search . '%');
			$query->where('g.title LIKE ' . $like);
		}
		
		// Add the list ordering clause
		$orderCol = $this->state->get('list.ordering', 'g.title');
		$orderDirn = $this->state->get('list.direction', 'desc');
		
		$query->order($this->dbo->escape($orderCol) . ' ' . $this->dbo->escape($orderDirn));
		
		
		return $query;
		
		
	}
	
	public function scorePublish($published) {
		$id = array_unique($this->app->input->get('cid', array(0), 'array'));
		Joomla\Utilities\ArrayHelper::toInteger($id);
		$query = "UPDATE #__jvarcade SET " . $this->dbo->quoteName('published') . " = " . $this->dbo->Quote((int)$published) . "
			WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $id) . ")";
		$this->dbo->setQuery($query);
		$this->dbo->execute();
		$this->app->redirect('index.php?option=com_jvarcade&c&task=manage_scores');
	
	}
	
	public function deleteScore() {
		$id = array_unique($this->app->input->get('cid', array(0), 'array'));
		Joomla\Utilities\ArrayHelper::toInteger($id);
		$query = "DELETE FROM #__jvarcade WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $id) . ")";
		$this->dbo->setQuery($query);
		$this->dbo->execute();
		$this->app->redirect('index.php?option=com_jvarcade&c&task=manage_scores');
	}
}
?>
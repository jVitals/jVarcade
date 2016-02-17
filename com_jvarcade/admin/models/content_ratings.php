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

class jvarcadeModelContent_ratings extends JModelList {
	
	public function __construct($config = array()) {
	
		$this->dbo = JFactory::getDBO();
		$this->app = JFactory::getApplication();
	
		if(empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
					'id',
					'name',
					'warningrequired',
					'published'
			);
		}
	
		parent::__construct($config);
	}
	
	protected function getListQuery(){
		// Initialize variables
		$query = $this->dbo->getQuery(true);
		// Create the base statement
		$query->select('SQL_CALC_FOUND_ROWS *')->from('#__jvarcade_contentrating');
	
		// Add the list ordering clause
		$orderCol = $this->state->get('list.ordering', 'id');
		$orderDirn = $this->state->get('list.direction', 'asc');
	
		$query->order($this->dbo->escape($orderCol) . ' ' . $this->dbo->escape($orderDirn));
	
		return $query;
	}
	
	public function contentratingPublish($published) {
		$id = array_unique($this->app->input->get('cid', array(0), 'array'));
		Joomla\Utilities\ArrayHelper::toInteger($id);
		$query = "UPDATE #__jvarcade_contentrating SET " . $this->dbo->quoteName('published') . " = " . $this->dbo->Quote((int)$published) . "
			WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $id) . ")";
		$this->dbo->setQuery($query);
		$this->dbo->execute();
		$this->app->redirect('index.php?option=com_jvarcade&task=content_ratings');
	
	}
	
	public function deleteContentRating() {
		$id = array_unique($this->app->input->get('cid', array(0), 'array'));
		Joomla\Utilities\ArrayHelper::toInteger($id);
	
		$query = "DELETE FROM #__jvarcade_contentrating WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $id) . ")";
		$this->dbo->setQuery($query);
		$this->dbo->execute();
		$this->app->redirect('index.php?option=com_jvarcade&task=content_ratings');
	}
	
	public function editContentRating() {
		$id = $this->app->input->get('cid', null, 'contentrating', array());
		if (!is_array($id)) $id = array($id);
		Joomla\Utilities\ArrayHelper::toInteger($id, array(0));
		$this->app->redirect('index.php?option=com_jvarcade&task=edit_contentrating&id='. implode(',', $id));
	}
}

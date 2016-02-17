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

class jvarcadeModelManage_folders extends JModelList {
	
public function __construct($config = array()) {
		
		$this->dbo = JFactory::getDBO();
		$this->app = JFactory::getApplication();
		
		if(empty($config['filter_fields']))
		{
			$config['filter_fields'] = array(
					'f.name',
					'f.published',
					'f.permissions',
					'parentname'
			);
		}
		
		parent::__construct($config);
	}
	
	protected function getListQuery(){
		// Initialize variables
		$query = $this->dbo->getQuery(true);
		// Create the base statement
		$query->select(array('SQL_CALC_FOUND_ROWS f.*', $this->dbo->quoteName('p.name', 'parentname')))
		->from($this->dbo->quoteName('#__jvarcade_folders', 'f'))
			->leftJoin($this->dbo->quoteName('#__jvarcade_folders', 'p') . 'ON p.id = f.parentid');
		
		// Add the list ordering clause
		$orderCol = $this->state->get('list.ordering', 'f.name');
		$orderDirn = $this->state->get('list.direction', 'desc');
		
		$query->order($this->dbo->escape($orderCol) . ' ' . $this->dbo->escape($orderDirn));

		return $query;
	}
	
	public function getAcl() {
		$query = 'SELECT id, title as name FROM #__usergroups';
		$this->dbo->setQuery($query);
		return $this->dbo->loadAssocList('id');
	}
	
	public function deleteFolder() {
		$id = $this->app->input->get('cid', null, 'folders', array());
		if (!is_array($id)) $id = array($id);
		Joomla\Utilities\ArrayHelper::toInteger($id, array(0));
		$query = "DELETE FROM #__jvarcade_folders WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $id) . ")";
		$this->dbo->setQuery($query);
		$this->dbo->execute();
		$this->app->redirect('index.php?option=com_jvarcade&task=manage_folders');
	}
	
	public function folderPublish($published) {
		$id = $this->app->input->get('cid', null, null);
		if (!is_array($id)) $id = array($id);
		Joomla\Utilities\ArrayHelper::toInteger($id, array(0));
		$query = "UPDATE #__jvarcade_folders SET " . $this->dbo->quoteName('published') . " = " . $this->dbo->Quote((int)$published) . "
			WHERE " . $this->dbo->quoteName('id') . " IN (" . implode(',', $id) . ")";
		$this->dbo->setQuery($query);
		$this->dbo->execute();
		$this->app->redirect('index.php?option=com_jvarcade&task=manage_folders');
	
	}
	
	public function editFolder() {
		$id = $this->app->input->get('cid', null, 'folders', array());
		if (!is_array($id)) $id = array($id);
		Joomla\Utilities\ArrayHelper::toInteger($id, array(0));
		$this->app->redirect('index.php?option=com_jvarcade&task=edit_folder&id='. implode(',', $id));
	}
	
}
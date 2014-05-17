<?php
/**
 * @package		jVArcade
 * @version		2.12
 * @date		2014-05-17
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.view');

class jvarcadeViewManage_games extends JViewLegacy {
	var $permnames = array();

	function display($tpl = null) {
	
		$app = JFactory::getApplication('site');
		
		$task = $app->input->getWord('task', 'manage_games');
		$this->assignRef('task', $task);
		$lists = array();
		
		$search = '';
		$searchfields = array('filter_title', 'filter_name');
		
		$filter_order = $app->getUserStateFromRequest('com_jvarcade.manage_games.filter_order', 'filter_order', 'g.id', 'cmd' );
		$filter_order_Dir = $app->getUserStateFromRequest('com_jvarcade.manage_games.filter_order_Dir', 'filter_order_Dir', '', 'word' );
		$filter_order_Dir = $filter_order_Dir ? $filter_order_Dir : 'DESC';
		// ensure filter_order has a valid value.
		if (!in_array($filter_order, array('g.id', 'g.title', 'g.scoring', 'g.numplayed', 'f.name', 'g.published'))) {
			$filter_order = 'g.id';
		}

		foreach ($searchfields as $field) {
			$search = $app->getUserStateFromRequest('com_jvarcade.manage_games.' . $field, $field, '', 'string');
			if (strpos($search, '"') !== false) {
				$search = str_replace(array('=', '<'), '', $search);
			}
			$search = JString::strtolower($search);
			$lists[$field] = $search;
		}

		$model = $this->getModel();
		$model->setOrderBy($filter_order);
		$model->setOrderDir($filter_order_Dir);
		$model->setSearchFields($lists);
		
		$lists['order_Dir']	= $filter_order_Dir;
		$lists['order'] = $filter_order;

		$this->assignRef('lists', $lists);
		
		$games = $model->getGames();
		$pagination = $model->getPagination();
		$this->assignRef('pagination', $pagination);
		$this->assignRef('games', $games);
		
		$folderlist = $model->getFolderList();
		$folders[] = JHTML::_('select.option', '', JText::_( 'COM_JVARCADE_FOLDERS_ALL'));
		foreach($folderlist as $obj ) {
			$folders[] = JHTML::_('select.option', $obj->name, $obj->name);
		}
		$lists['folders'] = JHTML::_('select.genericlist', $folders, 'filter_name', 'class="inputbox" size="1" onchange="document.adminForm.submit( );"', 'value', 'text', $lists['filter_name']);
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_MANAGE_GAMES'), 'jvagames');
		//JToolBarHelper::custom('addgametocontest', 'default.png', 'default.png', JText::_('COM_JVARCADE_CONTESTSLINK_ADDTOCONTESTS'), true);
		JToolBarHelper::editList('editgame', JText::_('COM_JVARCADE_GAMES_EDIT'));
		JToolBarHelper::addNew('addgame', JText::_('COM_JVARCADE_GAMES_ADD'));
		JToolBarHelper::deleteList(JText::_('COM_JVARCADE_GAMES_ASK_DELETE'), 'deletegame', JText::_('COM_JVARCADE_GAMES_DELETE'));
		JToolBarHelper::publishList('gamePublish', JText::_('COM_JVARCADE_GAMES_PUBLISH'));
		JToolBarHelper::unpublishList('gameUnPublish', JText::_('COM_JVARCADE_GAMES_UNPUBLISH'));
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('manage_games');

		parent::display($tpl);
	}
	protected function addSidebar() {
		
		$this->sidebar = JHtmlSidebar::render();
	}
}

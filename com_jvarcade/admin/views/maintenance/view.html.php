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

class jvarcadeViewMaintenance extends JViewLegacy {

	function display($tpl = null) {
		$app = JFactory::getApplication();
		$task = $app->input->get('task', 'maintenance');
		$this->task = $task;
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_MAINTENANCE'), 'jvamaintenance');
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('maintenance');
		
		
		parent::display($tpl);
	}
	protected function addSidebar() {
		$this->sidebar = JHtmlSidebar::render();
	}
}

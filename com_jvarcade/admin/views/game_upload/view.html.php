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

jimport('bootstrap.framework');


class jvarcadeViewGame_upload extends JViewLegacy {
	var $folderlist;

	public function display($tpl = null) {
		$config = JFactory::getConfig();
		$model = $this->getModel();
		$app = JFactory::getApplication();
		$this->task = $app->input->getCmd('task', 'game_upload');
		$published = 1;
		$this->published = $published;
		
		$this->folderlist = $model->getFolderList();
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_UPLOADARCHIVE_TITLE'), 'jvaupload');
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('game_upload');
		
		$tmp_path = $config->get('tmp_path') . '/';
		$this->tmp_path = $tmp_path;
		
		$example1 = "archive.zip\n\t|-game.swf\n\t|-config.txt\n\t|-game.jpg";
		$example2 = "archive.zip\n\t|-game1.zip\n\t|-game2.zip\n\t|-game2.zip";
		$legend = JText::sprintf('COM_JVARCADE_UPLOADARCHIVE_LEGEND', $example1, $example2);
		$this->legend = $legend;
		
		parent::display($tpl);
	}
	protected function addSidebar() {
		$this->sidebar = JHtmlSidebar::render();
	}
	
	function list_folders($active = '') {
		$list = '<select name="folderid">';
		foreach($this->folderlist as $folder) {
			$list .= '<option value="' . $folder->id . '"' . ($active == $folder->id? ' selected ' : '') . '>' . $folder->name . '</option>';
		}
		$list .= '</select>';
		return $list;
	}
	
}

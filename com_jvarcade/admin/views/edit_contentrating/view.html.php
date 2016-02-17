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

jimport('joomla.application.component.view');

class jvarcadeViewEdit_contentrating extends JViewLegacy {
	var $folderlist;
	var $contentratinglist;

	function display($tpl = null) {
		
		$model = $this->getModel();
		$app = JFactory::getApplication();
		$task = $app->input->get('task', 'edit_contentrating');
		$this->task = $task;

		$contentratingid = $app->input->get('id', 'game');
		if (is_array($contentratingid)) $contentratingid = $contentratingid[0];
		
		if ($task == 'add_contentrating') {
			$contentrating = new stdClass();
			$contentrating->id = 0;
			$contentrating->name = '';
			$contentrating->description = '';
			$contentrating->warningrequired = 0;
			$contentrating->imagename = '';
			$contentrating->published = 0;

		} else {
			$contentrating = $model->getContentRatings((int)$contentratingid);
			if (is_array($contentrating)) $contentrating = $contentrating[0];
		}
		$this->contentrating = $contentrating;
		
		$editor = JFactory::getEditor();
		$this->editor = $editor;
		$editor_params = array('mode' => 'advanced');
		$this->editor_params = $editor_params;
		
		$upimage = ($task == 'add_contentrating' ? JText::_('COM_JVARCADE_CONTENT_RATINGS_NEWIMAGE') : JText::_('COM_JVARCADE_CONTENT_RATINGS_CHIMAGE'));
		$upimage_desc = ($task == 'add_contentrating' ? JText::_('COM_JVARCADE_CONTENT_RATINGS_NEWIMAGE_DESC') : JText::_('COM_JVARCADE_CONTENT_RATINGS_CHIMAGE_DESC'));
		$this->upimage = $upimage;
		$this->upimage_desc = $upimage_desc;
		
		JToolBarHelper::title(($task == 'add_contentrating' ? JText::_('COM_JVARCADE_CONTENT_RATINGS_NEW') : $contentrating->name), 'jvacontent');
		JToolBarHelper::custom('content_ratings', 'cancel.png', 'cancel.png', JText::_('COM_JVARCADE_CONTENT_RATINGS_CANCEL'), false, false);
		JToolBarHelper::save('edit_contentrating.savecontentrating', JText::_('COM_JVARCADE_CONTENT_RATINGS_SAVE'));			
		JToolBarHelper::apply('edit_contentrating.applycontentrating', JText::_('COM_JVARCADE_CONTENT_RATINGS_APPLY'));			
		
		
		parent::display($tpl);
	}
	
}

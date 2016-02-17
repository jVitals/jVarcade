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

class jvarcadeViewedit_contest extends JViewLegacy {

	function display($tpl = null) {
		
		$model = $this->getModel();
		$app = JFactory::getApplication();
		$task = $app->input->get('task', 'edit_contest');
		$this->task = $task;

		$contestid = $app->input->getInt('id', 0);
		if (is_array($contestid)) $contestid = $contestid[0];
		
		if ($task == 'add_contest') {
			$contest = new stdClass();
			$contest->id = 0;
			$contest->name = '';
			$contest->description = '';
			$contest->imagename = '';
			$contest->startdatetime = '';
			$contest->enddatetime = '';
			$contest->islimitedtoslots = 0;
			$contest->minaccesslevelrequired = 0;
			$contest->published = 0;
			$contest->hasadvertisedstarted = 0;
			$contest->hasadvertisedended = 0;
			$contest->maxplaycount = 0;
		} else {
			$contest = $model->getContests((int)$contestid);
			if (is_array($contest)) $contest = $contest[0];
		}
		$this->contest = $contest;
		
		$editor = JFactory::getEditor();
		$this->editor = $editor;
		$editor_params = array('mode' => 'advanced');
		$this->editor_params = $editor_params;
		
		$upimage = ($task == 'add_contest' ? JText::_('COM_JVARCADE_CONTESTS_NEWIMAGE') : JText::_('COM_JVARCADE_CONTESTS_CHIMAGE'));
		$upimage_desc = ($task == 'add_contest' ? JText::_('COM_JVARCADE_CONTESTS_NEWIMAGE_DESC') : JText::_('COM_JVARCADE_CONTESTS_CHIMAGE_DESC'));
		$this->upimage = $upimage;
		$this->upimage_desc = $upimage_desc;
		
		JToolBarHelper::title(($task == 'add_contest' ? JText::_('COM_JVARCADE_CONTESTS_NEWCONTEST') : $contest->name), 'jvacontests');
		JToolBarHelper::custom('contests', 'cancel.png', 'cancel.png', JText::_('COM_JVARCADE_CONTESTS_CANCEL'), false, false);
		JToolBarHelper::save('edit_contest.savecontest', JText::_('COM_JVARCADE_CONTESTS_SAVE'));			
		JToolBarHelper::apply('edit_contest.applycontest', JText::_('COM_JVARCADE_CONTESTS_APPLY'));			
		
		
		parent::display($tpl);
	}
	
}

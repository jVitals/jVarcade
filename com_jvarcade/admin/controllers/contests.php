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
defined('_JEXEC') or die;



class jvarcadeControllerContests extends JControllerLegacy {
	
	protected $default_view = 'contests';
	
	public function contestPublish() {
		$model = $this->getModel('contests');
		$model->contestPublish(1);
	}
	
	public function contestUnpublish() {
		$model = $this->getModel('contests');
		$model->contestPublish(0);
	}
	
	public function deletecontest() {
		$model = $this->getModel('contests');
		$model->deleteContest();
	}
	
	public function editcontest() {
		$model = $this->getModel('contests');
		$model->editContest();
	}
}

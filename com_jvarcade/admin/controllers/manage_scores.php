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



class jvarcadeControllerManage_scores extends JControllerLegacy {
	
	protected $default_view = 'manage_scores';
	
	public function scorePublish() {
		$model = $this->getModel('manage_scores');
		$model->scorePublish(1);
	}
	
	public function scoreUnpublish() {
		$model = $this->getModel('manage_scores');
		$model->scorePublish(0);
	}
	
	public function deletescore() {
		$model = $this->getModel('manage_scores');
		$model->deleteScore();
	}
}
?>
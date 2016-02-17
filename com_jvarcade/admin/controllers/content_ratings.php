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



class jvarcadeControllerContent_ratings extends JControllerLegacy {
	
	protected $default_view = 'content_ratings';
	
	public function contentratingPublish() {
		$model = $this->getModel('content_ratings');
		$model->contentratingPublish(1);
	}
	
	public function contentratingUnpublish() {
		$model = $this->getModel('content_ratings');
		$model->contentratingPublish(0);
	}
	
	public function deletecontentrating() {
		$model = $this->getModel('content_ratings');
		$model->deleteContentRating();
	}
	
	public function editcontentrating() {
		$model = $this->getModel('content_ratings');
		$model->editContentRating();
	}
}
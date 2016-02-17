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



class jvarcadeControllerEdit_contentrating extends JControllerLegacy {
	
	protected $default_view = 'edit_contentrating';
	
	public function savecontentrating() {
		$model = $this->getModel('edit_contentrating');
		$model->saveContentRating();
	}
	
	public function applycontentrating() {
		$this->savecontentrating();
	}
	
}

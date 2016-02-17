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



class jvarcadeControllerManage_folders extends JControllerLegacy {
	
	protected $default_view = 'manage_folders';
	
	public function folderPublish() {
		$model = $this->getModel('manage_folders');
		$model->folderPublish(1);
	}
	
	public function folderUnpublish() {
		$model = $this->getModel('manage_folders');
		$model->folderPublish(0);
	}
	
	public function deletefolder() {
		$model = $this->getModel('manage_folders');
		$model->deleteFolder();
	}
	
	public function editfolder() {
		$model = $this->getModel('manage_folders');
		$model->editFolder();
	}
	
}
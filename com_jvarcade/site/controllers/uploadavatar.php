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

class jvarcadeControllerUploadavatar extends JControllerLegacy {
	
	public function upload(){
		
		$app = JFactory::getApplication();
		$userid = $app->input->get('id');
		$upload = $app->input->files->get('avatar');
		
		$fileExt = JFile::getExt($upload['name']);
		if (!in_array(strtolower($fileExt), array('bmp', 'gif', 'jpeg', 'jpg', 'png'))){
			$app->enqueueMessage('File type .' . $fileExt . ' is not allowed', 'Error');
			$app->redirect('index.php?option=com_jvarcade&task=uploadavatar&tmpl=component&id=' . $userid);
			exit;
		}
		
		$imgSearch = glob('images/jvarcade/images/avatars/' .$userid. '.*');
		if (isset($imgSearch[0])) {
			JFile::delete($imgSearch[0]);
		}
		
		$src = $upload['tmp_name'];
		$dest = 'images/jvarcade/images/avatars/' . $userid . '.' . $fileExt;
		
		if (!JFile::upload($src, $dest)) {
			$app->enqueueMessage('Your avatar has not been updated', 'Error');
		} else {
			$app->enqueueMessage('Your avatar has been updated');
		}
		$app->redirect('index.php?option=com_jvarcade&task=uploadavatar&tmpl=component&id=' . $userid);
	}
}

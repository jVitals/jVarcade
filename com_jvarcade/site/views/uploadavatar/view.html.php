<?php
/**
 * @package		jVArcade
 * @version		2.14
 * @date		2016-03-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */

defined('_JEXEC') or die;

class jvarcadeViewUploadavatar extends JViewLegacy {
	
	public function display($tpl=null){
		$app = JFactory::getApplication();
		$this->user_id = (int)$app->input->get('id');
 
		// Display the view
		parent::display($tpl);
	}
}

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


class jvarcadeModelGame_upload extends JModelLegacy {
	
	public function __construct() {
		parent::__construct();
		$this->dbo = JFactory::getDBO();
	

	}
	
	public function getFolderList() {
		$this->dbo->setQuery('SELECT id, name FROM #__jvarcade_folders ORDER BY id');
		return $this->dbo->loadObjectList();
	}
	
	
}

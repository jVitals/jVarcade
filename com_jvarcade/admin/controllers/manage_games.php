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



class jvarcadeControllerManage_games extends JControllerLegacy {
	
	protected $default_view = 'manage_games';
	
	public function gamePublish() {
		$model = $this->getModel('manage_games');
		$model->gamePublish(1);
	}
	
	public function gameUnpublish() {
		$model = $this->getModel('manage_games');
		$model->gamePublish(0);
	}
	
	public function deletegame() {
		$model = $this->getModel('manage_games');
		$model->deleteGame();
	}
	
	public function savegame() {
		$model = $this->getModel('manage_games');
		$model->saveGame();
	}
	
	public function applygame() {
		$this->savegame();
	}
	
	public function editGame() {
		$model = $this->getModel('manage_games');
		$model->editGame();
	}

}

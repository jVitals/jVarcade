<?php
/**
 * @package		jVArcade
 * @version		2.1
 * @date		2014-01-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */


// no direct access
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.controller');

class jvarcadeController extends JControllerLegacy {
	var $_name = 'jvarcade';
	var $_returl;
	
	function __construct($config = array()) {
		parent::__construct($config);
		$this->config = JFactory::getConfig();
		$this->baseurl = $this->config->get('config.live_site');
		$this->db = JFactory::getDBO();

	}

	function settings($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$model->configSave();
		$view = $this->getView('settings', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	function manage_scores($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('manage_scores', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}

	function scorePublishYes() {
		$model = $this->getModel('Common');
		$model->scorePublish(1);
	}
	
	function scorePublishNo() {
		$model = $this->getModel('Common');
		$model->scorePublish(0);
	}

	function deletescore() {
		$model = $this->getModel('Common');
		$model->deleteScore();
	}
	
	function manage_folders($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('manage_folders', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}

	function folderPublishYes() {
		$model = $this->getModel('Common');
		$model->folderPublish(1);
	}
	
	function folderPublishNo() {
		$model = $this->getModel('Common');
		$model->folderPublish(0);
	}

	function deletefolder() {
		$model = $this->getModel('Common');
		$model->deleteFolder();
	}
	
	function editfolder($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('editfolder', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	function addfolder() {
		$this->editfolder();
	}
	
	function savefolder() {	
		$model = $this->getModel('Common');
		$model->saveFolder();
	}
	
	function applyfolder() {	
		$this->savefolder();
	}
	
	function cpanel($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('cpanel', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	function manage_games($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('manage_games', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}

	function gamePublishYes() {
		$model = $this->getModel('Common');
		$model->gamePublish(1);
	}
	
	function gamePublishNo() {
		$model = $this->getModel('Common');
		$model->gamePublish(0);
	}

	function deletegame() {
		$model = $this->getModel('Common');
		$model->deleteGame();
	}
	
	function editgame($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('editgame', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	function addgame() {
		$this->editgame();
	}
	
	function savegame() {	
		$model = $this->getModel('Common');
		$model->saveGame();
	}
	
	function applygame() {	
		$this->savegame();
	}

	function contests($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('contests', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	function editcontest($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('editcontest', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	function addcontest() {
		$this->editcontest();
	}

	function contestPublishYes() {
		$model = $this->getModel('Common');
		$model->contestPublish(1);
	}
	
	function contestPublishNo() {
		$model = $this->getModel('Common');
		$model->contestPublish(0);
	}

	function deletecontest() {
		$model = $this->getModel('Common');
		$model->deleteContest();
	}
	
	function savecontest() {	
		$model = $this->getModel('Common');
		$model->saveContest();
	}
	
	function applycontest() {	
		$this->savecontest();
	}
	
	// Popup for the manage_games and editgame page
	function addgametocontest($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('contestlink', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}

	// Popup for the editcontest page
	function addcontestgames($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('contestlink', 'html', '');
		$view->setLayout('contestgames');
		$view->setModel($model, true);
		$view->display();
	}

	function savegametocontest() {
		$game_ids = JRequest::getVar('game_ids', '');
		$contest_ids = JRequest::getVar('contest_ids', '');
		if ($game_ids && $contest_ids) {
			$game_ids = explode(',', $game_ids);
			$contest_ids = explode(',', $contest_ids);
			$model = $this->getModel('Common');
			if($model->addGameToContest($game_ids, $contest_ids)) {
				echo JText::_('COM_JVARCADE_CONTESTSLINK_SAVE_SUCCESS');
			} else {
				echo JText::_('COM_JVARCADE_CONTESTSLINK_SAVE_ERROR');
			}
		} else {
			echo JText::_('COM_JVARCADE_CONTESTSLINK_SAVE_EMPTY');
		}
		exit;
	}
	
	function showcontestgames($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('contestlink', 'raw', '');
		$view->setLayout('games');
		$view->setModel($model, true);
		$view->display();
	}
	
	function showgamecontests($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('contestlink', 'raw', '');
		$view->setLayout('contests');
		$view->setModel($model, true);
		$view->display();
	}
	
	function deletegamefromcontest() {
		$game_ids = JRequest::getVar('game_id', '');
		$contest_ids = JRequest::getVar('contest_id', '');
		if ($game_ids && $contest_ids) {
			$game_ids = explode(',', $game_ids);
			$contest_ids = explode(',', $contest_ids);
			$model = $this->getModel('Common');
			if($model->deleteGameFromContest($game_ids, $contest_ids)) {
				echo 1;
			} else {
				echo 0;
			}
		} else {
			echo 0;
		}
		exit;		
	}


	function upload_archive($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('upload_archive', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	function doInstall() {
		$model = $this->getModel('Install');
		$model->installPackage();
	}
	
	function maintenance($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('maintenance', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	function domaintenance() {
		$service = JRequest::getVar('service', '');
		$context = JRequest::getVar('context', '');
		$gameid = (int)JRequest::getVar('gameid', 0);
		$contestid = (int)JRequest::getVar('contestid', 0);
		$model = $this->getModel('Common');
		$jsonDATA = $model->doMaintenance($service, $context, $gameid, $contestid);
		JRequest::setVar('tmpl', 'component');
		JRequest::setVar('format', 'raw');
		$doc = JFactory::getDocument();
		$doc->setMimeEncoding('application/json', false);
		echo json_encode($jsonDATA);
		exit;
	}

	function domigration() {
		$step = (int)JRequest::getVar('step', '');
		$model = $this->getModel('Migration');
		$jsonDATA = $model->doMigration($step);
		JRequest::setVar('tmpl', 'component');
		JRequest::setVar('format', 'raw');
		$doc = JFactory::getDocument();
		$doc->setMimeEncoding('application/json', false);
		echo json_encode($jsonDATA);
		exit;
	}

	function content_ratings($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('content_ratings', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	function editcontentrating($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('editcontentrating', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	function addcontentrating() {
		$this->editcontentrating();
	}

	function contentratingPublishYes() {
		$model = $this->getModel('Common');
		$model->contentratingPublish(1);
	}
	
	function contentratingPublishNo() {
		$model = $this->getModel('Common');
		$model->contentratingPublish(0);
	}

	function deletecontentrating() {
		$model = $this->getModel('Common');
		$model->deleteContentRating();
	}
	
	function savecontentrating() {	
		$model = $this->getModel('Common');
		$model->saveContentRating();
	}
	
	function applycontentrating() {	
		$this->saveContentRating();
	}
	
	
}

?>

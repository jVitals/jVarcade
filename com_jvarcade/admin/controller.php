<?php
/**
 * @package		jVArcade
 * @version		2.11
 * @date		2014-05-04
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.controller');

class jvarcadeController extends JControllerLegacy {
	private $returl;
	
	public function __construct($config = array()) {
		parent::__construct($config);
		$this->config = JFactory::getConfig();
		$this->baseurl = $this->config->get('config.live_site');
		$this->db = JFactory::getDBO();

	}

	public function settings($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$model->configSave();
		$view = $this->getView('settings', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function manage_scores($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('manage_scores', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}

	public function scorePublish() {
		$model = $this->getModel('Common');
		$model->scorePublish(1);
	}
	
	public function scoreUnpublish() {
		$model = $this->getModel('Common');
		$model->scorePublish(0);
	}

	public function deletescore() {
		$model = $this->getModel('Common');
		$model->deleteScore();
	}
	
	public function manage_folders($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('manage_folders', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}

	public function folderPublish() {
		$model = $this->getModel('Common');
		$model->folderPublish(1);
	}
	
	public function folderUnpublish() {
		$model = $this->getModel('Common');
		$model->folderPublish(0);
	}

	public function deletefolder() {
		$model = $this->getModel('Common');
		$model->deleteFolder();
	}
	
	public function editfolder($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('editfolder', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function addfolder() {
		$this->editfolder();
	}
	
	public function savefolder() {	
		$model = $this->getModel('Common');
		$model->saveFolder();
	}
	
	public function applyfolder() {	
		$this->savefolder();
	}
	
	public function cpanel($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('cpanel', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function manage_games($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('manage_games', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}

	public function gamePublish() {
		$model = $this->getModel('Common');
		$model->gamePublish(1);
	}
	
	public function gameUnpublish() {
		$model = $this->getModel('Common');
		$model->gamePublish(0);
	}

	public function deletegame() {
		$model = $this->getModel('Common');
		$model->deleteGame();
	}
	
	public function editgame($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('editgame', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function addgame() {
		$this->editgame();
	}
	
	public function savegame() {	
		$model = $this->getModel('Common');
		$model->saveGame();
	}
	
	public function applygame() {	
		$this->savegame();
	}

	public function contests($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('contests', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function editcontest($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('editcontest', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function addcontest() {
		$this->editcontest();
	}

	public function contestPublish() {
		$model = $this->getModel('Common');
		$model->contestPublish(1);
	}
	
	public function contestUnpublish() {
		$model = $this->getModel('Common');
		$model->contestPublish(0);
	}

	public function deletecontest() {
		$model = $this->getModel('Common');
		$model->deleteContest();
	}
	
	public function savecontest() {	
		$model = $this->getModel('Common');
		$model->saveContest();
	}
	
	public function applycontest() {	
		$this->savecontest();
	}
	
	// Popup for the manage_games and editgame page
	public function addgametocontest($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('contestlink', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}

	// Popup for the editcontest page
	public function addcontestgames($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('contestlink', 'html', '');
		$view->setLayout('contestgames');
		$view->setModel($model, true);
		$view->display();
	}

	public function savegametocontest() {
		$app = JFactory::getApplication();
		$game_ids = $app->input->getString('game_ids', '');
		$contest_ids = $app->input->getString('contest_ids', '');
		if ($game_ids && $contest_ids) {
			$game_ids = explode(',', $game_ids);
			JArrayHelper::toInteger($game_ids);
			$contest_ids = explode(',', $contest_ids);
			JArrayHelper::toInteger($contest_ids);
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
	
	public function showcontestgames($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('contestlink', 'raw', '');
		$view->setLayout('games');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function showgamecontests($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('contestlink', 'raw', '');
		$view->setLayout('contests');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function deletegamefromcontest() {
		$app = JFactory::getApplication();
		$game_ids = $app->input->getString('game_id', '');
		$contest_ids = $app->input->getString('contest_id', '');
		if ($game_ids && $contest_ids) {
			$game_ids = explode(',', $game_ids);
			JArrayHelper::toInteger($game_ids);
			$contest_ids = explode(',', $contest_ids);
			JArrayHelper::toInteger($contest_ids);
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


	public function upload_archive($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('upload_archive', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function doInstall() {
		$model = $this->getModel('Install');
		$model->installPackage();
	}
	
	public function maintenance($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('maintenance', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function domaintenance() {
		$app = JFactory::getApplication();
		$service = $app->input->getWord('service', '');
		$context = $app->input->getWord('context', '');
		$gameid = $app->input->getInt('gameid', 0);
		$contestid = $app->input->getInt('contestid', 0);
		$model = $this->getModel('Common');
		$jsonDATA = $model->doMaintenance($service, $context, $gameid, $contestid);
		$app->input->set('tmpl', 'component');
		$app->input->set('format', 'raw');
		$doc = JFactory::getDocument();
		$doc->setMimeEncoding('application/json', false);
		echo json_encode($jsonDATA);
		exit;
	}

	public function domigration() {
		$app = JFactory::getApplication();
		$step = $app->input->getInt('step', 0);
		$model = $this->getModel('Migration');
		$jsonDATA = $model->doMigration($step);
		$app->input->set('tmpl', 'component');
		$app->input->set('format', 'raw');
		$doc = JFactory::getDocument();
		$doc->setMimeEncoding('application/json', false);
		echo json_encode($jsonDATA);
		exit;
	}

	public function content_ratings($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('content_ratings', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function editcontentrating($cachable = false, $urlparams = false) {
		$model = $this->getModel('Common');
		$view = $this->getView('editcontentrating', 'html', '');
		$view->setLayout('default');
		$view->setModel($model, true);
		$view->display();
	}
	
	public function addcontentrating() {
		$this->editcontentrating();
	}

	public function contentratingPublish() {
		$model = $this->getModel('Common');
		$model->contentratingPublish(1);
	}
	
	public function contentratingUnpublish() {
		$model = $this->getModel('Common');
		$model->contentratingPublish(0);
	}

	public function deletecontentrating() {
		$model = $this->getModel('Common');
		$model->deleteContentRating();
	}
	
	public function savecontentrating() {	
		$model = $this->getModel('Common');
		$model->saveContentRating();
	}
	
	public function applycontentrating() {	
		$this->saveContentRating();
	}
	
	
}

?>

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
defined('_JEXEC') or die;



class jvarcadeController extends JControllerLegacy {

	
	public function display($cachable = false, $urlparams = false)
	{
		
		$vName = $this->input->get('task', 'cpanel');
		
		switch ($vName)
		{
			case 'settings':
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'Common';
				
				break;
				
			case 'manage_scores':
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'manage_scores';
				
				break;
				
			case 'manage_folders':
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'manage_folders';
				
				break;
			
			case 'add_folder':
			case 'edit_folder':
				$vName = 'edit_folder';
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'edit_folder';
				
				break;
				
			case 'manage_games':
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'manage_games';
				
				break;
			
			case 'add_game':
			case 'edit_game':
				$vName = 'edit_game';
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'edit_game';
				
				break;
				
			case 'contests':
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'contests';
				
				break;
				
			case 'add_contest':
			case 'edit_contest':
				$vName = 'edit_contest';
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'edit_contest';
				
				break;
			
			case 'addgametocontest':
				$vName = 'contestlink';
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'Common';
				
				break;
				
			case 'addcontestgames':
				$vName = 'contestlink';
				$vLayout = $this->input->get('layout', 'contestgames', 'string');
				$mName = 'Common';
				
				break;
				
			case 'showcontestgames':
				$vName = 'contestlink';
				$vLayout = $this->input->get('layout', 'games', 'string');
				$mName = 'Common';
				
				break;
				
			case 'showgamecontests':
				$vName = 'contestlink';
				$vLayout = $this->input->get('layout', 'contests', 'string');
				$mName = 'Common';
				
				break;
				
			case 'game_upload':
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'game_upload';
				
				break;
				
			case 'maintenance':
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'Common';
				
				break;
				
			case 'rss':
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'Common';
				
				break;
				
			case 'content_ratings':
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'content_ratings';
				
				break;
				
			case 'add_contentrating':
			case 'edit_contentrating':
				$vName = 'edit_contentrating';
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'edit_contentrating';
				
				break;
				
			case 'cpanel':
			default:
				$vName = 'cpanel';
				$vLayout = $this->input->get('layout', 'default', 'string');
				$mName = 'Common';
				
				break;
			
				
		}
		
		$document = JFactory::getDocument();
		$vType    = $document->getType();
		
		// Get/Create the view
		$view = $this->getView($vName, $vType);
		$view->addTemplatePath(JPATH_COMPONENT_ADMINISTRATOR . '/views/' . strtolower($vName) . '/tmpl');
		
		// Get/Create the model
		if ($model = $this->getModel($mName))
		{
			// Push the model into the view (as default)
			$view->setModel($model, true);
		}
		
		// Set the layout
		$view->setLayout($vLayout);
		
		// Display the view
		$view->display();
		
		return $this;
	}

	public function settingssave() {
		$model = $this->getModel('Common');
		$model->configSave();
	}

	public function savegametocontest() {
		$app = JFactory::getApplication();
		$game_ids = $app->input->getString('game_ids', '');
		$contest_ids = $app->input->getString('contest_ids', '');
		if ($game_ids && $contest_ids) {
			$game_ids = explode(',', $game_ids);
			JArrayHelper::toInteger($game_ids);
			$contest_ids = explode(',', $contest_ids);
			Joomla\Utilities\ArrayHelper::toInteger($contest_ids);
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
	
	public function deletegamefromcontest() {
		$app = JFactory::getApplication();
		$game_ids = $app->input->getString('game_id', '');
		$contest_ids = $app->input->getString('contest_id', '');
		if ($game_ids && $contest_ids) {
			$game_ids = explode(',', $game_ids);
			Joomla\Utilities\ArrayHelper::toInteger($game_ids);
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
		
}

?>

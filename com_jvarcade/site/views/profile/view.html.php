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

class jvarcadeViewProfile extends JViewLegacy {
	
	
	public function display($tpl=null) {
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$pathway = $app->getPathway();
		$doc = JFactory::getDocument();
		
		$user_id = (int)$app->input->get('id');
		$userToProfile = JFactory::getUser($user_id);
		$this->userToProfile = $userToProfile;

		$currentUser = JFactory::getUser();
		$this->user = $currentUser;

		//Score Related
		$userLatestScores = $model->getUserScores($user_id, $limit='LIMIT 5');
		$this->userLatestScores = $userLatestScores;
		$totalScores = $model->getUserScores($user_id);
		$this->totalScores = count($userLatestScores);
		
		$latestScores = $model->getLatestScores();
		$highscores = array();
		foreach ($latestScores as $score) {
			if (!isset($highscores[$score['gameid']])) $highscores[$score['gameid']] = array();
			// get high scores
			if (!count($highscores[$score['gameid']])) {
				if ($score['scoring']) {
					$highscores[$score['gameid']] = $model->getHighestScore($score['gameid'], $score['reverse_score']);
					$highscores[$score['gameid']]['score'] =  round($highscores[$score['gameid']]['score'], 2);
					
				}
			}
		}
		$this->highscores = $highscores;
		
		$counts = array();
		foreach ($highscores as $key=>$subarr){
			if (isset($counts[$subarr['userid']])){
				$counts[$subarr['userid']]++;
			}else $counts[$subarr['userid']] = 1;
		}
		if (isset($counts[$user_id])) {
			$this->totalHighScores = $counts[$user_id];
		}else
		$this->totalHighScores = 0;
		
		//Leaderboard
		$this->lbPos = $model->getLbPos($user_id);
		
		//Gamersafe Achievements
		$achievements = $model->getUserAchievements($user_id);
		$this->achs = $achievements;
		
		//Online
		$useronline = false;
		$check = $model->checkOnline($user_id);
		if ($check) {
			$useronline = true;
		}
		$this->useronline = $useronline;
		
		$this->config = $this->config;
		
		$title = JText::_('COM_JVARCADE_PROFILE_TITLE') . ' - ' . $this->userToProfile->username;
		$pathway->addItem($title);
		$doc->setTitle(($this->config->title ? $this->config->title . ' - ' : '') . $title);
		
		parent::display($tpl);
	}
	
	
}
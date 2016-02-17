<?php
/**
 * @package		jVArcade
 * @version		2.13
 * @date		2016-02-18
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */

defined('_JEXEC') or die;

class jvarcadeViewProfile extends JViewLegacy {
	
	public function display($tpl=null) {
		$app = JFactory::getApplication();
		$model = $this->getModel();
		$user_id = (int)$app->input->get('id');
		$userToProfile = JFactory::getUser($user_id);
		$this->userToProfile = $userToProfile;
		
		$currentUser = JFactory::getUser();
		$this->user = $currentUser;

		//Score Related
		$scores = $model->getUserScores($user_id);
		$this->scores = $scores;
		//var_dump($scores);
		
		//Gamersafe Achievements
		$achievements = $model->getUserAchievements($user_id);
		$this->achs = $achievements;
		//var_dump($achievements);
		
		
		
		$this->config = $this->config;
		
		parent::display($tpl);
	}
	
	
}
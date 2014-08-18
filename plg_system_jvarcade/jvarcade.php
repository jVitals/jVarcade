<?php
/**
 * @package		jVArcade
 * @version		2.1
 * @date		2014-01-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */


defined('_JEXEC') or die( 'Restricted access' );

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

//jimport( 'cms.plugin.plugin' );

class plgSystemJvarcade extends JPlugin {
	var $url = '';
	var $u = '';
	
	function plgSystemJvarcade(&$subject, $config) {
		parent::__construct($subject, $config);
	}

	function onAfterInitialise() {
		$app = JFactory::getApplication();
		$redirect = false;
		
		// First check if the needed files are there. If not we create them and put content in them.
		// They might be missing if user have deleted them or if previous installation of PUArcade has been uninstalled.
		$scripts = array(
			'newscore.php' => '<?php require_once \'./index.php\';',
			'arcade.php' => '<?php require_once \'./index.php\';',
			'crossdomain.xml' => '<?xml version="1.0"?>' . "\n" . '<!DOCTYPE cross-domain-policy SYSTEM "http://www.macromedia.com/xml/dtds/cross-domain-policy.dtd">' . "\n" . '<cross-domain-policy>' . "\n\t" . '<allow-access-from domain="www.mochiads.com" />' . "\n\t" . '<allow-access-from domain="x.mochiads.com" />' . "\n\t" . '<allow-access-from domain="xs.mochiads.com" />' . "\n" . '</cross-domain-policy>' . "\n",
		);
		foreach ($scripts as $filename => $content) {
			if (!file_exists(JPATH_ROOT . DS . $filename)) {
				file_put_contents(JPATH_ROOT . DS . $filename, $content);
			}
		}
		
		
		/* MOCHI CROSSDOMAIN XML */
		
		
		// This file is used by Mochi games.
		if (strpos($_SERVER['REQUEST_URI'], 'crossdomain.xml') !== false) {
			header('Location: ' . JURI::root(true) . '/crossdomain.xml');
			jexit();
		}
		
		
		/* SCORE SUBMITS */
		
		
		// Catch the score submits to /newscore.php
		if (strpos($_SERVER['REQUEST_URI'], 'newscore.php') !== false) {
			$redirect = true;
			$task = 'newscore';
		}
		
		// Catch the score submits to /arcade.php
		if (strpos($_SERVER['REQUEST_URI'], 'arcade.php') !== false) {
			$redirect = true;
			$task = 'arcade';
		}
		
		// Catch any non-standart score submits to /index.php
		if(!in_array($app->input->getWord('task', '' ), array('storepnscore', 'storescore', 'newscore', 'arcade', 'index'))
			&& ((strtolower($app->input->getWord('act', '' )) == 'arcade')
				//|| (strtolower($app->input->getWord('autocom', '')) == 'arcade')
				//|| (strtolower($app->input->getWord('module', '')) == 'pnflashgames')
				//|| (strtolower($app->input->getWord('arcade', '')) == 'storescore')
				//|| (strtolower($app->input->getWord('func', '')) == 'storescore')
			)
		) {
			$redirect = true;
			$task = 'v3';
		}
		
		if(!in_array($app->input->getWord('task', '' ), array('storepnscore', 'storescore', 'newscore', 'arcade', 'index'))
		&& ((strtolower($app->input->getWord('autocom', '' )) == 'arcade'))) {
			$redirect = true;
			$task = 'v32';
		}
		
		if(!in_array($app->input->getWord('task', '' ), array('storepnscore', 'storescore', 'newscore', 'arcade', 'index'))
		&& ((strtolower($app->input->getWord('module', '')) == 'pnflashgames'))) {
			$redirect = true;
			$task = 'pnflash';
		}
		// If we are good to go
		if ($redirect) {
		
			$params = array();
			
			// the absence of this parameter was causing issues in one case
			/*if ( !(isset($_POST['pn_modvalue']) || isset($_GET['pn_modvalue'])) && (strpos($_SERVER['HTTP_REFERER'], 'pn_modvalue') !== false) ) {
				$params[] = 'pn_modvalue=com_jvarcade';
			}*/
			
			// get all the POST and GET parameters and append them to the redirect url
			// skip huge parameters containing ### as they break the other parameters needed for the task detection
			foreach($_POST as $k => $v) {
				if (strpos($v, '####') === false) {
					$k = str_replace(array("\n", "\t", "\r"), ' ', strip_tags(trim($k)));
					$v = str_replace(array("\n", "\t", "\r"), ' ', strip_tags(trim($v)));
					$params[] = "$k=$v";
				}
				unset($_POST[$k]);
			}
			foreach($_GET as $k => $v) {
				if (strpos($v, '####') === false) {
					$k = str_replace(array("\n", "\t", "\r"), ' ', strip_tags(trim($k)));
					$v = str_replace(array("\n", "\t", "\r"), ' ', strip_tags(trim($v)));
					$params[] = "$k=$v";
				}
				unset($_GET[$k]);
			}
		
			$url = JUri::root(true) . '/index.php?option=com_jvarcade&task=score.' . $task . '&' . implode('&', $params);
			
		if ($task == 'v3') {
			$parts = parse_url($url);
    		parse_str($parts['query'], $query);
			$app->redirect(JUri::root(true) . '/index.php?option=com_jvarcade&task=score.' . $task . '&gname=' . $query['gname'] .'&gscore=' . $query['gscore']);
			jexit();
			
		}
		
		if ($task == 'v32') {
			$u = JUri::getInstance($url);
			$u->delVar('autocom');
			$app->redirect($u->toString());
			jexit();
		}
			
		
		if ($task == 'arcade' || 'newscore' || 'pnflash') {
			$u = JUri::getInstance($url);
			$u->delVar('module');
			$app->redirect($u->toString());
			jexit();
		}
			
				
		}

	}

	/**
	 * onPUAHealthCheck - function for system healthcheck purposes
	 * This function is to be used by the arcade - it will determine whether the plugin has been enabled.
	 */
	function onPUAHealthCheck() {
		echo 'Plugin Healthcheck - ' . get_class() . '<br/>';
	}

	/**
	 * onPUABeforeFlashGame    	-  	called just before the flash game is written to the screen
	 *
	 * function onPUABeforeFlashGame( $gameid, $gametitle, $userid, $username ) {
	 * }
	 */

	/**
	 * onPUAAfterFlashGame		-	called just after the flash game is written to the screen
	 *
	 * function onPUAAfterFlashGame( $gameid, $gametitle, $userid, $username ) {
	 * }
	 */

	/**
	 * onPUAHighScoreBeaten	-	called when a highscore for a game has been beaten
	 *
	 * function onPUAHighScoreBeaten( $gameid, $gamename, $olduserid, $oldusername, $newuserid, $newusername, $score ) {
	 * }
	 *
	 */

	/**
	 * onPUAScoreSaved    	-  	called when ever a new score has been saved
	 *
	 * function onPUAScoreSaved( $gameid, $gamename, $newuserid, $newusername, $score) {
	 * }
	 *
	 */

	/**
	 * onPUANewGame			-	called when a new game is uploaded
	 *
	 * function onPUANewGame( $gameid, $gamename, $description, $imagefile, $folderid) {
	 * }
	 *
	 */

	/**
	 * onPUAContestScoreSaved	-	called when a new score for a contest has been saved
	 *
	 * function onPUAContestScoreSaved( $gameid, $gamename, $userid, $username, $score, $contestid, $contestname) {
	 * }
	 *
	 */

	/**
	 * onPUAContestStarted		-	called when a new contest has been published (to advertise)
	 *
	 * function onPUAContestStarted( $contestid, $contestname, $startdatetime, $enddatetime) {
	 * }
	 *
	 */

	/**
	 * onPUAContestEnded		-	called when a contest has completed
	 *
	 * function onPUAContestEnded( $contestid, $contestname, $startdatetime, $enddatetime) {
	 * }
	 *
	 */

}

?>

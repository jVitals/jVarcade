<?php
/**
* jVArcade system plugin. 
* Detailed copyright and licensing information can be found
* in the gpl-3.0.txt file which should be included in the distribution.
* 
* @version		2.02 2013-07-29 nuclear-head
* @copyright	2011 jVitals
* @license		GPLv3 Open Source
* @link			http://jvitals.com
* @package		jVArcade
*/

defined('_JEXEC') or die( 'Restricted access' );

if (!defined('DS')) {
	define('DS', DIRECTORY_SEPARATOR);
}

jimport( 'cms.plugin.plugin' );

class plgSystemJvarcade extends JPlugin {
	
	function plgSystemJvarcade(&$subject, $config) {
		parent::__construct($subject, $config);
	}

	function onAfterInitialise() {
	
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
		if(	!in_array(JRequest::getWord('task', '' ), array('storepnscore', 'storescore', 'newscore', 'arcade', 'index'))
			&& ((strtolower(JRequest::getWord('act', '' )) == 'arcade')
				||	(strtolower(JRequest::getWord('autocom', '')) == 'arcade')
				||	(strtolower(JRequest::getWord('module', '')) == 'pnflashgames')
				||	(strtolower(JRequest::getWord('arcade', '')) == 'storescore')
				||	(strtolower(JRequest::getWord('func', '')) == 'storescore')
			)
		) {
			$redirect = true;
			$task = 'index';
		}
		
		// If we are good to go
		if ($redirect) {
		
			$params = array();
			
			// the absence of this parameter was causing issues in one case
			if ( !(isset($_POST['pn_modvalue']) || isset($_GET['pn_modvalue'])) && (strpos($_SERVER['HTTP_REFERER'], 'pn_modvalue') !== false) ) {
				$params[] = 'pn_modvalue=com_jvarcade';
			}
			
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
			
			header('Location: ' . JURI::root(true) . '/index.php?option=com_jvarcade&task=score.' . $task . '&' . implode('&', $params));
			jexit();		
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

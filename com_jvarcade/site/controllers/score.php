<?php
/**
 * @package		jVArcade
 * @version		2.14
 * @date		2016-03-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die('Restricted access');

//jimport('joomla.application.component.controller');

class jvarcadeControllerScore extends JControllerLegacy {
	private $global_conf;
	private $config;
	private $db;
	private $session;
	private $sname;
	private $sid;
	private $securescoring = 1; // use secure scoring for v32 (it uses the encoded score)
	private $randchar_1 = 1; // these variables will be randomly overwritten (at some future version)...
	private $randchar_2 = 1;

	public function __construct() {
		parent::__construct();
		$this->global_conf = JFactory::getConfig();
		$this->db = JFactory::getDBO();
		$conf = jvarcadeModelCommon::getInst();
		$this->config = $conf->getConf();
	}
	
	/* METHODS HANDLING THE DIFFERENT SUBMISSIONS BASED ON WHICH FILE 
	   THE GAMES SUBMIT TO (arcade.php, newscore.php, index.php)
	*/

	public function arcade() {
		$app = JFactory::getApplication();
		// V3 game
		if (isset($_REQUEST['sessdo'])) {
			$sessdo = strtolower($app->input->getString('sessdo', ''));
			if ($sessdo != '') {
				$this->startSession();
				// work out what we are going to do with this request
				switch($sessdo){
					// called by flash game at start of the session.
					case 'sessionstart' :
						$this->handleV3SessionStart();
						break;
					// flash game wants a signal to know if it is allowed to save the score...
					case 'permrequest' :
						$this->handleV3ScoreRequest();
						break;
					// request to finally save the score.
					case 'burn' :
						$this->handleV3ScoreSubmit();
						break;
				}
			}
		}

		// V32 game
		if (isset($_REQUEST['do'])) {
			$sessdo = strtolower($app->input->getString('do', ''));
			if($sessdo == 'verifyscore') {
				$this->handleV32ScoreRequest();
			}
		}
	}

	public function newscore() {
		$this->startSession();
		$this->handlePhpbbScoreSubmit();
		jexit();
		
	}

	public function v3() {
		$app = JFactory::getApplication();
		// V3 support
		// $act = strtolower($app->input->getWord( 'act', '' ));
		// if($act == 'arcade') {
			$this->startSession();
			$this->handleV3ScoreSubmit();
		// }
	}
	
	public function v32() {
		$app = JFactory::getApplication();
		// V32 support
		// $auto = strtolower($app->input->getWord('autocom', ''));
		$sessdo = strtolower($app->input->getWord('do', ''));
		// if($auto == 'arcade'){
			$this->startSession();
			switch($sessdo) {
				case 'sessionstart' :
					$this->handleV3SessionStart();
					break;
				case 'verifyscore' :
					$this->handleV32ScoreRequest();
					break;
				case 'savescore' :
					$this->handleV32ScoreSubmit();
					break;
			}
		// }
	}
	
	public function pnflash() {
		$app = JFactory::getApplication();
		// pnflashgames support (it seems they can be submitted either by get or post data)
		$module = strtolower($app->input->getWord('module', ''));
		$arcade = strtolower($app->input->getWord('arcade', ''));
		$func = strtolower($app->input->getWord('func', ''));
		if ($module == 'pnflashgames' || $arcade == 'storescore' || $func == 'storescore') {
			$this->startSession();
			
			$this->handlePnflashScoreSubmit();
		}
	}


	/* HELPER METHODS FOR SCORE HANDLING 
	   FROM THE OLD HELPER CLASS 
	*/
	
	public function startSession() {
		$this->session = JFactory::getSession();
		$this->sid = $this->session->getId();
		$this->sname = $this->session->getName();
	}
	
	// Ensures to write session to disk before redirecting
	
	public function redirectPage($url, $sef = false) {
		$app = JFactory::getApplication();
		if ($sef) $url = JRoute::_($url, false);
		//session_write_close();
		$app->redirect($url);
		jexit();
	}
	
	// Handle PhpBB games
	
	public function handlePhpbbScoreSubmit() {
		$app = JFactory::getApplication();
		$game_name = $app->input->getString('game_name', '');
		$gamename = $app->input->getString('gamename', '');
		$gname  = $app->input->getString('gname', '');
		$gscore = $app->input->getFloat('gscore', 0);
		$score = $app->input->getFloat('score', 0);
		$ajaxscore = $app->input->getFloat('ajaxscore', 0);
		
		if (strlen($gamename) > strlen($game_name) && strlen($gamename) > strlen($gname)){
			$jva_gname = $gamename;
		} elseif (strlen($gname) > strlen($gamename) && strlen($gname) > strlen($game_name)){
			$jva_gname = $gname;
		} else {
			$jva_gname = $game_name;
		}
		
		if ($gscore > $score){
			$jva_gscore = $gscore;
		} else {
			$jva_gscore = $score;
		}
		
		
		$this->session->set('session_endtime', time(), 'jvarcade');
		$this->session->set('session_score', strip_tags($jva_gscore), 'jvarcade');
		$this->session->set('session_g', strip_tags($jva_gname), 'jvarcade');
		$this->session->set('ajaxscore', $ajaxscore, 'jvarcade');
		
		$this->redirectPage('index.php?option=com_jvarcade&task=storescore&' . strip_tags($this->sname) . '=' . strip_tags($this->sid));
	}


	// Handle V3 games
	
	public function handleV3SessionStart() {
		$app = JFactory::getApplication();
		$gname = $app->input->getString('gamename', '');
		$starttime = mktime();
		$randnum = rand(1,10);
		$this->session->set('session_g', $gname, 'jvarcade');
		$this->session->set('session_starttime', $starttime, 'jvarcade');
		$this->session->set('session_randnum', $randnum, 'jvarcade');
		// return signal back to flash game
		echo "&connStatus=1&initbar=$randnum&gametime=$starttime&lastid=$gname&result=OK";
		jexit();
	}
	
	public function handleV3ScoreRequest() {
		$app = JFactory::getApplication();
		$score = $app->input->getInt('score', 0);
		$fakekey = $app->input->getString('fakekey', '');
		$mtime = microtime();
		$this->session->set('session_score',$score, 'jvarcade');
		// old signal..
		// echo '&validate=1&microone='. $score .'|'. $fakekey .'&val=x';
		// return signal to be returned back.
		echo '&validate=1&microone=' . $mtime . '&result=OK';
		jexit();
	}
	
	public function handleV3ScoreSubmit() {
		$app = JFactory::getApplication();
		if (isset( $_REQUEST['gscore'])) {
			$score = $app->input->getInt('gscore',0);
			$this->session->set('session_score', $score, 'jvarcade');
		}
		if (isset( $_REQUEST['gname'])) {
			$gname = $app->input->getString('gname','');
			$this->session->set('session_g',$gname, 'jvarcade');
		}
		$this->session->set('session_endtime', time(), 'jvarcade');
		$this->redirectPage('index.php?option=com_jvarcade&task=storescore&' . $this->sname . '=' . strip_tags($this->sid));
	}


	// Handle V32 games
	
	public function handleV32ScoreRequest() {
		// select the random elements and pass back to the game for its encryption routine (cant do this unless we can save them away)
		//$this->randchar_1 = rand(1,10);
		//$this->randchar_2 = rand(1,10);
		echo "&randchar=" . $this->randchar_1 . "&randchar2=" . $this->randchar_2 . "&savescore=1&blah=OK";
	}
	
	public function handleV32ScoreSubmit() {
		$app = JFactory::getApplication();
		$gscore_real = $app->input->getInt('gscore', 0);
		$gscore = $app->input->getInt('enscore', 0);
		$gname = $app->input->getString('gname', '');

		// secure scoring
		if ($this->securescoring ) {
			// as per the code elsewhere...
			//$decodescore = $player_score * $vs['randchar1'] ^ $vs['randchar2'];
			//$gscore = $gscore * $this->randchar_1 ^ $this->randchar_2;
			$gscore = ( $gscore + $this->randchar_2 ) / $this->randchar_1 ;
			// check to see whether the computed score is close to what we are expecting... if so use it.
			if (abs($gscore - $gscore_real) <= 2 ) {
				$gscore = $gscore_real;
			} else {
				$gscore = 0;
			}
		} else {
			$gscore = $gscore_real;
		}
		
		$this->session->set('session_endtime', time(), 'jvarcade');
		$this->session->set('session_score', $gscore, 'jvarcade');
		$this->session->set('session_g', $gname, 'jvarcade');

		$this->redirectPage('index.php?option=com_jvarcade&task=storescore&' . $this->sname . '=' . strip_tags($this->sid));
	}
	
	
	// Handle Pnflash games
	
	public function handlePnflashScoreSubmit() {
		$app = JFactory::getApplication();
		$gid = $app->input->getInt('gid' , 0);
		$func = $app->input->getString('func', '');
		$score = $app->input->getInt('score', null);
		$gamedata = $app->input->getString('gameData', '');
		
		$this->session->set('session_endtime', time(), 'jvarcade');
		$this->session->set('session_g', $gid, 'jvarcade');
		$this->session->set('session_func', $func, 'jvarcade');
		$this->session->set('session_score', $score, 'jvarcade');
		$this->session->set('session_gdata', $gamedata, 'jvarcade');
		
		$this->redirectPage('index.php?option=com_jvarcade&task=storepnscore&' . $this->sname . '=' . strip_tags($this->sid));
	}
	
	/* END HELPER FUNCTIONS */
	
}

?>

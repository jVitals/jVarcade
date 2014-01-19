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


class jvarcadeController extends JControllerLegacy {
	var $_name = 'jvarcade';
	var $global_conf = null;
	var $config = null;

	function __construct() {
		parent::__construct();
		$this->global_conf = JFactory::getConfig();
		$this->db = JFactory::getDBO();
		$conf = jvarcadeModelCommon::getInst();
		$this->config = $conf->getConf();
	}
	/*
	function keepalive() {
		$session = JFactory::getSession();
		$session->set('keepalive', time(), 'jvarcade');
		echo $session->get('keepalive', 0, 'jvarcade');
		exit;
	}
	*/
	
	
	
	
	function home ($cachable = false, $urlparams = false) {
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$model = $this->getModel('Games');
		$view = $this->getView('home', $viewType);
		$view->setModel($model, true);
		$layout = $this->config->flat ? 'flat' : 'default';
		$view->setLayout($layout);
		$view->set('config', $this->config);
		$view->display();
	}
	
	// LISTINGS
	
	function newest() {
		$this->listgames();
	}

	function popular() {
		$this->listgames();
	}

	function favourite() {
		$this->listgames();
	}

	function folder() {
		$this->listgames();
	}

	function showtag() {
		$this->listgames();
	}

	function random() {
		$this->listgames();
	}

	function listgames($cachable = false, $urlparams = false) {
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$model = $this->getModel('Games');
		$view = $this->getView('list', $viewType);
		$view->setModel($model, true);
		$view->setLayout('default');
		$view->set('config', $this->config);
		$view->display();
	}

	function contests($cachable = false, $urlparams = false) {
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$model = $this->getModel('Scores');
		$view = $this->getView('contests', $viewType);
		$view->setModel($model, true);
		$view->setLayout('default');
		$view->set('config', $this->config);
		$view->display();
	}

	function contestdetail($cachable = false, $urlparams = false) {
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$model = $this->getModel('Scores');
		$view = $this->getView('contestdetail', $viewType);
		$view->setModel($model, true);
		$view->setLayout('default');
		$view->set('config', $this->config);
		$view->display($cachable = false, $urlparams = false);
	}
	
	function contestregister() {
		$mainframe = JFactory::getApplication();
		$id = (int)$mainframe->input->get('id');
		$type = (int)$mainframe->input->get('type');
		$user = JFactory::getUser();
		$user_id = (int)$user->id;
		$model = $this->getModel('Scores');
		$model->ContestMembership($id, $user_id, $type);
		$mainframe->redirect(JRoute::_('index.php?option=com_jvarcade&task=contestdetail&id=' . $id, false));
	}
	
	// GAME PAGE RELATED
	
	function game($cachable = false, $urlparams = false) {
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$model = $this->getModel('Games');
		$scores_model = $this->getModel('Scores');
		$view = $this->getView('game', $viewType);
		$view->setModel($model, true);
		$view->setLayout('default');
		$view->set('scores_model', $scores_model);
		$view->set('config', $this->config);
		
		if ((int)$this->config->scoreundergame) {
			ob_start();
			$this->scores(false, false, true);
			$scores_table = ob_get_clean();
			$view->set('scores_table', $scores_table);
		}
		
		$view->display();
	}
	
	function gametags($cachable = false, $urlparams = false) {
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$model = $this->getModel('Games');
		$view = $this->getView('gametags', $viewType);
		$view->setModel($model, true);
		$view->setLayout('default');
		$view->set('config', $this->config);
		$view->display();
	}
	
	function savetag() {
		$mainframe = JFactory::getApplication();
		$user = JFactory::getUser();
		$model = $this->getModel('Games');
		$id = (int)$mainframe->input->get('id');
		
		$ret = 0;
		if ((int)$user->id && (int)$id) {
			$tag = JRequest::getString('tag', '');
			if (strlen($tag)) {
				$count = $model->getTagCount($id, $tag);
				if ($count) {
					$model->updateTagCount($id, $tag);
					$ret = 1;
				} else {
					if ($model->canTagPerms($user)){
						$insertid = $model->addTag($id, $tag);
						if ((int)$insertid) $ret = 1;
					}
				}
			}
		}
		echo $ret;
		exit;
	}
	
	function savefave() {
		$mainframe = JFactory::getApplication();
		$game_id = (int)$mainframe->input->get('id');
		$user = JFactory::getUser();
		$model = $this->getModel('Games');
		$res = '';
		if ($user->id) {
			$res = $model->saveFavourite($game_id, $user->id);
		}
		if ($res) {
			echo '<a href="#" onclick="jQuery.jva.delfave(' . $game_id . '); return false;"> 
					<img src="' . JVA_IMAGES_SITEPATH . 'red_x.png" border="0" alt="" />' . JText::_('COM_JVARCADE_ALREADY_FAVORITE') . 
				'</a>';
		} else {
			echo 'Error Saving Favorite';
		}
	}
	
	function delfave() {
		$mainframe = JFactory::getApplication();
		$game_id = (int)$mainframe->input->get('id');
		$user = JFactory::getUser();
		$model = $this->getModel('Games');

		if ($user->id) {
			$res = $model->delFavourite($game_id, $user->id);
		}
		if ($res) {
			echo '<a href="#" onclick="jQuery.jva.savefave(' . $game_id . '); return false;">
						<img src="' . JVA_IMAGES_SITEPATH . 'plus.png" border="0" hspace="3" alt="" />' . JText::_('COM_JVARCADE_ADD_FAVE') .
					'</a>';
		} else {
			echo 'Error Removing Favorite';
		}
		exit;
	}
	
	function rategame() {
		$mainframe = JFactory::getApplication();
		$game_id = (int)$mainframe->input->get('gid');
		$rating = (float)$mainframe->input->get('rating');
		$user = JFactory::getUser();
		
		$this->db->setQuery('SELECT COALESCE(count(*), 0) as count, COALESCE(total_votes, 0) as total_votes, COALESCE(total_value, 0) as total_value, used_ids 
							FROM #__jvarcade_ratings WHERE gameid = ' . $this->db->Quote($game_id) . ' GROUP BY gameid');
		$result = $this->db->loadObjectList();
		
		if (!is_array($result) || !count($result)) {
			$total_votes = 0;
			$total_value = 0;
			$total_count = 0;
			$used_ids = null;
		} else {
			$result = $result[0];
			$total_votes = $result->total_votes;
			$total_value = $result->total_value;
			$total_count = $result->count;
			$used_ids = unserialize($result->used_ids);
		}
		
		if (!(int)$user->id || (is_array($used_ids) && in_array($user->id, $used_ids))) {
		
			echo "Sorry! You can't vote!";
			
		} else {
		
			$total_value = $total_value + $rating;
			
			$total_votes = ($total_value == 0) ? 1 : ($total_votes + 1);
			
			if (is_array($used_ids)) {
				array_push($used_ids, $user->id);
			} else {
				$used_ids = array($user->id);
			}
			$new_used_ids = serialize($used_ids);
			
			if ($total_count < 1) {
				$sql = 'INSERT INTO #__jvarcade_ratings (total_votes, total_value, used_ids, gameid) 
						VALUES (' . $this->db->Quote($total_votes) . ',' . $this->db->Quote($total_value) . ',' . $this->db->Quote($new_used_ids) . ',' . $this->db->Quote($game_id) . ')';
			} else {
				$sql = 'UPDATE #__jvarcade_ratings SET ' . 
							'total_votes = ' . $this->db->Quote($total_votes) . ', ' . 
							'total_value = ' . $this->db->Quote($total_value) . ',  ' .
							'used_ids = ' . $this->db->Quote($new_used_ids) .
						' WHERE gameid = ' . $this->db->Quote($game_id);

			}
			
			$this->db->setQuery($sql);
			$this->db->Query();

			echo "Vote accepted.";
		}
	}
	
	function reportgame() {
		$mainframe = JFactory::getApplication();
		$game_id = (int)$mainframe->input->get('id');
		$my = JFactory::getUser();
		$user_id_from = $my->id;
		$ret = 'Error repoting game';
		
		if ((int)$game_id && (int)$my->id) {
		
			$this->db->setQuery('SELECT title FROM #__jvarcade_games WHERE id = ' . (int)$game_id);
			$gametitle = $this->db->loadResult();
			$subject = 'jVArcade - Game ' . $gametitle . ' error notification';
			$message = 'Please investigate the game: ' . $gametitle . ' (game ID = ' . $game_id . ') It appears to not be loading/running correctly.';
		
			// send message to super admins
			$this->db->setQuery('SELECT user_id FROM #__user_usergroup_map WHERE group_id = 8');
			$users = $this->db->loadColumn();
			// Joomla PM
			foreach ($users as $user_id_to) {
				$rep = "INSERT INTO #__messages (user_id_from, date_time, user_id_to, subject, message) VALUES (" . $this->db->Quote($user_id_from) . ",NOW()," . $this->db->Quote($user_id_to) . "," . $this->db->Quote($subject) . "," . $this->db->Quote($message) . ")";
				$this->db->setQuery($rep);
				$this->db->execute();
				$this->db->setQuery('SELECT sendEmail FROM #__users WHERE id = ' . $user_id_to . '');
				$config = $this->db->loadResult();
		
				if ($config == 1) {
					// Load the user details (already valid from table check).
					$fromUser = JUser::getInstance($user_id_from);
					$toUser = JUser::getInstance($user_id_to);
					$sitename	= JFactory::getApplication()->getCfg('sitename');
					$gameURL	= JURI::root() . 'index.php?option=com_jvarcade&amp;task=game&amp;id=' . $game_id;
					$subject	= '' . $sitename . ' jVArcade - Game "' . $gametitle . '" error notification';
					$msg		= 'Please investigate the game: ' . $gametitle . '. URL : ' . $gameURL . '. It appears to not be loading/running correctly.';
					JFactory::getMailer()->sendMail($fromUser->email, $fromUser->name, $toUser->email, $subject, $msg);
				}
					
			}
			$ret = JText::_('COM_JVARCADE_REPORT_THANKS');
				
		}
		
		echo $ret;
		exit;
		
		}
		
		/*if ((int)$game_id && (int)$my->id) {
		
			$this->db->setQuery('SELECT title FROM #__jvarcade_games WHERE id = ' . (int)$game_id);
			$gametitle = $this->db->loadResult();
			$subject = "jVArcade - Game '" . $gametitle . "' error notification";
			$body = "Please investigate the game: '". $gametitle . "' (game ID = ". $game_id . ") It appears to not be loading/running correctly.";

			// send message to super admins
			$this->db->setQuery('SELECT id FROM #__users WHERE gid = 25');
			$users = $this->db->loadResultArray();
			// Joomla PM
			require_once (JPATH_SITE . '/administrator/components/com_messages/tables/message.php');
			foreach ($users as $user_id) {
				$msg = new TableMessage($this->db);
				$msg->send($my->id, $user_id, $subject, $body);
			}
			$ret = JText::_('COM_JVARCADE_REPORT_THANKS');
		}
		
		echo $ret;
		exit;
		
	}*/
	
	function downloadgame(){
		$mainframe = JFactory::getApplication();
		$game_id = (int)$mainframe->input->get('id');
		$gd_folder = './arcade/gamedata/'.$gdata['gamename'].'';
		$this->db->setQuery('SELECT gamename, imagename, filename, title, height, width, description, background, mochi, author FROM #__jvarcade_games WHERE id =' . $game_id);
		$gdata = $this->db->loadAssoc();
		
		//build the config file ready for downloading. .json file for mochimedia and .php for everything else.
		if ((int)$gdata['mochi'] == 1){
			$entry = array('slug'=> $gdata['gamename'], 'name'=> $gdata['title'], 'swf_url'=> $gdata['filename'], 'thumbnail_url'=> $gdata['imagename'], 'description'=> $gdata['description'], 'height'=> $gdata['height'], 'width'=> $gdata['width'], 'developer'=> $gdata['author']);
		$cfg_file = $this->global_conf->get('tmp_path') . '/' . $gdata['gamename'] .'.json';
		$cfg_fileHandle = fopen($cfg_file, 'w') or die("can't open file");
		fwrite($cfg_fileHandle, json_encode($entry));
		fclose($cfg_fileHandle);
		
		$archive_file_name = basename($this->global_conf->get('tmp_path') . '/' .'mochi_'.$gdata['gamename'].'.zip');
		}else{
			$config = array(gname => $gdata['gamename'],
						gtitle => $gdata['title'],
						gwords => $gdata['description'],
						object => $gdata['description'],
						gheight => $gdata['height'],
						gwidth => $gdata['width'],
						author => $gdata['author'],
						bgcolor => $gdata['background']
						);
			
			$cfg_file = $this->global_conf->get('tmp_path') . '/' . $gdata['gamename'] .'.php';
			$fh = fopen($cfg_file, 'w') or die("can't open file");
			$data = "<?php\n\n";
			fwrite($fh, $data);
			fclose($fh);
			
			file_put_contents($cfg_file, '$config = ' .var_export($config, TRUE).";\n\n ?>", FILE_APPEND);
			
		$archive_file_name = basename($this->global_conf->get('tmp_path') . '/' . 'game_'.$gdata['gamename'].'.zip');
		}
		

		$zip = new ZipArchive();
	//create the file and throw the error if unsuccessful
	if ($zip->open($archive_file_name, ZIPARCHIVE::CREATE )!==TRUE) {
    	exit("cannot open <$archive_file_name>\n");
	}
	foreach (glob('./images/jvarcade/games/' . $gdata['filename']) as $gfile)
	{
		$new_gfile = substr($gfile,strrpos($gfile,'/') + 1);
  		$zip->addFile($gfile, $new_gfile);
	}
	
	if (file_exists('./images/jvarcade/images/games/' . $gdata['imagename'])){
		$img = substr('./images/jvarcade/images/games/'. $gdata['imagename'],strrpos('./images/jvarcade/images/games/'. $gdata['imagename'],'/') + 1);
		$zip->addFile('./images/jvarcade/images/games/'. $gdata['imagename'], $img);
	}
	
	if (file_exists($gd_folder)) {
    	foreach(glob('./arcade/gamedata/'.$gdata['gamename'].'/*.*') as $file){
		$new_file = substr($file,strrpos($file,'gamedata/') + 0);
		$zip->addFile($file, $new_file);
		}
	}
	if (file_exists($cfg_file)){
		$dl_cfg = basename($cfg_file);
		$zip->addFile($cfg_file, $dl_cfg);
}
	
	$zip->close();
	//then send the headers to foce download the zip file
	header("Content-type: application/zip"); 
	header("Content-Disposition: attachment; filename=$archive_file_name"); 
	header("Pragma: no-cache"); 
	header("Expires: 0"); 
	readfile($archive_file_name);
	// Cleanup tmp directory
	unlink($archive_file_name);
	unlink($cfg_file);
	exit;
	
		
	}
	
	
	// LEADERBOARD
	
	function leaderboard($cachable = false, $urlparams = false) {
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$model = $this->getModel('Scores');
		$view = $this->getView('leaderboard', $viewType);
		$view->setModel($model, true);
		$view->setLayout('default');
		$view->set('config', $this->config);
		$view->display();
	}
	
	// SCORES
	
	function scores ($cachable = false, $urlparams = false, $table_only = false) {
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$model = $this->getModel('Scores');
		$view = $this->getView('scores', $viewType);
		$view->setModel($model, true);
		$view->setLayout('default');
		$view->set('config', $this->config);
		$games_model = $this->getModel('Games');
		$view->set('games_model', $games_model);
		$view->set('table_only', $table_only);
		$view->display();
	}
	
	function latestscores ($cachable = false, $urlparams = false) {
		$document = JFactory::getDocument();
		$viewType = $document->getType();
		$model = $this->getModel('Scores');
		$games_model = $this->getModel('Games');
		$view = $this->getView('latestscores', $viewType);
		$view->setModel($model, true);
		$view->setLayout('default');
		$view->set('config', $this->config);
		$view->set('games_model', $games_model);
		$view->display();
		
	}
	
	function storescore() {
	
		########################################################
		#Return codes:
		#0 - Real Error saving the score
		#1 - Score updated
		#2 - Score not good enough to update
		#3 - Validation/Permission error saving the score
		#4 - If guests aren't allowed to save scores
		#5 - No game ID
		########################################################
		
		$mainframe = JFactory::getApplication();

		$Itemid = JRequest::getInt('Itemid',0);
		if (!$Itemid) {
			$Itemid = $this->getJvarcadeItemID();
			JRequest::setVar('Itemid', $Itemid, 'GET');
			
		}
		
		// functionality to close out popup window
		//~ echo "<script language=\"javascript\" type=\"text/javascript\">
				//~ if (window.name=='PUarcade'){
					//~ window.close();
				//~ }
			//~ </script>";

		$scores_model = $this->getModel('Scores');
		$games_model = $this->getModel('Games');

		$dispatcher = JDispatcher::getInstance();
		$my = JFactory::getUser();
		$session = JFactory::getSession();
		
		$userid = (int)$my->id ? (int)$my->id : 0;
		$username = (int)$my->id ? $my->username : $this->config->guest_name;

		$res = 0;
		$message = '';
		$contest_result = array(0 => false);
		$updatescore = false;
		$isNewChampion = false;
		$ishighscore = false;
		
		// GET GAME RELATED SESSION VARIABLES
		$gname = $session->get('session_g', '', 'jvarcade');
		$score = (int)$session->get('session_score', 0, 'jvarcade');
		$starttime = (int)$session->get('session_starttime', 0 , 'jvarcade');
		$endtime = (int)$session->get('session_endtime', ($starttime + 1), 'jvarcade');
		$endtime = $endtime ? $endtime : ($starttime + 1) ;
		$randnum = (int)$session->get('session_randnum', 0, 'jvarcade');
		$ajaxscore = (int)$session->get('ajaxscore', 0, 'jvarcade');
		
		// GET GAME DETAILS
		$game = $games_model->getGameByName($gname);
		if (!is_array($game)) {
			$game = array();
		}
		$game_id = isset($game['id']) ? (int)$game['id'] : 0;
		$game_title = isset($game['title']) ? $game['title'] : '';
		$folderid = isset($game['folderid']) ? (int)$game['folderid'] : 0;
		$reverse = isset($game['reverse_score']) ? (int)$game['reverse_score'] : 0;
		
		// Get highest and lowest score for the game
		$highscore = $games_model->getHighestScore($game_id, $reverse);
		$highestscore = is_array($highscore) && isset($highscore['score']) ? round($highscore['score'], 2) : false;
		$highestuserid = is_array($highscore) && isset($highscore['userid']) ? (int)$highscore['userid'] : 0;
		$highestusername = is_array($highscore) && isset($highscore['username']) ? $highscore['username'] : '';

		$lowscore = $games_model->getHighestScore($game_id, !$reverse);
		$lowestscore = (is_array($lowscore) && isset($lowscore['score']) ? round($lowscore['score'], 2) : false);
		$lowestscoreid = (is_array($lowscore) && isset($lowscore['id']) ? (int)$lowscore['id'] : 0);
		
		// Get highest score for the game for the current user
		$userhighscore = $games_model->getHighestScore($game_id, $reverse, $userid);
		$userhighestscore = is_array($userhighscore) && isset($userhighscore['score']) ? round($userhighscore['score'], 2) : false;
		
		// VARIOUS CHECKS AND SAVING THE SCORE IF EVERYTHING IS OK
		if (!$game_id) {
			$res = 5;
		} else {
			if ($endtime > $starttime && is_numeric($starttime) ) {
				// ProcessScore
				if (is_numeric($score) && $score == 0) {
					$res = 2;
				} else if (is_numeric($score) && $score != 0) {
					
					if (!(int)$userid && (!(int)$this->config->allow_gplay || !(int)$this->config->allow_gsave)) {
						// Check if guests can play and save
						$res = 4;
					} else {

						// UNSET ALL SESSION VARIABLES RELATED TO SCORING
						$_SESSION['session_g'] = '';
						$_SESSION['session_score' ] = '';
						$_SESSION['session_endtime' ] = -1;
						$_SESSION['session_starttime' ] = -1;
							
						// CHECK IF THIS SCORE IS BETTER THAN USER'S PREVIOUS ONES
						if ($reverse == 1) {
							if ($userhighestscore == false || $score < $userhighestscore) {
								$goodscore = true;
							} else {
								$goodscore = false;
							}
						} else {
							if ($userhighestscore == false || $score > $userhighestscore) {
								$goodscore = true;
							} else {
								$goodscore = false;
							}
						}
						
						if ($goodscore) {
							// CHECK FOR NEW HIGHEST GAME SCORE (CHAMPION)
							$old_highestscore = 0;
							$old_highestuserid = 0;
							if (is_array($highscore) && count($highscore) > 0) {
								// compare with old scores to determine if this is the highest one
								$old_highestscore = $highestscore;
								$old_highestuserid = $highestuserid;
								if ($reverse == 1) {
									if ($old_highestscore > $score) {
										$ishighscore = 1;
									}
								} else {
									if ($old_highestscore < $score) {
										$ishighscore = 1;
									}
								}
							} else {
								// no score history, so we assume we have highest score
								$ishighscore = 1;
							}
							// is this going to be the new highest score?
							if ($ishighscore) {

								// is the champion different?
								if ($old_highestuserid != $userid) {
										
									$oldusername = $highestusername;
									$dispatcher->trigger('onPUAHighScoreBeaten', array($game_id, $game_title, $old_highestuserid, $oldusername, $userid, $username, $score));
									
									// mark this score as a new champion...
									$isNewChampion = 1;
								}
							}
							
							// CHECK IF WE ARE ABOVE THE MAXIMUM SCORES TO KEEP
							$count = $scores_model->gameScoreCount($game_id);
							if ($count < $this->config->table_max) {
								// We are not
								$updatescore = true;
							} else {
								// We are. If the new score is higher than the worst score, 
								// we delete the worst one to make room for the new one
								if ($score > $lowestscore) {
									if (!$scores_model->deleteScore($lowestscoreid)) {
										$res = 0;
									} else {
										$updatescore = true;
									}
								} else {
									$res = 2;
								}
							}
						} else {
							$res = 2;
						}
						
						// SAVE THE SCORE
						if ($updatescore) {
							// fire onPUAScoreSaved event if no champion and the game does not take part in any contest
							$trigger = (!$isNewChampion) && (!$contest_result[0]); 
							// actual saving
							$res = $scores_model->saveScore($game_id, $game_title, $userid, $username, $score, $userhighestscore, $trigger);
						}

						// SAVE THE SCORE FOR ALL THE CONTEST THAT THE GAME TAKES PART IN
						if ((int)$userid) {
							$contest_result = $scores_model->registerScore($game_id, $game_title, $userid, $username, $score, $reverse);
						}
					}
				} else {
					$res = 3;
				}
			} else {
				$res = 3;
			}
			
			// BUILD MESSAGES
			switch ($res) {
				case 1:
					$message = JText::sprintf('COM_JVARCADE_SCORE_SAVED', $score);
					break;
				case 2:
					$message = JText::sprintf('COM_JVARCADE_SCORE_NOT_GOOD_ENOUGH', $score);
					break;
				case 3:
					$message = JText::_('COM_JVARCADE_SCORE_PROBLEM');
					break;
				case 4:
					$message = JText::_('COM_JVARCADE_GUESTS_CANT_SCORE');
					break;
				case 5:
					$message = JText::_('COM_JVARCADE_TECH_TROUBLE');
					break;
				default:
					$message = JText::_('COM_JVARCADE_SCORE_PROBLEM');
					break;
			}
		}

		
		if (!$ajaxscore) {
			$message .= ' <a href="' . JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $game_id . '&fid=' . $folderid . '&Itemid=' . $Itemid) . '">' . JText::_('COM_JVARCADE_PLAY_AGAIN') . '</a>';
			// Redirect to scores page for the game
			$mainframe->redirect(JRoute::_('index.php?option=com_jvarcade&task=scores&id=' . $game_id . '&Itemid=' . $Itemid, false), $message);
		} else {
			$message .= ' <a href="' . JRoute::_('index.php?option=com_jvarcade&task=scores&id=' . $game_id . '&Itemid=' . $Itemid, false) . '" target="_blank">' . JText::_('COM_JVARCADE_SEE_SCORE_FOR_GAME') . '</a>';
			echo $message;
			exit;
		}
	
	}
	
	function storepnscore() {
	
		$mainframe = JFactory::getApplication();

		$Itemid = JRequest::getInt('Itemid',0);
		if (!$Itemid) {
			$Itemid = $this->getJvarcadeItemID();
			JRequest::setVar('Itemid', $Itemid, 'GET');
			
		}
	
		$scores_model = $this->getModel('Scores');
		$games_model = $this->getModel('Games');
		
		$dispatcher = JDispatcher::getInstance();
		$session = JFactory::getSession();
		$my = JFactory::getUser();
		
		$userid = (int)$my->id ? (int)$my->id : 0;
		$username = (int)$my->id ? $my->username : $this->config->guest_name;
		
		$res = 0;
		$message = '';
		$contest_result = array(0 => false);
		$updatescore = false;
		$isNewChampion = false;
		$ishighscore = false;
		
		$game_id = JRequest::getInt('gid', 0);
		$func = JRequest::getString('func');
		$gameData = JRequest::getString('gameData');
		$score = JRequest::getInt('score', 0);
		$endtime = time();
		$starttime = $session->get('session_starttime', 0, 'jvarcade');
		
		// GET GAME DETAILS
		$game = $games_model->getGame($game_id);
		if (!is_array($game)) {
			$game = array();
		}
		$game_title = isset($game['title']) ? $game['title'] : '';
		$folderid = isset($game['folderid']) ? (int)$game['folderid'] : 0;
		$reverse = isset($game['reverse_score']) ? (int)$game['reverse_score'] : 0;
		
		// Get highest and lowest score for the game
		$highscore = $games_model->getHighestScore($game_id, $reverse);
		$highestscore = is_array($highscore) && isset($highscore['score']) ? round($highscore['score'], 2) : false;
		$highestuserid = is_array($highscore) && isset($highscore['userid']) ? (int)$highscore['userid'] : 0;
		$highestusername = is_array($highscore) && isset($highscore['username']) ? $highscore['username'] : '';

		$lowscore = $games_model->getHighestScore($game_id, !$reverse);
		$lowestscore = (is_array($lowscore) && isset($lowscore['score']) ? round($lowscore['score'], 2) : false);
		$lowestscoreid = (is_array($lowscore) && isset($lowscore['id']) ? (int)$lowscore['id'] : 0);
		
		// Get highest score for the game for the current user
		$userhighscore = $games_model->getHighestScore($game_id, $reverse, $userid);
		$userhighestscore = is_array($userhighscore) && isset($userhighscore['score']) ? round($userhighscore['score'], 2) : false;
		
		//PNFlashGames functions
		switch($func){
			case 'storeScore':
			
				// ProcessScore
				if ($endtime > $starttime && is_numeric($starttime) ) {
					if (is_numeric($score) && $score == 0) {
						$res = 2;
					} else if (is_numeric($score) && $score != 0) {
						
						if (!(int)$userid && (!(int)$this->config->allow_gplay || !(int)$this->config->allow_gsave)) {
							// Check if guests can play and save
							$res = 4;
						} else {

							// UNSET ALL SESSION VARIABLES RELATED TO SCORING
							$_SESSION['session_g'] = '';
							$_SESSION['session_score' ] = '';
							$_SESSION['session_endtime' ] = -1;
							$_SESSION['session_starttime' ] = -1;
								
							// CHECK IF THIS SCORE IS BETTER THAN USER'S PREVIOUS ONES
							if ($reverse == 1) {
								if ($userhighestscore == false || $score < $userhighestscore) {
									$goodscore = true;
								} else {
									$goodscore = false;
								}
							} else {
								if ($userhighestscore == false || $score > $userhighestscore) {
									$goodscore = true;
								} else {
									$goodscore = false;
								}
							}
							
							if ($goodscore) {
								// CHECK FOR NEW HIGHEST GAME SCORE (CHAMPION)
								$old_highestscore = 0;
								$old_highestuserid = 0;
								if (is_array($highscore) && count($highscore) > 0) {
									// compare with old scores to determine if this is the highest one
									$old_highestscore = $highestscore;
									$old_highestuserid = $highestuserid;
									if ($reverse == 1) {
										if ($old_highestscore > $score) {
											$ishighscore = 1;
										}
									} else {
										if ($old_highestscore < $score) {
											$ishighscore = 1;
										}
									}
								} else {
									// no score history, so we assume we have highest score
									$ishighscore = 1;
								}
								// is this going to be the new highest score?
								if ($ishighscore) {

									// is the champion different?
									if ($old_highestuserid != $userid) {
											
										$oldusername = $highestusername;
										$dispatcher->trigger('onPUAHighScoreBeaten', array($game_id, $game_title, $old_highestuserid, $oldusername, $userid, $username, $score));
										
										// mark this score as a new champion...
										$isNewChampion = 1;
									}
								}
								
								// CHECK IF WE ARE ABOVE THE MAXIMUM SCORES TO KEEP
								$count = $scores_model->gameScoreCount($game_id);
								if ($count < $this->config->table_max) {
									// We are not
									$updatescore = true;
								} else {
									// We are. If the new score is higher than the worst score, 
									// we delete the worst one to make room for the new one
									if ($score > $lowestscore) {
										if (!$scores_model->deleteScore($lowestscoreid)) {
											$res = 0;
										} else {
											$updatescore = true;
										}
									} else {
										$res = 2;
									}
								}
							} else {
								$res = 2;
							}
							
							// SAVE THE SCORE
							if ($updatescore) {
								// fire onPUAScoreSaved event if no champion and the game does not take part in any contest
								$trigger = (!$isNewChampion) && (!$contest_result[0]); 
								// actual saving
								$res = $scores_model->saveScore($game_id, $game_title, $userid, $username, $score, $userhighestscore, $trigger);
							}

							// SAVE THE SCORE FOR ALL THE CONTEST THAT THE GAME TAKES PART IN
							if ((int)$userid) {
								$contest_result = $scores_model->registerScore($game_id, $game_title, $userid, $username, $score, $reverse);
							}
						}
					} else {
						$res = 3;
					}
				} else {
					$res = 3;
				}
				
				// BUILD MESSAGES
				switch ($res) {
					case 1:
						echo '&opSuccess=true&endvar=1';
						$message = JText::sprintf('COM_JVARCADE_SCORE_SAVED', $score);
						break;
					case 2:
						echo '&opSuccess=false&error=' . JText::sprintf('COM_JVARCADE_SCORE_NOT_GOOD_ENOUGH', $score) . '&endvar=1';
						$message = JText::sprintf('COM_JVARCADE_SCORE_NOT_GOOD_ENOUGH', $score);
						break;
					default:
						echo '&opSuccess=false&error=' . JText::_('COM_JVARCADE_SCORE_PROBLEM') . '&endvar=1';
						$message = JText::_('COM_JVARCADE_SCORE_PROBLEM');
						break;
				}
				$message .= ' <a href="' . JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $game_id . '&fid=' . $folderid . '&Itemid=' . $Itemid) . '">' . JText::_('COM_JVARCADE_PLAY_AGAIN') . '</a>';
				// it's not clear what to do with the message.. shall we redirect?
				echo $message;
				//$mainframe->redirect(JRoute::_('index.php?option=com_jvarcade&task=scores&id=' . $game_id . '&Itemid=' . $Itemid), $message);
				exit;
				break;
				
			case 'saveGame':
			
				if ((int)$my->id){
					if ($scores_model->savePNGame($game_id, (int)$my->id, $gameData)) {
						echo '&opSuccess=true&endvar=1';
					} else  {
						echo '&opSuccess=false&error=SaveGameError&endvar=1';
					}
				} else {
					echo '&opSuccess=false&error=MustLogin&endvar=1';
				}
				exit;
				break;
				
			case 'loadGame':
			
				if ((int)$my->id) {
					$gdata = $scores_model->loadPNGame($game_id, (int)$my->id);
					echo '&opSuccess=true&gameData=' . $gdata . '&endvar=1';
				} else {
					echo '&opSuccess=false&error=MustLogin&endvar=1';
				}
				exit;
				break;
				
			case 'loadGameScores':

				$order = $reverse == 1 ? 'ASC' : 'DESC';
				$scorelist = clsPUArcade::GetScoreXML($game_id, $order, $this->config->display_max);
				echo '&opSuccess=true&gameScores=' . $scorelist . '&endvar=1';
				exit;
				break;
				
			default:
				echo '&opSuccess=false&error=' . JText::_('COM_JVARCADE_SCORE_PROBLEM') . '&endvar=1';
				exit;
				break;
		}
	}
	
	function getJvarcadeItemID() {
		$Itemid1 = 0;
		$Itemid2 = 0;
		$menu = JFactory::getApplication()->getMenu();
		$entry1 = $menu->getItems('link', 'index.php?option=com_jvarcade&view=home&task=home');
		if (is_array($entry1) && is_object($entry1[0])) $Itemid1 = (int)$entry1[0]->id;
		$entry2 = $menu->getItems('link', 'index.php?option=com_jvarcade&view=home');
		if (is_array($entry2) && is_object($entry2[0])) $Itemid2 = (int)$entry2[0]->id;
		return ((int)$Itemid1 ? $Itemid1 : $Itemid2);
	}
	
}

?>
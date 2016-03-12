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

//jimport('joomla.application.component.view');
jimport('joomla.html.pagination');

class jvarcadeViewGame extends JViewLegacy {
	
	var $comment_data;
	
	function display($tpl = null) {
		
		$app = JFactory::getApplication();
		$pathway = $app->getPathway();
		$doc = JFactory::getDocument();
		$user = JFactory::getUser();
		$this->user = $user;
		
		$model = $this->getModel();

		// vars
		$scheme = (strpos(JURI::root(), 'https://') !== false) ? 'https://' : 'http://';
    	$this->scheme = $scheme;
		$sitename = $app->get('sitename');
		$this->sitename = $sitename;
		$folder_id = $app->input->getInt('fid', 0);
		$this->folder_id = $folder_id;

		// game
		$game_id = (int)$app->input->get('id');
		$game = $model->getGame($game_id);
		$game['current_vote'] = ($game['total_value'] > 0 && $game['total_votes'] > 0) ? round($game['total_value']/$game['total_votes'], 1) : 0;
		$this->game = $game;
		$model->increaseNumplayed($game_id);
		
		// Play permissions based on folder permissions 
		$can_play = $model->folderPerms($user, $game['viewpermissions']);
		$this->can_play = $can_play;
		
		// Download permissions
		
		$can_dload = $model->canDloadPerms($user);
		$this->can_dload = $can_dload;

		// bookmarks
		$uri = JURI::getInstance();
		$prefix = $uri->toString(array('host', 'port'));
		$bookmark_url = JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $game_id, false);
		if (!preg_match('#^/#', $bookmark_url)) {
			$bookmark_url = '/' . $bookmark_url;
		}
		$bookmark_url = $scheme . $prefix . $bookmark_url;
		$this->bookmark_url = $bookmark_url;
		
		// contests
		$contests = $this->scores_model->getContestsByGame($game['id'], 1);
		$this->contests = $contests;
		
		// stats
		$scorecount = $this->scores_model->gameScoreCount($game_id);
		$this->scorecount = $scorecount;
		$favoured = $model->getGameFavCount($game_id);
		$this->favoured = $favoured;
		$favoured_by_me = $model->getGameFavCount($game_id, $user->id);
		$this->favoured_by_me = $favoured_by_me;
		$my_fav_count = $model->getMyFavCount($user->id);
		$this->my_fav_count = $my_fav_count;
		$parents = $model->getParents((int)$game['folderid']);
		$folderpath = $this->buildFolders($parents);
		$this->folderpath = $folderpath;
		
		// page title and breadcrumbs
		$doc->setTitle(($this->config->title ? $this->config->title . ' - ' : '') . $game['title']);
		$doc->setDescription(strip_tags($game['description']));
		$this->buildPathway($pathway, $parents, $game['title']);
		
		// Comments
		$db	= JFactory::getDbo();
		$sql = JVA_COMPATIBLE_MODE == '15' 
				? "SELECT `option`, enabled FROM #__components WHERE parent = 0 AND `option` IN ('com_comment', 'com_jcomments', 'com_jacomment')"
				: "SELECT element as `option`, enabled FROM #__extensions WHERE `type` = 'component' AND element IN ('com_comment', 'com_jcomments', 'com_jacomment')";
		$db->setQuery($sql);
		$this->comment_data = $db->loadAssocList('option');
		
		// events
		$dispatcher = JEventDispatcher::getInstance();
		$result = $dispatcher->trigger('onPUABeforeFlashGame', array($game['id'], $game['title'], (int)$user->id, ((int)$user->id ? $user->username : $this->config->guest_name) ));
		
		parent::display($tpl);
		$result = $dispatcher->trigger('onPUAAfterFlashGame', array($game['id'], $game['title'], (int)$user->id, ((int)$user->id ? $user->username : $this->config->guest_name) ));
		
		$session = JFactory::getSession();
		$session->set('session_starttime', time(), 'jvarcade');
		//~ session_write_close();
		
	}
	
	function buildFolders($folders) {
		$folderpath = '<a href=' . JRoute::_('index.php?option=com_jvarcade&task=home') . '>' . stripslashes($this->config->title) . '</a>';
		foreach ($folders as $folder) {
		$folderpath .= ' » <a href=' . JRoute::_('index.php?option=com_jvarcade&task=folder&id=' . $folder['id']) . '>' . $folder['name'] . '</a>';
		}
		return $folderpath;
		
	}
	
	function buildPathway(&$pathway, $folders, $title) {
		foreach ($folders as $folder) {
			$pathway->addItem($folder['name'], JRoute::_('index.php?option=com_jvarcade&task=folder&id=' . $folder['id']));
		}
		$pathway->addItem($title);
	}
	
	function displayContest(&$contest) {
		$return = '';
		
		// if there is no registration or there is and we are registered
		if (!$contest->islimitedtoslots || $contest->userid) {
			// deal with the maximum allowed plays per game in the contest (if any)
			if (!$contest->maxplaycount) {
				$return =  JText::sprintf('COM_JVARCADE_ELIGIBLE_YES', $contest->name);
			} elseif ($contest->maxplaycount && $contest->maxplaycount <= $contest->attemptnum) {
				$return =  JText::sprintf('COM_JVARCADE_ELIGIBLE_NO', $contest->name);
			} elseif ($contest->maxplaycount && $contest->maxplaycount > $contest->attemptnum) {
				$return =  JText::sprintf('COM_JVARCADE_ELIGIBLE_YES_COND', $contest->name, ($contest->maxplaycount - $contest->attemptnum), $contest->maxplaycount);
			}
		} else {
			// there is registration for the contest and we are not registered
			$return =  JText::sprintf('COM_JVARCADE_ELIGIBLE_NOTREGISTERED', $contest->name);
		}
		
		return $return;

	}
	
	function displayComments() {
		$start = '<div class="pu_heading" style="text-align: center;margin: 20px 0 20px 0;">' . JText::_('COM_JVARCADE_COMMENTS') . '</div><div id="comment-block">';
		$end = '</div>';
		if ($this->config->comments == 1 && $this->componentEnabled($this->comment_data, 'com_comment')) {
			// CComment
			
			$path = JPATH_SITE . '/administrator/components/com_comment/plugins/com_jvarcade/jvarcade.php';
			if (file_exists($path)) {
				JLoader::discover('ccommentHelper', JPATH_SITE . '/components/com_comment/helpers');
				echo $start;
				echo CcommentHelperUtils::commentInit('com_jvarcade', $this->game);
				echo $end;
			}
		} elseif ($this->config->comments == 2 && $this->componentEnabled($this->comment_data, 'com_jcomments')) {
			// JComments
			$jcommentspath = JPATH_SITE . '/components/com_jcomments/jcomments.php';
			$jcommentsplugin = JPATH_SITE . '/components/com_jcomments/plugins/com_jvarcade.plugin.php';
			if (file_exists($jcommentspath) && file_exists($jcommentsplugin)) {
				require_once($jcommentspath);
				echo $start;
				echo JComments::show($this->game['id'], 'com_jvarcade', $this->game['gamename']); 
				echo $end;
			}
		} elseif ($this->config->comments == 3 && $this->componentEnabled($this->comment_data, 'com_jacomment')) {
			// JA Comment
			echo $start;
			echo '{jacomment contentid='.$this->game['id'].' option=com_jvarcade contenttitle='.$this->game['gamename'].'}';
			echo $end;
		}
		echo '';
	}
	
	function componentEnabled(&$comment_data, $name) {
		return (is_array($comment_data) && count($comment_data) && isset($comment_data[$name]) && isset($comment_data[$name]['enabled']) && (int)$comment_data[$name]['enabled']);
	}
}

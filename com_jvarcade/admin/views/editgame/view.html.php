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

//jimport('joomla.application.component.view');

class jvarcadeVieweditgame extends JViewLegacy {
	var $folderlist;
	var $contentratinglist;

	function display($tpl = null) {
		
		$model = $this->getModel();
		$app = JFactory::getApplication();
		$task = $app->input->getWord('task', 'editgame');
		$this->assignRef('task', $task);

		if ($task == 'addgame') {
			$game = new stdClass();
			$game->id = 0;
			$game->title = '';
			$game->gamename = '';
			$game->description = '';
			$game->folderid = 0;
			$game->contentratingid = 0;
			$game->imagename = '';
			$game->background = '';
			$game->width = '';
			$game->height = '';
			$game->published = 0;
			$game->numplayed = 0;
			$game->reverse_score = 0;
			$game->scoring = 0;
			$game->gsafe = 0;
			$game->window = 1;
			$game->ajaxscore = 0;
		} else {
			$gameid = array_unique($app->input->get('id', array(), 'array'));
			$gameid = (int)$gameid[0];
			$game = $model->getGames((int)$gameid);
			if (is_array($game)) $game = $game[0];
		}
		$this->assignRef('game', $game);
		
		$this->folderlist = $model->getFolderList();
		$this->contentratinglist = $model->getContentRatingList();
		
		$editor = JFactory::getEditor();
		$this->assignRef('editor', $editor);
		
		$upfile = ($task == 'addgame' ? JText::_('COM_JVARCADE_GAMES_NEWFILE') : JText::_('COM_JVARCADE_GAMES_CHFILE'));
		$upfile_desc = ($task == 'addgame' ? JText::_('COM_JVARCADE_GAMES_NEWFILE_DESC') : JText::_('COM_JVARCADE_GAMES_CHFILE_DESC'));
		$upimage = ($task == 'addgame' ? JText::_('COM_JVARCADE_GAMES_NEWIMAGE') : JText::_('COM_JVARCADE_GAMES_CHIMAGE'));
		$upimage_desc = ($task == 'addgame' ? JText::_('COM_JVARCADE_GAMES_NEWIMAGE_DESC') : JText::_('COM_JVARCADE_GAMES_CHIMAGE_DESC'));
		$this->assignRef('upfile', $upfile);
		$this->assignRef('upfile_desc', $upfile_desc);
		$this->assignRef('upimage', $upimage);
		$this->assignRef('upimage_desc', $upimage_desc);
		
		JToolBarHelper::title(($task == 'addgame' ? JText::_('COM_JVARCADE_GAMES_NEWGAME') : $game->title), 'jvagames');
		JToolBarHelper::custom('manage_games', 'cancel.png', 'cancel.png', JText::_('COM_JVARCADE_GAMES_CANCEL'), false, false);
		JToolBarHelper::save('savegame', JText::_('COM_JVARCADE_GAMES_SAVE'));			
		JToolBarHelper::apply('applygame', JText::_('COM_JVARCADE_GAMES_APPLY'));			
		
		
		parent::display($tpl);
	}
	
	function list_folders($active = '') {
		$list = '<select name="folderid">';
		foreach($this->folderlist as $folder) {
			$list .= '<option value="' . $folder->id . '"' . ($active == $folder->id? ' selected ' : '') . '>' . $folder->name . '</option>';
		}
		$list .= '</select>';
		return $list;
	}
	
	function list_ratings($active = '') {
		$list = '<select name="contentratingid">';
		foreach($this->contentratinglist as $item) {
			$list .= '<option value="' . $item->id . '"' . ($active == $item->id? ' selected ' : '') . '>' . $item->name . '</option>';
		}
		$list .= '</select>';
		return $list;
	}
	
}

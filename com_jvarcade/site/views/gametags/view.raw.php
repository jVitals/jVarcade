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
//jimport('joomla.html.pagination');

class jvarcadeViewGametags extends JViewLegacy {

	function display($tpl = null) {
		
		$mainframe = JFactory::getApplication();
		$user = JFactory::getUser();
		$this->assignRef('user', $user);
		
		$model = $this->getModel();
		
		$game_id = (int)$mainframe->input->get('id');
		$status = (int)$mainframe->input->get('status');
		$Itemid = (int)$mainframe->input->get('Itemid');
		$show_itemid = $Itemid ? '&Itemid=' . $Itemid : '';
		$this->assignRef('show_itemid', $show_itemid);
		$this->assignRef('Itemid', $Itemid);
		$can_tag = $model->canTagPerms($user);
		
		
		// tag cloud
		$tags = $model->getTagData($game_id);
		$min_font_size = 8;
		$max_font_size = 26;
		$minimum_count = 0;
		$maximum_count = 0;
		$this->assignRef('min_font_size', $min_font_size);
		$this->assignRef('max_font_size', $max_font_size);
		$this->assignRef('minimum_count', $minimum_count);
		$this->assignRef('maximum_count', $maximum_count);
		foreach ($tags as $tag) {
			if ($minimum_count > $tag->count) {
				$minimum_count = $tag->count;
			}

			if ($maximum_count < $tag->count) {
				$maximum_count = $tag->count;
			}
		}
		$spread = $maximum_count - $minimum_count;
		if ($spread == 0) {
			$spread = 1;
		}
		$this->assignRef('tags', $tags);
		$this->assignRef('spread', $spread);
		$this->assignRef('game_id', $game_id);
		$this->assignRef('status', $status);
		$this->assignRef('can_tag', $can_tag);
		
		parent::display($tpl);
	}
}

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
defined('_JEXEC') or die('Restricted access');
class jvarcadeToolbarHelper {
	
	public static function addSubmenu($vName = 'cpanel') {
	
		JHtmlSidebar::addEntry(
		JText::_('COM_JVARCADE_CPANEL'), 'index.php?option=com_jvarcade&task=cpanel', $vName == 'cpanel');
		
		JHtmlSidebar::addEntry(
		JText::_('COM_JVARCADE_SETTINGS'), 'index.php?option=com_jvarcade&task=settings', $vName == 'settings');
		
		JHtmlSidebar::addEntry(
		JText::_('COM_JVARCADE_MANAGE_SCORES'), 'index.php?option=com_jvarcade&task=manage_scores', $vName == 'manage_scores');
		
		JHtmlSidebar::addEntry(
		JText::_('COM_JVARCADE_MANAGE_FOLDERS'), 'index.php?option=com_jvarcade&task=manage_folders', $vName == 'manage_folders');
		
		JHtmlSidebar::addEntry(
		JText::_('COM_JVARCADE_MANAGE_GAMES'), 'index.php?option=com_jvarcade&task=manage_games', $vName == 'manage_games');
		
		JHtmlSidebar::addEntry(
		JText::_('COM_JVARCADE_UPLOAD_ARCHIVE'), 'index.php?option=com_jvarcade&task=game_upload', $vName == 'game_upload');
		
		JHtmlSidebar::addEntry(
		JText::_('COM_JVARCADE_MAINTENANCE'), 'index.php?option=com_jvarcade&task=maintenance', $vName == 'maintenance');
		
		JHtmlSidebar::addEntry(
		JText::_('COM_JVARCADE_CONTENT_RATINGS'), 'index.php?option=com_jvarcade&task=content_ratings', $vName == 'content_ratings');
		
		JHtmlSidebar::addEntry(
		JText::_('COM_JVARCADE_CONTESTS'), 'index.php?option=com_jvarcade&task=contests', $vName == 'contests');
		
		JHtmlSidebar::addEntry(
		JText::_('COM_JVARCADE_RSS'), 'index.php?option=com_jvarcade&task=rss', $vName == 'rss');
		
		JHtmlSidebar::addEntry(
		JText::_('COM_JVARCADE_SUPPORT'), 'http://www.jvitals.com/support/support-forum/default-forum/14-jvarcade.html', $vName == 'support');
	
	}
	
}
?>

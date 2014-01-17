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

?>
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="adminForm" id="adminForm" method="post">
	<input type="hidden" name="field_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="savegametocontest" />
	<input type="hidden" name="game_ids" id="game_ids" value="<?php echo $this->game_ids;?>" />
	<div>
	<fieldset class="adminform">
	<h6><?php echo JText::_('COM_JVARCADE_CONTESTSLINK'); ?></h6>
	<table class="admintable">
		<colgroup>
			<col width="150"/>
			<col width="*"/>
		</colgroup>
		<tr>
			<td class="key"><label for="game_titles"><?php echo JText::_('COM_JVARCADE_GAMES_TITLE'); ?></label></td>
			<td><?php echo $this->game_titles; ?></td>
		</tr>
		<tr>
			<td class="key"><label for="contestlist"><?php echo JText::_('COM_JVARCADE_CONTESTS'); ?></label></td>
			<td><?php echo $this->contestlist; ?></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="button" onclick="jQuery.jva.addGameToContest();" value="<?php echo JText::_('COM_JVARCADE_CONTESTSLINK_ADD'); ?>" class="add"></td>
		</tr>
	</table>
	</fieldset>
	</div>
</form>

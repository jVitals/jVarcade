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
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="adminForm" id="customFldForm" method="post">
	<input type="hidden" name="field_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="savegametocontest" />
	<input type="hidden" name="contest_id" id="contest_id" value="<?php echo $this->contest_id;?>" />
	<div class="width-90">
	<fieldset class="adminform">
	<legend><?php echo JText::_('COM_JVARCADE_CONTESTSLINKGAMES'); ?></legend>
	<table class="admintable">
		<colgroup>
			<col width="150"/>
			<col width="*"/>
		</colgroup>
		<tr>
			<td class="key"><label for="gameslist"><?php echo JText::_('COM_JVARCADE_GAMES'); ?></label></td>
			<td><?php echo $this->gameslist; ?></td>
		</tr>
		<tr>
			<td colspan="2" align="center"><input type="button" onclick="jQuery.jva.addContestGames();" value="<?php echo JText::_('COM_JVARCADE_CONTESTSLINK_ADD'); ?>" class="add"></td>
		</tr>
	</table>
	</fieldset>
	</div>
</form>

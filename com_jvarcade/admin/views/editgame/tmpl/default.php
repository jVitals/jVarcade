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
JHtml::_('formbehavior.chosen', 'select');
?>
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="adminForm" id="adminForm" method="post">
	<input type="hidden" name="field_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="savegame" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id" value="<?php echo (int)$this->game->id;?>" />
	<div style="float:left;">
		<fieldset class="adminform">
			<table class="admintable">
				<colgroup>
					<col width="150"/>
					<col width="*"/>
				</colgroup>
				<tr>
					<td class="key"><label for="imagename"><?php echo JText::_('COM_JVARCADE_GAMES_IMAGE'); ?></label></td>
					<td><img src="<?php echo JVA_IMAGES_SITEPATH . 'games/' . $this->game->imagename; ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="image"><?php echo $this->upimage; ?></label></td>
					<td><input type="file" name="image" class="hasTip" title="<?php echo htmlspecialchars($this->upimage . '::' . $this->upimage_desc, ENT_QUOTES, 'UTF-8'); ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="file"><?php echo $this->upfile; ?></label></td>
					<td><input type="file" name="file" class="hasTip" title="<?php echo htmlspecialchars($this->upfile . '::' . $this->upfile_desc, ENT_QUOTES, 'UTF-8'); ?>" /></td>
				</tr>
				<tr>
					<td class="key"><label for="title"><?php echo JText::_('COM_JVARCADE_GAMES_DISPLAY_NAME'); ?></label></td>
					<td><input type="text" id="title" name="title" value="<?php echo $this->game->title;?>" size="40" /></td>
				</tr>
				<tr>
					<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_SYSTEM_NAME') . '::' . JText::_('COM_JVARCADE_GAMES_SYSTEM_NAME_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
						<label for="gamename"><?php echo JText::_('COM_JVARCADE_GAMES_SYSTEM_NAME'); ?></label>
					</td>
					<td class="hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_SYSTEM_NAME') . '::' . JText::_('COM_JVARCADE_GAMES_SYSTEM_NAME_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
						<?php if($this->task == 'addgame'): ?>
						<input type="text" id="gamename" name="gamename" value="<?php echo $this->game->gamename;?>" size="40" />
						<?php else: ?>
						<?php echo $this->game->gamename;?>
						<?php endif; ?>
					</td>
				</tr>
				<tr>
					<td class="key"><label for="folderid"><?php echo JText::_('COM_JVARCADE_GAMES_FOLDER'); ?></label></td>
					<td><?php echo $this->list_folders($this->game->folderid);?></td>
				</tr>
				<tr>
					<td class="key"><label for="contentratingid"><?php echo JText::_('COM_JVARCADE_GAMES_CONTENTRATING'); ?></label></td>
					<td><?php echo $this->list_ratings($this->game->contentratingid);?></td>
				</tr>
				<tr>
					<td class="key" valign="top"><label for="description"><?php echo JText::_('COM_JVARCADE_GAMES_DESCRIPTION'); ?></label></td>
					<td><?php echo $this->editor->display('description', $this->game->description, '70%', '150', '70', '15', false, array()); ?></td>
				</tr>
				<tr>
					<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_BACKGROUND') . '::' . JText::_('COM_JVARCADE_GAMES_BACKGROUND_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
						<label for="background"><?php echo JText::_('COM_JVARCADE_GAMES_BACKGROUND'); ?></label>
					</td>
					<td><input type="text" id="background" name="background" value="<?php echo $this->game->background;?>" size="40" /></td>
				</tr>
				<tr>
					<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_HEIGHT') . '::' . JText::_('COM_JVARCADE_GAMES_HEIGHT_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
						<label for="height"><?php echo JText::_('COM_JVARCADE_GAMES_HEIGHT'); ?></label>
					</td>
					<td><input type="text" id="height" name="height" value="<?php echo $this->game->height;?>" size="40" /></td>
				</tr>
				<tr>
					<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_WIDTH') . '::' . JText::_('COM_JVARCADE_GAMES_WIDTH_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
						<label for="width"><?php echo JText::_('COM_JVARCADE_GAMES_WIDTH'); ?></label>
					</td>
					<td><input type="text" id="width" name="width" value="<?php echo $this->game->width;?>" size="40" /></td>
				</tr>
				<tr>
					<td class="key"><label for="numplayed"><?php echo JText::_('COM_JVARCADE_GAMES_NUMPLAYED'); ?></label></td>
					<td><input type="text" id="numplayed" name="numplayed" value="<?php echo $this->game->numplayed;?>" size="40" /></td>
				</tr>		
				<tr>
					<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_REVERSE_SCORE') . '::' . JText::_('COM_JVARCADE_GAMES_REVERSE_SCORE_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
						<label for="reverse_score"><?php echo JText::_('COM_JVARCADE_GAMES_REVERSE_SCORE'); ?></label>
					</td>
					<td><?php echo JHTML::_('jvarcade.html.booleanlist',  'reverse_score', 'class="inputbox" size="1"', $this->game->reverse_score);?></td>
				</tr>
				<tr>
					<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_SCORING') . '::' . JText::_('COM_JVARCADE_GAMES_SCORING_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
						<label for="scoring"><?php echo JText::_('COM_JVARCADE_GAMES_SCORING'); ?></label>
					</td>
					<td><?php echo JHTML::_('jvarcade.html.booleanlist',  'scoring', 'class="inputbox" size="1"', $this->game->scoring);?></td>
				</tr>
				<tr>
					<td class="key"><label for="published"><?php echo JText::_('COM_JVARCADE_GAMES_PUBLISHED'); ?></label></td>
					<td><?php echo JHTML::_('jvarcade.html.booleanlist',  'published', 'class="inputbox" size="1"', $this->game->published);?></td>
				</tr>
				<tr>
					<td class="key"><label for="mochi"><?php echo JText::_('COM_JVARCADE_GAMES_MOCHI'); ?></label></td>
					<td><?php echo JHTML::_('jvarcade.html.booleanlist',  'mochi', 'class="inputbox" size="1"', $this->game->mochi);?></td>
				</tr>
				<tr>
					<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_AJAXSCORE') . '::' . JText::_('COM_JVARCADE_GAMES_AJAXSCORE_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
						<label for="ajaxscore"><?php echo JText::_('COM_JVARCADE_GAMES_AJAXSCORE'); ?></label>
					</td>
					<td><?php echo JHTML::_('jvarcade.html.booleanlist', 'ajaxscore', 'class="inputbox" size="1"', $this->game->ajaxscore);?></td>
				</tr>
				<tr>
					<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_WINDOW') . '::' . JText::_('COM_JVARCADE_GAMES_WINDOW_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
						<label for="window"><?php echo JText::_('COM_JVARCADE_GAMES_WINDOW'); ?></label></td>
					<td>
					<select name="window" id="window">
						<option value="1"<?php echo (trim($this->game->window) == 1 ? ' selected' : ''); ?>><?php echo JText::_('COM_JVARCADE_MAIN_WINDOW'); ?></option>
						<option value="2"<?php echo (trim($this->game->window) == 2 ? ' selected' : ''); ?>><?php echo JText::_('COM_JVARCADE_NEW_WINDOW'); ?></option>
					</select>
					</td>
				</tr>
		
			</table>
		</fieldset>
		<table>
				<tr>
				<td>
				<td>
				<fieldset class="adminform">
		<h3><?php echo JText::_('COM_JVARCADE_CONTESTSLINK_CONTESTSFORGAME'); ?></h3>
			<?php if ((int)$this->game->id) : ?>
			<input class="btn hasTooltip js-stools-btn-clear" type="button" onclick="jQuery.jva.showAddToContestPopup(<?php echo $this->game->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_CONTESTSLINK_ADDTOCONTESTS'); ?>" >
			<input class="btn hasTooltip js-stools-btn-clear" type="button" onclick="jQuery.jva.deleteGameFromContestMulti(<?php echo $this->game->id; ?>, 'game');" value="<?php echo JText::_('COM_JVARCADE_CONTESTSLINK_REMOVESELECTED'); ?>" >
			<div class="clr"></div>
			<div id="gamecontests"></div>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery.jva.showGameContests(<?php echo $this->game->id; ?>);
				});
			</script>
			<?php endif; ?>
		</fieldset>
		</td>
		</tr>
		</table>
	</div>
	<div style="float:right;">
	<table>
	<tr>
	<td>
	<div style="float:right;">
		<fieldset class="adminform">
		<h3><?php echo JText::_('COM_JVARCADE_MAINTENANCE_GAME'); ?></h3>
			<input class="btn hasTooltip js-stools-btn-clear" type="button" onclick="jQuery.jva.doMaintenance('deleteallscores','game',<?php echo $this->game->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_GAME_DELETEALLSCORES'); ?>">
			<div class="clr" style="margin-top:10px;"></div>
			<input class="btn hasTooltip js-stools-btn-clear" type="button" onclick="jQuery.jva.doMaintenance('deleteguestscores','game',<?php echo $this->game->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_GAME_DELETEGUESTSCORES'); ?>" >
			<div class="clr" style="margin-top:10px;"></div>
			<input class="btn hasTooltip js-stools-btn-clear" type="button" onclick="jQuery.jva.doMaintenance('deletezeroscores','game',<?php echo $this->game->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_GAME_DELETEZEROSCORES'); ?>" >
			<div class="clr" style="margin-top:10px;"></div>
			<input class="btn hasTooltip js-stools-btn-clear" type="button" onclick="jQuery.jva.doMaintenance('clearallratings','game',<?php echo $this->game->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_GAME_CLEARALLRATINGS'); ?>" >
			<div class="clr" style="margin-top:10px;"></div>
			<input class="btn hasTooltip js-stools-btn-clear" type="button" onclick="jQuery.jva.doMaintenance('deletealltags','game',<?php echo $this->game->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_GAME_DELETEALLTAGS'); ?>" >
			<div class="clr" style="margin-top:10px;"></div>
			<input class="btn hasTooltip js-stools-btn-clear" type="button" onclick="jQuery.jva.clearMaintenance();" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CLEARMESSAGES'); ?>" >
			<div class="clr" style="margin-top:10px;"></div>
		</fieldset>
	</div>
	</td>
	</tr>
	</table>
	<div style="float:right;">
		<fieldset class="adminform">
			<div id="maintenance-msg"></div>
		</fieldset>
	</div>
	<div class="clr"></div>
		</form>

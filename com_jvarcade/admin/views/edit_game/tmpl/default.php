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
JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.tooltip');
?>
<style>
.control-label {width: 30%!important;}
</style>
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="adminForm" id="adminForm" method="post">
	<input type="hidden" name="field_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="savegame" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id" value="<?php echo (int)$this->game->id;?>" />
	<?php echo JHtml::_('bootstrap.startTabSet', 'jveditgame', array('active' => 'game_edit'));?>
	<?php echo JHtml::_('bootstrap.addTab', 'jveditgame', 'game_edit', JText::_('Edit Game')); ?>
	<div class="row-fluid">
				<div class="span6">
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="imagename"><?php echo JText::_('COM_JVARCADE_GAMES_IMAGE'); ?></label>
							</div>
							<div class="controls">
								<img src="<?php echo JVA_IMAGES_SITEPATH . 'games/' . $this->game->imagename; ?>" />
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="image"><?php echo $this->upimage; ?></label>
							</div>
							<div class="controls">
								<input type="file" name="image" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars($this->upimage . '</strong><br>' . $this->upimage_desc, ENT_QUOTES, 'UTF-8'); ?>" />
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="file"><?php echo $this->upfile; ?></label>
							</div>
							<div class="controls">
								<input type="file" name="file" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars($this->upfile . '</strong><br>' . $this->upfile_desc, ENT_QUOTES, 'UTF-8'); ?>" />
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="title"><?php echo JText::_('COM_JVARCADE_GAMES_DISPLAY_NAME'); ?></label>
							</div>
							<div class="controls">
								<input type="text" id="title" name="title" value="<?php echo $this->game->title;?>" size="40" />
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="gamename" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_SYSTEM_NAME') . '</strong><br>' . JText::_('COM_JVARCADE_GAMES_SYSTEM_NAME_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_GAMES_SYSTEM_NAME'); ?></label>
							</div>
							<div class="controls">
								<?php if($this->task == 'add_game'): ?>
								<input type="text" id="gamename" name="gamename" value="<?php echo $this->game->gamename;?>" size="40" />
								<?php else: ?>
								<input style='-moz-user-select: none; -webkit-user-select: none; -ms-user-select:none; user-select:none;' unselectable='on' onselectstart='return false;' onmousedown='return false;' type="text" id="gamename" name="gamename" value="<?php echo $this->game->gamename;?>" size="40" />
								<?php endif; ?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="folderid"><?php echo JText::_('COM_JVARCADE_GAMES_FOLDER'); ?></label>
							</div>
							<div class="controls">
								<?php echo $this->list_folders($this->game->folderid);?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="contentratingid"><?php echo JText::_('COM_JVARCADE_GAMES_CONTENTRATING'); ?></label>
							</div>
							<div class="controls">
								<?php echo $this->list_ratings($this->game->contentratingid);?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="description"><?php echo JText::_('COM_JVARCADE_GAMES_DESCRIPTION'); ?></label>
							</div>
							<div class="controls">
								<?php echo $this->editor->display('description', $this->game->description, '70%', '150', '70', '15', false, array()); ?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="background" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_BACKGROUND') . '</strong><br>' . JText::_('COM_JVARCADE_GAMES_BACKGROUND_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_GAMES_BACKGROUND'); ?></label>
							</div>
							<div class="controls">
								<input type="text" id="background" name="background" value="<?php echo $this->game->background;?>" size="40" />
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="height" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_HEIGHT') . '</strong><br>' . JText::_('COM_JVARCADE_GAMES_HEIGHT_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_GAMES_HEIGHT'); ?></label>
							</div>
							<div class="controls">
								<input type="text" id="height" name="height" value="<?php echo $this->game->height;?>" size="40" />
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="width" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_WIDTH') . '</strong><br>' . JText::_('COM_JVARCADE_GAMES_WIDTH_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_GAMES_WIDTH'); ?></label>
							</div>
							<div class="controls">
								<input type="text" id="width" name="width" value="<?php echo $this->game->width;?>" size="40" />
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="numplayed"><?php echo JText::_('COM_JVARCADE_GAMES_NUMPLAYED'); ?></label>
							</div>
							<div class="controls">
								<input type="text" id="numplayed" name="numplayed" value="<?php echo $this->game->numplayed;?>" size="40" />
							</div>
						</div>
					</fieldset>		
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_REVERSE_SCORE') . '</strong><br>' . JText::_('COM_JVARCADE_GAMES_REVERSE_SCORE_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_GAMES_REVERSE_SCORE'); ?></label>
							</div>
							<div class="controls">
								<?php echo JHtml::_('jvarcade.html.booleanlist',  'reverse_score', 'size="1"', $this->game->reverse_score, 'JYES', 'JNO', 'grevscore'); ?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_SCORING') . '</strong><br>' . JText::_('COM_JVARCADE_GAMES_SCORING_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_GAMES_SCORING'); ?></label>
							</div>
							<div class="controls">
								<?php echo JHtml::_('jvarcade.html.booleanlist',  'scoring', 'size="1"', $this->game->scoring, 'JYES', 'JNO', 'gscoring'); ?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="published"><?php echo JText::_('COM_JVARCADE_GAMES_PUBLISHED'); ?></label>
							</div>
							<div class="controls">
								<?php echo JHtml::_('jvarcade.html.booleanlist',  'published', 'size="1"', $this->game->published, 'JYES', 'JNO', 'gpublished');?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="gsafe"><?php echo JText::_('COM_JVARCADE_GAMES_GSAFE'); ?></label>
							</div>
							<div class="controls">
								<?php echo JHtml::_('jvarcade.html.booleanlist',  'gsafe', 'size="1"', $this->game->gsafe, 'JYES', 'JNO', 'gsafe');?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="ajaxscore" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_AJAXSCORE') . '</strong><br>' . JText::_('COM_JVARCADE_GAMES_AJAXSCORE_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_GAMES_AJAXSCORE'); ?></label>
							</div>
							<div class="controls">
								<?php echo JHtml::_('jvarcade.html.booleanlist', 'ajaxscore', 'size="1"', $this->game->ajaxscore, 'JYES', 'JNO', 'ajaxscore'); ?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="window" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_GAMES_WINDOW') . '</strong><br>' . JText::_('COM_JVARCADE_GAMES_WINDOW_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_GAMES_WINDOW'); ?></label>
							</div>
							<div class="controls">
								<select name="window" id="window">
									<option value="1"<?php echo (trim($this->game->window) == 1 ? ' selected' : ''); ?>><?php echo JText::_('COM_JVARCADE_MAIN_WINDOW'); ?></option>
									<option value="2"<?php echo (trim($this->game->window) == 2 ? ' selected' : ''); ?>><?php echo JText::_('COM_JVARCADE_NEW_WINDOW'); ?></option>
									</select>
							</div>
						</div>
					</fieldset>

		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php if ((int)$this->game->id) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'jveditgame', 'contestforgame', JText::_('COM_JVARCADE_CONTESTSLINK_CONTESTSFORGAME')); ?>
	<?php echo JHtml::_('bootstrap.renderModal', 'contestForGame', array('url' => JRoute::_('index.php?option=com_jvarcade&task=addgametocontest&tmpl=component&cid=' . $this->game->id, false), 'title' => 'Add Game To Contest', 'height' => '300', 'width' => '600'));?>
		<div class="row-fluid">
				<fieldset class="form-horizontal">
					
					<input class="btn btn-primary" type="button" onclick="jQuery.jva.showAddToContestPopup();" value="<?php echo JText::_('COM_JVARCADE_CONTESTSLINK_ADDTOCONTESTS'); ?>" >
					<input class="btn btn-primary" type="button" onclick="jQuery.jva.deleteGameFromContestMulti(<?php echo $this->game->id; ?>, 'game');" value="<?php echo JText::_('COM_JVARCADE_CONTESTSLINK_REMOVESELECTED'); ?>" >
					<div class="clr"></div>
					<div id="gamecontests"></div>
					<script type="text/javascript">
							jQuery(document).ready(function(){
							jQuery.jva.showGameContests(<?php echo $this->game->id; ?>);
								});
					</script>
					
				</fieldset>
		</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>
	<?php echo JHtml::_('bootstrap.addTab', 'jveditgame', 'maintenance', JText::_('COM_JVARCADE_MAINTENANCE_GAME')); ?>
	<div class="row-fluid">
		<div class="span6">
			<fieldset class="form-horizontal">
				<input class="btn btn-primary hasTooltip" type="button" onclick="jQuery.jva.doMaintenance('deleteallscores','game',<?php echo $this->game->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_GAME_DELETEALLSCORES'); ?>">
				<div class="clr" style="margin-top:10px;"></div>
				<input class="btn btn-primary hasTooltip" type="button" onclick="jQuery.jva.doMaintenance('deleteguestscores','game',<?php echo $this->game->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_GAME_DELETEGUESTSCORES'); ?>" >
				<div class="clr" style="margin-top:10px;"></div>
				<input class="btn btn-primary hasTooltip" type="button" onclick="jQuery.jva.doMaintenance('deletezeroscores','game',<?php echo $this->game->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_GAME_DELETEZEROSCORES'); ?>" >
				<div class="clr" style="margin-top:10px;"></div>
				<input class="btn btn-primary hasTooltip" type="button" onclick="jQuery.jva.doMaintenance('clearallratings','game',<?php echo $this->game->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_GAME_CLEARALLRATINGS'); ?>" >
				<div class="clr" style="margin-top:10px;"></div>
				<input class="btn btn-primary hasTooltip" type="button" onclick="jQuery.jva.doMaintenance('deletealltags','game',<?php echo $this->game->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_GAME_DELETEALLTAGS'); ?>" >
				<div class="clr" style="margin-top:10px;"></div>
				<input class="btn btn-primary hasTooltip" type="button" onclick="jQuery.jva.clearMaintenance();" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CLEARMESSAGES'); ?>" >
				<div class="clr" style="margin-top:10px;"></div>
		</fieldset>
		<fieldset class="form-horizontal">
			<div id="maintenance-msg"></div>
		</fieldset>
		<div class="clr"></div>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php echo JHtml::_('bootstrap.endTabSet');?>
</form>

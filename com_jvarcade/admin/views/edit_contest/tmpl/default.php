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
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
?>

<style>
.control-label {width: 30%!important;}
</style>
<form enctype="multipart/form-data" action="index.php" name="adminForm" id="adminForm" method="post">
	<input type="hidden" name="field_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="savecontest" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id" value="<?php echo (int)$this->contest->id;?>" />
	<?php echo JHtml::_('bootstrap.startTabSet', 'jveditcontest', array('active' => 'contest_edit'));?>
	<?php echo JHtml::_('bootstrap.addTab', 'jveditcontest', 'contest_edit', JText::_('Edit Contest')); ?>
	<div class="row-fluid">
		<div class="span6">
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="imagename"><?php echo JText::_('COM_JVARCADE_CONTESTS_IMAGE'); ?></label>
					</div>
					<div class="controls">
						<img src="<?php echo JVA_IMAGES_SITEPATH . ($this->contest->imagename ? 'contests/' . $this->contest->imagename : 'cpanel/contests.png') ; ?>" border="0" alt="" />
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="image" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_CHIMAGE') . '</strong><br>' . JText::_('COM_JVARCADE_CONTESTS_CHIMAGE_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo $this->upimage; ?></label>
					</div>
					<div class="controls">
						<input type="file" name="image" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars($this->upimage . '</strong><br>' . $this->upimage_desc, ENT_QUOTES, 'UTF-8'); ?>" />
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="name"><?php echo JText::_('COM_JVARCADE_CONTESTS_NAME'); ?></label>
					</div>
					<div class="controls">
						<input type="text" id="name" name="name" value="<?php echo $this->contest->name;?>" size="40" />
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="description"><?php echo JText::_('COM_JVARCADE_CONTESTS_DESCRIPTION'); ?></label>
					</div>
					<div class="controls">
						<?php echo $this->editor->display('description', $this->contest->description, '95%', '250', '70', '15', false, array()); ?>
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="startdatetime" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_START') . '</strong><br>' . JText::_('COM_JVARCADE_CONTESTS_START_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_CONTESTS_START'); ?></label>
					</div>
					<div class="controls">
						<?php echo JHTML::_('calendar', $this->contest->startdatetime, 'startdatetime', 'startdatetime', '%Y-%m-%d %H:%M:%S', array('size'=>'40',  'maxlength'=>'19')); ?>
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="enddatetime" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_END') . '</strong><br>' . JText::_('COM_JVARCADE_CONTESTS_END_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_CONTESTS_END'); ?></label>
					</div>
					<div class="controls">
						<?php echo JHTML::_('calendar', $this->contest->enddatetime, 'enddatetime', 'enddatetime', '%Y-%m-%d %H:%M:%S', array('size'=>'40',  'maxlength'=>'19')); ?>
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="islimitedtoslots" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_SLOTS') . '</strong><br>' . JText::_('COM_JVARCADE_CONTESTS_SLOTS_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_CONTESTS_SLOTS'); ?></label>
					</div>
					<div class="controls">
						<input type="text" id="islimitedtoslots" name="islimitedtoslots" value="<?php echo $this->contest->islimitedtoslots;?>" size="40" />
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="maxplaycount" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_GAMECOUNT') . '</strong><br>' . JText::_('COM_JVARCADE_CONTESTS_GAMECOUNT_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_CONTESTS_GAMECOUNT'); ?></label>
					</div>
					<div class="controls">
						<input type="text" id="maxplaycount" name="maxplaycount" value="<?php echo $this->contest->maxplaycount;?>" size="40" />
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="published"><?php echo JText::_('COM_JVARCADE_CONTESTS_PUBLISHED'); ?></label>
					</div>
					<div class="controls">
						<?php echo JHTML::_('jvarcade.html.booleanlist',  'published', 'class="inputbox" size="1"', $this->contest->published, 'JYES', 'JNO', 'published');?>
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="hasadvertisedstarted" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_STARTADV') . '</strong><br>' . JText::_('COM_JVARCADE_CONTESTS_STARTADV_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_CONTESTS_STARTADV'); ?></label>
					</div>
					<div class="controls">
						<?php echo JHTML::_('jvarcade.html.booleanlist',  'hasadvertisedstarted', 'class="inputbox" size="1"', $this->contest->hasadvertisedstarted, 'JYES', 'JNO', 'adstart');?>
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="hasadvertisedended" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_ENDADV') . '</strong><br>' . JText::_('COM_JVARCADE_CONTESTS_ENDADV_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_CONTESTS_ENDADV'); ?></label>
					</div>
					<div class="controls">
						<?php echo JHTML::_('jvarcade.html.booleanlist',  'hasadvertisedended', 'class="inputbox" size="1"', $this->contest->hasadvertisedended, 'JYES', 'JNO', 'adend');?>
					</div>
				</div>
			</fieldset>
		</div>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php if ((int)$this->contest->id) : ?>
	<?php echo JHtml::_('bootstrap.addTab', 'jveditcontest', 'gameforcontest', JText::_('COM_JVARCADE_CONTESTSLINK_GAMESINCONTEST')); ?>
	<?php echo JHtml::_('bootstrap.renderModal', 'gameForContest', array('url' => JRoute::_('index.php?option=com_jvarcade&task=addcontestgames&tmpl=component&cid=' . $this->contest->id,false), 'title' => 'Add Game To Contest', 'height' => '300', 'width' => '600'));?>
	<div class="row-fluid">
			<fieldset class="form-horizontal">
				
				<input type="button" onclick="jQuery.jva.showAddGamesPopup();" value="<?php echo JText::_('COM_JVARCADE_CONTESTSLINK_ADDGAMES'); ?>" class="btn btn-primary">
				<input type="button" onclick="jQuery.jva.deleteGameFromContestMulti(<?php echo $this->contest->id; ?>, 'contest');" value="<?php echo JText::_('COM_JVARCADE_CONTESTSLINK_REMOVESELECTED'); ?>" class="btn btn-primary">
				<div class="clr"></div>
				<div id="contestgames"></div>
				<script type="text/javascript">
					jQuery(document).ready(function(){
					jQuery.jva.showContestGames(<?php echo $this->contest->id; ?>);
						});
				</script>
				
			</fieldset>
	</div>
	<?php echo JHtml::_('bootstrap.endTab'); ?>
	<?php endif; ?>
	<?php echo JHtml::_('bootstrap.addTab', 'jveditcontest', 'maintenance', JText::_('COM_JVARCADE_MAINTENANCE_CONTEST')); ?>
	<div class="row-fluid">
		<div class="span6">
			<fieldset class="form-horizontal">
				<input type="button" onclick="jQuery.jva.doMaintenance('deleteallscores','contest',<?php echo $this->contest->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_DELETEALLSCORES'); ?>" class="btn btn-primary hasTooltip">
				<div class="clr" style="margin-top:10px;"></div>
				<input type="button" onclick="jQuery.jva.doMaintenance('deleteguestscores','contest',<?php echo $this->contest->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_DELETEGUESTSCORES'); ?>" class="btn btn-primary hasTooltip">
				<div class="clr" style="margin-top:10px;"></div>
				<input type="button" onclick="jQuery.jva.doMaintenance('deletezeroscores','contest',<?php echo $this->contest->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_DELETEZEROSCORES'); ?>" class="btn btn-primary hasTooltip">
				<div class="clr" style="margin-top:10px;"></div>
				<input type="button" onclick="jQuery.jva.doMaintenance('recalculateleaderboard','contest',<?php echo $this->contest->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_RECALCULATELEADERBOARD'); ?>" class="btn btn-primary hasTooltip">
				<div class="clr" style="margin-top:10px;"></div>
				<input type="button" onclick="jQuery.jva.clearMaintenance();" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CLEARMESSAGES'); ?>" class="btn btn-primary hasTooltip">
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


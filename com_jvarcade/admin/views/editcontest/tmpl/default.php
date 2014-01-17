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
JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.multiselect');
JHtml::_('formbehavior.chosen', 'select');
?>
<script type="text/javascript">
	if (typeof Joomla != 'undefined') {
		Joomla.submitbutton = function(pressbutton) {
			if (pressbutton == 'contests') {
				Joomla.submitform(pressbutton);
			} else {
				if (jQuery.jva.validateContest()) {
					Joomla.submitform(pressbutton);    
				}
			}
		}
	} else {
		function submitbutton(pressbutton) {
			if (pressbutton == 'contests') {
				submitform(pressbutton);
			} else {
				if (jQuery.jva.validateContest()) {
					submitform(pressbutton);    
				}
			}
		}
	}
</script>
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="adminForm" id="adminForm" method="post" onsubmit="return false;">
	<input type="hidden" name="field_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="savecontent" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id" value="<?php echo (int)$this->contest->id;?>" />
	<div style="float:left;">
	<fieldset class="adminform">
	<table class="admintable">
		<tr>
			<td class="key"><label for="imagename"><?php echo JText::_('COM_JVARCADE_CONTESTS_IMAGE'); ?></label></td>
			<td>
				<img src="<?php echo JVA_IMAGES_SITEPATH . ($this->contest->imagename ? 'contests/' . $this->contest->imagename : 'cpanel/contests.png') ; ?>" border="0" alt="" />
			</td>
		</tr>
		<tr>
			<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_CHIMAGE') . '::' . JText::_('COM_JVARCADE_CONTESTS_CHIMAGE_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
				<label for="image"><?php echo $this->upimage; ?></label>
			</td>
			<td><input type="file" name="image" class="hasTip" title="<?php echo htmlspecialchars($this->upimage . '::' . $this->upimage_desc, ENT_QUOTES, 'UTF-8'); ?>" /></td>
		</tr>
		<tr>
			<td class="key"><label for="name"><?php echo JText::_('COM_JVARCADE_CONTESTS_NAME'); ?></label></td>
			<td><input type="text" id="name" name="name" value="<?php echo $this->contest->name;?>" size="40" /></td>
		</tr>
		<tr>
			<td class="key" valign="top"><label for="description"><?php echo JText::_('COM_JVARCADE_CONTESTS_DESCRIPTION'); ?></label></td>
			<td><?php echo $this->editor->display('description', $this->contest->description, '95%', '250', '70', '15', false, array()); ?></td>
		</tr>
		<tr>
			<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_START') . '::' . JText::_('COM_JVARCADE_CONTESTS_START_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
				<label for="startdatetime"><?php echo JText::_('COM_JVARCADE_CONTESTS_START'); ?></label>
			</td>
			<td><?php echo JHTML::_('calendar', $this->contest->startdatetime, 'startdatetime', 'startdatetime', '%Y-%m-%d %H:%M:%S', array('size'=>'40',  'maxlength'=>'19')); ?></td>
		</tr>
		<tr>
			<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_END') . '::' . JText::_('COM_JVARCADE_CONTESTS_END_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
				<label for="enddatetime"><?php echo JText::_('COM_JVARCADE_CONTESTS_END'); ?></label>
			</td>
			<td><?php echo JHTML::_('calendar', $this->contest->enddatetime, 'enddatetime', 'enddatetime', '%Y-%m-%d %H:%M:%S', array('size'=>'40',  'maxlength'=>'19')); ?></td>
		</tr>
		<tr>
			<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_SLOTS') . '::' . JText::_('COM_JVARCADE_CONTESTS_SLOTS_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
				<label for="islimitedtoslots"><?php echo JText::_('COM_JVARCADE_CONTESTS_SLOTS'); ?></label>
			</td>
			<td><input type="text" id="islimitedtoslots" name="islimitedtoslots" value="<?php echo $this->contest->islimitedtoslots;?>" size="40" /></td>
		</tr>
		<tr>
			<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_GAMECOUNT') . '::' . JText::_('COM_JVARCADE_CONTESTS_GAMECOUNT_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
				<label for="maxplaycount"><?php echo JText::_('COM_JVARCADE_CONTESTS_GAMECOUNT'); ?></label>
			</td>
			<td><input type="text" id="maxplaycount" name="maxplaycount" value="<?php echo $this->contest->maxplaycount;?>" size="40" /></td>
		</tr>
		<tr>
			<td class="key"><label for="published"><?php echo JText::_('COM_JVARCADE_CONTESTS_PUBLISHED'); ?></label></td>
			<td><?php echo JHTML::_('jvarcade.html.booleanlist',  'published', 'class="inputbox" size="1"', $this->contest->published);?></td>
		</tr>
		<tr>
			<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_STARTADV') . '::' . JText::_('COM_JVARCADE_CONTESTS_STARTADV_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
				<label for="hasadvertisedstarted"><?php echo JText::_('COM_JVARCADE_CONTESTS_STARTADV'); ?></label>
			</td>
			<td><?php echo JHTML::_('jvarcade.html.booleanlist',  'hasadvertisedstarted', 'class="inputbox" size="1"', $this->contest->hasadvertisedstarted);?></td>
		</tr>
		<tr>
			<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTESTS_ENDADV') . '::' . JText::_('COM_JVARCADE_CONTESTS_ENDADV_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
				<label for="hasadvertisedended"><?php echo JText::_('COM_JVARCADE_CONTESTS_ENDADV'); ?></label>
			</td>
			<td><?php echo JHTML::_('jvarcade.html.booleanlist',  'hasadvertisedended', 'class="inputbox" size="1"', $this->contest->hasadvertisedended);?></td>
		</tr>
	</table>
	</fieldset>
	</div>
	<div style="float:right;">
	<table>
	<tr>
	<td>
	<fieldset class="adminform">
	<h3><?php echo JText::_('COM_JVARCADE_CONTESTSLINK_GAMESINCONTEST'); ?></h3>
	<?php if ((int)$this->contest->id) : ?>
	<input type="button" onclick="jQuery.jva.showAddGamesPopup(<?php echo $this->contest->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_CONTESTSLINK_ADDGAMES'); ?>" class="btn hasTooltip js-stools-btn-clear">
	<input type="button" onclick="jQuery.jva.deleteGameFromContestMulti(<?php echo $this->contest->id; ?>, 'contest');" value="<?php echo JText::_('COM_JVARCADE_CONTESTSLINK_REMOVESELECTED'); ?>" class="btn hasTooltip js-stools-btn-clear">
	<div class="clr"></div>
	<div id="contestgames"></div>
	<script type="text/javascript">
		jQuery(document).ready(function(){
			jQuery.jva.showContestGames(<?php echo $this->contest->id; ?>);
		});
	</script>
	<?php endif; ?>
	</fieldset>
	</div>
	</td>
	</tr>
	<tr>
	<td>
	<div style="float:left;">
		<fieldset class="adminform">
		<h3><?php echo JText::_('COM_JVARCADE_MAINTENANCE_CONTEST'); ?></h3>
			<input type="button" onclick="jQuery.jva.doMaintenance('deleteallscores','contest',<?php echo $this->contest->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_DELETEALLSCORES'); ?>" class="btn hasTooltip js-stools-btn-clear">
			<div class="clr" style="margin-top:10px;"></div>
			<input type="button" onclick="jQuery.jva.doMaintenance('deleteguestscores','contest',<?php echo $this->contest->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_DELETEGUESTSCORES'); ?>" class="btn hasTooltip js-stools-btn-clear">
			<div class="clr" style="margin-top:10px;"></div>
			<input type="button" onclick="jQuery.jva.doMaintenance('deletezeroscores','contest',<?php echo $this->contest->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_DELETEZEROSCORES'); ?>" class="btn hasTooltip js-stools-btn-clear">
			<div class="clr" style="margin-top:10px;"></div>
			<input type="button" onclick="jQuery.jva.doMaintenance('recalculateleaderboard','contest',<?php echo $this->contest->id; ?>);" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_RECALCULATELEADERBOARD'); ?>" class="btn hasTooltip js-stools-btn-clear">
			<div class="clr" style="margin-top:10px;"></div>
			<input type="button" onclick="jQuery.jva.clearMaintenance();" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CLEARMESSAGES'); ?>" class="btn hasTooltip js-stools-btn-clear">
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


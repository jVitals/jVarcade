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
<?php if(!empty( $this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>
<div class="width-30" style="float:left;">
	<fieldset class="adminform">
	<!-- domigration removed as puarcade migration wont be used in J3.2+, consider creating migration archive system from J2.5 to J3.2+
	existing migration models, controllers and jquery MUST be left in place for future reference!!! -->
		<!-- input type="button" onclick="jQuery.jva.doMigration(1);" value=" --><?php //echo JText::_('COM_JVARCADE_MAINTENANCE_MIGRATION'); ?><!--  " class="btn hasTooltip js-stools-btn-clear" -->
		<!-- <div class="clr" style="margin-top:10px;"></div>-->
		<input type="button" onclick="jQuery.jva.doMaintenance('deleteallscores','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_DELETEALLSCORES'); ?>" class="btn hasTooltip js-stools-btn-clear">
		<div class="clr" style="margin-top:10px;"></div>
		<input type="button" onclick="jQuery.jva.doMaintenance('deleteguestscores','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_DELETEGUESTSCORES'); ?>" class="btn hasTooltip js-stools-btn-clear">
		<div class="clr" style="margin-top:10px;"></div>
		<input type="button" onclick="jQuery.jva.doMaintenance('deletezeroscores','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_DELETEZEROSCORES'); ?>" class="btn hasTooltip js-stools-btn-clear">
		<div class="clr" style="margin-top:10px;"></div>
		<input type="button" onclick="jQuery.jva.doMaintenance('deleteblankscores','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_DELETEBLANKSCORES'); ?>" class="btn hasTooltip js-stools-btn-clear">
		<div class="clr" style="margin-top:10px;"></div>
		<input type="button" onclick="jQuery.jva.doMaintenance('clearallratings','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CLEARALLRATINGS'); ?>" class="btn hasTooltip js-stools-btn-clear">
		<div class="clr" style="margin-top:10px;"></div>
		<input type="button" onclick="jQuery.jva.doMaintenance('deletealltags','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_DELETEALLTAGS'); ?>" class="btn hasTooltip js-stools-btn-clear">
		<div class="clr" style="margin-top:10px;"></div>
		<input type="button" onclick="jQuery.jva.doMaintenance('recalculateleaderboard','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_RECALCULATELEADERBOARD'); ?>" class="btn hasTooltip js-stools-btn-clear">
		<div class="clr" style="margin-top:10px;"></div>
		<input type="button" onclick="jQuery.jva.doMaintenance('supportdiagnostics','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_SUPPORTDIAGNOSTICS'); ?>" class="btn hasTooltip js-stools-btn-clear">
		<div class="clr" style="margin-top:10px;"></div>
		<input type="button" onclick="jQuery.jva.clearMaintenance();" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CLEARMESSAGES'); ?>" class="btn hasTooltip js-stools-btn-clear">
		<div class="clr" style="margin-top:10px;"></div>
	</fieldset>
</div>
<div style="float:right;">
	<fieldset class="adminform">
	<div id="maintenance-msg"></div>
	</fieldset>
</div>
<div class="clr" style="margin-top:10px;"></div>


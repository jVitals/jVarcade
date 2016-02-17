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
?>
<div class="row-fluid">
	<?php if(!empty( $this->sidebar)): ?>
	<div id="j-sidebar-container" class="span2">
		<?php echo $this->sidebar; ?>
	</div>
	<div id="j-main-container" class="span10">
	<?php else : ?>
	<div id="j-main-container">
	<?php endif;?>
	<div class="row-fluid">
		<div class="span3">
			<fieldset class="form-horizontal">
				<?php /* doMigration removed as PUarcade migration wont be used in J3.2+, consider creating migration archive system from J2.5 to J3.2+	
						Existing migration models, controllers and jquery MUST be left in place for future reference !!! */ ?>
				<!--div class="control-group">
					<div class="control-label">
						<input type="button" onclick="jQuery.jva.doMigration(1);" value="<?php //echo JText::_('COM_JVARCADE_MAINTENANCE_MIGRATION'); ?>" class="btn btn-primary">
					</div>
				</div-->
				<div class="control-group">
					<div class="control-label">
						<input type="button" onclick="jQuery.jva.doMaintenance('deleteallscores','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_DELETEALLSCORES'); ?>" class="btn btn-primary">
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<input type="button" onclick="jQuery.jva.doMaintenance('deleteguestscores','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_DELETEGUESTSCORES'); ?>" class="btn btn-primary">
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<input type="button" onclick="jQuery.jva.doMaintenance('deletezeroscores','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_DELETEZEROSCORES'); ?>" class="btn btn-primary">
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<input type="button" onclick="jQuery.jva.doMaintenance('deleteblankscores','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_DELETEBLANKSCORES'); ?>" class="btn btn-primary">
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<input type="button" onclick="jQuery.jva.doMaintenance('clearallratings','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CLEARALLRATINGS'); ?>" class="btn btn-primary">
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<input type="button" onclick="jQuery.jva.doMaintenance('deletealltags','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_DELETEALLTAGS'); ?>" class="btn btn-primary">
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<input type="button" onclick="jQuery.jva.doMaintenance('recalculateleaderboard','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_RECALCULATELEADERBOARD'); ?>" class="btn btn-primary">
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<input type="button" onclick="jQuery.jva.doMaintenance('supportdiagnostics','global');" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_SUPPORTDIAGNOSTICS'); ?>" class="btn btn-primary">
					</div>
				</div>
				<div class="control-group">
					<div class="control-label">
						<input type="button" onclick="jQuery.jva.clearMaintenance();" value="<?php echo JText::_('COM_JVARCADE_MAINTENANCE_CLEARMESSAGES'); ?>" class="btn btn-primary">
					</div>
				</div>
			</fieldset>
		</div>
		<div class="span9">
			<fieldset class="form-horizontal">
				<div id="maintenance-msg"></div>
			</fieldset>
		</div>
	</div>
</div>
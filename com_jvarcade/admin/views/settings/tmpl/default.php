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
.control-label {width: 40%!important;}
</style>
<form action="<?php echo JRoute::_('index.php?option=com_config'); ?>" name="adminForm" id="adminForm" method="post">
	<input type="hidden" name="config_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="settings" />
	<div class="row-fluid">
		<?php if(!empty($this->sidebar)): ?>
		<div id="j-sidebar-container" class="span2">
			<?php echo $this->sidebar; ?>
		</div>
		<div id="j-main-container" class="span10">
		<?php else : ?>
		<div id="j-main-container">
		<?php endif;?>
		<?php echo JHtml::_('bootstrap.startTabSet', 'jvsetstabset', array('active' => 'jvsetstabset_1'));?>
		<?php $grp = 1; foreach ($this->conf as $group => $conf) : ?>
			<?php echo JHtml::_('bootstrap.addTab', 'jvsetstabset', 'jvsetstabset_' . $grp, JText::_('COM_JVARCADE_GRP_' . strtoupper($group))); ?>
			<div class="row-fluid">
				<div class="span6">
					<fieldset class="form-horizontal">
						<?php foreach ($conf as $arr) : 
							$label = JText::_('COM_JVARCADE_OPT_' . strtoupper($arr['optname']));
							$description = (trim($arr['description'])) ? JText::_(trim($arr['description'])) : '';
						?>
						<div class="control-group">
							<div class="control-label">
								<label data-original-title="<strong><?php echo $label; ?></strong><?php echo ($description ? '<br />' . $description : ''); ?>" id="<?php echo $arr['optname']; ?>-lbl" for="<?php echo $arr['optname']; ?>" <?php echo ($description ? 'class="hasTooltip"': ''); ?> title=""><?php echo $label; ?></label>
							</div>
							<div class="controls">
								<?php echo $this->showSetting($arr); ?>
							</div>
						</div>
						<?php endforeach; ?>
					</fieldset>
				</div>
				<?php if($group == 'integration'): ?>
				<div class="span6">
					<fieldset class="form-horizontal">
						<?php echo $this->showCommentsLegend($arr['optname']); ?>
					</fieldset>
				</div>
				<?php endif;?>
			</div>
			<?php echo JHtml::_('bootstrap.endTab'); $grp ++; ?>
		<?php endforeach;?>
		<?php echo JHtml::_('bootstrap.endTabSet');?>
	</div>
</form>
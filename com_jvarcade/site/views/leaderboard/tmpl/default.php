<?php
/**
 * @package		jVArcade
 * @version		2.14
 * @date		2016-03-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die;

?>
<form action="index.php" method="get" name="adminForm">
<div id="puarcade_wrapper">
	
	<?php include_once(JVA_TEMPLATES_INCPATH . 'menu.php'); ?>

	<div class="pu_heading" style="text-align: center;"><?php echo $this->tabletitle ?></div>
	<?php if (is_array($this->leaderboard) && count($this->leaderboard)) : ?>
		<div class="pu_ListContainer">
			<table class="pu_ListHeader">
				<tr>
					<th width="20%" style="text-align: center">
						<?php echo JText::_('COM_JVARCADE_LEADERBOARD_PLACE'); ?>
					</th> 
					<th width="20%" style="text-align: center;">
						<?php echo JText::_('COM_JVARCADE_AVATAR'); ?>
					</th>
					<th width="20%" style="text-align: center;">
						<?php echo JText::_('COM_JVARCADE_LEADERBOARD_USERNAME'); ?>
						&nbsp;
					</th>
					<th width="40%" style="text-align: center;">
						<?php echo JText::_('COM_JVARCADE_LEADERBOARD_POINTS'); ?>
					</th>
				</tr>
			</table>
		</div>
		<div id="FlashTable">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<?php foreach ($this->leaderboard as $i => $entry) : ?>
				<?php $place = (in_array($i, array(0,1,2)) ? '<img src="' . JVA_IMAGES_SITEPATH . 'icons/medal_' . ($i+1) . '.gif" border="0" alt="" />'  : $i+1) ?>
				<tr class="sectiontableentry">
					<td width="20%" style="text-align: center">
						<?php echo $place; ?>
					</td>
					<td width="20%" style="text-align: center">
						<?php if ($this->config->show_avatar == 1) : ?>
							<center><?php echo jvaHelper::showAvatar($entry->userid); ?></center>
						<?php endif; ?>
					</td>
					<td width="20%" style="text-align: center">
						<center><?php echo jvaHelper::userlink($entry->userid, (!(int)$this->config->show_usernames ? $entry->name : $entry->username)); ?></center>
					</td>
					<td width="40%" style="text-align: center">
						<center><?php echo $entry->points; ?></center>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
		</div>
	<?php else: ?>
	<div style="text-align:left;"><?php echo JText::_('COM_JVARCADE_LEADERBOARD_EMPTY'); ?></div>
	<?php endif; ?>
	<table id="pufooter" class="pu_Listfooter"><tr><td>&nbsp;</td></tr></table>

	<?php include_once(JVA_TEMPLATES_INCPATH . 'footer.php'); ?>
	
</div>
<input type="hidden" name="option" value="com_jvarcade" />
<input type="hidden" name="task" value="<?php echo $this->task; ?>" />
<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>

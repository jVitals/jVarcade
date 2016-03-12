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
<?php if (!$this->table_only) :?>
<div id="puarcade_wrapper">
	
	<?php include_once(JVA_TEMPLATES_INCPATH . 'menu.php'); ?>
	
<?php endif; ?>

	<div class="pu_heading" style="text-align: center;"><?php echo stripslashes($this->game['title']) . ' ' . JText::_('COM_JVARCADE_SCORES'); ?></div>
	<div class="pu_ListContainer">
		<table class="pu_ListHeader">
			<tr>
				<th width="25%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_DATE', 'p.date', @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th>
				<th width="25%" style="text-align: center;"><?php echo JText::_('COM_JVARCADE_AVATAR'); ?></th>
				<th width="25%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_NAME', (!(int)$this->config->show_usernames ? 'u.name' : 'u.username'), @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th>
				<th width="25%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_SCORES', 'p.score', @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th>
			</tr>
		</table>
	</div>
	
	<div id="FlashTable">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<?php if (!is_array($this->scores) || !count($this->scores)) :?>
		<tr>
			<td align="center"><br />
			<b><?php echo JText::_('COM_JVARCADE_NO_SCORES_AVAILABLE'); ?></b><br />
			</td>
		</tr>
		<?php else: ?>
		<?php foreach ($this->scores as $score) : ?>
			<?php if ($score['published'] == 1) :?>
				<tr class="sectiontableentry1">
					<td width="25%" style="text-align: center;"><?php echo jvaHelper::formatDate($score['date']); ?></td>
					<td width="25%" style="text-align: center;">
					<?php if ($this->config->show_avatar == 1) : ?>
						<?php echo jvaHelper::showAvatar($score['userid']); ?>
					<?php endif; ?>
					</td>
					<td width="25%" style="text-align: center;"><?php echo jvaHelper::userlink((int)$score['userid'], (!(int)$this->config->show_usernames ? $score['name'] : $score['username'])); ?></td>
					<td width="25%" style="text-align: center;"><?php echo rtrim(rtrim(number_format($score['score'],2), '0'), '.'); ?></td>
				</tr>
			<?php endif;?>
		<?php endforeach;?>
		<?php endif;?>
		</table>
	</div>
	<table id="pufooter" class="pu_Listfooter"><tr><td>&nbsp;</td></tr></table>

	<?php include_once(JVA_TEMPLATES_INCPATH . 'pagination.php'); ?>
	
<?php if (!$this->table_only) :?>
	<?php if ($this->config->rate == 1) : ?> 
		<?php JHtml::script('com_jvarcade/jquery.rating.js', false, true); ?>
		<div id="rate1" class="rating">
		<script type="text/javascript">
			jQuery(document).ready(function() {
				jQuery.jva.rating(<?php echo $this->game['id'];?>, <?php echo $this->game['current_vote']; ?>);
			});
		</script>
		</div>
	<?php endif; ?>
	<br /><a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $this->game['id']); ?>"><?php echo JText::_('COM_JVARCADE_PLAY_THIS'); ?></a>
	<br /><a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=home'); ?>"><?php echo JText::_('COM_JVARCADE_CHOOSE_ANOTHER'); ?></a>

	<?php include_once(JVA_TEMPLATES_INCPATH . 'footer.php'); ?>
	
</div>
<?php endif; ?>
<input type="hidden" name="option" value="com_jvarcade" />
<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
<input type="hidden" name="task" value="<?php echo (!$this->table_only ? 'scores' : 'game') ;?>" />
<input type="hidden" name="id" value="<?php echo $this->game_id; ?>" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

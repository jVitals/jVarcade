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
defined('_JEXEC') or die('Restricted access');

?>
<form action="index.php" method="get" name="adminForm">
<div id="puarcade_wrapper">
	
	<?php include_once(JVA_TEMPLATES_INCPATH . 'menu.php'); ?>

	<div class="pu_heading" style="text-align: center;"><?php echo $this->tabletitle ?></div>
	<div class="pu_ListContainer">
		<table class="pu_ListHeader">
			<tr>
				<th width="13%">
				</th> 
				<th width="20%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_CONTESTS_NAME', 'name', @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th>
				<th width="15%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_CONTESTS_START', 'startdatetime', @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th>
				<th width="10%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_CONTESTS_END', 'enddatetime', @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th>
				<th width="10%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_CONTESTS_REGISTRATION', 'registration', @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th>
				<th width="10%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_CONTESTS_STATUS', 'status', @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th>
			</tr>
		</table>
	</div>
	
	<div id="FlashTable">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<?php if (is_array($this->contests) && count($this->contests)) : ?>
		<?php foreach ($this->contests as $contest) : ?>
			<tr class="sectiontableentry">
				<td width="10%">
					<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=contestdetail&id=' . $contest['id'], false); ?>">
						<img src="<?php echo JVA_IMAGES_SITEPATH . ($contest['imagename'] ? 'contests/' . $contest['imagename'] : 'cpanel/contests.png') ; ?>" border="0" alt="" />
					</a>
				</td>
				<td width="18%">
					<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=contestdetail&id=' . $contest['id']); ?>">
						<b><?php echo stripslashes($contest['name']); ?></b>
					</a>
					<br /><?php echo html_entity_decode($contest['description'], ENT_QUOTES, 'UTF-8'); ?>
				</td>
				<td width="10%">
					<center><?php echo jvaHelper::formatDate($contest['startdatetime']); ?></center>
				</td>
				<td width="10%">
					<center><?php echo jvaHelper::formatDate($contest['enddatetime']); ?></center>
				</td>
				<td width="10%">
					<center><?php echo $this->showRegistration($contest['registration'], $contest['islimitedtoslots']); ?></center>
				</td>
				<td width="10%">
					<center><?php echo $this->showStatus($contest['status']); ?></center>
				</td>
			</tr>
		<?php endforeach; ?>
		<?php else: ?>
		<tr class="sectiontableentry"><td><?php echo JText::_('COM_JVARCADE_CONTESTS_EMPTY'); ?></td></tr>
		<?php endif; ?>
		</table>
	</div>

	<table id="pufooter" class="pu_Listfooter"><tr><td>&nbsp;</td></tr></table>

	<?php include_once(JVA_TEMPLATES_INCPATH . 'pagination.php'); ?>

	<?php include_once(JVA_TEMPLATES_INCPATH . 'footer.php'); ?>
	
</div>
<input type="hidden" name="option" value="com_jvarcade" />
<input type="hidden" name="task" value="<?php echo $this->task; ?>" />
<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

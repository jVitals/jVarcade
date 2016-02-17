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

$listOrder = $this->escape($this->filter_order);
$listDirn = $this->escape($this->filter_order_Dir);
?>
<?php if(!empty( $this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
	<div class="row-fluid">
		<div class="span12">
			<?php
				echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filterButton' => 0)));
			?>
		</div>
	</div>
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="20"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th style="text-align: left;" class="title"><?php echo JHTML::_('grid.sort',   'COM_JVARCADE_SCORES_GAME', 'g.title', $listDirn, $listOrder); ?></th>
				<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_SCORES_USER', 'u.username', $listDirn, $listOrder); ?></th>
				<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_SCORES_SCORE', 'p.score', $listDirn, $listOrder); ?></th>
				<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_SCORES_IP', 'p.ip', $listDirn, $listOrder); ?></th>
				<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_SCORES_DATE', 'p.date', $listDirn, $listOrder); ?></th>
				<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_SCORES_PUBLISHED', 'p.published', $listDirn, $listOrder); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php
			
			if (!empty($this->items)) :
				foreach ($this->items as $i => $row): 
					$url = 'http://tools.whois.net/whoisbyip/?host=' . $row->ip;
					$checked = JHTML::_('grid.id', $i, $row->id, false, 'cid');
			?>
					<tr class="<?php echo "row$i"; ?>">
						<td style="text-align: left;"><?php echo $checked; ?></td>
						<td style="text-align: left;"><?php echo $row->title; ?></td>
						<td style="text-align: left;"><?php echo $row->username; ?></td>
						<td style="text-align: left;"><?php echo $row->score; ?></td>
						<td style="text-align: left;"><a target="_blank" href="<?php echo $url; ?>"><?php echo $row->ip; ?></a></td>
						<td style="text-align: left;"><?php echo jvaHelper::formatDate($row->date); ?></td>
						<td style="text-align: center;"><?php echo JHtml::_('jgrid.published', $row->published, $i, 'manage_scores.score'); ?></td>
					</tr>
			<?php endforeach;?>
			<?php  endif;?>
			<tr>
				<td colspan="8" class="erPagination"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="manage_scores" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
</form>

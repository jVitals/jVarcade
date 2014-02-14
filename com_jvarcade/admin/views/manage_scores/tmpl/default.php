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
	<table class="table table-striped">
		<thead>
			<tr>
				<th width="20"><input type="checkbox" name="toggle" value=""  onclick="checkAll(<?php echo count($this->scores); ?>);" /></th>
				<th style="text-align: left;" class="title"><?php echo JHTML::_('grid.sort',   'COM_JVARCADE_SCORES_GAME', 'g.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_SCORES_USER', 'u.username', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_SCORES_SCORE', 'p.score', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_SCORES_IP', 'p.ip', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_SCORES_DATE', 'p.date', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: left;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_SCORES_PUBLISHED', 'p.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php
			$i = 0;
			if (is_array($this->scores)) {
				foreach ($this->scores as $k => $obj) {
					$url = 'http://tools.whois.net/whoisbyip/?host=' . $obj->ip;
					$checked = JHTML::_('grid.id', $k, $obj->id, false, 'cid');
			?>
					<tr class="<?php echo "row$i"; ?>">
						<td style="text-align: left;"><?php echo $checked; ?></td>
						<td style="text-align: left;"><?php echo $obj->title; ?></td>
						<td style="text-align: left;"><?php echo $obj->username; ?></td>
						<td style="text-align: left;"><?php echo $obj->score; ?></td>
						<td style="text-align: left;"><a target="_blank" href="<?php echo $url; ?>"><?php echo $obj->ip; ?></a></td>
						<td style="text-align: left;"><?php echo jvaHelper::formatDate($obj->date); ?></td>
						<td style="text-align: center;"><?php echo JHtml::_('jgrid.published', $obj->published, $i, 'score'); ?></td>
					</tr>
			<?php
					if ($i == 0) {
						$i = 1;
					} else {
						$i++;
					}
				}
			}
			?>
			<tr>
				<td colspan="8" class="erPagination"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="manage_scores" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

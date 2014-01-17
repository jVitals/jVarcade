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
				<th width="20"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTESTS_ID', 'id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTESTS_NAME', 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTESTS_START', 'startdatetime', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTESTS_END', 'enddatetime', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTESTS_GAMECOUNT', 'maxplaycount', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTESTS_PUBLISHED', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php
			$i = 0;
			if (is_array($this->contests)) {
				foreach ($this->contests as $k => $obj) {
					$checked = JHTML::_('grid.id', $k, $obj->id, false, 'cid');
					$img = ($obj->published ? 'tick.png' : 'publish_x.png');
					$imgtag = (JVA_COMPATIBLE_MODE == '16') ? JHTML::_('image','admin/'.$img, '', array('border' => 0), true) : JHTML::_('image.administrator', $img, '/images/');
			?>
					<tr class="<?php echo "row$i"; ?>">
						<td style="text-align: center;"><?php echo $checked; ?></td>
						<td style="text-align: center;"><?php echo $obj->id; ?></td>
						<td style="text-align: center;"><a href="<?php echo JRoute::_('index.php?option=com_jvarcade&c&task=editcontest&id=' . $obj->id); ?>"><?php echo $obj->name; ?></a></td>
						<td style="text-align: center;"><?php echo jvaHelper::formatDate($obj->startdatetime); ?></td>
						<td style="text-align: center;"><?php echo jvaHelper::formatDate($obj->enddatetime); ?></td>
						<td style="text-align: center;"><?php echo $obj->maxplaycount; ?></td>
						<td style="text-align: center;">
							<a href="javascript:void(0);"	onclick="return listItemTask('cb<?php echo $i;?>','<?php echo (!$obj->published ? 'contestPublishYes' : 'contestPublishNo'); ?>')">
								<?php echo $imgtag; ?>
							</a>
						</td>
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
	<input type="hidden" name="task" value="contests" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

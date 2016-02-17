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
defined('_JEXEC') or die();
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
<form action="index.php" method="post" name="adminForm" id="adminForm" >
<table class="table table-striped">
			<tr>
				<th width="20"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th style="text-align: center;" class="title"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTENT_RATINGS_ID', 'id', $listDirn, $listOrder); ?></th>
				<th style="text-align: center;" class="title"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTENT_RATINGS_NAME', 'name', $listDirn, $listOrder); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTENT_RATINGS_WARNING_DISPLAYED', 'warningrequired', $listDirn, $listOrder); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTENT_RATINGS_PUBLISHED', 'published', $listDirn, $listOrder); ?></th>
			</tr>
	<?php
			
			if (!empty($this->items)):
				foreach ($this->items as $i => $row):
					$checked = JHTML::_('grid.id', $i, $row->id, false, 'cid');
					$imgwarntag = (JVA_COMPATIBLE_MODE == '16') ? JHTML::_('image','admin/icon-16-notice-note.png', '', array('border' => 0), true) : JHTML::_('image.administrator', 'warning.png', '../includes/js/ThemeOffice/');
					$imgwarntag = ((int)$row->warningrequired ? $imgwarntag : '');
			?>
					<tr class="<?php echo "row$i"; ?>">
						<td style="text-align: center;"><?php echo $checked; ?></td>
						<td style="text-align: center;"><?php echo $row->id; ?></td>
						<td style="text-align: center;"><a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=edit_contentrating&id=' . $row->id); ?>"><?php echo $row->name; ?></a></td>
						<td style="text-align: center;"><?php echo $imgwarntag; ?></td>
						<td style="text-align: center;"><?php echo JHtml::_('jgrid.published', $row->published, $i, 'contentrating'); ?></td>
					</tr>
			<?php endforeach;?>
			<?php endif;?>
			<tr>
				<td colspan="8" class="erPagination"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
	</table>
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="content_ratings" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
</form>

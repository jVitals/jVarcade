<?php
/**
 * @package		jVArcade
 * @version		2.12
 * @date		2014-05-17
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
<form action="index.php" method="post" name="adminForm" id="adminForm" >
<table class="table table-striped">
			<tr>
				<th width="20"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th style="text-align: center;" class="title"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTENT_RATINGS_ID', 'id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;" class="title"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTENT_RATINGS_NAME', 'name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTENT_RATINGS_WARNING_DISPLAYED', 'warningrequired', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_CONTENT_RATINGS_PUBLISHED', 'published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
			</tr>
	<?php
			$i = 0;
			if (is_array($this->ratings)) {
				foreach ($this->ratings as $k => $obj) {
					$checked = JHTML::_('grid.id', $k, $obj->id, false, 'cid');
					$imgwarntag = (JVA_COMPATIBLE_MODE == '16') ? JHTML::_('image','admin/icon-16-notice-note.png', '', array('border' => 0), true) : JHTML::_('image.administrator', 'warning.png', '../includes/js/ThemeOffice/');
					$imgwarntag = ((int)$obj->warningrequired ? $imgwarntag : '');
			?>
					<tr class="<?php echo "row$i"; ?>">
						<td style="text-align: center;"><?php echo $checked; ?></td>
						<td style="text-align: center;"><?php echo $obj->id; ?></td>
						<td style="text-align: center;"><a href="<?php echo JRoute::_('index.php?option=com_jvarcade&c&task=editcontentrating&id=' . $obj->id); ?>"><?php echo $obj->name; ?></a></td>
						<td style="text-align: center;"><?php echo $imgwarntag; ?></td>
						<td style="text-align: center;"><?php echo JHtml::_('jgrid.published', $obj->published, $i, 'contentrating'); ?></td>
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
	</table>
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="content_ratings" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
</form>

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
				echo JLayoutHelper::render('joomla.searchtools.default', array('view' => $this, 'options' => array('filterButton' => 1)));
			?>
		</div>
	</div>
	<table  class="table table-striped">
		<thead>
			<tr>
				<th width="20"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th style="text-align: center;" class="title"><?php echo JHTML::_('grid.sort',   'COM_JVARCADE_GAMES_ID', 'g.id', $listDirn, $listOrder); ?></th>
				<th style="text-align: center;" class="title"><?php echo JHTML::_('grid.sort',   'COM_JVARCADE_GAMES_TITLE', 'g.title', $listDirn, $listOrder); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_GAMES_SCORING', 'g.scoring', $listDirn, $listOrder); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_GAMES_NUMPLAYED', 'g.numplayed', $listDirn, $listOrder); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_GAMES_FOLDER', 'f.name', $listDirn, $listOrder); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_GAMES_PUBLISHED', 'g.published', $listDirn, $listOrder); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php
			
			if (is_array($this->items)) :
				foreach ($this->items as $i => $row) :
					$checked = JHTML::_('grid.id', $i, $row->id, false, 'cid');
					$imgscore = ($row->scoring ? 'tick.png' : 'publish_x.png');
					$imgtagscore = JHTML::_('image','admin/'.$imgscore, '', array('border' => 0), true);
			?>
					<tr class="<?php echo "row$i"; ?>">
						<td style="text-align: center;"><?php echo $checked; ?></td>
						<td style="text-align: center;"><?php echo $row->id; ?></td>
						<td style="text-align: center;"><a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=edit_game&id=' . $row->id); ?>"><?php echo $row->title; ?></a></td>
						<td style="text-align: center;"><?php echo $imgtagscore; ?></td>
						<td style="text-align: center;"><?php echo $row->numplayed; ?></td>
						<td style="text-align: center;"><?php echo $row->name; ?></td>
						<td style="text-align: center;"><?php echo JHtml::_('jgrid.published', $row->published, $i, 'manage_games.game'); ?></td>
					</tr>
			<?php endforeach;?>
			<?php endif;?>
			<tr>
				<td colspan="8" class="erPagination"><?php echo $this->pagination->getListFooter(); ?></td>
			</tr>
		</tbody>
	</table>
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="manage_games" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $listOrder; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $listDirn; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>

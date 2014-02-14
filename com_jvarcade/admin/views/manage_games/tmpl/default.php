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
JHtml::_('formbehavior.chosen', 'select');

?>
<?php if(!empty( $this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>
<script type="text/javascript">
	if (typeof Joomla != 'undefined') {
		Joomla.submitbutton = function(pressbutton) {
			if (pressbutton != 'addgametocontest') {
				Joomla.submitform(pressbutton);
			} else {
				jQuery.jva.showAddToContestPopup();
			}
		}
	} else {
		function submitbutton(pressbutton) {
			if (pressbutton != 'addgametocontest') {
				submitform(pressbutton);
			} else {
				jQuery.jva.showAddToContestPopup();
			}
		}
	}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">

	<table>
		<div id="filter-bar" class="btn-toolbar">
			<div class="filter-search btn-group pull-left">
				<input type="text" name="filter_title" id="filter_title" placeholder="<?php echo JText::_('Enter Game Title'); ?>" value="<?php echo htmlspecialchars($this->lists['filter_title'], ENT_QUOTES, 'UTF-8');?>" class="hasTooltip" title="<?php echo JHtml::tooltipText('Search Game Title'); ?>" />
			</div>
			<div class="btn-group pull-left">
				<button type="submit" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_SUBMIT'); ?>"><i class="icon-search"></i></button>
				<button type="button" class="btn hasTooltip" title="<?php echo JHtml::tooltipText('JSEARCH_FILTER_CLEAR'); ?>" onclick="document.id('filter_title').value='';this.form.getElementById('filter_name').value='';this.form.submit();"><i class="icon-remove"></i></button>
			</div>
			<div class="btn-group pull-left">
				<?php echo $this->lists['folders'];?>
			</div>
		</div>
	</table>

	<table  class="table table-striped">
		<thead>
			<tr>
				<th width="20"><?php echo JHtml::_('grid.checkall'); ?></th>
				<th style="text-align: center;" class="title"><?php echo JHTML::_('grid.sort',   'COM_JVARCADE_GAMES_ID', 'g.id', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;" class="title"><?php echo JHTML::_('grid.sort',   'COM_JVARCADE_GAMES_TITLE', 'g.title', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_GAMES_SCORING', 'g.scoring', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_GAMES_NUMPLAYED', 'g.numplayed', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_GAMES_FOLDER', 'f.name', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
				<th style="text-align: center;"><?php echo JHTML::_('grid.sort', 'COM_JVARCADE_GAMES_PUBLISHED', 'g.published', @$this->lists['order_Dir'], @$this->lists['order'] ); ?></th>
			</tr>
		</thead>
		<tbody>
	<?php
			$i = 0;
			if (is_array($this->games)) {
				foreach ($this->games as $k => $obj) {
					$checked = JHTML::_('grid.id', $k, $obj->id, false, 'cid');
					$imgscore = ($obj->scoring ? 'tick.png' : 'publish_x.png');
					$imgtagscore = (JVA_COMPATIBLE_MODE == '16') ? JHTML::_('image','admin/'.$imgscore, '', array('border' => 0), true) : JHTML::_('image.administrator', $imgscore, '/images/');
			?>
					<tr class="<?php echo "row$i"; ?>">
						<td style="text-align: center;"><?php echo $checked; ?></td>
						<td style="text-align: center;"><?php echo $obj->id; ?></td>
						<td style="text-align: center;"><a href="<?php echo JRoute::_('index.php?option=com_jvarcade&c&task=editgame&id=' . $obj->id); ?>"><?php echo $obj->title; ?></a></td>
						<td style="text-align: center;"><?php echo $imgtagscore; ?></td>
						<td style="text-align: center;"><?php echo $obj->numplayed; ?></td>
						<td style="text-align: center;"><?php echo $obj->name; ?></td>
						<td style="text-align: center;"><?php echo JHtml::_('jgrid.published', $obj->published, $i, 'game'); ?></td>
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
	<input type="hidden" name="task" value="manage_games" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="filter_order" value="<?php echo $this->lists['order']; ?>" />
	<input type="hidden" name="filter_order_Dir" value="<?php echo $this->lists['order_Dir']; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>

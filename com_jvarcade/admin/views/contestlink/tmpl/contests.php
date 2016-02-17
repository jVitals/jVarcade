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

?>

<?php if(is_array($this->contests) && count($this->contests)) : ?>
<table class="table table-striped">
	<thead>
		<tr>
			<th width="20"><?php echo JHtml::_('grid.checkall'); ?></th>
			<th style="text-align: center;" class="title"><?php echo JText::_('COM_JVARCADE_CONTESTS_ID'); ?></th>
			<th style="text-align: center;" class="title"><?php echo JText::_('COM_JVARCADE_CONTESTS_NAME'); ?></th>
			<th style="text-align: center;"><?php echo JText::_('COM_JVARCADE_CONTESTS_START'); ?></th>
			<th style="text-align: center;"><?php echo JText::_('COM_JVARCADE_CONTESTS_END'); ?></th>
			<th style="text-align: center;"><?php echo JText::_('COM_JVARCADE_CONTESTS_SLOTS'); ?></th>
			<th style="text-align: center;"><?php echo JText::_('COM_JVARCADE_CONTESTS_GAMECOUNT'); ?></th>
		</tr>
	</thead>
	<tbody>
<?php
		$i = 0;
		if (is_array($this->contests)) {
			foreach ($this->contests as $k => $obj) {
				$imgtag = (JVA_COMPATIBLE_MODE == '16') ? JHTML::_('image','admin/publish_x.png', '', array('border' => 0), true) : JHTML::_('image.administrator', 'publish_x.png', '/images/');
		?>
				<tr class="<?php echo "row$i"; ?>">
					<td style="text-align: center;"><?php echo JHTML::_('grid.id', $k, $obj->id, false, 'cid'); ?></td>
					<td style="text-align: center;"><?php echo $obj->id; ?></td>
					<td style="text-align: center;"><a target="_blank" href="<?php echo JRoute::_('index.php?option=com_jvarcade&c&task=editcontest&id=' . $obj->id); ?>"><?php echo $obj->name; ?></a></td>
					<td style="text-align: center;"><?php echo jvaHelper::formatDate($obj->startdatetime); ?></td>
					<td style="text-align: center;"><?php echo jvaHelper::formatDate($obj->enddatetime); ?></td>
					<td style="text-align: center;"><?php echo $obj->islimitedtoslots; ?></td>
					<td style="text-align: center;"><?php echo $obj->maxplaycount; ?></td>
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
	</tbody>
</table>
<?php else: ?>
<?php echo JText::_('COM_JVARCADE_CONTESTSLINK_NOCONTESTS'); ?>
<?php endif; ?>
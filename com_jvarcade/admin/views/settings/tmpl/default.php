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
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" name="adminForm" id="adminForm" method="post">
	<input type="hidden" name="config_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="settings" />
	<?php echo JHtml::_('bootstrap.startTabSet', 'jvsetstabset', array('active' => '1'));?>
	<?php 
		$grp = 1;
		foreach ($this->conf as $group => $conf) : 
			//$groupname = JText::_('COM_JVARCADE_GRP_' . strtoupper($group));
			echo JHtml::_('bootstrap.addTab', 'jvsetstabset', $grp , JText::_('COM_JVARCADE_GRP_' . strtoupper($group)));
	?>
	<div >
	<fieldset class="adminform">
	<table class="admintable" width="80%">
		<colgroup>
			<col width="200"/>
			<col width="*"/>
		</colgroup>
		<tbody>
			<?php foreach ($conf as $arr) : 
				$label = JText::_('COM_JVARCADE_OPT_' . strtoupper($arr['optname']));
				$description = '';
				if (trim($arr['description'])) $description = '<p>' . JText::_(trim($arr['description'])) . '</p>';
			?>
			<tr>
				<td class="key<?php echo ($description ? ' hasTip': ''); ?>" valign="top" nowrap="nowrap" <?php echo ($description ? 'title="'.htmlspecialchars($label .'::'.$description, ENT_QUOTES, 'UTF-8').'" style="vertical-align:top;"' : ''); ?>>
					<?php echo $label; ?>
				</td>
				<td>
					<?php echo $this->showSetting($arr); ?>
				</td>
			</tr>
			<?php echo $this->showCommentsLegend($arr['optname']); ?>
			<?php endforeach; ?>
		</tbody>
	</table>
	</fieldset>
	
	</div>
	<?php
		echo JHtml::_('bootstrap.endTab');
			$grp ++;
		endforeach;
	?>
	<?php echo JHtml::_('bootstrap.endTabSet');?>
</form>
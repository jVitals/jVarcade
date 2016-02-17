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
JHtml::_('bootstrap.tooltip');
?>
<?php if(!empty( $this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>

<?php echo JHtml::_('bootstrap.startTabSet', 'gfeed', array('active' => 'gsfeed'));?>
<?php echo JHtml::_('bootstrap.addTab', 'gfeed', 'gsfeed', JText::_('FGD Games')); ?>
<table cellspacing="0" cellpadding="0" border="0" width="100%">
	<tr>
		<td valign="top">
			<div id="dashboard">
			<!-- start dashboard -->
	<?php include($this->feed);
			 foreach($fgd_games as $item): ?>
				<div style="float:left;">
					<div class="icon">
						<a href="http://flashgamedistribution.com/game_info.php?gid=<?php echo $item['game_id']; ?>" class="hasTooltip"  target="_blank" data-original-title="<strong><?php echo $item['title']; ?></strong></br><?php echo $item['short_description']?>">
							<img  width="40" height="40" src="<?php echo $item['thumb_filename'] ?>" />
							<span><?php echo jvaHelper::truncate(stripslashes($item['title']), 30); ?></span>
						</a>
					</div>
				</div>
			<?php endforeach; ?>
			<!-- end dashboard -->
			</div>
			<div id="jvitals_banner_div" style="margin: 20px auto auto auto; float: none; clear: both;"></div>
		</td>
	</tr>
</table>
<?php echo JHtml::_('bootstrap.endTab'); ?>
<?php echo JHtml::_('bootstrap.endTabSet');?>
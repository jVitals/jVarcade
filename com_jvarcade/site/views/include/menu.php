<?php
/**
 * @package		jVArcade
 * @version		2.14
 * @date		2016-03-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die;

?>
	

	<?php if ($this->config->specialfolders == 1) : ?>
	<div id="pua_header">
		<?php if (($this->config->faves == 1) && ((int)$this->user->get('id') > 0)) : ?>
		<div class="pua_header_box">
			<img src="<?php echo JVA_IMAGES_SITEPATH; ?>icons/fav25.png" alt="" />
			<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=favourite');?>"><?php echo JText::_('COM_JVARCADE_MY_FAVORITES'); ?></a>
		</div>
		<?php endif; ?>
		<?php if ($this->config->leaderboard == 1) : ?>
		<div class="pua_header_box">
			<img src="<?php echo JVA_IMAGES_SITEPATH; ?>icons/leaderboard25.png" alt="" />
			<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=leaderboard');?>"><?php echo JText::_('COM_JVARCADE_LEADERBOARD'); ?></a>
		</div>
		<?php endif; ?>
		<div class="pua_header_box">
			<img src="<?php echo JVA_IMAGES_SITEPATH; ?>icons/contest25.png" alt="" />
			<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=contests');?>"><?php echo JText::_('COM_JVARCADE_CONTESTS'); ?></a>
		</div>
		<div class="pua_header_box">
			<img src="<?php echo JVA_IMAGES_SITEPATH; ?>icons/newest25.png" alt="" />
			<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=newest');?>"><?php echo JText::_('COM_JVARCADE_NEWEST_GAMES'); ?></a>
		</div>
		<div class="pua_header_box">
			<img src="<?php echo JVA_IMAGES_SITEPATH; ?>icons/popular25.png" alt="" />
			<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=popular');?>"><?php echo JText::_('COM_JVARCADE_POPULAR_GAMES'); ?></a>
		</div>
		<div class="pua_header_box">
			<img src="<?php echo JVA_IMAGES_SITEPATH; ?>icons/all25.png" alt="" />
			<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=home');?>"><?php echo JText::_('COM_JVARCADE_ALL'); ?></a>
		</div>
	</div>
	<?php endif; ?>
	
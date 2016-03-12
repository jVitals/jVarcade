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
<div id="puarcade_wrapper">
	
	<?php include_once(JVA_TEMPLATES_INCPATH . 'menu.php'); ?>

	<?php if (!$this->can_view) : ?>
		<?php echo JText::_('COM_JVARCADE_FOLDER_NO_VIEW_PERMS') ?> 
	<?php else : ?>

	<div class="pu_heading" style="text-align: center;"><?php echo $this->tabletitle ?></div>
	<div class="pu_ListContainer">
		<table class="pu_ListHeader">
			<tr>
				<th width="10%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_GAME_ID', 'game_id', @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th> 
				<th colspan="2" width="16%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_GAME_SELECTION', 'title', @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th> 
				<th width="10%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_TIMES_PLAYED', 'numplayed', @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th>
				<th width="14%" style="text-align: center;">
					<b><?php echo JText::_('COM_JVARCADE_HIGH_SCORE'); ?></b>
				</th>
				<?php if ($this->config->contentrating == 1) : ?>
				<th width="7%" style="text-align: center;">
					<?php echo JHTML::_('jvarcade.html.sort', 'COM_JVARCADE_CONTENT', 'rating_name', @$this->lists['order_Dir'], @$this->lists['order'], $this->sort_url); ?>
				</th>
				<?php endif; ?>
                <?php if ($this->config->enable_dload == 1 && $this->can_dload) : ?><th width="6%" style="text-align: center;"><b>Download</b></th><?php endif ?>
			</tr>
		</table>
	</div>
	
	<div id="FlashTable">
		<table width="100%" cellpadding="0" cellspacing="0" border="0">
		<?php foreach ($this->games as $game) : ?>
			<?php $alt = htmlspecialchars(stripslashes($game['title'])); ?>
			<tr class="sectiontableentry">
				<td width="10%">
					<center><?php echo $game['game_id']; ?></center>
				</td>
				<td width="10%">
					<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $game['id'], false); ?>">
						<img src="<?php echo JVA_IMAGES_SITEPATH . 'games/' . $game['imagename']; ?>" border="0" height="50" width="50" class="hasTooltip" data-original-title="<strong><?php echo $alt; ?></strong>"/>
					</a>
				</td>
				<td width="20%">
					<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $game['id'], false); ?>" class="hasTooltip" data-original-title="<strong><?php echo $alt; ?></strong></br><?php echo html_entity_decode($game['game_desc'], ENT_QUOTES, 'UTF-8'); ?>">
						<b><?php echo jvaHelper::truncate(stripslashes($game['title']), (int)$this->config->truncate_title); ?></b>
					</a>
					<br /><?php //echo html_entity_decode($game['game_desc'], ENT_QUOTES, 'UTF-8'); ?>
				</td>
				<td width="10%">
					<center><?php echo $game['numplayed']; ?></center>
				</td>
				<td width="20%">
					<center>
					<?php if ($game['scoring']) : ?>
						<?php if (array_key_exists('highscore', $game) && count($game['highscore']) && (int)$game['highscore']['score']) : ?>
								<b><?php echo JText::_('COM_JVARCADE_HIGH_SCORE') ?> : <?php echo $game['highscore']['score'] ?></b><br/>
								<b><?php echo JText::_('COM_JVARCADE_SCORE_BY') ?> :<?php echo $game['highscore']['username'] ?></b><br/>
								<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=scores&id=' . $game['id'], false) ?>">[<?php echo JText::_('COM_JVARCADE_ALL_SCORES')?>]</a>
						<?php else : ?>
								<h4><?php echo JText::_('COM_JVARCADE_NO_SCORES') ?></h4>
						<?php endif; ?>
					<?php else : ?>
						<h4><?php echo JText::_('COM_JVARCADE_SCORING_DISABLED') ?></h4>
					<?php endif; ?>
					</center>
				</td>
				<?php if ($this->config->contentrating == 1) : ?>
				<td width="10%">
					<?php if ($game['rating_image']) : ?>
					<center><img src="<?php echo JVA_IMAGES_SITEPATH . 'contentrating/' . $game['rating_image']; ?>" alt="<?php echo $game['rating_desc']; ?>"  title="<?php echo $game['rating_desc']; ?>" /></center>
					<?php endif; ?>
				</td>
				<?php endif; ?>
                <?php if ($this->config->enable_dload == 1 && $this->can_dload) : ?>
                <td width="8%"><center><a href="javascript:void(0)" onclick="jQuery.jva.downloadGame(<?php echo $game['id']; ?>); return false;"><img src="<?php echo JVA_IMAGES_SITEPATH; ?>dl.png" /></a></td><?php endif; ?>
			</tr>
		<?php endforeach; ?>
		</table>
	</div>

	<table id="pufooter" class="pu_Listfooter"><tr><td>&nbsp;</td></tr></table>
	
	<?php include_once(JVA_TEMPLATES_INCPATH . 'pagination.php'); ?>
	
	<?php if ($this->task == 'folder' && isset($this->subfolders) && is_array($this->subfolders) && count($this->subfolders)) : ?>
		<br/><?php echo JText::_('COM_JVARCADE_SUBFOLDERS'); ?>
		<?php $tmp = array();foreach ($this->subfolders as $subfolder) : ?>
			<?php $tmp[] = '<a href="' . JRoute::_('index.php?option=com_jvarcade&task=folder&id=' . $subfolder['id']) . '">' . $subfolder['name'] . '</a>'; ?>
		<?php endforeach; ?>
		<?php echo implode(', ', $tmp); ?>
	<?php endif; ?>
	
	<?php endif; ?>
	
	<?php if ($this->layout != 'flat') : ?>
	<br /><center><a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=home', false); ?>"><?php echo JText::_('COM_JVARCADE_CHOOSE_ANOTHER_FOLDER'); ?></a></center>
	<?php endif; ?>

	<?php include_once(JVA_TEMPLATES_INCPATH . 'footer.php'); ?>
	
</div>

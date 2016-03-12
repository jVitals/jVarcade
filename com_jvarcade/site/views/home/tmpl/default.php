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
defined('_JEXEC') or die('Restricted access');
$i = '';
?>
<div id="puarcade_wrapper">
	
	<?php include_once(JVA_TEMPLATES_INCPATH . 'menu.php'); ?>
	
	<?php if ($this->config->foldercols > 1) : ?>
		<?php $i = 1; ?>
		<div class="jva_foldercols" border="0" width="100%">
			<div class="jva_column_wrap">
	<?php endif; ?>
	
	<?php if (is_array($this->folders) && count($this->folders)) : ?>
	<?php foreach ($this->folders as $folder) : ?>
	
		<?php if ($this->config->foldercols > 1) : ?>
			<div class="jva_columns" style="width:<?php echo round(100/$this->config->foldercols); ?>%;">
		<?php endif; ?>
		
		<div class="pua_folder">
			<div class="pua_folder_description">
				<div class="pua_folder_description_left">
					<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=folder&id=' . $folder['id']); ?>">
						<img src="<?php echo JVA_IMAGES_SITEPATH . ($folder['imagename'] ? 'folders/' . $folder['imagename'] : 'cpanel/folder.png') ; ?>" border="0" alt="" />
					</a> 
				</div>
				<div class="pua_folder_description_right">
					<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=folder&id=' . $folder['id']); ?>">
						<b><?php echo stripslashes($folder['name']); ?></b>
					</a>
					<br />
					<?php echo $folder['description']; ?>
				</div>
			</div>
			<div style="float:none; clear:both;"></div>
			<div class="pua_game_container">
			<?php if (array_key_exists('games', $folder) && is_array($folder['games']) && count($folder['games'])) : ?>
			<?php foreach ($folder['games'] as $game) : ?>
				<?php $alt = htmlspecialchars(stripslashes($game['title'])); ?>
				<div class="pua_folder_games">
				<?php $game_url = JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $game['id'], false) ?>
					<a href="<?php echo $game_url; ?>">
						<?php echo jvaHelper::truncate(stripslashes($game['title']), (int)$this->config->truncate_title); ?>
					</a>
					<a href="<?php echo $game_url; ?>">
						<img src="<?php echo JVA_IMAGES_SITEPATH . 'games/' . $game['imagename']; ?>" alt="<?php echo $alt; ?>" title="<?php echo $alt; ?>" />
					</a>					
				
				<?php if ($this->config->showscoresinfolders == 1) : ?>
					
				<?php if (array_key_exists('highscore', $game)) : ?>
				<?php if (count($game['highscore']) && (int)$game['highscore']['score']) : ?>
						<h4><?php echo JText::_('COM_JVARCADE_HIGH_SCORE') ?> : <?php echo $game['highscore']['score'] ?></h4>
						<h4><?php echo $game['highscore']['username'] ?></h4>
						<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=scores&id=' . $game['id']) ?>">[<?php echo JText::_('COM_JVARCADE_ALL_SCORES')?>]</a>
				<?php else : ?>
						<h4><?php echo JText::_('COM_JVARCADE_NO_SCORES') ?></h4>
				<?php endif; ?>
				<?php else : ?>
					<h4><?php echo JText::_('COM_JVARCADE_SCORING_DISABLED'); ?></h4>
				<?php endif; ?>
				<?php endif; ?>
				
				</div>
			<?php endforeach; ?>
			<?php endif; ?>
			</div>			
			<div class="pu_AllGames">
				<?php if (is_array($this->games_count) && count($this->games_count) && isset($this->games_count[$folder['id']])) : ?>
				<br/>
				<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=folder&id=' . $folder['id']); ?>">
					<?php echo JText::sprintf('COM_JVARCADE_SEE_ALL_GAMES_IN_FOLDER', $this->games_count[$folder['id']]['count']); ?>
				</a>
				&raquo;&nbsp;
				<?php else: ?>
					<?php echo JText::_('COM_JVARCADE_FOLDER_EMPTY'); ?>
				<?php endif; ?>
			</div>
			<?php if (isset($this->all_folders[$folder['id']]) && is_array($this->all_folders[$folder['id']]) && count($this->all_folders[$folder['id']])) : ?>
			<div class="pu_AllGames">
					<span style="margin-left: 0.5em;padding-left: 0.75em;"><?php echo JText::_('COM_JVARCADE_SUBFOLDERS'); ?></span>
					<?php $tmp = array();foreach ($this->all_folders[$folder['id']] as $subfolder) : ?>
						<?php $tmp[] = '<a href="' . JRoute::_('index.php?option=com_jvarcade&task=folder&id=' . $subfolder['id']) . '">' . $subfolder['name'] . '</a>'; ?>
					<?php endforeach; ?>
					<?php echo implode(', ', $tmp); ?>
			</div>
			<?php endif; ?>
		</div>
		
		<?php if ($this->config->foldercols > 1) : ?>
			</div>
			<?php if(!($i%(int)$this->config->foldercols)) : ?>
			<div></div>
			<?php endif; ?>
			<?php $i++; ?>
		<?php endif; ?>
	
	<?php endforeach; ?>
	<?php else: ?>
		<?php echo JText::_('COM_JVARCADE_NO_GAMES'); ?>
	<?php endif; ?>
	
	<?php if ($this->config->foldercols > 1) : ?>
		</div>
	<?php endif; ?>

	<?php include_once(JVA_TEMPLATES_INCPATH . 'footer.php'); ?>
	<?php if ($this->config->foldercols > 1) : ?>
		</div>
	<?php endif; ?>
</div>
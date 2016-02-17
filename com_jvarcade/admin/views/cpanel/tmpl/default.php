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
<?php if(!empty( $this->sidebar)): ?>
<div id="j-sidebar-container" class="span2">
	<?php echo $this->sidebar; ?>
</div>
<div id="j-main-container" class="span10">
<?php else : ?>
<div id="j-main-container">
<?php endif;?>

<div class="span9">
<table cellspacing="0" cellpadding="0" border="0" ="100%">
	<tr>
		<td valign="top">
			<div id="dashboard">
			<!-- start dashboard -->
			<?php foreach($this->dashboard_buttons as $item): ?>
				<div style="float:left;">
					<div class="icon">
						<a href="<?php echo $item['link']; ?>"<?php echo array_key_exists('target',$item) ? ' target="'.$item['target'].'"' : ''; ?>>
							<img src="<?php echo JVA_IMAGES_SITEPATH; ?>cpanel/<?php echo $item['icon']; ?>" alt="<?php echo $item['label']; ?>" />
							<span><?php echo $item['label']; ?></span>
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
</div>

		
			<!-- start sliders -->
			<div class="span3">
			<?php echo JHtml::_('bootstrap.startAccordion', 'cp1'); ?>
			<?php echo JHtml::_('bootstrap.addSlide', 'cp1', JText::_('COM_JVARCADE_ABOUT'), 'jvabout'); ?> 
				<div>
					<table class="adminlist">
						<tr>
							<th colspan=2><?php echo JText::_('COM_JVARCADE_ABOUT_DESC'); ?></th>
						</tr>
						<tr>
							<td width="120" bgcolor="#FFFFFF"><strong><?php echo JText::_('COM_JVARCADE_NUM_GAMES'); ?></strong></td>
							<td bgcolor="#FFFFFF"><?php echo $this->games_count; ?></td>
						</tr>
						<tr>
							<td width="120" bgcolor="#FFFFFF"><strong><?php echo JText::_('COM_JVARCADE_NUM_SCORES'); ?></strong></td>
							<td bgcolor="#FFFFFF"><?php echo $this->scores_count;; ?></td>
						</tr>
						<tr>
							<td width="120" bgcolor="#FFFFFF"><strong><?php echo JText::_('COM_JVARCADE_INSTALLED_VERSION'); ?></strong></td>
							<td bgcolor="#FFFFFF"><?php echo JVA_VERSION; ?></td>
						</tr>
						<tr>
							<td bgcolor="#FFFFFF"><strong><?php echo JText::_('COM_JVARCADE_COPYRIGHT'); ?></strong></td>
							<td bgcolor="#FFFFFF">&copy; 2011 - <?php echo date('Y'); ?> jVitals.com</td>
						</tr>
						<tr>
							<td bgcolor="#FFFFFF"><strong><?php echo JText::_('COM_JVARCADE_LICENSE'); ?></strong></td>
							<td bgcolor="#FFFFFF">GNU GPLv3</td>
						</tr>
						<tr>
							<td bgcolor="#FFFFFF" valign="top"><strong><?php echo JText::_('COM_JVARCADE_PLUGINS_ENABLED'); ?></strong></td>
							<td bgcolor="#FFFFFF">
							<?php if ($this->sysplg_installed): ?>
								jVArcade System Plugin<br/>
							<?php endif; ?>
							<?php foreach($this->plugins as $plugin) : ?>
								<?php if (JPluginHelper::isEnabled('jvarcade', $plugin->name)): ?>
									<?php echo $plugin->name; ?><br/>
								<?php endif; ?>
							<?php endforeach; ?>
						</tr>
					</table>
				</div>
				<?php echo JHtml::_('bootstrap.endSlide'); ?>
				<?php echo JHtml::_('bootstrap.addSlide', 'cp1', JText::_('COM_JVARCADE_CHANGELOG'), 'jvchange'); ?>
				<div>
					<div id="changelog-wrapper">
						<?php echo $this->changelog; ?>
					</div>
				</div>
				<?php echo JHtml::_('bootstrap.endSlide'); ?>
				<?php echo JHtml::_('bootstrap.addSlide', 'cp1', JText::_('COM_JVARCADE_LATEST_SCORES'), 'jvscores'); ?>
				<div>
					<table class="adminlist">
						<tr>
							<th colspan="3"><?php echo JText::_('COM_JVARCADE_LATEST_SCORES_DESC');?></th>
						</tr>
						<tr>
							<td><strong><?php echo JText::_('COM_JVARCADE_PLAYER');?></strong></td>
							<td><strong><?php echo JText::_('COM_JVARCADE_GAME');?></strong></td>
							<td><strong><?php echo JText::_('COM_JVARCADE_SCORE');?></strong></td>
						</tr>
						<?php if(is_array($this->latest_scores) && count($this->latest_scores)): ?>
						<?php foreach($this->latest_scores as $score): ?>
						<tr>
							<td><?php echo ((int)$score->userid ? $score->username : $this->config->guest_name); ?></td>
							<td><?php echo $score->title; ?></td>
							<td><?php echo $score->score; ?></td>
						</tr>
						<?php endforeach; ?>
						<?php endif; ?>
						<tr>
							<th colspan="3">&nbsp;</th>
						</tr>
					</table>
				</div>
				<?php echo JHtml::_('bootstrap.endSlide'); ?>
				<?php echo JHtml::_('bootstrap.addSlide', 'cp1', JText::_('COM_JVARCADE_LATEST_GAMES'), 'jvgames'); ?>
				<div>
					<table class="adminlist">
						<tr>
							<th colspan="2"><?php echo JText::_('COM_JVARCADE_LATEST_GAMES_DESC');?></th>
						</tr>
						<tr>
							<td><strong><?php echo JText::_('COM_JVARCADE_GAME');?></strong></td>
							<td><strong><?php echo JText::_('COM_JVARCADE_HITS');?></strong></td>
						</tr>
						<?php if(is_array($this->latest_games) && count($this->latest_games)): ?>
						<?php foreach($this->latest_games as $game): ?>
						<tr>
							<td><?php echo $game->title; ?></td>
							<td><?php echo $game->numplayed; ?></td>
						</tr>
						<?php endforeach; ?>
						<?php endif; ?>				<tr>
							<th colspan="2">&nbsp;</th>
						</tr>
					</table>
				</div>
				<?php echo JHtml::_('bootstrap.endSlide'); ?>
				<?php echo JHtml::_('bootstrap.addSlide', 'cp1', JText::_('COM_JVARCADE_CREDITS'), 'jvcredits'); ?>
				<div>
					<table class="adminlist">
						<tr>
							<th>Thanks To:</th>
						</tr>
						<tr>
							<td><a href="http://www.puarcade.com">Pragma @ pragmaticutopia.com</a> for creating, nuturing and enhancing the arcade that so many people use today.</td>
						</tr>
						<tr>
							<td><a href="http://www.neave.com">Paul Neave</a> for creating all the games included with jVArcade.</td>
						</tr>
						<tr>
							<td><a href="http://www.jquery.com">jQuery</a> for the javascript library.</td>
						</tr>
					</table>
				</div>
				<?php echo JHtml::_('bootstrap.endSlide'); ?>
				<?php echo JHtml::_('bootstrap.endAccordion'); ?>
			</div>
			<!-- end sliders -->

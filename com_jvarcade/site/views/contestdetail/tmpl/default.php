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

?>
<form action="index.php" method="get" name="adminForm">
<div id="puarcade_wrapper">
	
	<?php include_once(JVA_TEMPLATES_INCPATH . 'menu.php'); ?>

	<div class="pu_heading" style="text-align: center;"><?php echo $this->tabletitle ?></div>
	<div id="cptabs" style="text-align:left;">
		<img src="<?php echo JVA_IMAGES_SITEPATH . ($this->contest->imagename ? 'contests/' . $this->contest->imagename : 'cpanel/contests.png') ; ?>" border="0" alt="" align="right"/>
		<div class="contentdescription"><?php echo $this->contest->description; ?><br/></div>
		<br />
		<?php if (!$this->contest->islimitedtoslots) : ?>
			<?php echo JText::_('COM_JVARCADE_CONTEST_NOREG'); ?><br/>
		<?php else : ?>
			<?php echo JText::_('COM_JVARCADE_CONTEST_TOTAL_SLOTS') . ' ' . $this->contest->islimitedtoslots; ?><br/>
			<?php echo JText::_('COM_JVARCADE_CONTEST_SLOTS_LEFT') . ' ' . ($this->slotsleft); ?><br/>
			<br />
			<?php if (is_array($this->members) && count($this->members) && array_key_exists($this->user->id, $this->members)) : ?>
				<?php echo JText::_('COM_JVARCADE_CONTEST_ALREADY_REGISTERED'); ?><br/>
				<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=contestregister&type=0&id=' . $this->contest->id); ?>"><?php echo JText::_('COM_JVARCADE_UNREGISTER'); ?></a>
			<?php elseif ($this->slotsleft == 0) : ?>
				<?php echo JText::_('COM_JVARCADE_CONTESTS_MAXIMUM_SLOTS_REACHED'); ?>
			<?php else : ?>
				<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=contestregister&type=1&id=' . $this->contest->id); ?>"><?php echo JText::_('COM_JVARCADE_REGISTER'); ?></a>
			<?php endif; ?>
			<br/>
		<?php endif; ?>
		<br />
		<?php echo JText::_('COM_JVARCADE_CONTESTS_START') . ': ' . jvaHelper::formatDate($this->contest->startdatetime); ?><br/>
		<?php echo JText::_('COM_JVARCADE_CONTESTS_END') . ': ' . jvaHelper::formatDate($this->contest->enddatetime); ?><br/>
		<br/>
	</div>
	
	<?php if (is_array($this->leaderboard) && count($this->leaderboard)) : ?>
	<div style="float:none; clear:both;"></div>
	<div class="pu_heading" style="text-align: center;"><?php echo JText::_('COM_JVARCADE_LEADERBOARD') ?></div>
		<div class="pu_ListContainer">
			<table class="pu_ListHeader">
				<tr>
					<th width="20%" style="text-align: center">
						<?php echo JText::_('COM_JVARCADE_LEADERBOARD_PLACE'); ?>
					</th> 
					<th width="20%" style="text-align: center;">
						<?php echo JText::_('COM_JVARCADE_LEADERBOARD_USERNAME'); ?>
					</th>
					<th width="20%" style="text-align: center;">
						<?php echo JText::_('COM_JVARCADE_AVATAR'); ?>
					</th>
					<th width="40%" style="text-align: center;">
						<?php echo JText::_('COM_JVARCADE_LEADERBOARD_POINTS'); ?>
					</th>
				</tr>
			</table>
		</div>
		<div id="FlashTable" style="margin-bottom:30px;">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<?php foreach ($this->leaderboard as $i => $entry) : ?>
				<?php $place = (in_array($i, array(0,1,2)) ? '<img src="' . JVA_IMAGES_SITEPATH . 'icons/medal_' . ($i+1) . '.gif" border="0" alt="" />'  : $i+1) ?>
				<tr class="sectiontableentry">
					<td width="20%" style="text-align: center">
						<?php echo $place; ?>
					</td>
					<td width="20%" style="text-align: center">
						<?php if ($this->config->show_avatar == 1) : ?>
							<center><?php echo jvaHelper::showAvatar($entry->userid); ?></center>
						<?php endif; ?>
					</td>
					<td width="20%" style="text-align: center">
						<center><?php echo jvaHelper::userlink($entry->userid, (!(int)$this->config->show_usernames ? $entry->name : $entry->username)); ?></center>
					</td>
					<td width="40%" style="text-align: center">
						<center><?php echo $entry->points; ?></center>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
		</div>
	<?php endif; ?>
	
	<div style="float:none; clear:both;"></div>
	
	<div class="pu_heading" style="text-align: center;"><?php echo JText::_('COM_JVARCADE_CONTESTS_CONTESTGAMES') ?></div>
	<?php if (is_array($this->games) && count($this->games)) : ?>
		<div class="pu_ListContainer">
			<table class="pu_ListHeader">
				<tr>
					<th width="10%">
					</th> 
					<th width="35%" style="text-align: center;">
						<?php echo JText::_('COM_JVARCADE_GAME_NAME'); ?>
					</th>
					<th width="40%" style="text-align: center;">
						<?php echo JText::_('COM_JVARCADE_GAME_DESC'); ?>
					</th>
					<th width="15%">
					</th>
				</tr>
			</table>
		</div>
		<div id="FlashTable">
			<table width="100%" cellpadding="0" cellspacing="0" border="0">
			<?php foreach ($this->games as $game) : ?>
				<tr class="sectiontableentry">
					<td width="10%">
						<img src="<?php echo JVA_IMAGES_SITEPATH . 'games/' . $game->imagename ; ?>" height="50" width="50" border="0" alt="" />
					</td>
					<td width="35%">
						<center><?php echo jvaHelper::truncate(stripslashes($game->title), (int)$this->config->truncate_title); ?></center>
					</td>
					<td width="40%">
						<center><?php echo $game->description; ?></center>
					</td>
					<td width="15%">
						<a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $game->id); ?>"><?php echo JText::_('COM_JVARCADE_PLAY_NOW'); ?></a>
					</td>
				</tr>
			<?php endforeach; ?>
			</table>
		</div>
	<?php else : ?>
		<?php echo JText::_('COM_JVARCADE_CONTEST_NO_GAMES'); ?>
	<?php endif; ?>
	<table id="pufooter" class="pu_Listfooter"><tr><td>&nbsp;</td></tr></table>
	
	<div style="float:none; clear:both;"></div>
	
	<?php if ($this->contest->islimitedtoslots) : ?>
		<div style="margin-top:20px; text-align:left;"><h3><?php echo JText::_('COM_JVARCADE_CONTESTS_CONTESTUSERS') ?></h3></div>
		<?php if (is_array($this->members) && count($this->members)) : ?>
			<table width="50%">
				<tr style="border:0px;text-align:left;">
					<th width="40%">
						<?php echo JText::_('COM_JVARCADE_USER'); ?>
					</th>
					<th width="60%">
						<?php echo JText::_('COM_JVARCADE_REGISTERED'); ?>
					</th>
				</tr>
				<?php foreach ($this->members as $member) : ?>
				<tr style="border:0px;text-align:left;">
					<td width="40%" style="border:0px;">
						<?php echo (!(int)$this->config->show_usernames ? $member->name : $member->username); ?>
					</td>
					<td width="60%" style="border:0px;">
						<?php echo jvaHelper::formatDate($member->dateregistered); ?>
					</td>
				</tr>
				<?php endforeach; ?>
			</table>
		</div>
		<?php else : ?>
			<div style="text-align:left;"><?php echo JText::_('COM_JVARCADE_CONTESTS_NOCONTESTUSERS'); ?></div>
		<?php endif; ?>
	<?php endif; ?>

	<?php include_once(JVA_TEMPLATES_INCPATH . 'footer.php'); ?>
	
</div>
<input type="hidden" name="option" value="com_jvarcade" />
<input type="hidden" name="task" value="<?php echo $this->task; ?>" />
<input type="hidden" name="Itemid" value="<?php echo $this->Itemid; ?>" />
</form>

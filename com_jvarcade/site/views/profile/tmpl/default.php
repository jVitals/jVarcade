<?php
/**
 * @package		jVArcade
 * @version		2.13
 * @date		2016-02-18
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */


defined('_JEXEC') or die;?>
<script>
jQuery(document).ready(function(){
	jQuery('.edit-avatar').click( 
		jQuery.jva.showUploadAvatar);
	
});
</script>
<div id="puarcade_wrapper">
<?php include_once(JVA_TEMPLATES_INCPATH . 'menu.php');?>

<div class="pu_heading" style="text-align: center;"><?php echo $this->userToProfile->username; ?>'s Profile</div>
<div class="profile-row">
<div class="avatar-clickarea">
<?php
	echo jvaHelper::showProfileAvatar($this->userToProfile->id);
	
	if ($this->user->id == $this->userToProfile->id) :?>
	<a href="#"><span class="edit-avatar">Edit Avatar</span></a>
	<?php echo JHtml::_('bootstrap.renderModal', 'avatarUpload', array('url' => JRoute::_('index.php?option=com_jvarcade&task=addgametocontest&tmpl=component&cid=' . $this->user->id, false), 'title' => 'Edit Avatar', 'height' => '300', 'width' => '600'));
	endif;
?>
</div>
</div>


	<div class="pu_block_container">
		<div class="pu_contentblock">
				<div class="pu_heading" style="text-align: center;"><?php echo JText::_('Achievements'); ?></div>
				<div id="ProTable">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<?php foreach ($this->achs as $achievements) : ?>
				<tr class="sectiontableentry">
				<td><a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $achievements['gameid'], false); ?>">
						<img src="<?php echo $achievements['icon_url'];?>" height="50px" width="50px" class="hasTooltip" data-original-title="<strong><?php echo $achievements['gametitle']; ?></strong>">
					</a>
				</td>
				<td><b class="hasTooltip" data-original-title="<strong><?php echo $achievements['description']; ?></strong>"><?php echo $achievements['title']?></b></td>
				<td><b class="hasTooltip" data-original-title="<strong>Points</strong>"><?php echo $achievements['points']?></b></td>
				</tr>
				<?php endforeach;?>
				</table>
				</div>
		</div>
		<div class="pu_AddMargin"></div>
		<div class="pu_contentblock">
				<div class="pu_heading" style="text-align: center;"><?php echo JText::_('Latest Scores'); ?></div>
				<div id="ProTable">
				<table width="100%" cellpadding="0" cellspacing="0" border="0">
				<?php foreach ($this->scores as $score) : ?>
				<tr class="sectiontableentry">
				<td><a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $score['id'], false); ?>">
						<img src="<?php echo JVA_IMAGES_SITEPATH . 'games/' . $score['imagename'];?>" height="50px" width="50px" class="hasTooltip" data-original-title="<strong><?php echo $score['title']; ?></strong>">
					</a>
				</td>
				<td><a href="<?php echo JRoute::_('index.php?option=com_jvarcade&task=game&id=' . $score['id'], false); ?>" class="hasTooltip" data-original-title="<strong><?php echo $score['description']; ?></strong>">
						<b><?php echo jvaHelper::truncate(stripslashes($score['title']), (int)$this->config->truncate_title); ?></b></a></td>
				<td><b><?php echo rtrim(rtrim(number_format($score['score'],2), '0'), '.'); ?></b>
				</tr>
				<?php endforeach;?>
				</table>
				</div>
		</div>
	</div>
	<?php include_once(JVA_TEMPLATES_INCPATH . 'footer.php'); ?>
</div>
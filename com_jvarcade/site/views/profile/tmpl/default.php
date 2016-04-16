<?php
/**
 * @package		jVArcade
 * @version		2.14
 * @date		2016-03-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */


defined('_JEXEC') or die;?>
<script>
jQuery(document).ready(function($){
	$('#edit-avatar').click( 
		jQuery.jva.showUploadAvatar);
	
});

jQuery(document).ready(function($) {
	   $('#avatarUpload').on('hide', function (){
		   $("#avatar-img").load(location.href+" #avatar-img>*",""); 
		   $('body').removeClass('modal-open');   
	   });
});
</script>
<div id="puarcade_wrapper">
<?php include_once(JVA_TEMPLATES_INCPATH . 'menu.php');?>
	<?php echo JHtml::_('bootstrap.renderModal', 'avatarUpload', array('url' => JRoute::_('index.php?option=com_jvarcade&task=uploadavatar&tmpl=component&id=' . $this->user->id, false), 'title' => JText::_('COM_JVARCADE_UPLOAD_AVATAR_MODAL_TITLE'), 'height' => '300'));?>

<div class="pu_heading" style="text-align: center;"><?php echo JText::_('COM_JVARCADE_PROFILE_TITLE'); ?></div>
	<div class="profile-row">
		
			<div class="avatar-clickarea">
				<?php if ($this->user->id == $this->userToProfile->id) :?>
					<a href="#" id="edit-avatar" class="hasTooltip" data-original-title="<strong><?php echo JText::_('COM_JVARCADE_PROFILE_EDIT_AVATAR'); ?></strong>" >
				<?php endif; ?>
					<div id="avatar-img"><?php echo jvaHelper::showProfileAvatar($this->userToProfile->id);?></div>
				<?php if ($this->user->id == $this->userToProfile->id) :?>
					</a>
				<?php endif; ?>
				<div class="user-online">
				<?php if ($this->useronline) :?>
					<img src="<?php echo JVA_IMAGES_SITEPATH . 'icons/online.png' ?>">
				<?php else :?>
					<img src="<?php echo JVA_IMAGES_SITEPATH . 'icons/offline.png' ?>">
				<?php endif; ?>
				</div>
			</div>
			<div class="pu_AddMargin"></div>
			<div class="info-area">
			<h4><?php echo JText::_('COM_JVARCADE_USERNAME') . $this->userToProfile->username; ?></h4>
			<h4>Scores: <?php echo $this->totalScores; ?></h4>
			<h4>High Scores: <?php echo $this->totalHighScores; ?></h4>
			<h4>Leaderboard Position: <?php echo $this->lbPos['id']; ?></h4>
			<h4>Leaderboard Points: <?php echo $this->lbPos['points']; ?></h4>
			</div>
	</div>


	<div class="pu_block_container">
		<div class="pu_contentblock">
			<div class="pu_heading" style="text-align: center;"><?php echo JText::_('COM_JVARCADE_PROFILE_ACHIEVEMENTS'); ?></div>
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
			<div class="pu_heading" style="text-align: center;"><?php echo JText::_('COM_JVARCADE_LATEST_SCORES'); ?></div>
				<div id="ProTable">
					<table width="100%" cellpadding="0" cellspacing="0" border="0">
					<?php foreach ($this->userLatestScores as $score) : ?>
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
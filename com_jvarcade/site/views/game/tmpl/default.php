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
// Deal with the content rating
?>
<?php if ($this->game['warningrequired']): ?>
<?php $warning =  '<div class="gamewarning">' . trim(strip_tags($this->game['rating_desc'])) . '</div><div align="center">' . JText::sprintf('COM_JVARCADE_WARNING_LEAVE' . ($this->config->window == 2 ? '_POPUP' : ''), JRoute::_('index.php?option=com_jvarcade&task=home'))  . '</div>'; ?>
<script type="text/javascript">
	window.addEvent('load', function() {
		jQuery.jva.loadModal('<?php echo str_replace('\'', '"', $warning); ?>', 'string', 500, 400);
	});
</script>
<?php endif; ?>

<div id="puarcade_wrapper">
	<?php if ($this->config->rate == 1) : ?> 
		<?php $document =& JFactory::getDocument(); $document->addScript(JVA_JS_SITEPATH . 'jquery.rating.js'); ?>
	<?php endif; ?>
	
	<?php include_once(JVA_TEMPLATES_INCPATH . 'menu.php'); ?>
	
	<?php if (!$this->game['published']) : ?>
		<?php echo JText::_('COM_JVARCADE_GAME_NOT_PUBLISHED'); ?>
	<?php elseif(!(int)$this->user->get('id') && !(int)$this->config->allow_gplay): ?>
		<?php echo JText::_('COM_JVARCADE_GUESTS_CANT_PLAY'); ?>
	<?php elseif(!$this->can_play): ?>
		<?php echo JText::_('COM_JVARCADE_GAME_NO_PLAY_PERMS'); ?>
	<?php else: ?>

		<div class="pu_heading" style="text-align: center;"><?php echo $this->game['title'] ?></div>
		<div><?php echo $this->game['description']; ?></div>
		
		<!-- SHOW CONTESTS -->
		
		<?php if ($this->user->id) : ?>
			<?php if (count($this->contests)) : ?>
			<?php foreach ($this->contests as $contest) : ?>
				<div class="pu_badcontest"><?php echo $this->displayContest($contest); ?></div>
			<?php endforeach; ?>
			<?php endif; ?>
		<?php else: ?>
			<?php if (!$this->config->allow_gsave) : ?>
				<div class="pu_cantscore"><?php echo JText::_('COM_JVARCADE_GUESTS_CANT_SCORE'); ?></div>
			<?php endif; ?>
		<?php endif; ?>
		
		<!-- RATE GAME -->
		
		<?php if ($this->config->rate) : ?>
			<div id="rate1" class="rating">
			<script type="text/javascript">
				jQuery(document).ready(function() {
					jQuery.jva.rating(<?php echo $this->game['id'];?>, <?php echo $this->game['current_vote']; ?>);
				});
			</script>
			</div>
		<?php endif; ?>

		<!-- MOCHI INTEGRATION -->
		
		<?php if ((int)$this->game['mochi'] == 1) : ?>
			<div id="leaderboard_bridge"></div>
			<script src="http://xs.mochiads.com/static/pub/swf/leaderboard.js" type="text/javascript"></script>
			<script type="text/javascript">
			<!--
				var options = {partnerID: "<?php echo $this->config->mochi_id; ?>", id: "leaderboard_bridge"};
				options.userID = "<?php echo ($this->user->id ? $this->user->id : '0'); ?>";
				options.username = "<?php echo $this->user->username; ?>";
				options.callback = function (params) {jQuery.jva.mochiScore('<?php echo $this->game['gamename']; ?>', params.score, <?php echo (int)$this->game['ajaxscore']; ?>);};
				options.globalScores = "true";
				<?php if (isset($_REQUEST['debug']) && $_REQUEST['debug']) : ?>
				options.width = 320;
				options.height = 240;
				options.debug = "true";
				<?php endif; ?>
				Mochi.addLeaderboardIntegration(options);
			-->
			</script>
			<?php if (!(int)$this->game['ajaxscore']) : ?>
			<form method="post" action="<?php echo JRoute::_(JURI::root() . 'newscore.php') ?>" id="mochi_bridge_helper_form" style="display: none;">
				<input type="hidden" name="score" value="" id="mochi_bridge_helper_form_score" />
				<input type="hidden" name="gname" value="" id="mochi_bridge_helper_form_gname" />
			</form>
			<?php endif; ?>
		<?php endif; ?>


		<!-- EMBED OBJECT -->
		
		<div class="pu_MediaObject">
			<center>
			<?php ob_start(); ?>
			<?php if (stristr($this->game['filename'], '.swf')) : ?>
			
			<!-- Flash game -->
			<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
            style="width: <?php echo $this->game['width']; ?>px; height: <?php echo $this->game['height']; ?>px;" 
            width="<?php echo $this->game['width']; ?>"
            height="<?php echo $this->game['height']; ?>" 
    		id="<?php echo $this->game['filename']; ?>" align="">
  			<param name="movie" value="<?php echo JVA_GAMES_SITEPATH . $this->game['filename']; ?>?pn_extravars=pn_uname=<?php echo $this->user->username; ?>&amp;pn_gid=<?php echo $this->game['id']; ?>" />
  			<param name="quality" value="high" />
  			<param name="wmode" value="opaque" />
  			<param name="bgcolor" value="<?php echo $this->game['background']; ?>" />
  			<param name="menu" value="false" />
  			<param name="swfversion" value="8.0.35.0" />
  <!-- This param tag prompts users with Flash Player 6.0 r65 and higher to download the latest version of Flash Player. Delete it if you don’t want users to see the prompt. -->
  	<param name="expressinstall" value="Scripts/expressInstall.swf" />
  <!-- Next object tag is for non-IE browsers. So hide it from IE using IECC. -->
  <!--[if !IE]>-->
  	<object type="application/x-shockwave-flash" data="<?php echo JVA_GAMES_SITEPATH . $this->game['filename']; ?>?pn_extravars=pn_uname=<?php echo $this->user->username; ?>&amp;pn_gid=<?php echo $this->game['id']; ?>"
  	style="width: <?php echo $this->game['width']; ?>px; height: <?php echo $this->game['height']; ?>px;"
  	width="<?php echo $this->game['width']; ?>" 
  	height="<?php echo $this->game['height']; ?>">
    <!--<![endif]-->
    <param name="quality" value="high" />
    <param name="wmode" value="opaque" />
    <param name="bgcolor" value="<?php echo $this->game['background']; ?>" />
    <param name="swfversion" value="8.0.35.0" />
    <param name="menu" value="false" />
    <param name="expressinstall" value="Scripts/expressInstall.swf" />
    <!-- The browser displays the following alternative content for users with Flash Player 6.0 and older. -->
    <div>
      <h4>Content on this page requires a newer version of Adobe Flash Player.</h4>
      <p><a href="http://www.adobe.com/go/getflashplayer"><img src="http://www.adobe.com/images/shared/download_buttons/get_flash_player.gif" alt="Get Adobe Flash player" width="112" height="33" /></a></p>
    </div>
    <!--[if !IE]>-->
  </object>
  <!--<![endif]-->
</object>
			<?php 
				$embed = ob_get_contents(); 
				ob_end_clean();
				if ($this->config->window == 2) {
					$width = $this->game['width']+30;
					echo JHtml::_('bootstrap.modal', 'swfModal');
					?>
					<style>
					div .modal {
						/* new custom width */
						width: <?php echo $width; ?>px;
						/* must be half of the width */
   						 margin-left: -<?php echo $width/2; ?>px;
    					margin-top: -50px;
					}
					#swfModal .modal-body {
						max-height: 800px;
					}
					</style>
					<div class="modal hide" id="swfModal">
						<div class="pu_heading" style="text-align: center;"><?php echo $this->game['title'] ?>
							<button type="button" class="close" data-dismiss="modal" >&times;</button>
						</div>
						<div class="modal-body"><?php echo $embed; ?></div>
					</div>
					<script type="text/javascript">
					jQuery(document).ready(function($) {
					 $('#swfModal').modal({
							backdrop: 'static',
							keyboard: true
							});
					});
					jQuery('#swfModal').on('show.bs.modal', function (e) {
						jQuery("body").css("overflow", "hidden");
					});
					jQuery('#swfModal').on('hide.bs.modal', function (e) {
						jQuery("body").css("overflow", "none");
					});
					</script>						
				<?php
				} else {
					echo $embed;
				}
				?>

			<?php elseif(stristr($this->game['filename'], '.dcr')) : ?>
			
			<!-- Director game -->
			<object classid="clsid:166B1BCA-3F9C-11CF-8075-444553540000"
					codebase="<?php echo $this->scheme; ?>download.macromedia.com/pub/shockwave/cabs/director/sw.cab#version=8,5,1,0"
					width="<?php echo $this->game['width']; ?>" 
					height="<?php echo $this->game['height']; ?>" 
					id="<?php echo $this->game['filename']; ?>" 
					align="">
				<param name="src" value="<?php echo JVA_GAMES_SITEPATH . $this->game['filename']; ?>?pn_extravars=arcade~storescore&amp;pn_modvar=option&amp;pn_modvalue=com_jvarcade&amp;pn_uname=<?php echo $this->user->username; ?>&amp;pn_gid=<?php echo $this->game['id']; ?>&amp;pn_domain=jVArcade" />
				<param name="quality" value="high" />
				<param name="bgcolor" value="<?php echo $this->game['background']; ?>" />
				<param name="menu" value="false" />
				<comment> 
					<embed
						src="<?php echo JVA_GAMES_SITEPATH . $this->game['filename']; ?>?pn_extravars=arcade~storescore&amp;pn_modvar=option&amp;pn_modvalue=com_jvarcade&amp;pn_uname=<?php echo $this->user->username; ?>&amp;pn_gid=<?php echo $this->game['id']; ?>&amp;pn_domain=jVArcade"
						quality="high" bgcolor="<?php echo $this->game['background']; ?>"
						width="<?php echo $this->game['width']; ?>"
						height="<?php echo $this->game['height']; ?>"
						name="<?php echo $this->game['filename']; ?>" align="" menu="false"
						type="application/x-director"
						pluginspage="<?php echo $this->scheme; ?>www.adobe.com/shockwave/download/">
					</embed>
					<noembed></noembed>
				</comment>
			</object>
            <?php 
				$embed = ob_get_contents(); 
				ob_end_clean();
				if ($this->config->window == 2) {
					$width = $this->game['width']+30;
					echo JHtml::_('bootstrap.modal', 'dcrModal');
			?>
					<style>
					div .modal {
						/* new custom width */
						width: <?php echo $width; ?>px;
						/* must be half of the width */
   						 margin-left: -<?php echo $width/2; ?>px;
    					margin-top: -50px;
					}
					#dcrModal .modal-body {
						max-height: 800px;
					}
					</style>
					<div class="modal hide" id="dcrModal">
						<div class="pu_heading" style="text-align: center;">
							<?php echo $this->game['title'] ?>
							<button type="button" class="close" data-dismiss="modal" >&times;</button>
						</div>
						<div class="modal-body"><?php echo $embed; ?></div>
					</div>
					<script type="text/javascript">
					jQuery(document).ready(function($) {
					 $('#dcrModal').modal({
							backdrop: 'static',
							keyboard: true
							});
					});
					jQuery('#dcrModal').on('show.bs.modal', function (e) {
						jQuery("body").css("overflow", "hidden");
					});
					jQuery('#dcrModal').on('hide.bs.modal', function (e) {
						jQuery("body").css("overflow", "none");
					});
					</script>					
					<?php
				} else {
					echo $embed;
				}
			?>	
			
			<?php elseif(stristr($this->game['filename'], '.nes')) : ?>
			
			<!-- NES Emulation for playing NES ROMS courtesy of vNES http://www.thatsanderskid.com/programming/vnes -->
			<center>
			<?php	
				// 
				$gamepath = JVA_GAMES_INCPATH . $this->game['filename'];
				$gamesize = @filesize($gamepath);
				if ($gamesize < 1000) {
					$gamesize = 24592;
				}
			?>
			<applet name="vNES" code="vNES.class" archive="<?php echo $this->baseurl . '/plugins/jvarcade/nes/vnes.jar'; ?>" width="512" height="480">
				<param name="sound" value="on" />
				<param name="timeemulation" value="on"/ >
				<param name="fps" value="off" />
				<param name="stereo" value="off" />
				<param name="rom" value="<?php echo JVA_GAMES_SITEPATH . $this->game['filename']; ?>" />
				<param name="showsoundbuffer" value="off" />
				<param name="scale" value="on" />
				<param name="scanlines" value="off" />
				<param name="nicesound" value="on" />
				<param name="romsize" value="<?php echo $gamesize;?>" />
                <param name="p1_up" value="UP">
  				<param name="p1_down" value="DOWN">
 	 			<param name="p1_left" value="LEFT">
  				<param name="p1_right" value="RIGHT">
  				<param name="p1_a" value="SLASH">
  				<param name="p1_b" value="PERIOD">
  				<param name="p1_start" value="PAGE_UP">
  				<param name="p1_select" value="PAGE_DOWN">
  				<param name="p2_up" value="W">
  				<param name="p2_down" value="S">
  				<param name="p2_left" value="A">
  				<param name="p2_right" value="D">
  				<param name="p2_a" value="SLASH">
  				<param name="p2_b" value="PERIOD">
  				<param name="p2_start" value="PAGE_UP">
  				<param name="p2_select" value="PAGE_DOWN">
			</applet>
            <?php 
				$embed = ob_get_contents(); 
				ob_end_clean();
				if ($this->config->window == 2) {
					echo JHtml::_('bootstrap.modal', 'nesModal');
					?>
					<style>
					div .modal {
						/* new custom width */
						width: 560px;
						/* must be half of the width */
   						 margin-left: -280px;
    					margin-top: -50px;
					}
					#nesModal .modal-body {
						max-height: 800px;
					}
					</style>
					<div class="modal hide" id="nesModal">
						<div class="pu_heading" style="text-align: center;">
							<?php echo $this->game['title'] ?>
							<button type="button" class="close" data-dismiss="modal" >&times;</button>
						</div>
						<div class="modal-body">
							<?php echo $embed; ?>
						</div>
					</div>
					<script type="text/javascript">
					jQuery(document).ready(function($) {
					 $('#nesModal').modal({
							backdrop: 'static',
							keyboard: true
							});
					});
					jQuery('#nesModal').on('show.bs.modal', function (e) {
						jQuery("body").css("overflow", "hidden");
					});
					jQuery('#nesModal').on('hide.bs.modal', function (e) {
						jQuery("body").css("overflow", "none");
					});
					</script>					
					<?php
				} else {
					echo $embed;
				}
			?>
			
			<?php elseif(stristr($this->game['filename'], '.gb') || stristr($this->game['filename'], '.gbc')) : ?>

			<!-- GameBoy emulation for playing GB ROMS courtesy of JavaBoy http://www.millstone.demon.co.uk/download/javaboy/ -->
			
			<applet code="JavaBoy.class" archive="<?php echo $this->baseurl . '/plugins/jvarcade/gameboy/jb.jar'; ?>" width="320" height="288">
				<param name="ROMIMAGE" value="<?php echo $this->baseurl . '/images/jvarcade/games/'. $this->game['filename']; ?>" />
				<param name="SOUND" value="off" />
				<param name="SAVERAMURL" value="<?php echo $this->baseurl; ?>/index.php?option=com_jvarcade&amp;arcade=savegbram" />
				<param name="LOADRAMURL" value="<?php echo $this->baseurl; ?>/index.php?option=com_jvarcade&amp;arcade=loadgbram" />
			</applet>
            <?php 
				$embed = ob_get_contents(); 
				ob_end_clean();
				if ($this->config->window == 2) {
					echo JHtml::_('bootstrap.modal', 'gbModal');
					?>
					<style>
					div .modal {
						/* new custom width */
						width: 350px;
						/* must be half of the width */
   						 margin-left: -175px;
    					margin-top: -50px;
					}
					#gbModal .modal-body {
						max-height: 800px;
					}
					</style>
					<div class="modal hide" id="gbModal">
						<div class="pu_heading" style="text-align: center;">
							<?php echo $this->game['title'] ?>
							<button type="button" class="close" data-dismiss="modal" >&times;</button>
						</div>
						<div class="modal-body">
							<?php echo $embed; ?>
						</div>
					</div>
					<script type="text/javascript">
					jQuery(document).ready(function($) {
					 $('#gbModal').modal({
							backdrop: 'static',
							keyboard: true
							});
					});
					jQuery('#gbModal').on('show.bs.modal', function (e) {
						jQuery("body").css("overflow", "hidden");
					});
					jQuery('#gbModal').on('hide.bs.modal', function (e) {
						jQuery("body").css("overflow", "none");
					});
					</script>					
					<?php
				} else {
					echo $embed;
				}
			?>
			
			<?php elseif(stristr($this->game['filename'], '.bin')) : ?>
			
			<!-- Atari 2600 emulation for playing Atari 2600 ROMS courtesy of javatari http://javatari.org/ -->
            <center>
			<applet archive="<?php echo $this->baseurl . '/plugins/jvarcade/atari/javatari40.jar'; ?>" code="org.javatari.main.AppletStandalone" height="603" width="654">
            <param name="background" value="16777215" />
				<param name="arg0" value="<?php echo JVA_GAMES_SITEPATH . $this->game['filename']; ?>" />
				<param name="arg1" value="-screen_cartridge_change=false" />
				Your browser does not seem to support applets.
			</applet>
            <?php
				$embed = ob_get_contents(); 
				ob_end_clean();
				if ($this->config->window == 2) {
					echo JHtml::_('bootstrap.modal', 'atariModal');
					?>
					<style>
					div .modal {
						/* new custom width */
						width: 700px;
						/* must be half of the width */
   						 margin-left: -350px;
    					margin-top: -50px;
					}
					#atariModal .modal-body {
						max-height: 800px;
					}
					</style>
					<div class="modal hide" id="atariModal">
						<div class="pu_heading" style="text-align: center;">
							<?php echo $this->game['title'] ?>
							<button type="button" class="close" data-dismiss="modal" >&times;</button>
						</div>
            			<div class="modal-body">
							<?php echo $embed; ?>
						</div>
					</div>
					<script type="text/javascript">
					jQuery(document).ready(function($) {
					 $('#atariModal').modal({
							backdrop: 'static',
							keyboard: true
							});
					});
					jQuery('#atariModal').on('show.bs.modal', function (e) {
						jQuery("body").css("overflow", "hidden");
					});
					jQuery('#atariModal').on('hide.bs.modal', function (e) {
						jQuery("body").css("overflow", "none");
					});
					</script>						
					<?php
				} else {
					echo $embed;
				}
			?>
			
            <?php elseif(stristr($this->game['filename'], '.prg')) : ?>
            <!-- Commodore 64 emulation for playing C64 .prg files courtesy of http://jac64.sourceforge.net -->
			<center>
			<applet id="c64" name="c64" code="C64Applet.class" archive="<?php echo $this->baseurl . '/plugins/jvarcade/c64/c64small.jar'; ?>" WIDTH="768" HEIGHT ="568" MAYSCRIPT>
				<param name="soundOn" value="1">
				<param name="doubleScreen" value="1">
                <param name="freescale" value="0">
				<param name="autostartPGM" value="<?php echo JVA_GAMES_SITEPATH . $this->game['filename']; ?>">
				<param name="type" value="application/x-java-applet;version=1.7.21">
				<param name="extendedKeyboard" value="1">
                <param name="userDefinedStick" value="1">
			</applet><br />
            <a href="javascript:document.c64.setStick(0)">Joystick 0</a>,
			<a href="javascript:document.c64.setStick(1)">Joystick 1</a>
            <?php
			$embed = ob_get_contents(); 
				ob_end_clean();
			if ($this->config->window == 2) {
				echo JHtml::_('bootstrap.modal', 'c64Modal');
					?>
					
					<style>
					div .modal {
						/* new custom width */
						width: 815px;
						/* must be half of the width */
   						 margin-left: -407.5px;
    					margin-top: -50px;
					}
					#c64Modal .modal-body {
						max-height: 800px;
					}
					</style>
					<div class="modal hide" id="c64Modal">
						<div class="pu_heading" style="text-align: center;">
							<?php echo $this->game['title'] ?>
							<button type="button" class="close" data-dismiss="modal" >&times;</button>
						</div>
            			<div class="modal-body">
							<?php echo $embed; ?>
						</div>
					</div>
					<script type="text/javascript">
					jQuery(document).ready(function($) {
					 $('#c64Modal').modal({
							backdrop: 'static',
							keyboard: true
							});
					});
					jQuery('#c64Modal').on('show.bs.modal', function (e) {
						jQuery("body").css("overflow", "hidden");
					});
					jQuery('#c64Modal').on('hide.bs.modal', function (e) {
						jQuery("body").css("overflow", "none");
					});
					</script>					
					<?php
				} else {
					echo $embed;
				}
			?>
            
            
			<?php elseif(stristr($this->game['filename'], '.htm') || stristr($this->game['filename'], '.html') || stristr($this->game['filename'], '.txt')) : ?>
			
			<!-- Javascript games and such -->
			<?php include(JPATH_SITE . $this->config->games_dir . $this->game['filename']); ?>
			
			<?php endif; ?>
			
			</center>
		</div>
		<br />
		<!-- BOOKMARKS -->
		<?php include_once(JVA_TEMPLATES_INCPATH . 'bookmarks.php');
        
			if ($this->config->enable_dload == 1 && $this->can_dload) : 
				?>
            	<a href="javascript:void(0)" onclick="jQuery.jva.downloadGame(<?php echo $this->game['id']; ?>); return false;">
				<img src="<?php echo JVA_IMAGES_SITEPATH; ?>dlg.png" /></a>
             <?php endif; ?>
             
		<div class="pu_block_container">
			<?php if ((int)$this->config->scoreundergame) : ?>
			<div class="pu_ScoreUnderGame">
				<!-- SCORES -->
				<?php echo $this->scores_table; ?>
			</div>
			<?php endif; ?>
			<?php if ($this->config->tagcloud == 1) : ?>
			<!-- TAG CLOUD -->
			<div class="pu_contentblock">
				<div class="pu_heading" style="text-align: center;"><?php echo JText::_('COM_JVARCADE_TAG_CLOUD'); ?></div>
				<div id="tag"></div>
			</div>
			<script type="text/javascript">
				jQuery(document).ready(function(){
					jQuery.jva.showTags(<?php echo $this->game['id']?>, 0, <?php echo isset($_REQUEST['Itemid']) ? $_REQUEST['Itemid'] : 0; ?>);
				});
			</script>
			<?php endif; ?>
			<?php if ($this->config->showstats == 1 && $this->config->tagcloud == 1) : ?>
			<div class="pu_AddMargin"></div>
			<?php endif; ?>
			<?php if ($this->config->showstats == 1) : ?>
			<!-- STATS -->
			<div class="pu_contentblock">
				<div class="pu_heading" style="text-align: center;"><?php echo JText::_('COM_JVARCADE_STATS'); ?></div>
				<table cellspacing="0" cellpadding="0" border="0" width="100%">
					<tr>
						<td><?php echo JText::_('COM_JVARCADE_GAME_TITLE'); ?></td>
						<td><?php echo $this->game['title']; ?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_JVARCADE_TIMES_PLAYED'); ?></td>
						<td><?php echo $this->game['numplayed']; ?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_JVARCADE_SCORING'); ?></td>
						<td><?php echo ($this->game['scoring'] ? JText::_('COM_JVARCADE_YES') : JText::_('COM_JVARCADE_NO') ) ?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_JVARCADE_FOLDER'); ?></td>
						<td><?php echo $this->folderpath; ?></td>
					</tr>
					<tr>
						<td><?php echo JText::_('COM_JVARCADE_SCORES'); ?></td>
						<td><?php echo $this->scorecount; ?></td>
					</tr>
					<?php if ($this->config->rate) : ?>				
					<tr>
						<td><?php echo JText::_('COM_JVARCADE_CURRENT_RATING'); ?></td>
						<td><?php echo $this->game['current_vote'] . '/5'; ?></td>
					</tr>
					<?php endif; ?>
					<?php if ($this->config->faves == 1 && $this->favoured > 0) : ?>
					<tr>
						<td><?php echo JText::_('COM_JVARCADE_FAVOURED'); ?></td>
						<td><?php echo $this->favoured; ?></td>
					</tr>
					<?php endif; ?>
				</table>
			</div>
			<?php endif; ?>
			
			<div class="pu_GamePageBottomLinks">
			<?php if ($this->config->faves == 1 ) : ?>
			<!-- ADD/REMOVE FAVOURITES-->
			<div id="fave">
			<?php if ($this->user->id): ?>
				<?php if ($this->favoured_by_me) : ?>
					<a href="#" onclick="jQuery.jva.delfave(<?php echo $this->game['id']; ?>); return false;"> 
						<img src="<?php echo JVA_IMAGES_SITEPATH; ?>red_x.png" border="0" alt="" /><?php echo JText::_('COM_JVARCADE_ALREADY_FAVORITE'); ?>
					</a>
				<?php else: ?>
					<?php if ($this->my_fav_count < $this->config->max_faves) : ?> 
						<a href="#" onclick="jQuery.jva.savefave(<?php echo $this->game['id']; ?>);return false;">
							<img src="<?php echo JVA_IMAGES_SITEPATH; ?>plus.png" border="0" hspace="3" alt="" /><?php echo JText::_('COM_JVARCADE_ADD_FAVE'); ?>
						</a>
					<?php else : ?>
						<?php echo JText::_('COM_JVARCADE_FAVES_FULL'); ?>
					<?php endif; ?>
				<?php endif; ?>
			<?php else : ?>
				<?php echo JText::_('COM_JVARCADE_LOGIN_FOR_FAVE'); ?>
			<?php endif; ?>
			</div>
			
			<?php endif; ?>
			
			<?php if ($this->config->report == 1 && $this->user->id) : ?>
				<!-- REPORT GAME -->
				<div id="gameissues">
					<a href="#" onclick="jQuery.jva.reportGame(<?php echo $this->game['id']; ?>); return false;">
						<img src="<?php echo JVA_IMAGES_SITEPATH; ?>red_x.png" border="0" alt="" /> <?php echo JText::_('COM_JVARCADE_REPORT_GAME'); ?>
					</a>
				</div>
			<?php endif; ?>
			</div>
			
		</div>
		
		<div id="comment-block">
		<?php $this->displayComments(); ?>
		</div>
		
	<?php endif; ?>

	<?php include_once(JVA_TEMPLATES_INCPATH . 'footer.php'); ?>
	
</div>
<?php echo JHTML::_('behavior.keepalive'); ?>
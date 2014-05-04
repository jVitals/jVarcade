<?php
/**
 * @package		jVArcade
 * @version		2.10
 * @date		2014-05-04
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die('Restricted access');

?>

		<div class="pu_bookmarks">
		<?php if ($this->config->bookmarks == 1) : ?>
			<?php
				$url = rawurlencode($this->bookmark_url);
				$title = 'Game From ' . $this->sitename;
			?>
			<script type="text/javascript" language="JavaScript">
				var url = '<?php echo $url; ?>';
				var title = '<?php echo $title; ?>';
			</script>
			<a onclick="window.open('http://digg.com/submit?phase=2&amp;url='+url+'&amp;title='+title);return false;" href="http://digg.com/submit?phase=2&amp;url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>" title="Digg!" target="_blank">
				<img src="<?php echo JVA_IMAGES_SITEPATH; ?>bookmarks/digg.png" alt="Digg!" title="Digg!" border="0"  />
			</a>
			<a onclick="window.open('http://reddit.com/submit?url='+url+'&amp;title='+title);return false;" href="http://reddit.com/submit?url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>" title="Reddit!" target="_blank">
				<img src="<?php echo JVA_IMAGES_SITEPATH; ?>bookmarks/reddit.png" alt="Reddit!" title="Reddit!" border="0" />
			</a>
			<a onclick="window.open('http://del.icio.us/post?url='+url+'&amp;title='+title);return false;" href="http://del.icio.us/post?url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>" title="Del.icio.us!" target="_blank">
				<img src="<?php echo JVA_IMAGES_SITEPATH; ?>bookmarks/delicious.png" alt="Del.icio.us!" title="Del.icio.us!" border="0" />
			</a>
			<a onclick="window.open('http://www.google.com/bookmarks/mark?op=add&amp;bkmk='+url+'&amp;title='+title);return false;" href="http://www.google.com/bookmarks/mark?op=add&amp;bkmk=<?php echo $url; ?>&amp;title=<?php echo $title; ?>" title="Google!" target="_blank">
				<img src="<?php echo JVA_IMAGES_SITEPATH; ?>bookmarks/google.png" alt="Google!" title="Google!" border="0" />
			</a>
			<a onclick="window.open('http://www.facebook.com/share.php?u='+url+'&amp;t='+title);return false;" href="http://www.facebook.com/share.php?u=<?php echo $url; ?>&amp;t=<?php echo $title; ?>" title="Facebook!" target="_blank">
				<img src="<?php echo JVA_IMAGES_SITEPATH; ?>bookmarks/facebook.png" alt="Facebook!" title="Facebook!" border="0" />
			</a>
			<a onclick="window.open('http://slashdot.org/bookmark.pl?url='+url+'&amp;title='+title);return false;" href="http://slashdot.org/bookmark.pl?url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>" title="Slashdot!" target="_blank">
				<img src="<?php echo JVA_IMAGES_SITEPATH; ?>bookmarks/slashdot.png" alt="Slashdot!" title="Slashdot!" border="0" />
			</a>
			<a onclick="window.open('http://www.stumbleupon.com/submit?url='+url);return false;" href="http://www.stumbleupon.com/submit?url=<?php echo $url; ?>&amp;title=<?php echo $title; ?>" title="StumbleUpon!" target="_blank">
				<img src="<?php echo JVA_IMAGES_SITEPATH; ?>bookmarks/stumbleupon.png" alt="StumbleUpon!" title="StumbleUpon!" border="0" />
			</a>
			<a onclick="window.open('http://myweb2.search.yahoo.com/myresults/bookmarklet?u='+url+'&amp;t='+title);return false;" href="http://myweb2.search.yahoo.com/myresults/bookmarklet?u=<?php echo $url; ?>&amp;t=<?php echo $title; ?>" title="Yahoo!" target="_blank">
				<img src="<?php echo JVA_IMAGES_SITEPATH; ?>bookmarks/yahoo.png" alt="Yahoo!" title="Yahoo!" border="0" />
			</a>
			<a onclick="window.open('http://technorati.com/faves/?add='+url+'&amp;title='+title);return false;" href="http://technorati.com/faves/?add=<?php echo $url; ?>" title="Technorati!" target="_blank">
				<img src="<?php echo JVA_IMAGES_SITEPATH; ?>bookmarks/technorati.png" alt="Technorati!" title="Technorati!" border="0" />
			</a>
		<?php endif;?>
		</div>
	
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

?>

	<?php if ($this->pageNav->getPagesLinks()) : ?>
	<style>
		#pagenav ul { list-style-type: none; }
	</style>
	<div id="pagenav">
		<ul class="pagination pagination-sm">
			<li><?php echo $this->pageNav->getPagesLinks(); ?></li>
			<li><?php echo $this->pageNav->getPagesCounter(); ?></li>
		</ul>
	</div>
	<?php endif; ?>
	
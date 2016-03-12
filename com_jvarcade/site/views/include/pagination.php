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

	<?php if ($this->pageNav->getPagesLinks()) : ?>

	<div id="pagenav">
		<ul class="pagination pagination-sm">
			<?php echo $this->pageNav->getPagesLinks(); ?>
			
		</ul>
		<?php echo $this->pageNav->getPagesCounter(); ?>
	</div>
	<?php endif; ?>
	
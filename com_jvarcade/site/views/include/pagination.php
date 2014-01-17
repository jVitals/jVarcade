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
	<table class="jvapagenav" border="0" width="100%">
		<thead>
			<tr>
				<th align="center"><?php echo $this->pageNav->getPagesLinks(); ?></th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td align="center">
				<center><?php echo $this->pageNav->getPagesCounter(); ?></center>
				</td>
			</tr>
		</tbody>
	</table>
	<?php endif; ?>
	
<?php
/**
 * @package		jVArcade
 * @version		2.12
 * @date		2014-05-17
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */


defined('_JEXEC') or die('Restricted access');

include_once(JVA_TEMPLATES_INCPATH . 'menu.php');?>

<div class="pu_heading" style="text-align: center;"><?php echo $this->userToProfile->username; ?>'s Profile</div></br>
This Profile is being viewed by <?php echo $this->user->username; ?></br>
<?php foreach ($this->achs as $achievements) : ?>
<img src="<?php echo $achievements['icon_url'];?>" height="40px" width="40px" ></br>

<?php endforeach;?>
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
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="adminForm" id="adminForm" method="post">
	<input type="hidden" name="field_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="savecontentrating" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id" value="<?php echo (int)$this->contentrating->id;?>" />
	<div>
	<fieldset class="adminform">
	<table class="admintable">
		<colgroup>
			<col width="150"/>
			<col width="*"/>
		</colgroup>
		<tr>
			<td class="key"><label for="name"><?php echo JText::_('COM_JVARCADE_CONTENT_RATINGS_NAME'); ?></label></td>
			<td><input type="text" id="name" name="name" value="<?php echo $this->contentrating->name;?>" size="40" /></td>
		</tr>
		<tr>
			<td class="key" valign="top"><label for="description"><?php echo JText::_('COM_JVARCADE_CONTENT_RATINGS_DESCRIPTION'); ?></label></td>
			<td><?php echo $this->editor->display('description', $this->contentrating->description, '95%', '250', '70', '15', false, array()); ?></td>
		</tr>
		<tr>
			<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTENT_RATINGS_WARNING') . '::' . JText::_('COM_JVARCADE_CONTENT_RATINGS_WARNING_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
				<label for="warningrequired"><?php echo JText::_('COM_JVARCADE_CONTENT_RATINGS_WARNING'); ?></label>
			</td>
			<td><?php echo JHTML::_('jvarcade.html.booleanlist',  'warningrequired', 'class="inputbox" size="1"', $this->contentrating->warningrequired);?></td>
		</tr>
		<tr>
			<td class="key"><label for="published"><?php echo JText::_('COM_JVARCADE_CONTENT_RATINGS_PUBLISHED'); ?></label></td>
			<td><?php echo JHTML::_('jvarcade.html.booleanlist',  'published', 'class="inputbox" size="1"', $this->contentrating->published);?></td>
		</tr>
		<tr>
			<td class="key"><label for="imagename"><?php echo JText::_('COM_JVARCADE_CONTENT_RATINGS_IMAGE'); ?></label></td>
			<td><img src="<?php echo JVA_IMAGES_SITEPATH . 'contentrating/' . $this->contentrating->imagename; ?>" /></td>
		</tr>
		<tr>
			<td class="key"><label for="image"><?php echo $this->upimage; ?></label></td>
			<td><input type="file" name="image" class="hasTip" title="<?php echo htmlspecialchars($this->upimage . '::' . $this->upimage_desc, ENT_QUOTES, 'UTF-8'); ?>" /></td>
		</tr>
	</table>
	</fieldset>
	</div>
</form>

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

JHtml::_('formbehavior.chosen', 'select');
?>
<img src="<?php echo JVA_IMAGES_SITEPATH . ($this->folder->imagename ? 'folders/' . $this->folder->imagename : 'cpanel/folder.png') ; ?>" border="0" alt="" />
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="adminForm" id="adminForm" method="post" >
	<input type="hidden" name="field_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="savefolder" />
	<input type="hidden" name="id" value="<?php echo (int)$this->folder->id;?>" />
	<fieldset class="adminform">
	<table class="admintable">
		<colgroup>
			<col width="150"/>
			<col width="*"/>
		</colgroup>
		<tr>
			<td class="key"><label for="parentid"><?php echo JText::_('COM_JVARCADE_FOLDERS_PARENT'); ?></label></td>
			<td><?php echo $this->list_folders($this->folder->parentid);?></td>
		</tr>
		<tr>
			<td class="key"><label for="name"><?php echo JText::_('COM_JVARCADE_FOLDERS_NAME'); ?></label></td>
			<td><input type="text" class="inputbox" id="name" name="name" value="<?php echo $this->folder->name;?>" size="40" /></td>
		</tr>
		<tr>
			<td class="key hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_FOLDERS_ALIAS') . '::' . JText::_('COM_JVARCADE_FOLDERS_ALIAS_DESC'), ENT_QUOTES, 'UTF-8'); ?>">
				<label for="alias"><?php echo JText::_('COM_JVARCADE_FOLDERS_ALIAS'); ?></label>
			</td>
			<td><input type="text" class="inputbox" id="alias" name="alias" value="<?php echo $this->folder->alias;?>" size="40" /></td>
		</tr>
		<tr>
			<td class="key"><label for="image"><?php echo JText::_('COM_JVARCADE_FOLDERS_IMAGE'); ?></label></td>
			<td><input type="file" name="image" class="hasTip" title="<?php echo htmlspecialchars(JText::_('COM_JVARCADE_FOLDERS_IMAGE') . '::' . JText::_('COM_JVARCADE_FOLDERS_IMAGE_DESC'), ENT_QUOTES, 'UTF-8'); ?>" /></td>
		</tr>
		<tr>
			<td class="key" valign="top"><label for="description"><?php echo JText::_('COM_JVARCADE_FOLDERS_DESCRIPTION'); ?></label></td>
			<td><?php echo $this->editor->display('description', $this->folder->description, '60%', '70', '30', '50', false, array()); ?></td>
		</tr>
		<tr>
			<td class="key"><label for="published"><?php echo JText::_('COM_JVARCADE_FOLDERS_PUBLISHED'); ?></label></td>
			<td><?php echo JHTML::_('jvarcade.html.booleanlist',  'published', 'class="inputbox" size="1"', $this->folder->published) ;?></td>
		</tr>
		<tr>
			<td class="key"><label for="viewpermissions"><?php echo JText::_('COM_JVARCADE_FOLDERS_VIEWPERMISSIONS'); ?></label></td>
			<td><?php echo JHTML::_('jvarcade.html.usergroup',  'viewpermissions[]', $this->folder->viewpermissions,  'multiple', $this->gtree) ;?></td>
		</tr>
	</table>
	</fieldset>
	</div>
</form>

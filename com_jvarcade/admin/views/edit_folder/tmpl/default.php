<?php
/**
 * @package		jVArcade
 * @version		2.13
 * @date		2016-02-18
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die('Restricted access');

JHtml::_('formbehavior.chosen', 'select');
JHtml::_('bootstrap.tooltip');
?>
<img src="<?php echo JVA_IMAGES_SITEPATH . ($this->folder->imagename ? 'folders/' . $this->folder->imagename : 'cpanel/folder.png') ; ?>" border="0" alt="" />
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="adminForm" id="adminForm" method="post" >
	<input type="hidden" name="field_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="savefolder" />
	<input type="hidden" name="id" value="<?php echo (int)$this->folder->id;?>" />
	<div class="row-fluid">
				<div class="span6">
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="parentid"><?php echo JText::_('COM_JVARCADE_FOLDERS_PARENT'); ?></label>
							</div>
							<div class="controls">
								<?php echo $this->list_folders($this->folder->parentid);?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="name"><?php echo JText::_('COM_JVARCADE_FOLDERS_NAME'); ?></label>
							</div>
							<div class="controls">
								<input type="text" class="inputbox" id="name" name="name" value="<?php echo $this->folder->name;?>" size="40" />
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="alias" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_FOLDERS_ALIAS') . '</strong><br>' . JText::_('COM_JVARCADE_FOLDERS_ALIAS_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_FOLDERS_ALIAS'); ?></label>
							</div>
							<div class="controls">
								<input type="text" class="inputbox" id="alias" name="alias" value="<?php echo $this->folder->alias;?>" size="40" />
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="image"><?php echo JText::_('COM_JVARCADE_FOLDERS_IMAGE'); ?></label>
							</div>
							<div class="controls">
								<input type="file" name="image" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_FOLDERS_IMAGE') . '</strong><br>' . JText::_('COM_JVARCADE_FOLDERS_IMAGE_DESC'), ENT_QUOTES, 'UTF-8'); ?>" />
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="description"><?php echo JText::_('COM_JVARCADE_FOLDERS_DESCRIPTION'); ?></label>
							</div>
							<div class="controls">
								<?php echo $this->editor->display('description', $this->folder->description, '60%', '70', '30', '50', false, array()); ?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="published"><?php echo JText::_('COM_JVARCADE_FOLDERS_PUBLISHED'); ?></label>
							</div>
							<div class="controls">
								<?php echo JHTML::_('jvarcade.html.booleanlist',  'published', 'class="inputbox" size="1"', $this->folder->published, 'JYES', 'JNO', 'fpublished');?>
							</div>
						</div>
					</fieldset>
					<fieldset class="form-horizontal">
						<div class="control-group">
							<div class="control-label">
								<label for="viewpermissions"><?php echo JText::_('COM_JVARCADE_FOLDERS_VIEWPERMISSIONS'); ?></label>
							</div>
							<div class="controls">
								<?php echo JHTML::_('jvarcade.html.usergroup',  'viewpermissions[]', $this->folder->viewpermissions,  'multiple') ;?>
							</div>
						</div>
					</fieldset>
				</div>
	</div>
</form>

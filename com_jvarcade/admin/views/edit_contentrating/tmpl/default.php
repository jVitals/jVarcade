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
JHtml::_('bootstrap.tooltip');
?>
<style>
.control-label {width: 30%!important;}
</style>
<form enctype="multipart/form-data" action="<?php echo $_SERVER['PHP_SELF']; ?>" name="adminForm" id="adminForm" method="post">
	<input type="hidden" name="field_save" value="1" />
	<input type="hidden" name="option" value="com_jvarcade" />
	<input type="hidden" name="task" value="savecontentrating" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id" value="<?php echo (int)$this->contentrating->id;?>" />
	<div class="row-fluid">
		<div class="span6">
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="name"><?php echo JText::_('COM_JVARCADE_CONTENT_RATINGS_NAME'); ?></label>
					</div>
					<div class="controls">
						<input type="text" id="name" name="name" value="<?php echo $this->contentrating->name;?>" size="40" />
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="description"><?php echo JText::_('COM_JVARCADE_CONTENT_RATINGS_DESCRIPTION'); ?></label>
					</div>
					<div class="controls">
						<?php echo $this->editor->display('description', $this->contentrating->description, '95%', '250', '70', '15', false, array()); ?>
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="warningrequired" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars(JText::_('COM_JVARCADE_CONTENT_RATINGS_WARNING') . '</strong><br>' . JText::_('COM_JVARCADE_CONTENT_RATINGS_WARNING_DESC'), ENT_QUOTES, 'UTF-8'); ?>"><?php echo JText::_('COM_JVARCADE_CONTENT_RATINGS_WARNING'); ?></label>
					</div>
					<div class="controls">
						<?php echo JHTML::_('jvarcade.html.booleanlist',  'warningrequired', 'class="inputbox" size="1"', $this->contentrating->warningrequired, 'JYES', 'JNO', 'warningrequired');?>
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="published"><?php echo JText::_('COM_JVARCADE_CONTENT_RATINGS_PUBLISHED'); ?></label>
					</div>
					<div class="controls">
						<?php echo JHTML::_('jvarcade.html.booleanlist',  'published', 'class="inputbox" size="1"', $this->contentrating->published, 'JYES', 'JNO', 'published');?>
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="imagename"><?php echo JText::_('COM_JVARCADE_CONTENT_RATINGS_IMAGE'); ?></label>
					</div>
					<div class="controls">
						<img src="<?php echo JVA_IMAGES_SITEPATH . 'contentrating/' . $this->contentrating->imagename; ?>" />
					</div>
				</div>
			</fieldset>
			<fieldset class="form-horizontal">
				<div class="control-group">
					<div class="control-label">
						<label for="image"><?php echo $this->upimage; ?></label>
					</div>
					<div class="controls">
						<input type="file" name="image" class="hasTooltip" data-original-title="<strong><?php echo htmlspecialchars($this->upimage . '</strong><br>' . $this->upimage_desc, ENT_QUOTES, 'UTF-8'); ?>" />
					</div>
				</div>
			</fieldset>
		</div>
	</div>
</form>

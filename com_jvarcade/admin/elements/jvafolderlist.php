<?php
/**
* This file is part of the jVArcade distribution. 
* Detailed copyright and licensing information can be found
* in the gpl-3.0.txt file which should be included in the distribution.
* 
* @version		2.01 2013-07-29 nuclear-head
* @copyright	2011-2013 jVitals
* @license		GPLv3 Open Source
* @link			http://jvitals.com
* @since		File available since initial release
*/

// no direct access
defined('_JEXEC') or die('Restricted access');

$JVersion = new JVersion();
$version = $JVersion->getShortVersion();

if (version_compare($version, '1.6.0', 'ge')) {

	jimport('joomla.html.html');
	jimport('joomla.form.formfield');

	class JFormFieldJvaFolderList extends JFormField {
		protected $type = 'JvaFolderList';
	 
		protected function getInput() {
			$options = array();
			$attr = '';
			
			// build attributes
			$attr .= $this->element['class'] ? ' class="'.(string) $this->element['class'].'"' : '';
			if ( (string) $this->element['readonly'] == 'true' || (string) $this->element['disabled'] == 'true') {
				$attr .= ' disabled="disabled"';
			}
			$attr .= $this->element['size'] ? ' size="'.(int) $this->element['size'].'"' : '';
			$attr .= $this->multiple ? ' multiple="multiple"' : '';
			$attr .= $this->element['onchange'] ? ' onchange="'.(string) $this->element['onchange'].'"' : '';
			
			// get folders
			$db = JFactory::getDbo();
			$db->setQuery('SELECT id as value, name as text FROM #__jvarcade_folders WHERE published = 1 ');
			$folders = $db->loadObjectList();
			
			return JHTML::_('select.genericlist', $folders, $this->name, trim($attr), 'value', 'text', $this->value );
		}
	}

} else {

	class JElementJvaFolderList extends JElement {
		var	$_name = 'JvaFolderList';
	 
		function fetchElement($name, $value, &$node, $control_name) {
			$ctrl	= $control_name .'['. $name .']';
			$attribs = '';
			$folders = array();

			// build attributes
			$attribs	= ' ';
			if ($v = $node->attributes( 'size' )) {
				$attribs .= 'size="'.$v.'"';
			}
			if ($v = $node->attributes( 'class' )) {
				$attribs .= 'class="'.$v.'"';
			} else {
				$attribs .= 'class="inputbox"';
			}
			if ($m = $node->attributes( 'multiple' )) {
				$attribs .= ' multiple="multiple"';
				$ctrl .= '[]';
			}
			
			// get folders
			$db = JFactory::getDbo();
			$db->setQuery('SELECT id as value, name as text FROM #__jvarcade_folders WHERE published = 1 ');
			$folders = $db->loadObjectList();
			
			return JHTML::_('select.genericlist', $folders, $ctrl, $attribs, 'value', 'text', $value, $control_name.$name);
		}
	}

}


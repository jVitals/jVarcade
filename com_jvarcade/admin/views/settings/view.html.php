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

jimport('cms.html.bootstrap');
//jimport('joomla.application.component.view');

class jvarcadeViewSettings extends JViewLegacy {
	var $acl;
	var $gtree;
	var $comment_data;

	function display($tpl = null) {
		

		$model = $this->getModel();
		$this->acl = JFactory::getACL();
		$this->gtree = JVA_COMPATIBLE_MODE == '15' ? $this->acl->get_group_children_tree(null, 'USERS', false) : array();
		$mainframe = JFactory::getApplication();
		$task = $mainframe->input->get('task', 'settings');
		$this->task = $this->get($task);
		
		$confdb = $model->getConf();
		$conf = array('general' => array(), 'integration' => array(), 'frontend' => array());
		foreach ($confdb as $obj) {
			$conf[$obj['group']][] = array(
				'optname' => $obj['optname'],
				'value' => $obj['value'],
				'description' => $obj['description'],
				'type' => $obj['type'],
			);
		}

		
		$this->assignRef('conf', $conf);
		
		$editor = JFactory::getEditor();
		$this->assignRef('editor', $editor);
		
		$editor_params = array('mode' => 'advanced');
		$this->assignRef('editor_params', $editor_params);
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_SETTINGS'), 'jvasettings');
		JToolBarHelper::custom('settings', 'save.png', 'save.png', JText::_('COM_JVARCADE_SAVE_SETTINGS'), false, false);
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('settings');
		

		// Comments
		$db	= JFactory::getDbo();
		$sql = JVA_COMPATIBLE_MODE == '15' 
				? "SELECT `option`, enabled FROM #__components WHERE parent = 0 AND `option` IN ('com_comment', 'com_jcomments', 'com_jacomment')"
				: "SELECT element as `option`, enabled FROM #__extensions WHERE `type` = 'component' AND element IN ('com_comment', 'com_jcomments', 'com_jacomment')";
		$db->setQuery($sql);
		$this->comment_data = $db->loadAssocList('option');

		parent::display($tpl);
	}
	
	protected function addSidebar() {
		$this->sidebar = JHtmlSidebar::render();
	}
	
	function template_list($active, $label) {
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.file');
		$files = JFolder::files(JVA_CSS_INCPATH, '.css');
		$list = '<select name="' . $label . '">';
		foreach ($files as $file) {
			$file = str_replace('.css', '', $file);
			$list .= '<option value="' . $file . '"';
			if ($file == $active) {
				$list .= 'selected';
			}
			$list .= '>' . $file . '</option>';
		}
		$list.='</select>';
		return $list;
	}
	
	function showSetting($arr) {
		switch ($arr['type']) {
			case 'label' :
				$result = $arr['value'];
				break;
			case 'text' :
				$result = '<input class="inputbox" type="text" name="' . $arr['optname'] . '" id="' . $arr['optname'] . '" value="' . $arr['value'] . '" style="width:50%;" />';
				break;
			case 'yesno' :
				$result = JHTML::_('jvarcade.html.booleanlist',  $arr['optname'], 'size="1"', $arr['value']);
				break;
			default :
				$result = '';
				break;
		}
		switch ($arr['optname']) {
			case 'homepage_order' :
				$result = '<select name="homepage_order" id="homepage_order">
						<option value="1"' . (trim($arr['value']) == 1 ? ' selected' : '') . '>' . JText::_('COM_JVARCADE_HOMEPAGE_ORDER_1') . '</option>
						<option value="2"' . (trim($arr['value']) == 2 ? ' selected' : '') . '>' . JText::_('COM_JVARCADE_HOMEPAGE_ORDER_2') . '</option>
					</select>';
				break;
			case 'homepage_order_dir' :
				$result = '<select name="homepage_order_dir" id="homepage_order_dir">
						<option value="1"' . (trim($arr['value']) == 1 ? ' selected' : '') . '>' . JText::_('COM_JVARCADE_HOMEPAGE_ORDER_DIR_1') . '</option>
						<option value="2"' . (trim($arr['value']) == 2 ? ' selected' : '') . '>' . JText::_('COM_JVARCADE_HOMEPAGE_ORDER_DIR_2') . '</option>
					</select>';
				break;
			case 'window' :
				$result = '<select name="window" id="window">
						<option value="1"' . (trim($arr['value']) == 1 ? ' selected' : '') . '>' . JText::_('COM_JVARCADE_MAIN_WINDOW') . '</option>
						<option value="2"' . (trim($arr['value']) == 2 ? ' selected' : '') . '>' . JText::_('COM_JVARCADE_NEW_WINDOW') . '</option>
					</select>';
				break;
			case 'TagPerms' :
				$result = JHTML::_('jvarcade.html.usergroup',  'TagPerms[]', $arr['value'], ' multiple size="11" ', $this->gtree);
					break;
			case 'DloadPerms' :
				$result = JHTML::_('jvarcade.html.usergroup',  'DloadPerms[]', $arr['value'], ' multiple size="11" ', $this->gtree);
				break;
			case 'template' :
				$result = $this->template_list($arr['value'], 'template');
				break;
			case 'comments' :
				$item = array(
					JHTML::_( 'select.option', 0, 'None' ),
					JHTML::_( 'select.option', 1, 'CompoJoom Comment' ),
					JHTML::_( 'select.option', 2, 'JComments' ),
					JHTML::_( 'select.option', 3, 'JA Comment' ),
				);
				$result = JHTML::_('jvarcade.html.radiolist', $item, 'comments', 'class="inputbox"', 'value', 'text', $arr['value'], 'comments' );
				break;
			case 'scorelink' :
				$item = array(
					JHTML::_( 'select.option', 0, 'None' ),
					JHTML::_( 'select.option', 1, 'JomSocial' ),
					JHTML::_( 'select.option', 2, 'Community Builder' ),
				);
				$result = JHTML::_('jvarcade.html.radiolist', $item, 'scorelink', 'class="inputbox" ', 'value', 'text', $arr['value'], 'scorelink' );
				break;
			default :
				break;
		}
		return $result;
	}
	
	function showCommentsLegend($optname) {
		$ret = '';
		if ($optname == 'comments') {
			$compojoom_enabled = $this->componentEnabled($this->comment_data, 'com_comment') ? '<span style="color:green;">' . JText::_('YES') . '</span>' : '<span style="color:red;">' . JText::_('NO') . '</span>';
			$jcomments_enabled = $this->componentEnabled($this->comment_data, 'com_jcomments') ? '<span style="color:green;">' . JText::_('YES') . '</span>' : '<span style="color:red;">' . JText::_('NO') . '</span>';
			$jacomment_enabled = $this->componentEnabled($this->comment_data, 'com_jacomment') ? '<span style="color:green;">' . JText::_('YES') . '</span>' : '<span style="color:red;">' . JText::_('NO') . '</span>';
			$ret = '<tr><td class="key"></td><td>' . JText::sprintf('COM_JVARCADE_OPT_COMMENTS_LEGEND', $compojoom_enabled, $jcomments_enabled, $jacomment_enabled) . '</td></tr>';
		}
		return $ret;
	}
	
	function componentEnabled(&$comment_data, $name) {
		return (is_array($comment_data) && count($comment_data) && isset($comment_data[$name]) && isset($comment_data[$name]['enabled']) && (int)$comment_data[$name]['enabled']);
	}
}

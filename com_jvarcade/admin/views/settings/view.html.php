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

class jvarcadeViewSettings extends JViewLegacy {
	var $acl;
	var $gtree;
	var $comment_data;

	function display($tpl = null) {
	
		$model = $this->getModel();
		$this->acl = JFactory::getACL();
		$this->gtree = array();
		$app = JFactory::getApplication();
		$task = $app->input->getCmd('task', 'settings');
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
		
		$this->conf = $conf;
		$editor = JFactory::getEditor();
		$this->editor = $editor;
		
		$editor_params = array('mode' => 'advanced');
		$this->editor_params = $editor_params;
		
		JToolBarHelper::title(JText::_('COM_JVARCADE_SETTINGS'), 'jvasettings');
		JToolBarHelper::custom('settingssave', 'save.png', 'save.png', JText::_('COM_JVARCADE_SAVE_SETTINGS'), false, false);
		jvarcadeToolbarHelper::addSubmenu($this->getName());
		$this->addSidebar('settings');

		// Comments
		$db	= JFactory::getDbo();
		$db->setQuery("SELECT element as `option`, enabled FROM #__extensions WHERE `type` = 'component' AND element IN ('com_comment', 'com_jcomments', 'com_jacomment')");
		$this->comment_data = $db->loadAssocList('option');

		parent::display($tpl);
	}
	
	protected function addSidebar() {
		$this->sidebar = JHtmlSidebar::render();
	}
	
	function showSetting($arr) {
		switch ($arr['type']) {
			case 'label' :
				$result = $arr['value'];
				break;
			case 'text' :
				$result = '<input class="inputbox" type="text" name="' . $arr['optname'] . '" id="' . $arr['optname'] . '" value="' . $arr['value'] . '" />';
				break;
			case 'yesno' :
				$result = JHtml::_('jvarcade.html.booleanlist',  $arr['optname'], 'size="1"', trim($arr['value']), 'JYES', 'JNO', $arr['optname']);
				break;
			default :
				$result = '';
				break;
		}
		switch ($arr['optname']) {
			case 'homepage_view' :
		jimport('joomla.filesystem.folder');
				jimport('joomla.filesystem.file');
				$files = JFolder::files(JVA_HOMEVIEW_INCPATH, '.php');
				$opts = array();
				foreach ($files as $file) {
					$file = str_replace('.php', '', $file);
					$opts[$file] = ucfirst($file);
				}
				$result = JHtml::_('select.genericlist', $opts, $arr['optname'], null, 'value', 'text', trim($arr['value']), $arr['optname']);
				break;
			case 'homepage_order' :
				$opts = array(1 => JText::_('COM_JVARCADE_HOMEPAGE_ORDER_1'), 2 => JText::_('COM_JVARCADE_HOMEPAGE_ORDER_2'));
				$result = JHtml::_('select.genericlist', $opts, $arr['optname'], null, 'value', 'text', trim($arr['value']), $arr['optname']);
				break;
			case 'homepage_order_dir' :
				$opts = array(1 => JText::_('COM_JVARCADE_HOMEPAGE_ORDER_DIR_1'), 2 => JText::_('COM_JVARCADE_HOMEPAGE_ORDER_DIR_2'));
				$result = JHtml::_('select.genericlist', $opts, $arr['optname'], null, 'value', 'text', trim($arr['value']), $arr['optname']);
				break;
			case 'window' :
				$opts = array(1 => JText::_('COM_JVARCADE_MAIN_WINDOW'), 2 => JText::_('COM_JVARCADE_NEW_WINDOW'));
				$result = JHtml::_('select.genericlist', $opts, $arr['optname'], null, 'value', 'text', trim($arr['value']), $arr['optname']);
				break;
			case 'TagPerms' :
				$result = JHtml::_('jvarcade.html.usergroup',  'TagPerms[]', $arr['value'], ' multiple size="11" ', $this->gtree);
					break;
			case 'DloadPerms' :
				$result = JHtml::_('jvarcade.html.usergroup',  'DloadPerms[]', $arr['value'], ' multiple size="11" ', $this->gtree);
				break;
			case 'template' :
				jimport('joomla.filesystem.folder');
				jimport('joomla.filesystem.file');
				$files = JFolder::files(JVA_CSS_INCPATH, '.css');
				$opts = array();
				foreach ($files as $file) {
					$file = str_replace('.css', '', $file);
					$opts[$file] = ucfirst($file);
				}
				$result = JHtml::_('select.genericlist', $opts, $arr['optname'], null, 'value', 'text', trim($arr['value']), $arr['optname']);			
				break;
			case 'comments' :
				$opts = array(
					JHtml::_( 'select.option', 0, 'None' ),
					JHtml::_( 'select.option', 1, 'CComment' ),
					JHtml::_( 'select.option', 2, 'JComments' ),
					
				);
				$result = JHtml::_('jvarcade.html.radiolist', $opts, $arr['optname'], null, 'value', 'text', trim($arr['value']), $arr['optname']);
				break;
			case 'scorelink' :
				$opts = array(
					JHtml::_( 'select.option', 0, 'None' ),
					JHtml::_( 'select.option', 1, 'JomSocial' ),
					JHtml::_( 'select.option', 2, 'Community Builder' ),
					JHtml::_( 'select.option', 3, 'Alta User Points'),
				);
				$result = JHtml::_('jvarcade.html.radiolist', $opts, $arr['optname'], null, 'value', 'text', trim($arr['value']), $arr['optname']);
				break;
			default :
				break;
		}
		
		
		return $result;
	}
	
	function showCommentsLegend() {
		$compojoom_enabled = $this->componentEnabled($this->comment_data, 'com_comment') ? '<span style="color:green;">' . JText::_('YES') . '</span>' : '<span style="color:red;">' . JText::_('NO') . '</span>';
		$jcomments_enabled = $this->componentEnabled($this->comment_data, 'com_jcomments') ? '<span style="color:green;">' . JText::_('YES') . '</span>' : '<span style="color:red;">' . JText::_('NO') . '</span>';
		$ret = JText::sprintf('COM_JVARCADE_OPT_COMMENTS_LEGEND', $compojoom_enabled, $jcomments_enabled);
		return $ret;
	}
	
	function componentEnabled(&$comment_data, $name) {
		return (is_array($comment_data) && count($comment_data) && isset($comment_data[$name]) && isset($comment_data[$name]['enabled']) && (int)$comment_data[$name]['enabled']);
	}
}

<?php
/**
* @package		jVArcade
* @version		2.13
* @date		2016-02-18
* @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
* @link		http://jvitals.com
*/

defined('JPATH_BASE') or die;

JFormHelper::loadFieldClass('list');



class JFormFieldFolders extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since   1.6
	 */
	protected $type = 'folders';

	/**
	 * Method to get the field options.
	 *
	 * @return  array  The field option objects.
	 *
	 * @since   1.6
	 */
	
	public function getFolderList() {
		$this->dbo = JFactory::getDbo();
		$this->dbo->setQuery('SELECT id AS value, name As text FROM #__jvarcade_folders ORDER BY id');
		$options = $this->dbo->loadObjectList();

		array_unshift($options, JHtml::_('select.option', '0', JText::_('COM_JVARCADE_GAMES_FILTERBYFOLDER')));
		
		return $options;
	}
	
	public function getOptions()
	{
		$options = $this->getFolderList();

		return array_merge(parent::getOptions(), $options);
	}
	
}
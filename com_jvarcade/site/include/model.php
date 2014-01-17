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

//jimport('joomla.application.component.model');

class jvarcadeModelCommon extends JModelLegacy {

	var $config = null;
	var $global_conf = null;
	var $dbo = null;
	var $user = null;
	var $_pagination = null;
	var $_orderby = null;
	var $_orderdir = null;

	function __construct() {
	
		parent::__construct();
		$this->dbo = JFactory::getDbo();
		$this->user = JFactory::getUser();
		$this->config = $this->getConf();
		$this->global_conf = JFactory::getConfig();
 
        // Get pagination request variables
		$input = JFactory::getApplication()->input;
		$limit = $this->config->GamesPerPage;
        $limitstart = $input->get('limitstart', 0, '', 'int');
 
        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);
 
        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
	}

	public static function getInst() {
		static $instance;
		
		if (!isset( $instance )) {
			$instance = null;
		}
		if (empty($instance)) {
			$c = __CLASS__;
			$instance = new $c;
		}
		
		return $instance;
	}
	
	function getTotal() {
		$this->dbo->setQuery('SELECT FOUND_ROWS();');
		return (int)$this->dbo->loadResult();
	}
	
	function getPagination() {
		// Load the content if it doesn't already exist
		if (empty($this->_pagination)) {
			jimport('joomla.html.pagination');
			$this->_pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
	
	function setOrderBy($orderby) {
		$this->_orderby = $orderby;
	}

	function setOrderDir($orderdir) {
		$this->_orderdir = $orderdir;
	}

	function getConf() {
		if (!$this->config) {
			$this->_loadConf();
		}
		return $this->config;
	}
	
	private function _loadConf() {
		static $loadedconf;
		if (!$loadedconf) {
			$my = JFactory::getUser();
			$app = JFactory::getApplication();
			$this->dbo->setQuery("SELECT * FROM #__jvarcade_settings ORDER BY " . $this->dbo->quoteName('group') . ", " . $this->dbo->quoteName('ord') . "");
			$res = $this->dbo->loadObjectList();
			$obj = new stdClass();
			if (count($res)) {
				foreach ($res as $row) {
					$optname = $row->optname;
					$obj->$optname = $row->value;
				}
			}
			
			// TIMEZONE - if user is logged in we use the user timezone, if guest - we use timezone in global settings
			$obj->timezone = ((int)$my->guest ? $app->getCfg('offset') : $my->getParam('timezone', $app->getCfg('offset')));
			
			$this->config = $loadedconf = $obj;
			return (boolean)$this->config;
		} else {
			$this->config = $loadedconf;
		}
		return true;
	}

}

?>

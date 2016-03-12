<?php
/**
 * @package		jVArcade
 * @version		2.14
 * @date		2016-03-12
 * @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
 * @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
 * @link		http://jvitals.com
 */



// no direct access
defined('_JEXEC') or die('Restricted access');

require_once ('define.php');
require_once (JPATH_COMPONENT . '/controller.php');
require_once (JPATH_COMPONENT . '/controllers/score.php');
require_once ('model.php');
require_once (JVA_HELPERS_INCPATH . 'helper.php');

// Load jVArcade configuration
$conf = jvarcadeModelCommon::getInst();
$config = $conf->getConf();

// define time/date formats
define('COM_JVARCADE_DATE_FORMAT', $config->date_format);
define('COM_JVARCADE_TIME_FORMAT', $config->time_format);
define('COM_JVARCADE_TIMEZONE', $config->timezone);

// Javascript includes and declarations
$document = JFactory::getDocument();


$jsconstants = 'var JVA_HOST_NAME = \'' . JURI::base() . '\';' . "\n";
$jsconstants .= 'var JVA_AJAX_URL = \'' . JURI::base() . '\';' . "\n";
$jsconstants .= 'var JVA_AJAX_RATING_URL = JVA_HOST_NAME + \'index.php?option=com_jvarcade&task=rategame&format=raw&gid=\';' . "\n";
$jsconstants .= 'var JVA_MAIN_URL = JVA_HOST_NAME + \'index.php\';' . "\n";
$document->addScriptDeclaration($jsconstants);
JHtml::_('jquery.framework');
JHtml::script('com_jvarcade/jquery.jva.js', false, true);

// Load the puarcade plugins
$dispatcher = JDispatcher::getInstance();
JPluginHelper::importPlugin('puarcade', null, true, $dispatcher);
JPluginHelper::importPlugin('jvarcade', null, true, $dispatcher);

// Load styles
$css = (strlen($config->template) && $config->template && file_exists(JVA_CSS_INCPATH . $config->template . '.css')) ? $config->template : 'default' ;
$document->addStyleSheet(JVA_CSS_SITEPATH . $css . '.css');
if((int)$config->roundcorners) {
	$document->addStyleSheet(JVA_CSS_SITEPATH . '/smoothness/round.corners.css');
}

?>

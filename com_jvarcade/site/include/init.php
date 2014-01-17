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

require_once ('define.php');
require_once (JPATH_COMPONENT . '/controller.php');
require_once (JPATH_COMPONENT . '/controllers/score.php');
require_once ('model.php');
require_once (JVA_HELPERS_INCPATH . 'helper.php');

// Load jVArcade configuration
$conf = jvarcadeModelCommon::getInst();
$config = $conf->getConf();

// Javascript includes and declarations
JHTML::_('behavior.modal', 'a.modal');
$document = JFactory::getDocument();
if((int)$config->load_jquery) {
	$document->addScript(JVA_JS_SITEPATH . 'jquery-1.6.2.min.js');
	$document->addScript(JVA_JS_SITEPATH . 'jquery-noconflict.js');
	$document->addScript(JVA_JS_SITEPATH . 'jquery-ui-1.8.16.min.js');
	$document->addScript(JVA_JS_SITEPATH . 'jquery.js');
}

$document->addScript(JVA_JS_SITEPATH . 'jquery.jva.js');
$jsconstants = 'var JVA_HOST_NAME = \'' . JURI::base() . '\';' . "\n";
$jsconstants .= 'var JVA_AJAX_URL = \'' . JURI::base() . '\';' . "\n";
$jsconstants .= 'var JVA_AJAX_RATING_URL = JVA_HOST_NAME + \'index.php?option=com_jvarcade&task=rategame&format=raw&gid=\';' . "\n";
$jsconstants .= 'var JVA_MAIN_URL = JVA_HOST_NAME + \'index.php\';' . "\n";
$document->addScriptDeclaration($jsconstants);

// Load the puarcade plugins
$dispatcher = JDispatcher::getInstance();
JPluginHelper::importPlugin('puarcade', null, true, $dispatcher);
JPluginHelper::importPlugin('jvarcade', null, true, $dispatcher);

// Load styles
$css = (strlen($config->template) && $config->template && file_exists(JVA_CSS_INCPATH . $config->template . '.css')) ? $config->template : 'default' ;
$document->addStyleSheet(JVA_CSS_SITEPATH . $css . '.css');
$document->addStyleSheet(JVA_CSS_SITEPATH . 'smoothness/jquery-ui-1.8.16.css');
if((int)$config->roundcorners){
	$document->addStyleSheet(JVA_CSS_SITEPATH . '/smoothness/round.corners.css');
}
define('COM_JVARCADE_DATE_FORMAT', $config->date_format);
define('COM_JVARCADE_TIME_FORMAT', $config->time_format);
define('COM_JVARCADE_TIMEZONE', $config->timezone);
?>

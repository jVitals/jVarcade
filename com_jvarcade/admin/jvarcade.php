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

JLoader::register("jvarcadeToolbarHelper", JPATH_COMPONENT_ADMINISTRATOR . DIRECTORY_SEPARATOR . "sidebar.php");

require_once (dirname(__FILE__) . '/model.php');
require_once (dirname(__FILE__) . '/models/migration.php');
require_once (dirname(__FILE__) . '/controller.php');
require_once (JPATH_ROOT . '/components/com_jvarcade/include/define.php');
require_once (JVA_HELPERS_INCPATH . 'helper.php');
$model = JModelLegacy::getInstance('common', 'jvarcadeModel');
$config = $model->getConfObj();
define('COM_JVARCADE_DATE_FORMAT', $config->date_format);
define('COM_JVARCADE_TIME_FORMAT', $config->time_format);
define('COM_JVARCADE_TIMEZONE', $config->timezone);

$document = JFactory::getDocument();
$document->addStyleSheet(JUri::root() . 'administrator/components/com_jvarcade/css/'. 'style.css');
$document->addStyleSheet(JVA_CSS_SITEPATH . 'smoothness/jquery-ui-1.8.16.css');

// Javascript includes and declarations

$document->addScript(JVA_JS_SITEPATH . 'jquery-1.6.2.min.js');
$document->addScript(JVA_JS_SITEPATH . 'jquery-noconflict.js');
$document->addScript(JVA_JS_SITEPATH . 'jquery-ui-1.8.16.min.js');
$document->addScript(JVA_JS_SITEPATH . 'jquery.jva.js');

$jsconstants  = 'var JVA_HOST_NAME = \'' . JUri::base() . '\';' . "\n";
$jsconstants .= 'var JVA_AJAX_URL = \'' . JUri::base() . '\';' . "\n";
$jsconstants .= 'var JVA_CONTESTLINK_ADDGAME_URL = \'' . JRoute::_('index.php?option=com_jvarcade&task=addgametocontest&tmpl=component&',false) . '\';' . "\n";
$jsconstants .= 'var JVA_CONTESTLINK_ADDCONTESTGAMES_URL = \'' . JRoute::_('index.php?option=com_jvarcade&task=addcontestgames&tmpl=component&',false) . '\';' . "\n";
$jsconstants .= 'var JVA_MAIN_URL = JVA_HOST_NAME + \'index.php\';' . "\n";
$jsconstants .= 'var JVA_MAX_MIGRATION_STEPS = 12;' . "\n";

$jsconstants .= 'var COM_JVARCADE_CONTESTSLINK_DELETE_WARNING = \'' . JText::_('COM_JVARCADE_CONTESTSLINK_DELETE_WARNING') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_CONTESTSLINK_GAME_EMPTY = \'' . JText::_('COM_JVARCADE_CONTESTSLINK_GAME_EMPTY') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_CONTESTSLINK_SAVE_EMPTY = \'' . JText::_('COM_JVARCADE_CONTESTSLINK_SAVE_EMPTY') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_VALIDATION_ERROR = \'' . JText::_('COM_JVARCADE_VALIDATION_ERROR') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_CONTESTS_NAME_EMPTY = \'' . JText::_('COM_JVARCADE_CONTESTS_NAME_EMPTY') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_CONTESTS_START_EMPTY = \'' . JText::_('COM_JVARCADE_CONTESTS_START_EMPTY') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_CONTESTS_END_LOWER_START = \'' . JText::_('COM_JVARCADE_CONTESTS_END_LOWER_START') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_DESC_DELETEALLSCORES = \'' . JText::_('COM_JVARCADE_MAINTENANCE_DESC_DELETEALLSCORES') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_DESC_DELETEGUESTSCORES = \'' . JText::_('COM_JVARCADE_MAINTENANCE_DESC_DELETEGUESTSCORES') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_DESC_DELETEZEROSCORES = \'' . JText::_('COM_JVARCADE_MAINTENANCE_DESC_DELETEZEROSCORES') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_DESC_DELETEBLANKSCORES = \'' . JText::_('COM_JVARCADE_MAINTENANCE_DESC_DELETEBLANKSCORES') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_DESC_CLEARALLRATINGS = \'' . JText::_('COM_JVARCADE_MAINTENANCE_DESC_CLEARALLRATINGS') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_DESC_DELETEALLTAGS = \'' . JText::_('COM_JVARCADE_MAINTENANCE_DESC_DELETEALLTAGS') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_DESC_RECALCULATELEADERBOARD = \'' . JText::_('COM_JVARCADE_MAINTENANCE_DESC_RECALCULATELEADERBOARD') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_DESC_SUPPORTDIAGNOSTICS = \'' . JText::_('COM_JVARCADE_MAINTENANCE_DESC_SUPPORTDIAGNOSTICS') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_GAME_DESC_DELETEALLSCORES = \'' . JText::_('COM_JVARCADE_MAINTENANCE_GAME_DESC_DELETEALLSCORES') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_GAME_DESC_DELETEGUESTSCORES = \'' . JText::_('COM_JVARCADE_MAINTENANCE_GAME_DESC_DELETEGUESTSCORES') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_GAME_DESC_DELETEZEROSCORES = \'' . JText::_('COM_JVARCADE_MAINTENANCE_GAME_DESC_DELETEZEROSCORES') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_GAME_DESC_CLEARALLRATINGS = \'' . JText::_('COM_JVARCADE_MAINTENANCE_GAME_DESC_CLEARALLRATINGS') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_GAME_DESC_DELETEALLTAGS = \'' . JText::_('COM_JVARCADE_MAINTENANCE_GAME_DESC_DELETEALLTAGS') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_CONTEST_DESC_DELETEALLSCORES = \'' . JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_DESC_DELETEALLSCORES') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_CONTEST_DESC_DELETEGUESTSCORES = \'' . JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_DESC_DELETEGUESTSCORES') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_CONTEST_DESC_DELETEZEROSCORES = \'' . JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_DESC_DELETEZEROSCORES') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_CONTEST_DESC_RECALCULATELEADERBOARD = \'' . JText::_('COM_JVARCADE_MAINTENANCE_CONTEST_DESC_RECALCULATELEADERBOARD') . '\';' . "\n";
$jsconstants .= 'var COM_JVARCADE_MAINTENANCE_MIGRATION_FAILURE = \'' . JText::_('COM_JVARCADE_MAINTENANCE_MIGRATION_FAILURE') . '\';' . "\n";

$document->addScriptDeclaration($jsconstants);

JHTML::_('behavior.modal', 'a.modal');
JHTML::_('behavior.tooltip');

$task = JRequest::getVar('task', 'cpanel');

// check for new version 
jvaHelper::checkForNewVersion();

// Create the controller
$controller = new jvarcadeController();
$controller->execute($task);
$controller->redirect();


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

define('JVA_VERSION', '2.14');

// File system paths
@define('JVA_CSS_INCPATH', JPATH_ROOT . '/components/com_jvarcade/css/');
@define('JVA_HOMEVIEW_INCPATH', JPATH_ROOT . '/components/com_jvarcade/views/home/tmpl/');
@define('JVA_IMAGES_INCPATH', JPATH_ROOT . '/images/jvarcade/images/');
@define('JVA_MODELS_INCPATH', JPATH_ROOT . '/components/com_jvarcade/models/');
@define('JVA_HELPERS_INCPATH', JPATH_ROOT . '/components/com_jvarcade/helpers/');
@define('JVA_TEMPLATES_INCPATH', JPATH_ROOT . '/components/com_jvarcade/views/include/');
@define('JVA_GAMES_INCPATH', JPATH_ROOT . '/images/jvarcade/games/');

// Url paths
@define('JVA_CSS_SITEPATH', JUri::root() . 'components/com_jvarcade/css/');
@define('JVA_IMAGES_SITEPATH', JUri::root() . 'images/jvarcade/images/');
@define('JVA_GAMES_SITEPATH', JUri::root() . 'images/jvarcade/games/');


$JVersion = new JVersion();
$version = $JVersion->getShortVersion();
@define('JVA_JOOMLA_VERSION', $version);
if (version_compare($version, '3.2.0', 'ge')) {
	@define('JVA_COMPATIBLE_MODE', '16');
} else {
	@define('JVA_COMPATIBLE_MODE', '15');
}

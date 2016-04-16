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

class jvaHelper {

	public static function formatDate($input, $only_date = false) {
		
		if (!$input || $input == '' || $input == '0000-00-00 00:00:00' || $input == '0000-00-00') {
			return '';
		}
		
		if (JVA_COMPATIBLE_MODE == '15') { // warning - no Daylight Saving Time here
		
			$date_format = self::convertToStrftimeFormat(COM_JVARCADE_DATE_FORMAT);
			$time_format = self::convertToStrftimeFormat(COM_JVARCADE_TIME_FORMAT);
			
			$srv_tz = (int)(date('Z')/60/60);
			$user_tz = (int)COM_JVARCADE_TIMEZONE;
			
			$date = JFactory::getDate($input, $srv_tz);
			$date->setOffset($user_tz);
			
			$date_string = $date->toFormat($date_format);
			$time_string = $date->toFormat($time_format);
			
			$today = JFactory::getDate(date('Y-m-d H:i:s'), $srv_tz);
			$today->setOffset($user_tz);
			$today_string = $today->toFormat($date_format);
			
			$yesterday = JFactory::getDate(date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m")  , date("d")-1, date("Y"))), $srv_tz);
			$yesterday->setOffset($user_tz);
			$yesterday_string = $yesterday->toFormat($date_format);
			
		} else {
		
			$date_format = COM_JVARCADE_DATE_FORMAT;
			$time_format = COM_JVARCADE_TIME_FORMAT;
			
			$srv_tz = new DateTimeZone(date('e'));
			$user_tz = new DateTimeZone(COM_JVARCADE_TIMEZONE);
			
			$date = JFactory::getDate($input, $srv_tz);
			$date->setTimezone($user_tz);
			
			$date_string = $date->format($date_format, true);
			$time_string = $date->format($time_format, true);
			
			$today = JFactory::getDate(date('Y-m-d H:i:s'), $srv_tz);
			$today->setTimezone($user_tz);
			$today_string = $today->format($date_format, true);
			
			$yesterday = JFactory::getDate(date('Y-m-d H:i:s', mktime(date('H'), date('i'), date('s'), date("m")  , date("d")-1, date("Y"))), $srv_tz);
			$yesterday->setTimezone($user_tz);
			$yesterday_string = $yesterday->format($date_format, true);
			
		}
		
		if ($date_string == $today_string) {
			$date_string = JText::_('COM_JVARCADE_TODAY');
		} else if ($date_string == $yesterday_string) {
			$date_string = JText::_('COM_JVARCADE_YESTERDAY');
		}
		
		return $date_string . ($only_date ? '' : ' ' . $time_string);
	}
	
	public static function convertToStrftimeFormat($format) {
		$replaces = array(
			// Day - no strf eq : S
			'd' => '%d', 'D' => '%a', 'j' => '%e', 'l' => '%A', 'N' => '%u', 'w' => '%w', 'z' => '%j',
			// Week - no date eq : %U, %W
			'W' => '%V', 
			// Month - no strf eq : n, t
			'F' => '%B', 'm' => '%m', 'M' => '%b',
			// Year - no strf eq : L; no date eq : %C, %g
			'o' => '%G', 'Y' => '%Y', 'y' => '%y',
			// Time - no strf eq : B, G, u; no date eq : %r, %R, %T, %X
			'a' => '%P', 'A' => '%p', 'g' => '%l', 'h' => '%I', 'H' => '%H', 'i' => '%M', 's' => '%S',
			// Timezone - no strf eq : e, I, P, Z
			'O' => '%z', 'T' => '%Z',
			// Full Date / Time - no strf eq : c, r; no date eq : %c, %D, %F, %x 
			'U' => '%s'
		);
		return strtr((string)$format, $replaces); 
	}
	
	public static function isSuperAdmin(&$user) {
		if (JVA_COMPATIBLE_MODE == '16') {
			if ($user->authorise('core.admin')) return true;
		} else {
			if ($user->usertype == 'Super Administrator') return true;
		}
		return false;
	}
	
	public static function userGroups(&$user) {
		$groups = JVA_COMPATIBLE_MODE == '16' ? array_values($user->groups) : array($user->gid);
		if (!count($groups)) $groups = array(0);
		return $groups;
	}
	
	public static function checkPerms($user_groups, $setting_perms) {
		return count(array_intersect($user_groups, $setting_perms));
	}

	public static function unpack($p_filename) {
		$retval = array();
		
		// Path to the archive
		$archivename = $p_filename;

		// Temporary folder to extract the archive into
		$tmpdir = uniqid('install_');

		// Clean the paths to use for archive extraction
		$extractdir = JPath::clean(dirname($p_filename).'/'.$tmpdir);
		$archivename = JPath::clean($archivename);

		// do the unpacking of the archive
		$result = JArchive::extract($archivename, $extractdir);

		if ( $result === false ) {
			return false;
		}

		/*
		 * Lets set the extraction directory and package file in the result array so we can
		 * cleanup everything properly later on.
		 */
		$retval['extractdir'] = $extractdir;
		$retval['packagefile'] = $archivename;

		/*
		 * Try to find the correct install directory.  In case the package is inside a
		 * subdirectory detect this and set the install directory to the correct path.
		 *
		 * List all the items in the installation directory.  If there is only one, and
		 * it is a folder, then we will set that folder to be the installation folder.
		 */
		$dirList = array_merge(JFolder::files($extractdir, ''), JFolder::folders($extractdir, ''));

		if (count($dirList) == 1) {
			if (JFolder::exists($extractdir.'/'.$dirList[0])) {
				$extractdir = JPath::clean($extractdir.'/'.$dirList[0]);
			}
		}

		/*
		 * We have found the install directory so lets set it and then move on
		 * to detecting the extension type.
		 */
		$retval['dir'] = $extractdir;

		/*
		 * Get the extension type and return the directory/type array on success or
		 * false on fail.
		 */
		
		return $retval;
		
	}
	
	public static function detectPackageType($dir) {
		$type = '';
		if (count(JFolder::files($dir, '\.json'))) {
			$type = 'mochi';
		} elseif (count(JFolder::files($dir, '\.ini'))) {
			$type = 'pnflash';
		} elseif (count(JFolder::files($dir, '\.txt'))) {
			$type = 'pnflashtxt';
		} elseif (count(JFolder::files($dir, '\.php'))) {
			$type = 'ibpro';
		}
		return $type;
	}
	
	public static function getUploadErr($num) {
		$errors = array(
			1 => JText::_('COM_JVARCADE_UPLOAD_ERR1'),
			2 => JText::_('COM_JVARCADE_UPLOAD_ERR2'),
			3 => JText::_('COM_JVARCADE_UPLOAD_ERR3'),
			4 => JText::_('COM_JVARCADE_UPLOAD_ERR4'),
			5 => JText::_('COM_JVARCADE_UPLOAD_ERR5'),
			6 => JText::_('COM_JVARCADE_UPLOAD_ERR6'),
			7 => JText::_('COM_JVARCADE_UPLOAD_ERR7'),
			8 => JText::_('COM_JVARCADE_UPLOAD_ERR8'),
		);
		return $errors[$num];
	}
	
	public static function checkForNewVersion() {
		$config = JFactory::getConfig();
		$tmp_path = $config->get('tmp_path');
		$filename = 'jva-version-compare.txt';
		$tmpfile = $tmp_path . '/' . $filename;
		
		$dorequest = false;
		$filefound = false;
		
		if (is_file($tmpfile)) {
			$filefound = true;
			if ((filemtime($tmpfile) + (15 * 60)) < time()) {
				// older then 15 minutes
				$dorequest = true;
			}
		}
		
		if (!$filefound) $dorequest = true;
		
		if ($dorequest) {
			$JVersion = new JVersion();
			
			$opts = array(
					'http'=>array(
							'header'=>'User-Agent: Mozilla\r\n'
							)
					);
			$context = stream_context_create($opts);
			
			$response = json_decode(file_get_contents('https://api.github.com/repos/jvitals/jvarcade/releases/latest' , false, $context), true);
			$version= $response['tag_name'];
			
			$message = "The latest release of jVArcade(" . $version . ") is now available on GitHub! <a href='https://github.com/jVitals/jVarcade/releases/download/" . $version . "/jVArcade-" . $version . "-unzip-first.zip'> Download Here</a>";
			$version_info = $version . ':' . $message;
			$fp = @fopen($tmpfile, "wb");
			if ($fp) {
				@flock($fp, LOCK_EX);
				$len = strlen($version_info);
				@fwrite($fp, $version_info, $len);
				@flock($fp, LOCK_UN);
				@fclose($fp);
				$written = true;
			}
			// Data integrity check
			if ($written && ($version_info == file_get_contents($tmpfile))) {
				// nothing to do
			} else {
				unlink($tmpfile);
			}
		} else {
			@$version_info = file_get_contents($tmpfile);
		}
		
		$version_info_pos = strpos($version_info, ":");
		if ($version_info_pos === false) {
			$version = $version_info;
			$info = null;
		} else {
			$version = substr($version_info, 0, $version_info_pos);
			$info = substr($version_info, $version_info_pos + 1);
		}
		
		if ($version == JVA_VERSION) {
			return false;
		}
		
		// Version check
		$session = JFactory::getSession();
		$sessionQueue = $session->get('application.queue');
		if ($info && (!is_array($sessionQueue) || !in_array(array('message' => $info, 'type' => 'notice'), $sessionQueue))) {
			$app = JFactory::getApplication();
			$app->enqueueMessage($info, 'notice');
		}
		return true;
	}
	
	public static function createGsFeed() {
		$config = JFactory::getConfig();
		$tmp_path = $config->get('tmp_path');
		$filename = 'gsfeed.php';
		$tmpfile = $tmp_path . '/' . $filename;
	
		$dorequest = false;
		$filefound = false;
	
		if (is_file($tmpfile)) {
			$filefound = true;
			if ((filemtime($tmpfile) + (60 * 60 * 24)) < time()) {
				// only once per day
				$dorequest = true;
			}
		}
	
		if (!$filefound) $dorequest = true;
	
		if ($dorequest) {
	
			$http = JHttpFactory::getHttp();
			$response = $http->get('http://flashgamedistribution.com/feed?type=mochi&gpp=32&feed=phpcode', array(), 90);
			$response = $response->body;
	
	
	
			$fp = @fopen($tmpfile, "wb");
			if ($fp) {
				@flock($fp, LOCK_EX);
				$len = strlen($response);
				@fwrite($fp, $response, $len);
				@flock($fp, LOCK_UN);
				@fclose($fp);
				$written = true;
			}
			// Data integrity check
			if ($written && (file_get_contents($tmpfile))) {
				// nothing to do
			} else {
				unlink($tmpfile);
			}
		}
	
		return (is_file($tmpfile) ? $tmpfile : $default_file);
	}
	
	public static function showAvatar($userid) {
	
		static $jva_avatars;
		$model = jvarcadeModelCommon::getInst();
		$config = $model->getConf();
		
		if (!($jva_avatars && is_array($jva_avatars) && count($jva_avatars) && array_key_exists((int)$userid, $jva_avatars))) {
			if(!is_array($jva_avatars)) $jva_avatars = array();
			$_avatar = '';
			
			
			//Community Builder
			if ((int)$config->scorelink == 2) {
				$db = JFactory::getDBO();
				$db->setQuery('SELECT avatar FROM #__comprofiler WHERE user_id = ' . (int)$userid);
				$_avatar = $db->loadResult();
				if (strlen($_avatar) && file_exists(JPATH_BASE . '/images/comprofiler/' . $_avatar) && ($img_size = @getimagesize(JPATH_BASE . '/images/comprofiler/' . $_avatar))) {
					$_avatar = JURI::root(). 'images/comprofiler/' . $_avatar;
				} else {
					$_avatar = JURI::root() . 'components/com_comprofiler/plugin/templates/default/images/avatar/tnnophoto_n.png';
				}
			//JomSocial
			} elseif (((int)$config->scorelink == 1) && is_file(JPATH_ROOT . '/components/com_community/libraries/core.php')) {
				include_once(JPATH_ROOT . '/components/com_community/libraries/core.php');
				$js_user = CFactory::getUser((int)$userid);
				$_avatar = $js_user->getThumbAvatar();
			//AltaUserPoints
			}/*elseif ((int)$config->scorelink ==3) {
				$api_AUP = JPATH_SITE . '/components/com_altauserpoints/helper.php';
				if ( file_exists($api_AUP))
				{
					require_once ($api_AUP);
					$avatar = AltaUserPointsHelper:: getAupAvatar( $userid, 0, '50', '50', '', '' );
					echo $avatar;
				}
				
			}*/ elseif ((int)$config->scorelink == 0) {
				
				if ((int)$userid == 0) {
					$_avatar = JVA_IMAGES_SITEPATH . '/avatars/blank_avatar.png';
				} else {
				$imgSearch = glob('images/jvarcade/images/avatars/' .$userid. '.*');
					if (isset($imgSearch[0])) {
						$_avatar = $imgSearch[0];
					} else {
						$_avatar = JVA_IMAGES_SITEPATH . '/avatars/blank_avatar.png';
					}
				}
				
				
			}
		
			$_avatar = $_avatar ? '<img src="' . $_avatar . '" border="0" height="50" width="50" align="middle" />' : '' ;
			$jva_avatars[(int)$userid] = $_avatar;
			
		}
		
		return $jva_avatars[(int)$userid];
	}
	
	public static function showProfileAvatar($userid) {
	
		static $jva_avatars;
		$imgSearch = glob('images/jvarcade/images/avatars/' .$userid. '.*');
		if (isset($imgSearch[0])) {
			$_avatar = $imgSearch[0];
		} else {
			$_avatar = JVA_IMAGES_SITEPATH . '/avatars/blank_avatar.png';
		}
		
		$_avatar = $_avatar ? '<img src="' . $_avatar . '" border="0" align="middle" width="140px" height="175px"/>' : '' ;
		$jva_avatars[(int)$userid] = $_avatar;
				
	
		return $jva_avatars[(int)$userid];
	}
	
	public static function userlink($userid, $username) {
	
		static $jva_userlinks;
		$model = jvarcadeModelCommon::getInst();
		$config = $model->getConf();
		
		if (!($jva_userlinks && is_array($jva_userlinks) && count($jva_userlinks) && array_key_exists((int)$userid, $jva_userlinks))) {
			if(!is_array($jva_userlinks)) $jva_userlinks = array();
			$_name = '';
			
			// Guest
			if ((int)$userid == 0) {
				$_name = $config->guest_name;
			//Alta User Points
			} elseif (((int)$config->scorelink == 3) && is_file(JPATH_SITE . '/components/com_altauserpoints/helper.php')) {
				$api_AUP = JPATH_SITE . '/components/com_altauserpoints/helper.php';
				if ( file_exists($api_AUP))
				{
					require_once ($api_AUP);
					$linktoAUPprofil = AltaUserPointsHelper::getAupLinkToProfil($userid, (int)$config->aup_itemid);
					$_name = '<a href="' . $linktoAUPprofil . '">' . $username . '</a>';
				}
			//Community Builder
			} elseif ((int)$config->scorelink == 2) {
				$_name = '<a href="' . JRoute::_('index.php?option=com_comprofiler&task=userProfile&user=' . (int)$userid . '&Itemid=' . (int)$config->communitybuilder_itemid ) . '">' . $username . '</a>';
			//JomSocial
			} elseif (((int)$config->scorelink == 1) && is_file(JPATH_ROOT . '/components/com_community/libraries/core.php')) {
				include_once(JPATH_ROOT . '/components/com_community/libraries/core.php');
				$js_user = CFactory::getUser((int)$userid);
				$_name = '<a href="' . CRoute::_('index.php?option=com_community&view=profile&userid=' . (int)$userid) . '">' . $js_user->getDisplayName() . '</a>';
			// No integration
			} else {
				$_name = '<a href="' . JRoute::_('index.php?option=com_jvarcade&task=profile&id=' . (int)$userid) . '">' . $username . '</a>';
			}
			
			$jva_userlinks[(int)$userid] = $_name;
			
		}
		
		return $jva_userlinks[(int)$userid];
	}
	
	public static function substr($text, $start, $end) {
		if (function_exists('mb_strlen')) {
			$substr_func = 'mb_substr';
		} elseif (function_exists('iconv_strlen')) {
			$substr_func = 'iconv_substr';
		} else {
			$substr_func = 'substr';
		}
		return ((int)$end ? $substr_func($text, $start, $end) : $substr_func($text, $start));
	}
	
	public static function strlen($text) {
		if (function_exists('mb_strlen')) {
			$strlen_func = 'mb_strlen';
		} elseif (function_exists('iconv_strlen')) {
			$strlen_func = 'iconv_strlen';
		} else {
			$strlen_func = 'strlen';
		}
		return $strlen_func($text);
	}
	
	public static function truncate($string, $length = 80, $etc = '...', $middle = false) {
		
		if ($length == 0) {
			return '';
		}
		
		if (self::strlen($string) > $length) {
			$length -= min($length, self::strlen($etc));
			if (!$middle) {
				$string = preg_replace('/\s+?(\S+)?$/', '', self::substr($string, 0, $length+1));
			}
			if(!$middle) {
				return self::substr($string, 0, $length) . $etc;
			} else {
				return self::substr($string, 0, $length/2) . $etc . self::substr($string, -$length/2, 0);
			}
		} else {
			return $string;
		}
	}

}

class jvarcadeHtml {

	public static function booleanlist ($name, $attributes, $value, $yes, $no, $id) {
		if (JVA_COMPATIBLE_MODE == '16') {
			return '<fieldset id="' . $name . '" class="radio btn-group btn-group-yesno">' . str_replace(array('<div class="controls">', '</div>'), '',JHtml::_('select.booleanlist',  $name, $attributes, $value, $yes, $no, $id)) . '</fieldset>';
		} else {
			return '';
		}
	}

	public static function radiolist ($item, $name, $attributes, $name1, $name2, $value, $id) {
		if (JVA_COMPATIBLE_MODE == '16') {
			return '<fieldset id="' . $name . '"  class="radio">' . str_replace(array('<div class="controls">', '</div>'), '',JHtml::_('select.radiolist',  $item, $name, $attributes, $name1, $name2, $value, $id)) . '</fieldset>';
		} else {
		}
	}
	
	public static function sort($title, $order, $direction = 'asc', $selected = 0, $sort_url, $new_direction = 'asc', $task = null) {
		$app = JFactory::getApplication();
		$linktitle = JText::_('JGLOBAL_CLICK_TO_SORT_THIS_COLUMN');
		$direction = strtolower($direction);
		$images = array('sort_asc.png', 'sort_desc.png');
		$index = intval($direction == 'desc');
		
		if ($order != $selected) {
			$direction = $new_direction;
		} else {
			$direction = ($direction == 'desc') ? 'asc' : 'desc';
		}
		
		$sort_url .= ($app->input->getInt('limitstart', 0) ? '&limitstart=' . $app->input->getInt('limitstart', 0)  : '') . '&filter_order=' . $order . '&filter_order_Dir=' . $direction;
		
		$html = '<a href="' . JRoute::_($sort_url) . '" rel="nofollow" title="' . $linktitle . '">';
		$html .= JText::_($title);

		if ($order == $selected) {
			$html .= JHtml::_('image','system/'.$images[$index], '', null, true);
		}
		
		$html .= '</a>';
		
		return $html;
	}
	
	public static function usergroup($name, $selected, $attribs = '', $options = array()) {
		$ret = '';
		$selected = explode(',', $selected);
		

		
			$db = JFactory::getDbo();
			$db->setQuery(
				'SELECT a.id AS value, a.title AS text, COUNT(DISTINCT b.id) AS level' .
				' FROM #__usergroups AS a' .
				' LEFT JOIN `#__usergroups` AS b ON a.lft > b.lft AND a.rgt < b.rgt' .
				' GROUP BY a.id' .
				' ORDER BY a.lft ASC'
			);
			$options = $db->loadObjectList();
 		
			if (is_array($options) && count($options)) {
				for ($i = 0, $n = count($options); $i < $n; $i++) {
					$options[$i]->text = str_repeat('- ',$options[$i]->level).$options[$i]->text;
				}
				array_unshift($options, JHtml::_('select.option', 0, 'Guest'));
				$ret = JHtml::_('select.genericlist', $options, $name, array('list.attr' => $attribs, 'list.select' => $selected ));
			
			
		}
		
		return $ret;
		
	}

}

?>

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

class jvarcadeModelInstall extends JModelLegacy {
	private $dbo;
	
	function __construct() {
		parent::__construct();
		$this->db = JFactory::getDBO();
		$this->app = JFactory::getApplication();
		$this->config = JFactory::getConfig();
	}
	
	function installPackage() {
		jimport('joomla.installer.installer');
		jimport('joomla.installer.helper');
		jimport('joomla.filesystem.file');
		jimport('joomla.filesystem.folder');
		jimport('joomla.filesystem.archive');
		jimport('joomla.filesystem.path');
		
		$installtype = $this->app->input->getWord('installtype', '');
		if ($installtype == 'folder') {
			$this->_installFromFolder();
		} elseif ($installtype == 'upload') {
			$this->_installFromUpload();
		}
		
		$this->app->enqueueMessage(JText::_('COM_JVARCADE_UPLOADARCHIVE_INSTALLMETHOD_ERROR'), 'error');
		$this->app->redirect('index.php?option=com_jvarcade&task=upload_archive');
		jexit();
	}
	
	private function _installFromFolder() {
		// Get the path to the package to install
		$p_dir = $this->app->input->getString('install_directory', '');
		$p_dir = JPath::clean($p_dir);
		$errormsg = '';
		
		// Did you give us a valid directory?
		if (!$errormsg && !is_dir($p_dir)) {
			$errormsg = JText::_('COM_JVARCADE_UPLOADARCHIVE_INVALID_DIR');
		}
		
		if ($errormsg) {
			$this->app->enqueueMessage($errormsg, 'error');
			$this->app->redirect('index.php?option=com_jvarcade&task=upload_archive');
			jexit();
		}
		
		$dest = $this->config->get('tmp_path') . '/' . uniqid('install_');
		JFolder::copy($p_dir, $dest);
		
		$package['packagefile'] = null;
		$package['extractdir'] = null;
		$package['dir'] = $dest;
		$this->_doAcctualInstall($package);
	}

	private function _installFromUpload() {
		
		// Get the uploaded file information
		$userfile = $this->app->input->files->get('install_package');
		$errormsg = '';
		
		// Make sure that file uploads are enabled in php
		if (!$errormsg && !(bool) ini_get('file_uploads')) {
			$errormsg = JText::_('COM_JVARCADE_UPLOADARCHIVE_INIFALSE');
		}
		// Make sure that zlib is loaded so that the package can be unpacked
		if (!$errormsg && !extension_loaded('zlib')) {
			$errormsg = JText::_('COM_JVARCADE_UPLOADARCHIVE_ZIPFALSE');
		}
		// If there is no uploaded file, we have a problem...
		if (!$errormsg && !is_array($userfile) ) {
			$errormsg = JText::_('COM_JVARCADE_UPLOADARCHIVE_NOFILEUPL');
		}
		// Check if there was a problem uploading the file.
		if (!$errormsg && ($userfile['error'] || $userfile['size'] < 1)) {
			$errormsg = JText::sprintf('COM_JVARCADE_UPLOADARCHIVE_UPLOADERR', jvaHelper::getUploadErr((int)$userfile['error']));
		}
		
		if ($errormsg) {
			$this->app->enqueueMessage($errormsg, 'error');
			$this->app->redirect('index.php?option=com_jvarcade&task=upload_archive');
			exit;
		}
		
		// Build the appropriate paths and move uploaded file
		$tmp_dest = $this->config->get('tmp_path') . '/'. $userfile['name'];
		$tmp_src = $userfile['tmp_name'];
		$uploaded = JFile::upload($tmp_src, $tmp_dest);

		// Unpack the downloaded package file
		$package = jvaHelper::unpack($tmp_dest);
		if (!$package) {
			$this->app->enqueueMessage(JText::_('COM_JVARCADE_UPLOADARCHIVE_NOPACKAGE'), 'error');
			$this->app->redirect('index.php?option=com_jvarcade&task=upload_archive');
			exit;
		}
		
		$this->_doAcctualInstall($package);
	}
	
	private function _doAcctualInstall($pkg) {
		
		if (!$pkg) {
			$this->app->enqueueMessage(JText::_('COM_JVARCADE_UPLOADARCHIVE_NOPACKAGE'), 'error');
			$this->app->redirect('index.php?option=com_jvarcade&task=upload_archive');
			jexit();
		}
		
		$folderid = $this->app->input->getInt('folderid', 1);
		$published = $this->app->input->getInt('published', 0);
		$packages = array();
		$errormsg = array();
		
		$archives = JFolder::files($pkg['dir'], '\.zip|\.tar|\.tgz|\.gz|\.gzip|.tbz2|\.bz2|\.bzip2', false, false, array('.svn', 'CVS', '.DS_Store', '__MACOSX'), array('^\..*','.*~'));
		$bulk = count($archives) ? true : false;
		
		if ($bulk) {
			if ($archives && is_array($archives) && count($archives)) {
				$tmp_package = false;
				foreach ($archives as $archive) {
					$tmp_package = jvaHelper::unpack($pkg['dir'] . '/' . $archive);
					if (!$tmp_package) {
						$errormsg[] = $archive . ': ' . JText::_('COM_JVARCADE_UPLOADARCHIVE_NOPACKAGE');
					} else {
						$packages[] = $tmp_package;
					}
				}
			}
		} else {
			$packages[] = $pkg;
		}
		
		if ($packages && is_array($packages) && count($packages)) {
			foreach ($packages as $package) {

				// Detect game type
				$config = array();
				$package_type = jvaHelper::detectPackageType($package['dir']);
				if ($package_type) {
					$name = 'parseConfig' . ucfirst($package_type);
					$config = $this->$name($package['dir']);
				}
				
				// CHECKS
				
				// check if we have game file
				if (!isset($config['name']) || !$config['name']) {
					$errormsg[] = $package['packagefile'] . ': ' . JText::_('COM_JVARCADE_UPLOADARCHIVE_NOGNAME');
				}
				
				// check if the extensions are allowed
				$file_ext = substr(strrchr($config['filename'], '.'), 1);
				$image_ext = substr(strrchr($config['imagename'], '.'), 1);
				if (!count($errormsg) && !in_array($file_ext, array('bin', 'd64', 'dcr', 'gb', 'gbc', 'htm', 'html', 'nes', 'prg', 'sna', 'swf', 'z80'))) {
					$errormsg[] = $config['name'] . ': ' . JText::_('COM_JVARCADE_UPLOADARCHIVE_BADEXTGAME');
				}
				if (!count($errormsg) && !in_array($image_ext, array('bmp', 'gif', 'jpeg', 'jpg', 'png'))) {
					$errormsg[] = $config['name'] . ': ' . JText::_('COM_JVARCADE_UPLOADARCHIVE_BADEXTIMG');
				}
				
				if (!count($errormsg)) {
				
					// if there is already game with that name, we make a unique name
					$this->db->setQuery('SELECT id FROM #__jvarcade_games WHERE gamename = ' . $this->db->Quote($config['name']));
					$game_exists = (int)$this->db->loadResult();
					if ($game_exists) {
						$config['name'] = uniqid($config['name'] . '_');
					}
				
					// change filename and imagename to be unique as well
					$config['newfilename'] = $config['name'] . '.' . $file_ext;
					$config['newimagename'] = $config['name'] . '.' . $image_ext;
					
					// INSTALL
					
					$this->db->setQuery("INSERT INTO #__jvarcade_games " . 
							  "(" . $this->db->quoteName('gamename') . ", " . $this->db->quoteName('title') . ", " . $this->db->quoteName('description') . ", " . 
									$this->db->quoteName('height') . ", " . $this->db->quoteName('width') . ", " . $this->db->quoteName('filename') . ", " . $this->db->quoteName('imagename') . ", " . 
									$this->db->quoteName('background') . ", " . $this->db->quoteName('published') . ", " . $this->db->quoteName('reverse_score') . ", " . 
									$this->db->quoteName('scoring') . ", " . $this->db->quoteName('folderid') . ", " .  $this->db->quoteName('mochi'). ") " . 
							"VALUES (" . $this->db->Quote($config['name']) . "," . $this->db->Quote($config['title']) . "," . $this->db->Quote($config['description']) . "," . 
										$this->db->Quote((int)$config['height']) . "," . $this->db->Quote((int)$config['width']) . "," . $this->db->Quote($config['newfilename']) . "," . $this->db->Quote($config['newimagename']) . "," . 
										$this->db->Quote($config['background']) . "," . $this->db->Quote((int)$published) . "," . $this->db->Quote((int)$config['reverse_score']) . "," . 
										$this->db->Quote((int)$config['scoring']) . "," . $this->db->Quote((int)$folderid) . "," . $this->db->Quote((int)$config['mochi']) . ")"
					);
					if(!$this->db->execute()) {
						$errormsg[] = $config['name'] . ': ' . $this->db->getErrorMsg();
					} else {
						$gameid = $this->db->insertid();
					}
					
					if (!count($errormsg)) {
						$copyfiles = array(
							array('src' => $package['dir'] . '/' . $config['filename'], 'dest' => JVA_GAMES_INCPATH . $config['newfilename']),
							array('src' => $package['dir'] . '/' . $config['imagename'], 'dest' => JVA_IMAGES_INCPATH . 'games' . '/' . $config['newimagename']),
						);
						foreach ($copyfiles as $copyfile) {
							if (copy($copyfile['src'], $copyfile['dest'])) {
								@chmod($copyfile['dest'], 0644);
							} else {
								$errormsg[] = $config['name'] . ': ' . JText::sprintf('COM_JVARCADE_UPLOADARCHIVE_COPYERR', $copyfile['src'], $copyfile['dest']);
							}
						}
					}
					
					if (!count($errormsg)) {
						// Take care of gamedata folder if exists
						$gamedatasrc = $package['dir'] . '/' . 'gamedata' . '/' . $config['name'];
						$gamedatadest = JPATH_SITE . '/' . 'arcade' . '/' . 'gamedata' . '/' . $config['name'];
						if (JFolder::exists($gamedatasrc)) {
							//@JFolder::create(JPATH_SITE . '/' . 'arcade', 0755);
							//@JFolder::create(JPATH_SITE . '/' . 'arcade' . '/' . 'gamedata', 0755);
							JFolder::move($gamedatasrc, $gamedatadest);
						}
					}
					
					// cleanup
					if ($package['packagefile'] && is_file($package['packagefile'])) JFile::delete($package['packagefile']);
					if ($package['extractdir'] && is_dir($package['extractdir'])) JFolder::delete($package['extractdir']);
				}
			}
		}
		
		// GENERAL CLEANUP
		if ($pkg['packagefile'] && is_file($pkg['packagefile'])) JFile::delete($pkg['packagefile']);
		if ($pkg['extractdir'] && is_dir($package['extractdir'])) JFolder::delete($pkg['extractdir']);
		
		// Redirect and show messages
		$msg = (count($errormsg) ? implode('<br />', $errormsg) : JText::sprintf('COM_JVARCADE_UPLOADARCHIVE_SUCCESS'));
		$msg_type = count($errormsg) ? 'error' : 'message';
		$this->app->enqueueMessage($msg, $msg_type);
		$this->app->redirect('index.php?option=com_jvarcade&task=upload_archive');
		jexit();
	}
	
	function parseConfigMochi($dir) {
		$files = JFolder::files($dir, '\.json');
		$config = $dir . '/' . $files[0];
		$obj = json_decode(file_get_contents($config));
		return array(
			'name' => $obj->slug,
			'title' => $obj->name,
			'filename' => urldecode(basename($obj->swf_url)),
			'imagename' => basename($obj->thumbnail_url),
			'description' => $obj->description . '<br/>' . $obj->instructions,
			'width' => $obj->width,
			'height' => $obj->height,
			'author' => $obj->developer,
			'background' => '',
			'mochi' => 1,
			'scoring' => 1,
			'reverse_score' => 0,
		);
	}
	
	function parseConfigPnflash($dir) {
		$files = JFolder::files($dir, '\.ini');
		$config = $dir . '/' . $files[0];
		$arr = parse_ini_file($config);
		//$basename = basename(basename($files[0], '.ini'), '.INI');
		$game_files = JFolder::files($dir, '\.bin|\.d64|\.dcr|\.gb|\.gbc|\.htm|\.html|\.nes|\.prg|\.sna|\.swf|\.z80');
		reset($game_files);
		$game_file = current($game_files);
		$image_files = JFolder::files($dir, '\.bmp|\.gif|\.jpeg|\.jpg|\.png');
		reset($image_files);
		$image_file = current($image_files);
		
		return array(
			'name' => strtolower(str_replace(' ', '-', $arr['name'])),
			'title' => $arr['name'],
			'filename' => $game_file,
			'imagename' => $image_file,
			'description' => $arr['description'],
			'width' => $arr['width'],
			'height' => $arr['height'],
			'author' => $arr['author'],
			'background' => '#' . $arr['bgcolor'],
			'mochi' => 0,
			'scoring' => 1,
			'reverse_score' => ((int)$arr['gameType'] == 2 ? 1: 0),
		);
	}
	
	function parseConfigPnflashtxt($dir) {
		$files = JFolder::files($dir, '\.txt');
		$config = $dir . '/' . $files[0];
		$string = file_get_contents($config);
		
		$game_title = preg_match('~Game Name(.*)~i', $string, $matches) ? trim(str_replace(':', '', $matches[1])) : '';
		$game_name = strtolower(str_replace(' ', '-', $game_title));
		$game_desc = preg_match('~Description(.*)~i', $string, $matches) ? trim(str_replace(':', '', $matches[1])) : '';
		$game_desc2 = preg_match('~Desc:(.*)~i', $string, $matches) ? trim(str_replace(':', '', $matches[1])) : '';
		$game_desc = !$game_desc ? $game_desc2 : $game_desc;
		$game_type = preg_match('~Game Type(.*)~i', $string, $matches) ? trim(str_replace(':', '', $matches[1])) : '';
		$game_author = preg_match('~Authors Name(.*)~i', $string, $matches) ? trim(str_replace(':', '', $matches[1])) : '';
		$game_width = preg_match('~Width(.*)~i', $string, $matches) ? trim(str_replace(':', '', $matches[1])) : '';
		$game_height = preg_match('~Height(.*)~i', $string, $matches) ? trim(str_replace(':', '', $matches[1])) : '';
		$game_bgcolor = preg_match('~Color(.*)~i', $string, $matches) ? trim(str_replace(':', '', $matches[1])) : '';
		
		//$basename = basename(basename($files[0], '.txt'), '.txt');
		$game_files = JFolder::files($dir, '\.bin|\.d64|\.dcr|\.gb|\.gbc|\.htm|\.html|\.nes|\.prg|\.sna|\.swf|\.z80');
		reset($game_files);
		$game_file = current($game_files);
		$image_files = JFolder::files($dir, '\.bmp|\.gif|\.jpeg|\.jpg|\.png');
		reset($image_files);
		$image_file = current($image_files);
		
		return array(
			'name' => $game_name,
			'title' => $game_title,
			'filename' => $game_file,
			'imagename' => $image_file,
			'description' => $game_desc,
			'width' => (int)$game_width,
			'height' => (int)$game_height,
			'author' => $game_author,
			'background' => $game_bgcolor,
			'mochi' => 0,
			'scoring' => 1,
			'reverse_score' => ($game_type == 'Highest Score Wins' ? 0: 1),
		);
	}
	
	function parseConfigIbpro($dir) {
		$files = JFolder::files($dir, '\.php');
		$file = $dir . '/' . $files[0];
		@require_once($file);
		$arr = $config;
		//$basename = basename(basename($files[0], '.php'), '.PHP');
		$game_files = JFolder::files($dir, '\.bin|\.d64|\.dcr|\.gb|\.gbc|\.htm|\.html|\.nes|\.prg|\.sna|\.swf|\.z80');
		reset($game_files);
		$game_file = current($game_files);
		$image_files = JFolder::files($dir, '\.bmp|\.gif|\.jpeg|\.jpg|\.png');
		reset($image_files);
		$image_file = current($image_files);
		
		return array(
			'name' => $arr['gname'],
			'title' => $arr['gtitle'],
			'filename' => $game_file,
			'imagename' => $image_file,
			'description' => ($arr['gwords'] ? $arr['gwords'] : ($arr['object'] ? $arr['object'] : '')),
			'width' => $arr['gwidth'],
			'height' => $arr['gheight'],
			'author' => '',
			'background' => '#' . $arr['bgcolor'],
			'mochi' => 0,
			'scoring' => 1,
			'reverse_score' => 0,
		);
	}

}
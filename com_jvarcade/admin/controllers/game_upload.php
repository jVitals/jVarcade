<?php
/**
 * @package		jVArcade
* @version		2.12
* @date		2014-05-17
* @copyright		Copyright (C) 2007 - 2014 jVitals Digital Technologies Inc. All rights reserved.
* @license		http://www.gnu.org/copyleft/gpl.html GNU/GPLv3 or later
* @link		http://jvitals.com
*/



// no direct access
defined('_JEXEC') or die;

jimport('joomla.filesystem.file');
jimport('joomla.filesystem.folder');

class jvarcadeControllerGame_upload extends JControllerLegacy {
	
	private $app;
	private $config;
	
	public function __construct() {
		parent::__construct();
		$this->app = JFactory::getApplication();
		$this->config = JFactory::getConfig();
	}
	
	public function installUpload() {
		
		$userfile = $this->input->files->get('install_package', '', 'raw');
		foreach ($userfile as &$file){
			$file['name']     = JFile::makeSafe($file['name']);
		}
		
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
		if (!$errormsg && !is_array($file) ) {
			$errormsg = JText::_('COM_JVARCADE_UPLOADARCHIVE_NOFILEUPL');
		}
		
		// Check if there was a problem uploading the file.
		if (!$errormsg && ($file['error'] == 1 || $file['size'] < 1)) {
			$errormsg = JText::sprintf('COM_JVARCADE_UPLOADARCHIVE_UPLOADERR', jvaHelper::getUploadErr((int)$file['error']));
		}
		
		if ($errormsg) {
			$this->app->enqueueMessage($errormsg, 'error');
			$this->app->redirect('index.php?option=com_jvarcade&task=game_upload');
			exit;
		}
		
		// Build the appropriate paths and move uploaded file
		$safeFileOptions = array('php_tag_in_content' => false, 'shorttag_in_content' => false, 'fobidden_ext_in_content' => false);
		$tmp_dest = $this->config->get('tmp_path') . '/'. ($file['name']);
		$tmp_src = $file['tmp_name'];
		$uploaded = JFile::upload($tmp_src, $tmp_dest, false, $safeFileOptions);
		
		// Unpack the downloaded package file
		$package = jvaHelper::unpack($tmp_dest);
		if (!$package) {
			$this->app->enqueueMessage(JText::_('COM_JVARCADE_UPLOADARCHIVE_NOPACKAGE'), 'error');
			$this->app->redirect('index.php?option=com_jvarcade&task=game_upload');
			exit;
		}
		
		$this->getModel('install')->doAcctualInstall($package);
	}
	
	public function installFolder() {
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
			$this->app->redirect('index.php?option=com_jvarcade&task=game_upload');
			jexit();
		}
	
		$dest = $this->config->get('tmp_path') . '/' . uniqid('install_');
		JFolder::copy($p_dir, $dest);
	
		$package['packagefile'] = null;
		$package['extractdir'] = null;
		$package['dir'] = $dest;
		$this->getModel('install')->doAcctualInstall($package);
	}

}

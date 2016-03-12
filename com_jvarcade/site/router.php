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
defined('_JEXEC') or die;

$JVersion = new JVersion();
$version = $JVersion->getShortVersion();
 
if (version_compare($version, '3.3.0', 'lt')) {
	interface JComponentRouterInterface {
		public function build(&$query);
		public function parse(&$segments);
	}
	function JvarcadeBuildRoute(&$query) {
		$router = new JvarcadeRouter;
		$query = $router->preprocess($query);
		return $router->build($query);
	}
	function JvarcadeParseRoute($segments) {
		$router = new JvarcadeRouter;
		return $router->parse($segments);
	}
 }
 

// TODO: may be extend the new JComponentRouterBasic class in 5.3.0

class JvarcadeRouter implements JComponentRouterInterface {
	protected $dbo;
	protected $app;
	
	public function __construct() {
		$this->dbo = JFactory::getDBO();
		$this->app = JFactory::getApplication();
	}
	
	public function build(&$query) {
		$segments = array();
		$task = '';
		$aliases = $this->getAlias();
		
		if(isset($query['task'])) {
			$task = $query['task'];
			$segments[] = (is_array($aliases) && count($aliases) && isset($aliases[$task])) ? $aliases[$task] : $query['task'];
			unset($query['task']);
		}
		
		if (($task == 'game' || $task == 'scores' || $task == 'contestdetail' || $task == 'folder') && isset($query['id'])) {
			$id = (int)$query['id'];
			switch($task) {
				case 'game':
				case 'scores':
					$sql = 'SELECT title from #__jvarcade_games WHERE id = ' . $id;
					break;
				case 'contestdetail':
					$sql = 'SELECT name from #__jvarcade_contest WHERE id = ' . $id;
					break;
				case 'folder':
					$sql = 'SELECT coalesce(NULLIF( alias,\'\'), name) as name from #__jvarcade_folders WHERE id = ' . $id;
					break;
			}
			$this->dbo->setQuery($sql);
			$title = $this->dbo->loadResult();
			$title = $title ? $this->makeAlias($title) : '';
			$title = $title ? 'id:' . $id . ':' . $title : 'id:' . $id ;
			$segments[] = $title;
			unset($query['id']);
		}
		
		if (($task == 'profile') && isset($query['id'])) {
			$id = (int)$query['id'];
			$sql = 'SELECT username from #__users WHERE id =' . $id;
			$this->dbo->setQuery($sql);
			$name = $this->dbo->loadResult();
			$name = $name ? $this->makeAlias($name) : '';
			$name = $name ? 'id:' . $id . ':' . $name : 'id:' . $id;
			$segments[] = $name;
			unset($query['id']);
		}
		
		if ($task == 'showtag') {
			$tag = isset($query['tag']) ? (string)$query['tag'] : '';
			$this->dbo->setQuery('SELECT id from #__jvarcade_tags WHERE tag = ' . $this->dbo->Quote($tag));
			$tag_id = (int)$this->dbo->loadResult();
			$tag = $tag ? $this->makeAlias($tag) : '';
			$tag = 'tag:' . $tag_id . ':' . $tag;
			$segments[] = $tag;
			unset($query['tag']);
		}
		
		if(isset($query['start'])) {
			$segments[] = 'start:' . $query['start'];
			unset($query['start']);
		}
		
		if(isset($query['filter_order'])) {
			$segments[] = 'ord:' . $query['filter_order'];
			unset($query['filter_order']);
		}
		
		if(isset($query['filter_order_Dir'])) {
			$segments[] = 'dir:' . $query['filter_order_Dir'];
			unset($query['filter_order_Dir']);
		}
		
		unset($query['view']);
		return $segments;
	}

	public function parse(&$segments) {
		$vars = array();
		$segment_id = '';
		$segment_tag = '';
		$segment_start = '';
		$segment_ord = '';
		$segment_dir = '';
		$aliases = $this->getAlias();
		$aliases = array_flip($aliases);
		
		if (isset($segments[0])) {
			// the first case is when we access a joomla menu and the first segment is not the task
			if (strpos($segments[0], 'id:') !== false || strpos($segments[0], 'tag:') !== false || strpos($segments[0], 'start:') !== false || strpos($segments[0], 'ord:') !== false || strpos($segments[0], 'dir:') !== false) {
				$vars['task'] = $this->getMenuQuery('task', 'home');
			} else { 
				$vars['task'] = (is_array($aliases) && count($aliases) && isset($aliases[$segments[0]])) ? $aliases[$segments[0]] : $segments[0];
			}
		}
		
		foreach($segments as $segment) {
			if (strpos($segment, 'id:') !== false) {
				$segment_id = $segment;
			}
			if (strpos($segment, 'tag:') !== false) {
				$segment_tag = $segment;
			}
			if (strpos($segment, 'start:') !== false) {
				$segment_start = $segment;
			}
			if (strpos($segment, 'ord:') !== false) {
				$segment_ord = $segment;
			}
			if (strpos($segment, 'dir:') !== false) {
				$segment_dir = $segment;
			}
		}
		
		// if we access the page through a menu - we have to try to get the id from the menu link
		if(!$segment_id) $segment_id = 'id:' . $this->getMenuQuery('id', 0);

		if ($segment_id) {
			$id = explode(':', $segment_id);
			$vars['id'] = (int)$id[1];
		}

		if ($segment_tag) {
			$tag = explode(':', $segment_tag);
			$tag_id = (int)$tag[1];
			$this->dbo->setQuery('SELECT tag from #__jvarcade_tags WHERE id = ' . (int)$tag_id);
			$tag_name = $this->dbo->loadResult();
			$vars['tag'] = $tag_name;
		}

		if ($segment_start) {
			$start = explode(':', $segment_start);
			$vars['limitstart'] = (int)$start[1];
		}

		if ($segment_ord) {
			$ord = explode(':', $segment_ord);
			$vars['filter_order'] = $ord[1];
		}

		if ($segment_dir) {
			$dir = explode(':', $segment_dir);
			$vars['filter_order_Dir'] = $dir[1];
		}

		return $vars;
	}
	
	public function preprocess($query) {
		return $query;
	}

	public function getMenuQuery($var, $def) {
		$menus = $this->app->getMenu();
		$menu = $menus->getActive();
		$query = is_object($menu) ? $menu->query : array();
		return ( (is_array($query) && count($query) && isset($query[$var])) ? $query[$var] : $def);
	}

	public function getAlias() {
		static $aliases;
		if (is_null($aliases) || !is_array($aliases) || !count($aliases)) {
			$aliases = array();
			$this->dbo->setQuery('SELECT optname, value from #__jvarcade_settings WHERE optname LIKE \'%alias%\'');
			$results = $this->dbo->loadAssocList();
			foreach($results as $result) {
				if (isset($result['value']) && $result['value']) {
					$aliases[str_replace('alias_', '', $result['optname'])] = $this->makeAlias($result['value']);
				}
			}
		}
		return $aliases;
	}

	public function makeAlias($str) {
		return str_replace(array('-', ' '), array('', '-'), trim($str));
	}
}


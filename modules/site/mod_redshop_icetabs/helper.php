<?php
/**
 * IceTabs Module for Joomla 1.6 By IceTheme
 *
 *
 * @copyright      Copyright (C) 2008 - 2011 IceTheme.com. All rights reserved.
 * @license        GNU General Public License version 2
 *
 * @Website    http://www.icetheme.com/Joomla-Extensions/icetabs.html
 * @Support    http://www.icetheme.com/Forums/IceTabs/
 *
 */


// no direct access
defined('_JEXEC') or die;
require_once JPATH_SITE . DS . 'components' . DS . 'com_content' . DS . 'helpers' . DS . 'route.php';

if (!defined('PhpThumbFactoryLoaded'))
{
	require_once dirname(__FILE__) . DS . 'libs' . DS . 'phpthumb' . DS . 'ThumbLib.inc.php';
	define('PhpThumbFactoryLoaded', 1);
}
if (!class_exists('LofSliderGroupBase'))
{
	require_once(dirname(__FILE__) . DS . 'libs' . DS . 'group_base.php');
}

abstract class modIceTabsHelper
{
	/**
	 * get list articles
	 */
	public static function getList($params)
	{
		if ($params->get('enable_cache'))
		{
			$cache = JFactory::getCache('mod_icetabs');
			$cache->setCaching(true);
			$cache->setLifeTime($params->get('cache_time', 15) * 60);

			return $cache->get(array('modIceTabsHelper', 'getGroupObject'), array($params));
		}
		else
		{
			return self::getGroupObject($params);
		}
	}

	/**
	 * get list articles
	 */
	public static function getGroupObject($params)
	{
		$group = $params->get('group', 'content');
		$file  = dirname(__FILE__) . DS . 'libs' . DS . 'groups' . DS . strtolower($group) . DS . strtolower($group) . '.php';

		if (file_exists($file))
		{
			require_once($file);
			$className = 'LofSliderGroup' . ucfirst($group);
			if (class_exists($className))
			{
				$object = new $className($group);
				$object->setCurrentPath(dirname(__FILE__) . DS . 'libs' . DS . 'groups' . DS . strtolower($group) . DS);
			}
		}
		if ($object)
		{
			return $object->getListByParameters($params);
		}
		else
		{
			return array();
		}
	}


	/**
	 * load css - javascript file.
	 *
	 * @param JParameter $params;
	 * @param JModule    $module
	 *
	 * @return void.
	 */

	public static function loadMediaFiles($params, $module, $theme = '')
	{
		$mainframe = JFactory::getApplication();
		// if the verion is equal 1.6.x
		JHTML::script('modules/' . $module->module . '/assets/script_16.js');

		if ($theme && $theme != -1)
		{
			$tPath = JPATH_BASE . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html' . DS . $module->module . DS . $theme . DS . 'assets' . DS . 'style.css';

			if (file_exists($tPath))
			{
				JHTML::stylesheet('templates/' . $mainframe->getTemplate() . '/html/' . $module->module . '/' . $theme . '/assets/style.css');
			}
			else
			{
				JHTML::stylesheet('modules/' . $module->module . '/themes/' . $theme . '/assets/style.css');
			}
		}
		else
		{
			JHTML::stylesheet('modules/' . $module->module . '/assets/style.css');
		}
		// load js of modalbox
		if ($params->get('load_jslibs', 'modal') && !defined('LOF_ADDED_MODALBOX') && $params->get('open_target', '') == 'modalbox')
		{
			$doc    = JFactory::getDocument();
			$string = '<script type="text/javascript">';
			$string .= "
				var box = {};
				window.addEvent('domready', function(){
					box = new MultiBox('mb', {  useOverlay: false,initialWidth:1000});
				});
			";
			$string .= '</script>';
			$doc->addCustomTag($string);
			JHTML::stylesheet('modules/' . $module->module . '/assets/multibox/multibox.css');
			JHTML::script('modules/' . $module->module . '/assets/multibox/multibox.js');
			JHTML::script('modules/' . $module->module . '/assets/multibox/overlay.js');
		}
	}

	/**
	 *
	 */
	public function renderItem(&$row, $params, $layout = '_item')
	{
		$mainframe = JFactory::getApplication();
		$theme     = $params->get('theme');
		$target    = $params->get('open_target', '_parent') != 'modalbox'
			? 'target="' . $params->get('open_target', '_parent') . '"'
			: 'rel="' . $params->get('modal_rel', 'width:800,height:350') . '" class="mb"';

		$tPath = JPATH_BASE . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html' . DS . 'mod_icetabs' . DS . $theme . DS . $layout . '.php';
		$bPath = JPATH_BASE . DS . 'modules' . DS . 'mod_icetabs' . DS . 'themes' . DS . $theme . DS . $layout . '.php';

		if (file_exists($tPath))
		{
			require($tPath);
		}
		elseif (file_exists($bPath))
		{
			require($bPath);
		}
	}

	/**
	 * load theme
	 */

	public static function getLayoutByTheme($module, $group, $theme = '')
	{
		$mainframe = JFactory::getApplication();
		// Build the template and base path for the layout
		$tPath = JPATH_BASE . DS . 'templates' . DS . $mainframe->getTemplate() . DS . 'html' . DS . $module->module . DS . $theme . DS . 'default.php';
		$bPath = JPATH_BASE . DS . 'modules' . DS . $module->module . DS . 'themes' . DS . $theme . DS . 'default.php';

		// If the template has a layout override use it
		if (file_exists($tPath))
		{
			return $tPath;
		}
		elseif (file_exists($bPath))
		{
			return $bPath;
		}
	}

	/**
	 * get the list of articles, using for joomla 1.6.x
	 *
	 * @param JParameter $params;
	 *
	 * @return array;
	 */
	public function getArticles($params = array())
	{
		$model = JModel::getInstance('Articles', 'ContentModel', array('ignore_request' => true));
		// Set application parameters in model
		$appParams = JFactory::getApplication()->getParams();
		$model->setState('params', $appParams);

		$model->setState('list.select', 'a.fulltext, a.id, a.title, a.alias, a.title_alias, a.introtext, a.state, a.catid, a.created, a.created_by, a.created_by_alias,' .
			' a.modified, a.modified_by,a.publish_up, a.publish_down, a.attribs, a.metadata, a.metakey, a.metadesc, a.access,' .
			' a.hits, a.featured,' .
			' LENGTH(a.fulltext) AS readmore');

		$openTarget = isset($params['open_target']) ? $params['open_target'] : 'parent';
		$limit      = isset($params['limit']) ? $params['limit'] : 5;
		// Set the filters based on the module params
		$model->setState('list.start', 0);
		$model->setState('list.limit', (int) $limit);
		$model->setState('filter.published', 1);
		$featured = isset($params['content_featured_items_show']) ? $params['content_featured_items_show'] : 1;

		if (!$featured)
		{
			$model->setState('filter.featured', 'hide');
		}
		elseif ($featured == 2)
		{
			$model->setState('filter.featured', 'only');
		}
		// Access filter
		$access     = !JComponentHelper::getParams('com_content')->get('show_noauth');
		$authorised = JAccess::getAuthorisedViewLevels(JFactory::getUser()->get('id'));
		$model->setState('filter.access', $access);
		$source = isset($params['source']) ? $params['source'] : 'content_category';
		$source = trim($source);
		if ($source == 'content_category')
		{
			// Category filter
			$catids = isset($params['content_category']) ? $params['content_category'] : '';
			$flag   = true;
			if (is_array($catids) && count($catids) == 1)
			{
				if (empty($catids[0]))
				{
					$flag = false;
				}
			}
			if ($flag)
			{
				$catids = is_array($catids) ? $catids : explode(",", $catids);
				$model->setState('filter.category_id', $catids);
			}
		}
		else
		{
			$article_ids = isset($params['article_ids']) ? $params['article_ids'] : '';
			$ids         = preg_split('/,/', $article_ids);
			$tmp         = array();
			foreach ($ids as $id)
			{
				$tmp[] = (int) trim($id);
			}
			$model->setState('filter.a_id', $tmp);
		}
		// User filter
		$userId = JFactory::getUser()->get('id');

		$ordering = isset($params['ordering']) ? $params['ordering'] : 'created_asc';
		$ordering = preg_split('_', $ordering);

		if (trim($ordering[0]) == 'rand')
		{
			$model->setState('list.ordering', ' RAND() ');
		}
		else
		{
			$model->setState('list.ordering', "a." . $ordering[0]);
			$model->setState('list.direction', $ordering[1]);
		}

		$items = $model->getItems();
		foreach ($items as $key => &$item)
		{
			$item->slug    = $item->id . ':' . $item->alias;
			$item->catslug = $item->catid . ':' . $item->category_alias;

			if ($access || in_array($item->access, $authorised))
			{
				// We know that user has the privilege to view the article
				$item->link = JRoute::_(ContentHelperRoute::getArticleRoute($item->slug, $item->catslug));
			}
			else
			{
				$item->link = JRoute::_('index.php?option=com_user&view=login');
			}
			$item->date      = JHtml::_('date', $item->created, JText::_('DATE_FORMAT_LC2'));
			$item->introtext = JHtml::_('content.prepare', $item->introtext);
		}

		return $items;
	}
}
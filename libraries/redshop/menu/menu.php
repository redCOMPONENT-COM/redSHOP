<?php
/**
 * @package     Redshop.Library
 * @subpackage  Product
 *
 * @copyright   Copyright (C) 2014 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 *
 * @since       __DEPLOY_VERSION__
 */

defined('_JEXEC') or die;

/**
 * Menu architecture, use: new RedshopMenu
 *
 * @package     Redshop.Library
 *
 * @subpackage  Menu
 *
 * @since       __DEPLOY_VERSION__
 */
class RedshopMenu
{
	/**
	 * Set menu is disable or not
	 *
	 * @var boolean
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $disableMenu = false;

	/**
	 * Set items for menu
	 *
	 * @var array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	public $items = array();

	/**
	 * Data of items in menu
	 *
	 * @var    array
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $data = array();

	/**
	 * Store section
	 *
	 * @var    null
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $section = null;

	/**
	 * Store title
	 *
	 * @var    null
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $title = null;

	/**
	 * Store hidden menu
	 *
	 * @var    null
	 *
	 * @since  __DEPLOY_VERSION__
	 */
	protected $menuhide = null;

	/**
	 * Protected menu constructor. Must use getInstance() method.
	 *
	 * @since       __DEPLOY_VERSION__
	 */
	public function __construct()
	{
		$this->menuhide = explode(",", Redshop::getConfig()->get('MENUHIDE', ''));
	}

	/**
	 * Set section value for an instance
	 *
	 * @param   integer  $section  Section value
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function section($section)
	{
		$this->section = $section;

		return $this;
	}

	/**
	 * Set title value for an instance
	 *
	 * @param   integer  $title  Title value
	 *
	 * @return  self
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function title($title)
	{
		$this->title = $title;

		return $this;
	}

	/**
	 * Get Data by section ID
	 *
	 * @param   string  $section  Section ID
	 *
	 * @return  array
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function getData($section)
	{
		return $this->data[$section];
	}

	/**
	 * Set data to group
	 *
	 * @param   string  $group  Group name
	 *
	 * @return  void
	 *
	 * @since   __DEPLOY_VERSION__
	 */
	public function group($group)
	{
		$this->items[$group] = $this->data;
	}

	/**
	 * Add new menu item
	 *
	 * @param   string   $link    Link of item
	 * @param   string   $title   Title of item
	 * @param   boolean  $active  Active or not
	 * @param   array    $param   Other options
	 *
	 * @return  self
	 */
	public function addItem($link, $title, $active = null, $param = null)
	{
		if ($this->disableMenu || !in_array($title, $this->menuhide))
		{
			$item         = new stdClass;
			$item->link   = $link;
			$item->title  = $title;
			$item->active = $active;
			$item->param  = $param;

			if ($this->section)
			{
				$this->data[$this->section]->items[] = $item;
			}

			if ($this->title)
			{
				$this->data[$this->section]->title = $this->title;
			}
		}

		return $this;
	}
}

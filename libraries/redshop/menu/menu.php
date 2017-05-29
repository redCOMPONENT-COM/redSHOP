<?php
/**
 * @package     Redshop.Library
 * @subpackage  Product
 *
 * @copyright   Copyright (C) 2014 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later, see LICENSE.
 *
 * @since       2.0.3
 */

defined('_JEXEC') or die;

/**
 * Menu architecture, use: new RedshopMenu
 *
 * @package     Redshop.Library
 *
 * @subpackage  Menu
 *
 * @since       2.0.3
 */
class RedshopMenu
{
	/**
	 * Set menu is disable or not
	 *
	 * @var boolean
	 *
	 * @since  2.0.3
	 */
	public $disableMenu = false;

	/**
	 * Set items for menu
	 *
	 * @var array
	 *
	 * @since  2.0.3
	 */
	public $items = array();

	/**
	 * Data of items in menu
	 *
	 * @var    array
	 *
	 * @since  2.0.3
	 */
	protected $data = array();

	/**
	 * Store section
	 *
	 * @var    null
	 *
	 * @since  2.0.3
	 */
	protected $section = null;

	/**
	 * Store title
	 *
	 * @var    null
	 *
	 * @since  2.0.3
	 */
	protected $title = null;

	/**
	 * Store hidden menu
	 *
	 * @var    null
	 *
	 * @since  2.0.3
	 */
	protected $menuhide = null;

	/**
	 * Protected menu constructor. Must use getInstance() method.
	 *
	 * @since       2.0.3
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
	 * @since   2.0.3
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
	 * @since   2.0.3
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
	 * @since   2.0.3
	 */
	public function getData($section)
	{
		return $this->data[$section];
	}

	/**
	 * Set data to group
	 *
	 * @param   string  $group  Group name
	 * @param   string  $style  Group display
	 *
	 * @return  void
	 *
	 * @since   2.0.3
	 */
	public function group($group, $style = 'tree')
	{
		// Check if all of sub-menu is disabled => Disable this menu too.
		$isDisable = true;

		foreach ($this->data as $groupData)
		{
			if (empty($groupData->items))
			{
				continue;
			}

			foreach ($groupData->items as $item)
			{
				if ($item->disable === false)
				{
					$isDisable = false;

					break;
				}
			}

			if (!$isDisable)
			{
				break;
			}
		}

		$this->items[$group]['items'] = $this->data;
		$this->items[$group]['style'] = $style;
		$this->items[$group]['disable'] = $isDisable;

		$this->data = array();
	}

	/**
	 * Add new menu item
	 *
	 * @param   string   $link    Link of item
	 * @param   string   $title   Title of item
	 * @param   boolean  $active  Active or not
	 * @param   array    $param   Other options
	 * @param   string   $icon    Icon class
	 *
	 * @return  self
	 */
	public function addItem($link, $title, $active = null, $param = null, $icon = '')
	{
		if (!$this->disableMenu)
		{
			$item          = new stdClass;
			$item->link    = $link;
			$item->title   = $title;
			$item->active  = $active;
			$item->param   = $param;
			$item->icon    = $icon;
			$item->disable = in_array($title, $this->menuhide);

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

	/**
	 * Add new menu header item
	 *
	 * @param   string   $link    Link of item
	 * @param   string   $title   Title of item
	 * @param   boolean  $active  Active or not
	 * @param   array    $param   Other options
	 * @param   string   $icon    Icon class
	 *
	 * @return  self
	 */
	public function addHeaderItem($link, $title, $active = null, $param = null, $icon = '')
	{
		if (!$this->disableMenu)
		{
			$item          = new stdClass;
			$item->link    = $link;
			$item->title   = $title;
			$item->active  = $active;
			$item->param   = $param;
			$item->icon    = $icon;
			$item->disable = in_array($title, $this->menuhide);

			array_push($this->items, $item);
		}

		return $this;
	}
}

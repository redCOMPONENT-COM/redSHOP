<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Helper
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

class redPagination extends JPagination
{
	public function getPagesLinks()
	{
		$app = JFactory::getApplication();

		$lang = JFactory::getLanguage();

		// Build the page navigation list
		$data = $this->_buildDataObject();

		$list = array();

		$itemOverride = false;
		$listOverride = false;

		// Pagination = 0 - joomla template pagination
		// Pagination = 1 - redSHOP pagination
		if (PAGINATION == 0)
		{
			$templatefile_path = JPATH_THEMES . '/' . $app->getTemplate() . '/html/pagination.php';

			if (file_exists($templatefile_path))
			{
				require_once $templatefile_path;

				if (function_exists('pagination_item_active') && function_exists('pagination_item_inactive'))
				{
					$itemOverride = true;
				}

				if (function_exists('pagination_list_render'))
				{
					$listOverride = true;
				}
			}
		}

		// Build the select list
		if ($data->all->base !== null)
		{
			$list['all']['active'] = true;
			$list['all']['data']   = ($itemOverride) ? pagination_item_active($data->all) : $this->_item_active($data->all);
		}
		else
		{
			$list['all']['active'] = false;
			$list['all']['data']   = ($itemOverride) ? pagination_item_inactive($data->all) : $this->_item_inactive($data->all);
		}

		if ($data->start->base !== null)
		{
			$list['start']['active'] = true;
			$list['start']['data']   = ($itemOverride) ? pagination_item_active($data->start) : $this->_item_active($data->start);
		}
		else
		{
			$list['start']['active'] = false;
			$list['start']['data']   = ($itemOverride) ? pagination_item_inactive($data->start) : $this->_item_inactive($data->start);
		}

		if ($data->previous->base !== null)
		{
			$list['previous']['active'] = true;
			$list['previous']['data']   = ($itemOverride) ? pagination_item_active($data->previous) : $this->_item_active($data->previous);
		}
		else
		{
			$list['previous']['active'] = false;
			$list['previous']['data']   = ($itemOverride) ? pagination_item_inactive($data->previous) : $this->_item_inactive($data->previous);
		}

		// Make sure it exists
		$list['pages'] = array();

		foreach ($data->pages as $i => $page)
		{
			if ($page->base !== null)
			{
				$list['pages'][$i]['active'] = true;
				$list['pages'][$i]['data']   = ($itemOverride) ? pagination_item_active($page) : $this->_item_active($page);
			}
			else
			{
				$list['pages'][$i]['active'] = false;
				$list['pages'][$i]['data']   = ($itemOverride) ? pagination_item_inactive($page) : $this->_item_inactive($page);
			}
		}

		if ($data->next->base !== null)
		{
			$list['next']['active'] = true;
			$list['next']['data']   = ($itemOverride) ? pagination_item_active($data->next) : $this->_item_active($data->next);
		}
		else
		{
			$list['next']['active'] = false;
			$list['next']['data']   = ($itemOverride) ? pagination_item_inactive($data->next) : $this->_item_inactive($data->next);
		}

		if ($data->end->base !== null)
		{
			$list['end']['active'] = true;
			$list['end']['data']   = ($itemOverride) ? pagination_item_active($data->end) : $this->_item_active($data->end);
		}
		else
		{
			$list['end']['active'] = false;
			$list['end']['data']   = ($itemOverride) ? pagination_item_inactive($data->end) : $this->_item_inactive($data->end);
		}

		if ($this->total > $this->limit)
		{
			return ($listOverride) ? pagination_list_render($list) : $this->_list_render($list);
		}
		else
		{
			return '';
		}
	}

	public function getPagesCounter()
	{
		// Initialize variables
		return;
		$html = null;

		if ($this->get('pages.total') > 1)
		{
			$html .= JText::_('COM_REDSHOP_Page') . " " . $this->get('pages.current') . " " . JText::_('COM_REDSHOP_of') . " " . $this->get('pages.total');
		}

	}

	public function _list_footer($list)
	{
		$html = "<div class=\"pagination-list-footer\">\n";

		$html .= "\n<div class=\"limit\">" . JText::_('COM_REDSHOP_DISPLAY_NUM') . $list['limitfield'] . "</div>";
		$html .= $list['pageslinks'];
		$html .= "\n<div class=\"counter\">" . $list['pagescounter'] . "</div>";

		$html .= "\n<input type=\"hidden\" name=\"limitstart\" value=\"" . $list['limitstart'] . "\" />";
		$html .= "\n</div>";

		return $html;
	}


	public function _list_render($list)
	{
		// Initialize variables
		$html = "<span class=\"pagination-links\" >";

		foreach ($list['pages'] as $page)
		{
			$html .= $page['data'];
		}

		if (PAGINATION == 0)
		{
			$html .= '<div style="clear:both"><br></div>';
			$html .= '<span>&laquo;</span>';
			$html .= $list['previous']['data'];
			$html .= $list['next']['data'];
			$html .= '<span>&raquo;</span>';
		}
		else
		{
			$html .= "<span class='redpre'>" . $list['previous']['data'] . "</span>";
			$html .= "<span class='rednext'>" . $list['next']['data'] . "</span>";
		}

		$html .= "</span>";

		return $html;

	}

	public function _item_active(&$item)
	{
		if (PAGINATION == 0)
		{
			return $this->_item_active_joomla($item);
		}
		else
		{
			return $this->_item_active_redshop($item);
		}

	}

	public function _item_inactive(&$item)
	{
		if (PAGINATION == 0)
		{
			return $this->_item_inactive_joomla($item);
		}
		else
		{
			return $this->_item_inactive_redshop($item);
		}
	}

	/**
	 *
	 *
	 * @param    object  $item
	 *
	 * @return   string  HTML link
	 * @since    11.1
	 */
	protected function _item_active_redshop(&$item)
	{
		if ($item->text > 0)
		{
			return "<a class='redpagination-style' href=\"" . $item->link . "\" title=\"" . $item->text . "\" >" . $item->text . "</a>";
		}
		else
		{
			return "<a href=\"" . $item->link . "\" title=\"" . $item->text . "\" >" . $item->text . "</a>";

		}
	}

	/**
	 *
	 *
	 * @param    object  $item
	 *
	 * @return   string  HTML link
	 * @since    11.1
	 */
	protected function _item_active_joomla(&$item)
	{
		$app = JFactory::getApplication();

		if ($app->isAdmin())
		{
			if ($item->base > 0)
			{
				return "<a title=\"" . $item->text
					. "\" onclick=\"document.adminForm." . $this->prefix
					. "limitstart.value=" . $item->base
					. "; Joomla.submitform();return false;\">" . $item->text . "</a>";
			}
			else
			{
				return "<a title=\"" . $item->text
					. "\" onclick=\"document.adminForm." . $this->prefix
					. "limitstart.value=0; Joomla.submitform();return false;\">" . $item->text . "</a>";
			}
		}
		else
		{
			return "<a title=\"" . $item->text . "\" href=\"" . $item->link . "\" class=\"pagenav\">" . $item->text . "</a>";
		}
	}

	/**
	 *
	 *
	 * @param    object  $item
	 *
	 * @return   string
	 * @since    11.1
	 */
	protected function _item_inactive_redshop(&$item)
	{
		if ($item->text > 0)
		{
			return "<span class='redpagination-enable-style'>" . $item->text . "</span>";
		}
		else
		{
			return "<span >" . $item->text . "</span>";
		}
	}

	/**
	 *
	 *
	 * @param    object  $item
	 *
	 * @return   string
	 * @since    11.1
	 */
	protected function _item_inactive_joomla(&$item)
	{
		$app = JFactory::getApplication();

		if ($app->isAdmin())
		{
			return "<span>" . $item->text . "</span>";
		}
		else
		{
			return "<span class=\"pagenav\">" . $item->text . "</span>";
		}
	}
}


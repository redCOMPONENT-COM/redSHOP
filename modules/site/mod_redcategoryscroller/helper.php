<?php
/**
 * @package     RedSHOP.Module
 * @subpackage  mod_redfeaturedproduct
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

$document = JFactory::getDocument();
JHTML::script('com_redshop/redbox.js', false, true);
JHtml::script('com_redshop/attribute.js', false, true);
JHtml::script('com_redshop/common.js', false, true);

/**
 * Helper for mod_redcategoryscroller
 * 
 * @since  1.5.4
 */
class RedCategoryScroller
{
	/**
	 * @var $NumberOfCategory
	 */
	var $NumberOfCategory = 5;

	/**
	 * @var $featuredCategory
	 */
	var $featuredCategory = false;

	/**
	 * // scroll, alternate, slide
	 * @var $ScrollBehavior
	 */
	var $ScrollBehavior = 'scroll';

	/**
	 * @var $PS_DIRECTION
	 */
	var $ScrollDirection = 'up';

	/**
	 * @var $ScrollHeight
	 */
	var $ScrollHeight = '125';

	/**
	 * @var $ScrollWidth
	 */
	var $ScrollWidth = '150';

	/**
	 * @var $ScrollAmount
	 */
	var $ScrollAmount = '2';

	/**
	 * @var $ScrollDelay
	 */
	var $ScrollDelay = '80';

	/**
	 * @var $ScrollAlign
	 */
	var $ScrollAlign = 'center';

	/**
	 * // newest [asc], oldest [desc], random [rand]
	 * @var $SortMethod
	 */
	var $ScrollSortMethod = 'random';

	/**
	 * @var $ScrollTitles
	 */
	var $ScrollTitles = 'yes';

	/**
	 * @var $ScrollSpaceChar
	 */
	var $ScrollSpaceChar = '&nbsp;';

	/**
	 * @var $ScrollSpaceCharTimes
	 */
	var $ScrollSpaceCharTimes = 5;

	/**
	 * @var $ScrollLineChar
	 */
	var $ScrollLineChar = '<br />';

	/**
	 * @var $ScrollLineCharTimes
	 */
	var $ScrollLineCharTimes = 2;

	/**
	 * @var $ScrollSection
	 */
	var $ScrollSection = 0;

	// CSS override -----------------------
	/**
	 * @var $ScrollCSSOverride
	 */
	var $ScrollCSSOverride = 'no';

	/**
	 * @var $ScrollTextAlign
	 */
	var $ScrollTextAlign = 'left';

	/**
	 * @var $ScrollTextWeight
	 */
	var $ScrollTextWeight = 'normal';

	/**
	 * @var $ScrollTextSize
	 */
	var $ScrollTextSize = '10';

	/**
	 * @var $ScrollTextColor
	 */
	var $ScrollTextColor = '#000000';

	/**
	 * @var $ScrollBGColor
	 */
	var $ScrollBGColor = 'transparent';

	/**
	 * @var $ScrollMargin
	 */
	var $ScrollMargin = '2';

	var $show_discountpricelayout = 0;

	var $boxwidth = '100';

	var $params = null;

	/**
	 * [redCategoryScroller description]
	 * 
	 * @param   [type]  &$params    [description]
	 * @param   [type]  $module_id  [description]
	 * 
	 * @return  [type]
	 */
	function redCategoryScroller(&$params, $module_id)
	{
		$this->params = $params;

		// Standard mammeters
		$this->show_category_name = $params->get('show_category_name', "yes");
		$this->show_addtocart     = $params->get('show_addtocart', "yes");
		$this->show_price         = $params->get('show_price', "yes");

		$this->category_id = JRequest::getInt('cid', 0);

		$this->thumbwidth  = $params->get('thumbwidth', 100);
		$this->thumbheight = $params->get('thumbheight', 100);

		// Limit by NoP
		$this->NumberOfCategory = $params->get('NumberOfCategory', $this->NumberOfCategory);
		$this->featuredCategory = $params->get('featuredCategory', $this->featuredCategory);

		$this->ScrollSection        = $params->get('ScrollSection', $this->ScrollSection);
		$this->ScrollBehavior       = $params->get('ScrollBehavior', $this->ScrollBehavior);
		$this->ScrollDirection      = $params->get('ScrollDirection', $this->ScrollDirection);
		$this->ScrollHeight         = $params->get('ScrollHeight', $this->ScrollHeight);
		$this->ScrollWidth          = $params->get('ScrollWidth', $this->ScrollWidth);
		$this->ScrollAmount         = $params->get('ScrollAmount', $this->ScrollAmount);
		$this->ScrollDelay          = $params->get('ScrollDelay', $this->ScrollDelay);
		$this->ScrollAlign          = $params->get('ScrollAlign', $this->ScrollAlign);
		$this->ScrollSortMethod     = $params->get('ScrollSortMethod', $this->ScrollSortMethod);
		$this->ScrollTitles         = $params->get('ScrollTitles', $this->ScrollTitles);
		$this->ScrollSpaceChar      = $params->get('ScrollSpaceChar', $this->ScrollSpaceChar);
		$this->ScrollSpaceCharTimes = $params->get('ScrollSpaceCharTimes', $this->ScrollSpaceCharTimes);
		$this->ScrollLineChar       = $params->get('ScrollLineChar', $this->ScrollLineChar);
		$this->ScrollLineCharTimes  = $params->get('ScrollLineCharTimes', $this->ScrollLineCharTimes);

		// Customization mammeters
		$this->ScrollCSSOverride        = $params->get('ScrollCSSOverride', $this->ScrollCSSOverride);
		$this->ScrollTextAlign          = $params->get('ScrollTextAlign', $this->ScrollTextAlign);
		$this->ScrollTextWeight         = $params->get('ScrollTextWeight', $this->ScrollTextWeight);
		$this->ScrollTextSize           = $params->get('ScrollTextSize', $this->ScrollTextSize);
		$this->ScrollTextColor          = $params->get('ScrollTextColor', $this->ScrollTextColor);
		$this->ScrollBGColor            = $params->get('ScrollBGColor', $this->ScrollBGColor);
		$this->ScrollMargin             = $params->get('ScrollMargin', $this->ScrollMargin);
		$this->show_discountpricelayout = $params->get('show_discountpricelayout', $this->show_discountpricelayout);
		$this->boxwidth                 = $params->get('boxwidth', $this->boxwidth);
		$this->module_id                = $module_id;
	}

	/**
	 * displayredScroller function
	 * 
	 * @param   array  &$rows  data
	 * 
	 * @return  void
	 */
	function displayRedScroller(&$rows)
	{
		if ($this->ScrollCSSOverride == 'yes')
		{
			$txt_size = $this->ScrollTextSize . 'px';
			$margin   = $this->ScrollMargin . 'px';

			require_once JModuleHelper::getLayoutPath('mod_redcategoryscroller', $this->params->get('layout', 'override'));
		}
		else
		{
			require_once JModuleHelper::getLayoutPath('mod_redcategoryscroller', $this->params->get('layout', 'default'));
		}
	}

	/**
	 * getRedCategorySKU function
	 * 
	 * @param   integer  $limit       [description]
	 * @param   string   $how         [description]
	 * @param   integer  $categoryId  [description]
	 * 
	 * @return [type]               [description]
	 */
	function getRedCategorySKU($limit = 0, $how = null, $categoryId = 0)
	{
		$app   = JFactory::getApplication();
		$db    = JFactory::getDbo();
		$query = $db->getQuery(true);

		$categories = new product_category;

		$hierachy = $categories->getCategoryListArray($categoryId, $categoryId);

		$cid = array();

		for ($i = 0, $in = count($hierachy); $i < $in; $i++)
		{
			$cid[] = $hierachy[$i]->category_id;
		}

		$database = JFactory::getDbo();

		if ($limit > 0)
		{
			$limit = "LIMIT " . $db->q($limit);
		}
		else
		{
			$limit = "";
		}

		$query->select(
					$db->qn(
							[
								"category_id", "category_name", "category_short_description",
								"category_description", "category_thumb_image", "category_full_image",
								"category_pdate"
							]
						)
					)
				->from($db->qn('#__redshop_category'))
				->where($db->qn('published') . ' = 1');

		switch ($how)
		{
			case 'random':
				$query->order('RAND()');
				break;
			case 'newest':
				$query->order($db->qn('category_pdate') . ' DESC');
				break;
			case 'oldest':
				$query->order($db->qn('category_pdate') . ' ASC');
				break;
			default:
				$query->order($db->qn('category_pdate') . ' DESC');
				break;
		}

		$query->setLimit($limit);

		$db->setQuery($query);
		$rows = $db->loadObjectList();

		return $rows;
	}

	/**
	 * [showCategory description]
	 * 
	 * @param   [type]  $row  [description]
	 * 
	 * @return  [type]
	 */
	function showCategory($row)
	{
		$redhelper     = redhelper::getInstance();

		$ItemData = $redhelper->getCategoryItemid($row->category_id);

		if (count($ItemData) > 0)
		{
			$Itemid = $ItemData->id;
		}
		else
		{
			$Itemid = $redhelper->getItemid($row->category_id);
		}

		$data_add   = '';

		$pname = $row->category_name;

		$link = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid=' . $row->category_id . '&Itemid=' . $Itemid);

		$pname = $row->category_name;

		if ($this->boxwidth > 0)
		{
			$pwidth = $this->boxwidth / 10;
			$pname  = wordwrap($pname, $pwidth, "<br>\n", true);
		}

		if ($row->category_full_image || Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'))
		{
			$title   = " title='" . $row->category_name . "' ";
			$alt     = " alt='" . $row->category_name . "' ";
			$w_thumb = $this->thumbwidth;
			$h_thumb = $this->thumbheight;

			$linkimage = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg";

			if ($row->category_full_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $row->category_full_image))
			{
				$product_img = $redhelper->watermark('category', $row->category_full_image, $w_thumb, $h_thumb, Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE'));
				$linkimage   = $redhelper->watermark('category', $row->category_full_image, '', '', Redshop::getConfig()->get('WATERMARK_CATEGORY_IMAGE'));
			}
			elseif (Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE') && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE')))
			{
				$product_img = $redhelper->watermark('category', Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'), $w_thumb, $h_thumb, Redshop::getConfig()->get('WATERMARK_CATEGORY_THUMB_IMAGE'));
				$linkimage   = $redhelper->watermark('category', Redshop::getConfig()->get('CATEGORY_DEFAULT_IMAGE'), '', '', Redshop::getConfig()->get('WATERMARK_CATEGORY_IMAGE'));
			}

			if (Redshop::getConfig()->get('CAT_IS_LIGHTBOX'))
			{
				$cat_thumb = "<a class='modal' href='" . $linkimage . "' rel=\"{handler: 'image', size: {}}\" " . $title . ">";
			}
			else
			{
				$cat_thumb = "<a href='" . $link . "' " . $title . ">";
			}

			$cat_thumb .= "<img src='" . $product_img . "' " . $alt . $title . ">";
			$cat_thumb .= "</a>";
			$data_add .= "<tr><td>" . $cat_thumb . "</td></tr>";
		}

		if ($this->show_category_name == 'yes')
		{
			$pname = "<tr><td style='text-align:" . $this->ScrollTextAlign . ";font-weight:" . $this->ScrollTextWeight . ";font-size:" . $this->ScrollTextSize . "px;'><a href='" . $link . "' >" . $pname . "</a></td></tr>";
			$data_add .= $pname;
		}

		return $data_add;
	}
}

<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_main_categoryscroller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

global $my, $mosConfig_absolute_path;


// Getting the configuration in redshop.js.php

require_once JPATH_ROOT . '/components/com_redshop/helpers/redshop.js.php';

global $Redconfiguration;
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/category.php';

// get product helper
require_once JPATH_ROOT . '/components/com_redshop/helpers/product.php';

// get product helper
require_once JPATH_ROOT . '/components/com_redshop/helpers/helper.php';

$document = JFactory::getDocument();
JHTML::Script('fetchscript.js', 'components/com_redshop/assets/js/', false);
JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);
/**
 * This class sets all Parameters.
 * Must first call the MOS function, something like:
 * $params = mosParseParams( $module->params );
 * and send the $params variable to this class (productScroller)
 *
 * @param $params the results from mosParseParams( $module->params );
 *
 * @example $scroller = new productScroller($params);
 */
if (!class_exists('redcategoryScroller'))
{ // Prevent double class declaration

	class redcategoryScroller
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
		 * set mammeters
		 */
		function redcategoryScroller(&$params, $module_id)
		{

			$this->params = $params;
			// standard mammeters
			$this->show_category_name = $params->get('show_category_name', "yes");
			$this->show_addtocart     = $params->get('show_addtocart', "yes");
			$this->show_price         = $params->get('show_price', "yes");
			//$this->category_id            =  intval( $params->get('cid', 0 ) );
			$this->category_id = intval(JRequest::getVar('cid', 0));

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
			// customization mammeters
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
		 * Display Product Data
		 */
		function displayredScroller(&$rows)
		{
			global $mosConfig_absolute_path;

			$database = JFactory::getDBO();

			$cnt = 0;
			if ($this->ScrollCSSOverride == 'yes')
			{
				$txt_size = $this->ScrollTextSize . 'px';
				$margin   = $this->ScrollMargin . 'px';
				//$height=($height-intval($margin+0));
				//$width=($width-intval($margin+30));
				echo $this->params->get('pretext', "");
				echo " <div style=\"text-align:" . $this->ScrollAlign . ";background-color: " . $this->ScrollBGColor . "; width:" . $this->ScrollWidth . "px;
                       margin-top: $margin; margin-right: $margin; margin-bottom: $margin; margin-left: $margin;\" >
               <marquee behavior=\"" . $this->ScrollBehavior . "\"
                        direction=\"" . $this->ScrollDirection . "\"
                        height=\"" . $this->ScrollHeight . "\"
                        width=\"" . $this->ScrollWidth . "\"
                        scrollamount=\"" . $this->ScrollAmount . "\"
                        scrolldelay=\"" . $this->ScrollDelay . "\"
                        truespeed=\"true\" onmouseover=\"this.stop()\" onmouseout=\"this.start()\"
                        style=\"text-align: " . $this->ScrollTextAlign . "; color: " . $this->ScrollTextColor . "; font-weight: " . $this->ScrollTextWeight . "; font-size: $txt_size;px\" >";
			}
			else
			{

				echo " <div style=\"width:" . $this->ScrollWidth . "px;text-align:" . $this->ScrollAlign . ";\">
               <marquee behavior=\"" . $this->ScrollBehavior . "\"
                        direction=\"" . $this->ScrollDirection . "\"
                        height=\"" . $this->ScrollHeight . "\"
                        width=\"" . $this->ScrollWidth . "\"
                        scrollamount=\"" . $this->ScrollAmount . "\"
                        scrolldelay=\"" . $this->ScrollDelay . "\"
                        truespeed=\"true\" onmouseover=\"this.stop()\" onmouseout=\"this.start()\">";
			}
			$show_category_name = ($this->show_category_name == "yes") ? true : false;
			$show_addtocart     = ($this->show_addtocart == "yes") ? true : false;
			$show_price         = ($this->show_price == "yes") ? true : false;
			if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right'))
			{
				echo '<table><tr>';
			}
			$i = 0;
			foreach ($rows as $row)
			{
				if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right'))
				{
					echo '<td style="vertical-align:top;padding: 2px 5px 2px 5px;"><table width="' . $this->boxwidth . '">';
				}

				// Display Product
				$categorydata = $this->ShowCategory($row, $i);
				echo $categorydata;
				if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right'))
				{
					echo '</table></td>';

				}
				else
				{
					for ($i = 0; $i < $this->ScrollLineCharTimes; $i++)
					{
						echo $this->ScrollLineChar;
					}
				}
				$i++;
			}
			if (($this->ScrollDirection == 'left') || ($this->ScrollDirection == 'right'))
			{
				echo '</tr></table>';
			}
			echo "    </marquee>
            </div>";
		} // end displayredScroller


		/**
		 * Helper DB function
		 */
		function getredCategorySKU($limit = 0, $how = null, $category_id = 0, $featuredCategory = 'no')
		{
			$app = JFactory::getApplication();

			$category_array = new product_category();

			$hierachy = $category_array->getCategoryListArray($category_id, $category_id);

			$cid = array();

			for ($i = 0; $i < count($hierachy); $i++)
			{
				$cid[] = $hierachy[$i]->category_id;
			}


			$database = JFactory::getDBO();

			if ($limit > 0)
			{
				$limit = "LIMIT $limit";
			}
			else
			{
				$limit = "";
			}

			$query = "SELECT DISTINCT c.*  FROM #__redshop_category AS c";

			$query .= "\n WHERE c.published = '1'";


			switch ($how)
			{
				case 'random':
					$query .= "\n ORDER BY RAND() $limit";
					break;
				case 'newest':
					$query .= "\n ORDER BY c.category_pdate DESC $limit";
					break;
				case 'oldest':
					$query .= "\n ORDER BY c.category_pdate ASC $limit";
					break;
				default:
					$query .= "\n ORDER BY c.category_pdate DESC $limit";
					break;
			}

			$database->setQuery($query);
			$rows = $database->loadObjectList();

			return $rows;
		}

		function ShowCategory($row, $i)
		{
			$producthelper = new producthelper();
			$redhelper     = new redhelper();

			$category_id = intval(JRequest::getVar('cid', 0));

			$ItemData = $redhelper->getCategoryItemid($row->category_id);
			if (count($ItemData) > 0)
			{
				$Itemid = $ItemData->id;
			}
			else
			{
				$Itemid = $redhelper->getItemid($row->category_id);
			}
			//$data_add ='<div>';
			$data_add   = '';
			$thum_image = "";

			$pname = $row->category_name;

			$link = JRoute::_('index.php?option=com_redshop&view=category&layout=detail&cid=' . $row->category_id . '&Itemid=' . $Itemid);

			$pname = $row->category_name;

			if ($this->boxwidth > 0)
			{
				$pwidth = $this->boxwidth / 10;
				$pname  = wordwrap($pname, $pwidth, "<br>\n", true);
			}

			if ($row->category_full_image || CATEGORY_DEFAULT_IMAGE)
			{
				$title   = " title='" . $row->category_name . "' ";
				$alt     = " alt='" . $row->category_name . "' ";
				$w_thumb = $this->thumbwidth;
				$h_thumb = $this->thumbheight;

				$linkimage = REDSHOP_FRONT_IMAGES_ABSPATH . "noimage.jpg";
				if ($row->category_full_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . $row->category_full_image))
				{
					$product_img = $redhelper->watermark('category', $row->category_full_image, $w_thumb, $h_thumb, WATERMARK_CATEGORY_THUMB_IMAGE, $row->category_id);
					$linkimage   = $redhelper->watermark('category', $row->category_full_image, '', '', WATERMARK_CATEGORY_IMAGE, $row->category_id);
				}
				else if (CATEGORY_DEFAULT_IMAGE && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . 'category/' . CATEGORY_DEFAULT_IMAGE))
				{
					$product_img = $redhelper->watermark('category', CATEGORY_DEFAULT_IMAGE, $w_thumb, $h_thumb, 0, 1);
					$linkimage   = $redhelper->watermark('category', CATEGORY_DEFAULT_IMAGE, '', '', 0, 1);
				}
				if (CAT_IS_LIGHTBOX)
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
} // end class categoryScroller
$module_id = "mod_" . $module->id;
// start of category Scroller Script
$scroller = new redcategoryScroller($params, $module->id);

/**
 * Load category
 **/
$rows = $scroller->getredCategorySKU($scroller->NumberOfCategory, $scroller->ScrollSortMethod, $scroller->category_id, $scroller->featuredCategory);

/**
 * Display category Scroller
 **/
$scroller->displayredScroller($rows);


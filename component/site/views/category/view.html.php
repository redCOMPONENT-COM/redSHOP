<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * Category Detail View
 *
 * @package     RedShop.Component
 * @subpackage  Admin
 *
 * @since       1.0
 */
class RedshopViewCategory extends RedshopView
{
	public $app;

	public $input;

	public $state = null;

	public $productPriceSliderEnable = false;

	/**
	 * Execute and display a template script.
	 *
	 * @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	 *
	 * @return  mixed  A string if successful, otherwise a JError object.
	 *
	 * @see     fetch()
	 * @since   11.1
	 */
	public function display($tpl = null)
	{
		$this->app     = JFactory::getApplication();
		$this->input   = $this->app->input;
		$objhelper     = redhelper::getInstance();
		$prodhelperobj = productHelper::getInstance();

		// Request variables
		$this->option = $this->input->getString('option', 'com_redshop');
		$this->itemid = $this->input->getInt('Itemid', null);
		$this->catid = $this->input->getInt('cid', 0);
		$layout = $this->input->getString('layout', '');
		$this->print = $this->input->getBool('print', false);

		$params = $this->app->getParams('com_redshop');
		/** @var RedshopModelCategory $model */
		$model  = $this->getModel('category');
		$this->state = $model->get('state');

		JPluginHelper::importPlugin('redshop_product');
		JPluginHelper::importPlugin('redshop_product_type');
		$this->dispatcher = RedshopHelperUtility::getDispatcher();

		$menu_meta_keywords    = $params->get('menu-meta_keywords');
		$menu_robots           = $params->get('robots');
		$menu_meta_description = $params->get('menu-meta_description');

		if (!$this->catid && $layout == 'detail')
		{
			$this->catid = $params->get('cid');

			if (!$this->catid)
			{
				throw new InvalidArgumentException(JText::_('COM_REDSHOP_CATEGORY_NOT_FOUND'), 404);
			}

			$this->setLayout('detail');
		}

		if (empty($layout) && $this->catid > 0)
		{
			$this->setLayout('detail');
		}

		$document = JFactory::getDocument();
		JHtml::stylesheet('com_redshop/priceslider.css', array(), true);

		$lists   = array();
		$minmax  = array(0, 0);
		$product = array();

		$maincat = $model->_loadCategory();

		$categoryTemplateId   = $model->getState('category_template');
		$allCategoryTemplate  = $model->getCategoryTemplate();
		$orderData            = RedshopHelperUtility::getOrderByList();
		$manufacturers        = $model->getManufacturer();
		$loadCategorytemplate = $model->loadCategoryTemplate($categoryTemplateId);
		$detail               = $model->getdata();

		if (count($maincat) > 0 && $maincat->canonical_url != "")
		{
			$main_url  = JURI::root() . $maincat->canonical_url;
			$canonical = '<link rel="canonical" href="' . $main_url . '" />';
			$document->addCustomTag($canonical);
		}

		$pageheadingtag = '';

		if ($this->catid)
		{
			// Restrict category if category not published
			if ($maincat->published == 0)
			{
				$this->setLayout('notfound');
			}

			$isSlider = false;

			if (count($loadCategorytemplate) > 0 && strpos($loadCategorytemplate[0]->template_desc, "{include_product_in_sub_cat}") !== false)
			{
				$model->setState('include_sub_categories_products', true);
				$loadCategorytemplate[0]->template_desc = str_replace("{include_product_in_sub_cat}", '', $loadCategorytemplate[0]->template_desc);
			}

			if (count($loadCategorytemplate) > 0 && strpos($loadCategorytemplate[0]->template_desc, "{product_price_slider}") !== false)
			{
				$model->getCategoryProduct(1);
				$minmax[0]     = $model->getState('minprice');
				$minmax[1]     = $model->getState('maxprice');

				$isSlider    = true;
				$texpricemin = $this->input->getInt('texpricemin', $minmax[0]);
				$texpricemax = $this->input->getInt('texpricemax', $minmax[1]);
				$model->setMaxMinProductPrice(array($texpricemin, $texpricemax));
			}

			$product = $model->getCategoryProduct(0, $isSlider);

			$document->setMetaData('keywords', $maincat->metakey);
			$document->setMetaData('description', $maincat->metadesc);
			$document->setMetaData('robots', $maincat->metarobot_info);

			// For page title
			$pagetitletag = Redshop::getConfig()->get('SEO_PAGE_TITLE_CATEGORY');
			$parentcat    = "";
			$parentid     = $prodhelperobj->getParentCategory($maincat->id);

			while ($parentid != 0)
			{
				$parentdetail = $prodhelperobj->getSection("category", $parentid);
				$parentcat    = $parentdetail->name . "  " . $parentcat;
				$parentid     = $prodhelperobj->getParentCategory($parentdetail->id);
			}

			$pagetitletag = str_replace("{parentcategoryloop}", $parentcat, $pagetitletag);
			$pagetitletag = str_replace("{categoryname}", $maincat->name, $pagetitletag);
			$pagetitletag = str_replace("{shopname}", Redshop::getConfig()->get('SHOP_NAME'), $pagetitletag);
			$pagetitletag = str_replace("{categoryshortdesc}", strip_tags($maincat->short_description), $pagetitletag);

			if ($maincat->pagetitle != "" && RedShop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_TITLE_CATEGORY') != '')
			{
				if ($maincat->append_to_global_seo == 'append')
				{
					$pagetitletag = $pagetitletag . $maincat->pagetitle;
					$document->setTitle($pagetitletag);
				}
				elseif ($maincat->append_to_global_seo == 'prepend')
				{
					$pagetitletag = $maincat->pagetitle . $pagetitletag;
					$document->setTitle($pagetitletag);
				}
				elseif ($maincat->append_to_global_seo == 'replace')
				{
					$document->setTitle($maincat->pagetitle);
				}
			}
			elseif ($maincat->pagetitle != "")
			{
				$document->setTitle($maincat->pagetitle);
			}
			elseif (RedShop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_TITLE_CATEGORY') != '')
			{
				$document->setTitle($pagetitletag);
			}
			else
			{
				$document->setTitle($this->app->getCfg('sitename'));
			}

			$pagekeywordstag = '';

			if (RedShop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_KEYWORDS_CATEGORY') != '')
			{
				$pagekeywordstag = Redshop::getConfig()->get('SEO_PAGE_KEYWORDS_CATEGORY');
				$pagekeywordstag = str_replace("{categoryname}", $maincat->name, $pagekeywordstag);
				$pagekeywordstag = str_replace("{categoryshortdesc}", strip_tags($maincat->short_description), $pagekeywordstag);
				$pagekeywordstag = str_replace("{shopname}", Redshop::getConfig()->get('SHOP_NAME'), $pagekeywordstag);
				$document->setMetaData('keywords', $pagekeywordstag);
			}

			if (trim($maincat->metakey) != ''
				&& RedShop::getConfig()->get('AUTOGENERATED_SEO')
				&& Redshop::getConfig()->get('SEO_PAGE_KEYWORDS_CATEGORY') != '')
			{
				if ($maincat->append_to_global_seo == 'append')
				{
					$pagekeywordstag .= "," . trim($maincat->metakey);
					$document->setMetaData('keywords', $pagekeywordstag);
				}
				elseif ($maincat->append_to_global_seo == 'prepend')
				{
					$pagekeywordstag = trim($maincat->metakey) . $pagekeywordstag;
					$document->setMetaData('keywords', $pagekeywordstag);
				}
				elseif ($maincat->append_to_global_seo == 'replace')
				{
					$document->setMetaData('keywords', $maincat->metakey);
				}
			}
			else
			{
				if ($maincat->metakey != '')
				{
					$document->setMetaData('keywords', $maincat->metakey);
				}
				else
				{
					if (RedShop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_KEYWORDS_CATEGORY') != '')
					{
						$document->setMetaData('keywords', $pagekeywordstag);
					}
					else
					{
						$document->setMetaData('keywords', $maincat->name);
					}
				}
			}

			$pagedesctag = '';

			// For custom + auto generated description
			if (RedShop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_DESCRIPTION_CATEGORY') != '')
			{
				$pagedesctag = Redshop::getConfig()->get('SEO_PAGE_DESCRIPTION_CATEGORY');
				$pagedesctag = str_replace("{categoryname}", $maincat->name, $pagedesctag);
				$pagedesctag = str_replace("{shopname}", Redshop::getConfig()->get('SHOP_NAME'), $pagedesctag);
				$pagedesctag = str_replace("{categoryshortdesc}", strip_tags($maincat->short_description), $pagedesctag);
				$pagedesctag = str_replace("{categorydesc}", strip_tags($maincat->description), $pagedesctag);
			}

			if ($maincat->metadesc != '' && RedShop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_DESCRIPTION_CATEGORY') != '')
			{
				if ($maincat->append_to_global_seo == 'append')
				{
					$pagedesctag .= $maincat->metadesc;
					$document->setMetaData('description', $pagedesctag);
				}
				elseif ($maincat->append_to_global_seo == 'prepend')
				{
					$pagedesctag = trim($maincat->metadesc) . $pagedesctag;
					$document->setMetaData('description', $pagedesctag);
				}
				elseif ($maincat->append_to_global_seo == 'replace')
				{
					$document->setMetaData('description', $maincat->metadesc);
				}
			}
			elseif ($maincat->metadesc != '')
			{
				$document->setMetaData('description', $maincat->metadesc);
			}
			else
			{
				if (RedShop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_DESCRIPTION_CATEGORY') != '')
				{
					$document->setMetaData('description', $pagedesctag);
				}
				else
				{
					$document->setMetaData('description', $maincat->name);
				}
			}

			// For metarobot
			if ($maincat->metarobot_info != '')
			{
				$document->setMetaData('robots', $maincat->metarobot_info);
			}
			else
			{
				if (RedShop::getConfig()->get('AUTOGENERATED_SEO') && JFactory::getConfig()->get('robots') != '')
				{
					$document->setMetaData('robots', JFactory::getConfig()->get('robots'));
				}
				else
				{
					$document->setMetaData('robots', "INDEX,FOLLOW");
				}
			}

			$pageheadingtag = str_replace("{categoryname}", $maincat->name, Redshop::getConfig()->get('SEO_PAGE_HEADING_CATEGORY'));

			if ($maincat->pageheading != "" && RedShop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_HEADING_CATEGORY') != '')
			{
				$pageheadingtag = $pageheadingtag . $maincat->pageheading;
			}
			elseif ($maincat->pageheading != "")
			{
				$pageheadingtag = $maincat->pageheading;
			}
			else
			{
				$pageheadingtag = $this->app->getCfg('sitename');
			}
		}
		else
		{
			if ($menu_meta_keywords != "")
			{
				$document->setMetaData('keywords', $menu_meta_keywords);
			}
			else
			{
				$document->setMetaData('keywords', $this->app->getCfg('sitename'));
			}

			if ($menu_meta_description != "")
			{
				$document->setMetaData('description', $menu_meta_description);
			}
			else
			{
				$document->setMetaData('description', $this->app->getCfg('sitename'));
			}

			if ($menu_robots != "")
			{
				$document->setMetaData('robots', $menu_robots);
			}
			else
			{
				$document->setMetaData('robots', $this->app->getCfg('sitename'));
			}
		}

		// Breadcrumbs
		RedshopHelperBreadcrumb::generate($this->catid);
		$disabled = "";

		if ($this->print)
		{
			$disabled = "disabled";
		}

		$manufacturerId = $model->getState('manufacturer_id');

		$lists['category_template'] = "";
		$lists['manufacturer']      = "";

		if (count($manufacturers) > 0)
		{
			$temps = array(
				(object) array(
					'manufacturer_id'   => 0,
					'manufacturer_name' => JText::_('COM_REDSHOP_SELECT_MANUFACTURE')
				)
			);
			$manufacturers = array_merge($temps, $manufacturers);
			$lists['manufacturer'] = JHtml::_(
				'select.genericlist',
				$manufacturers,
				'manufacturer_id',
				'class="inputbox" onchange="javascript:setSliderMinMaxForManufactur();" ' . $disabled . ' ',
				'manufacturer_id',
				'manufacturer_name',
				$manufacturerId
			);
		}

		if (count($allCategoryTemplate) > 1)
		{
			$lists['category_template'] = JHtml::_(
				'select.genericlist',
				$allCategoryTemplate,
				'category_template',
				'class="inputbox" size="1" onchange="javascript:setSliderMinMaxForTemplate();" ' . $disabled . ' ',
				'template_id',
				'template_name',
				$categoryTemplateId
			);
		}

		$orderByMethod = $this->app->getUserStateFromRequest($model->context . '.order_by', 'order_by');
		$lists['order_by'] = JHtml::_(
			'select.genericlist',
			$orderData,
			'order_by',
			'class="inputbox" size="1" onChange="javascript:setSliderMinMax();" ' . $disabled . ' ',
			'value',
			'text',
			$orderByMethod
		);

		if ($this->catid && count($loadCategorytemplate) > 0)
		{
			if (strpos($loadCategorytemplate[0]->template_desc, "{product_price_slider}") !== false)
			{
				$ajaxSlide = $this->input->getBool('ajaxslide', false);

				if (!$ajaxSlide)
				{
					$strToInsert = "<div id='oldredcatpagination'>{show_all_products_in_category}</div>";
					$loadCategorytemplate[0]->template_desc = str_replace("{show_all_products_in_category}", $strToInsert, $loadCategorytemplate[0]->template_desc);

					$strToInsert = "<div id='oldredcatpagination'>{pagination}</div>";
					$loadCategorytemplate[0]->template_desc = str_replace("{pagination}", $strToInsert, $loadCategorytemplate[0]->template_desc);

					$strToInsert = '<span id="oldRedPageLimit">{product_display_limit}</span>';
					$loadCategorytemplate[0]->template_desc = str_replace("{product_display_limit}", $strToInsert, $loadCategorytemplate[0]->template_desc);
				}

				if (count($product) > 0)
				{
					$this->productPriceSliderEnable = true;

					// Start Code for fixes IE9 issue
					JHtml::_('redshopjquery.ui');

					// End Code for fixes IE9 issue
					require_once JPATH_ROOT . '/media/com_redshop/js/catprice_filter.php';
				}
				else
				{
					$loadCategorytemplate[0]->template_desc = str_replace("{product_price_slider}", "", $loadCategorytemplate[0]->template_desc);
					$loadCategorytemplate[0]->template_desc = str_replace("{pagination}", "", $loadCategorytemplate[0]->template_desc);
				}
			}

			if (!count($product))
			{
				$loadCategorytemplate[0]->template_desc = str_replace("{order_by_lbl}", "", $loadCategorytemplate[0]->template_desc);
				$loadCategorytemplate[0]->template_desc = str_replace("{order_by}", "", $loadCategorytemplate[0]->template_desc);

				if (!$manufacturerId)
				{
					$loadCategorytemplate[0]->template_desc = str_replace("{filter_by_lbl}", "", $loadCategorytemplate[0]->template_desc);
					$loadCategorytemplate[0]->template_desc = str_replace("{filter_by}", "", $loadCategorytemplate[0]->template_desc);
				}
			}
		}

		$this->detail = $detail;
		$this->lists = $lists;
		$this->product = $product;
		$this->pageheadingtag = $pageheadingtag;
		$this->params = $params;
		$this->maincat = $maincat;
		$this->category_template_id = $categoryTemplateId;
		$this->order_by_select = $orderByMethod;
		$this->manufacturer_id = $manufacturerId;
		$this->loadCategorytemplate = $loadCategorytemplate;

		parent::display($tpl);
	}
}

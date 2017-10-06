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
 * Product Detail View
 *
 * @package     RedShop.Component
 * @subpackage  Site
 *
 * @since       1.0
 */
class RedshopViewProduct extends RedshopView
{
	// JApplication object
	public $app;

	// JInput object
	public $input;

	// Redtemplate helper
	public $redTemplate;

	// Redhelper
	public $redHelper;

	// Text_library helper
	public $textHelper;

	// Menu item ID
	public $itemId;

	// Product ID
	public $pid;

	// The Dispatcher
	public $dispatcher;

	// Product model
	public $model;

	// JDocument object
	public $document;

	// JSession object
	public $session;

	// Product data object
	public $data;

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
		// Request variables
		$prodhelperobj     = productHelper::getInstance();
		$this->redTemplate = Redtemplate::getInstance();
		$this->redHelper   = redhelper::getInstance();
		$this->textHelper  = new text_library;

		$this->app             = JFactory::getApplication();
		$this->input           = $this->app->input;
		$this->model           = $this->getModel('product');
		$this->document        = JFactory::getDocument();
		$this->session         = JFactory::getSession();
		$pageheadingtag        = '';
		$params                = $this->app->getParams('com_redshop');
		$menu_meta_keywords    = $params->get('menu-meta_keywords');
		$menu_meta_description = $params->get('menu-meta_description');
		$menu_robots           = $params->get('robots');
		$this->data            = $this->get('data');

		$productTemplate       = null;

		$this->itemId = $this->input->getInt('Itemid', null);
		$this->pid    = $this->input->getInt('pid', 0);
		$layout       = $this->input->getString('layout', 'default');
		$template     = $this->input->getString('r_template', '');

		JPluginHelper::importPlugin('redshop_product');
		$this->dispatcher = RedshopHelperUtility::getDispatcher();

		if (!$this->pid)
		{
			$this->pid = $params->get('productid');
		}

		if (Redshop::getConfig()->get('MY_WISHLIST'))  // if enable wishlist
		{
			JHtml::script('com_redshop/redshop.wishlist.js', false, true);
		}

		/*
		 *  Include JavaScript.
		 *  But, first check if a plugin wants to use its own jQuery.
		 */
		$stopJQuery = $this->dispatcher->trigger('stopProductRedshopJQuery', array($this->data, $layout));

		if (in_array(true, $stopJQuery, true))
		{
			$stopJQuery = true;
		}
		else
		{
			$stopJQuery = false;
		}

		JHtml::stylesheet('com_redshop/scrollable-navig.css', array(), true);

		if ($layout == "downloadproduct")
		{
			$this->setLayout('downloadproduct');
		}
		elseif ($layout == "compare")
		{
			$this->setLayout('compare');
		}
		elseif ($layout == "viewajaxdetail")
		{
			$this->setLayout('viewajaxdetail');
		}
		else
		{
			// Ajax box
			if ($template == 'cartbox' && Redshop::getConfig()->get('AJAX_CART_BOX') == 1)
			{
				$this->loadTemplate('cartbox');
				JFactory::getApplication()->close();
			}
			else
			{
				$this->setLayout('default');
			}

			$prodhelperobj_array_main = $prodhelperobj->getProductNetPrice($this->data->product_id);

			if ($this->data->published == 0)
			{
				JError::raiseError(404, sprintf(JText::_('COM_REDSHOP_PRODUCT_IS_NOT_PUBLISHED'), $this->data->product_name, $this->data->product_number));
			}

			$productTemplate = $this->model->getProductTemplate();

			/*
			 * Process the prepare Product plugins
			 */
			$this->dispatcher->trigger('onPrepareProduct', array(& $productTemplate->template_desc, & $params, $this->data));

			$pagetitletag = '';

			// For page title
			if (Redshop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_TITLE') != '')
			{
				$pagetitletag = Redshop::getConfig()->get('SEO_PAGE_TITLE');

				$pagetitletag = str_replace("{productname}", $this->data->product_name, $pagetitletag);
				$pagetitletag = str_replace("{categoryname}", $this->data->category_name, $pagetitletag);
				$pagetitletag = str_replace("{manufacturer}", $this->data->manufacturer_name, $pagetitletag);
				$pagetitletag = str_replace("{productsku}", $this->data->product_number, $pagetitletag);
				$pagetitletag = str_replace("{productnumber}", $this->data->product_number, $pagetitletag);
				$pagetitletag = str_replace("{shopname}", Redshop::getConfig()->get('SHOP_NAME'), $pagetitletag);
				$pagetitletag = str_replace("{productshortdesc}", strip_tags($this->data->product_s_desc), $pagetitletag);
				$pagetitletag = str_replace("{saleprice}", $prodhelperobj->getProductFormattedPrice($prodhelperobj_array_main['product_price']), $pagetitletag);

				$parentcat = "";
				$parentid  = $prodhelperobj->getParentCategory($this->data->category_id);

				while ($parentid != 0)
				{
					$parentdetail = $prodhelperobj->getSection("category", $parentid);
					$parentcat    = $parentdetail->name . "  " . $parentcat;
					$parentid     = $prodhelperobj->getParentCategory($parentdetail->id);
				}

				$pagetitletag = str_replace("{parentcategoryloop}", $parentcat, $pagetitletag);

				$pagetitletag = $prodhelperobj->getProductNotForSaleComment($this->data, $pagetitletag);
			}

			if ($this->data->pagetitle != '' && Redshop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_TITLE') != '')
			{
				if ($this->data->append_to_global_seo == 'append')
				{
					$pagetitletag .= " " . $this->data->pagetitle;
					$this->document->setTitle($pagetitletag);
					$this->document->setMetaData("og:title", $pagetitletag);
				}
				elseif ($this->data->append_to_global_seo == 'prepend')
				{
					$pagetitletag = $this->data->pagetitle . " " . $pagetitletag;
					$this->document->setTitle($pagetitletag);
					$this->document->setMetaData("og:title", $pagetitletag);
				}
				elseif ($this->data->append_to_global_seo == 'replace')
				{
					$this->document->setTitle($this->data->pagetitle);
					$this->document->setMetaData("og:title", $this->data->pagetitle);
				}
			}
			else
			{
				if ($this->data->pagetitle != '')
				{
					$this->document->setTitle($this->data->pagetitle);
					$this->document->setMetaData("og:title", $this->data->pagetitle);
				}
				elseif (Redshop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_TITLE') != '')
				{
					$this->document->setTitle($pagetitletag);
					$this->document->setMetaData("og:title", $pagetitletag);
				}
				else
				{
					$this->document->setTitle(
												$this->data->product_name . " | " .
												$this->data->category_name . " | " .
												$this->app->getCfg('sitename') . " | " .
												$this->data->product_number
											);

					$this->document->setMetaData(
											"og:title",
											$this->data->product_name . " | " .
											$this->data->category_name . " | " .
											$this->app->getCfg('sitename') . " | " .
											$this->data->product_number
										);
				}
			}

			$uri    = JFactory::getURI();
			$scheme = $uri->getScheme();
			$host   = $uri->getHost();

			if ($this->data->product_thumb_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $this->data->product_thumb_image))
			{
				$imageLink = $scheme . "://" . $host . "/components/com_redshop/assets/images/product/" . $this->data->product_thumb_image;

				list($width, $height, $type, $attr) = @getimagesize(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $this->data->product_thumb_image);

				$openGraphTag = '<meta property="og:image" content="' . $imageLink . '" />' . "\n";
				$openGraphTag .= '<meta property="og:image:type" content="image/jpeg" />' . "\n";
				$openGraphTag .= '<meta property="og:image:width" content="' . $width . '" />' . "\n";
				$openGraphTag .= '<meta property="og:image:height" content="' . $height . '" />' . "\n";
				$this->document->addCustomTag($openGraphTag);
			}
			elseif ($this->data->product_full_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $this->data->product_full_image))
			{
				$imageLink = $scheme . "://" . $host . "/components/com_redshop/assets/images/product/" . $this->data->product_full_image;

				list($width, $height, $type, $attr) = @getimagesize(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $this->data->product_full_image);

				$openGraphTag = '<meta name="og:image" property="og:image" content="' . $imageLink . '" />' . "\n";
				$openGraphTag .= '<meta name="og:image:type" property="og:image:type" content="image/jpeg" />' . "\n";
				$openGraphTag .= '<meta name="og:image:width" property="og:image:width" content="' . $width . '" />' . "\n";
				$openGraphTag .= '<meta name="og:image:height" property="og:image:height" content="' . $height . '" />' . "\n";
				$this->document->addCustomTag($openGraphTag);
			}

			$pagekeywordstag = '';

			if (Redshop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_KEYWORDS') != '')
			{
				$pagekeywordstag = Redshop::getConfig()->get('SEO_PAGE_KEYWORDS');
				$pagekeywordstag = str_replace("{productname}", $this->data->product_name, $pagekeywordstag);
				$pagekeywordstag = str_replace("{categoryname}", $this->data->category_name, $pagekeywordstag);
				$pagekeywordstag = str_replace("{manufacturer}", $this->data->manufacturer_name, $pagekeywordstag);
				$pagekeywordstag = str_replace("{productsku}", $this->data->product_number, $pagekeywordstag);
				$pagekeywordstag = str_replace("{productnumber}", $this->data->product_number, $pagekeywordstag);
				$pagekeywordstag = str_replace("{shopname}", Redshop::getConfig()->get('SHOP_NAME'), $pagekeywordstag);
				$pagekeywordstag = str_replace("{productshortdesc}", strip_tags($this->data->product_s_desc), $pagekeywordstag);
				$pagekeywordstag = str_replace("{saleprice}", $prodhelperobj->getProductFormattedPrice($prodhelperobj_array_main['product_price']), $pagekeywordstag);
				$pagekeywordstag = $prodhelperobj->getProductNotForSaleComment($this->data, $pagekeywordstag);

				$this->document->setMetaData('keywords', $pagekeywordstag);
			}

			if (trim($this->data->metakey) != '' && Redshop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_KEYWORDS') != '')
			{
				if ($this->data->append_to_global_seo == 'append')
				{
					$pagekeywordstag .= "," . trim($this->data->metakey);
					$this->document->setMetaData('keywords', $pagekeywordstag);
				}
				elseif ($this->data->append_to_global_seo == 'prepend')
				{
					$this->document->setMetaData('keywords', $pagekeywordstag);
				}
				elseif ($this->data->append_to_global_seo == 'replace')
				{
					$this->document->setMetaData('keywords', $this->data->metakey);
				}
			}
			else
			{
				if (trim($this->data->metakey) != '')
				{
					$this->document->setMetaData('keywords', $this->data->metakey);
				}
				else
				{
					if (Redshop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_KEYWORDS') != '')
					{
						$this->document->setMetaData('keywords', $pagekeywordstag);
					}
					elseif ($menu_meta_keywords != "")
					{
						$this->document->setMetaData('keywords', $menu_meta_keywords);
					}
					else
					{
						$this->document->setMetaData('keywords', $this->data->product_name . ", " . $this->data->category_name . ", " . Redshop::getConfig()->get('SHOP_NAME') . ", " . $this->data->product_number);
					}
				}
			}

			if (trim($this->data->metarobot_info) != '')
			{
				$this->document->setMetaData('robots', $this->data->metarobot_info);
			}
			else
			{
				if (Redshop::getConfig()->get('AUTOGENERATED_SEO') && JFactory::getConfig()->get('robots') != '')
				{
					$this->document->setMetaData('robots', JFactory::getConfig()->get('robots'));
				}
				elseif ($menu_robots != "")
				{
					$this->document->setMetaData('robots', $menu_robots);
				}
				else
				{
					$this->document->setMetaData('robots', "INDEX,FOLLOW");
				}
			}

			$pagedesctag = '';

			// For meta description
			if (Redshop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_DESCRIPTION') != '')
			{
				if ($prodhelperobj_array_main['product_price_saving'] != "")
				{
					$product_price_saving_main = $prodhelperobj_array_main['product_price_saving'];
				}
				else
				{
					$product_price_saving_main = 0;
				}

				$pagedesctag = Redshop::getConfig()->get('SEO_PAGE_DESCRIPTION');
				$pagedesctag = str_replace("{productname}", $this->data->product_name, $pagedesctag);
				$pagedesctag = str_replace("{categoryname}", $this->data->category_name, $pagedesctag);
				$pagedesctag = str_replace("{manufacturer}", $this->data->manufacturer_name, $pagedesctag);
				$pagedesctag = str_replace("{productsku}", $this->data->product_number, $pagedesctag);
				$pagedesctag = str_replace("{productnumber}", $this->data->product_number, $pagedesctag);
				$pagedesctag = str_replace("{shopname}", Redshop::getConfig()->get('SHOP_NAME'), $pagedesctag);
				$pagedesctag = str_replace("{productshortdesc}", strip_tags($this->data->product_s_desc), $pagedesctag);
				$pagedesctag = str_replace("{productdesc}", strip_tags($this->data->product_desc), $pagedesctag);
				$pagedesctag = str_replace("{saleprice}", $prodhelperobj->getProductFormattedPrice($prodhelperobj_array_main['product_price']), $pagedesctag);
				$pagedesctag = str_replace("{saving}", $prodhelperobj->getProductFormattedPrice($product_price_saving_main), $pagedesctag);
				$pagedesctag = $prodhelperobj->getProductNotForSaleComment($this->data, $pagedesctag);
			}

			if (trim($this->data->metadesc) != '' && Redshop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_DESCRIPTION') != '')
			{
				if ($this->data->append_to_global_seo == 'append')
				{
					$pagedesctag .= " " . $this->data->metadesc;
					$this->document->setMetaData('description', $pagedesctag);
					$this->document->setMetaData("og:description", $pagedesctag);
				}
				elseif ($this->data->append_to_global_seo == 'prepend')
				{
					$this->document->setMetaData('description', $pagedesctag);
					$this->document->setMetaData("og:description", $pagedesctag);
				}
				elseif ($this->data->append_to_global_seo == 'replace')
				{
					$this->document->setMetaData('description', $this->data->metadesc);
					$this->document->setMetaData("og:description", $this->data->metadesc);
				}
			}
			else
			{
				if (trim($this->data->metadesc) != '')
				{
					$this->document->setMetaData('description', $this->data->metadesc);
					$this->document->setMetaData("og:description", $pagedesctag);
				}
				elseif (Redshop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_DESCRIPTION') != '')
				{
					$this->document->setMetaData('description', $pagedesctag);
					$this->document->setMetaData("og:description", $pagedesctag);
				}
				elseif ($menu_meta_description != "")
				{
					$this->document->setMetaData('description', $menu_meta_description);
					$this->document->setMetaData("og:description", $menu_meta_description);
				}
				else
				{
					$prodhelperobj_array = $prodhelperobj->getProductNetPrice($this->data->product_id);

					if ($prodhelperobj_array['product_price_saving'] != '')
					{
						$product_price_saving_main = $prodhelperobj->getProductFormattedPrice($prodhelperobj_array['product_price_saving']);
					}
					else
					{
						$product_price_saving_main = 0;
					}

					$this->document->setMetaData(
											'description',
											JText::_('COM_REDSHOP_META_BUY') . ' ' . $this->data->product_name . ' ' .
											JText::_('COM_REDSHOP_META_AT_ONLY') . ' ' . $prodhelperobj->getProductFormattedPrice($prodhelperobj_array['product_price']) . ' ' .
											JText::_('COM_REDSHOP_META_SAVE') . ' ' . $product_price_saving_main
										);
					$this->document->setMetaData(
											'og:description',
											JText::_('COM_REDSHOP_META_BUY') . ' ' . $this->data->product_name . ' ' .
											JText::_('COM_REDSHOP_META_AT_ONLY') . ' ' . $prodhelperobj->getProductFormattedPrice($prodhelperobj_array['product_price']) . ' ' .
											JText::_('COM_REDSHOP_META_SAVE') . ' ' . $product_price_saving_main
										);
				}
			}

			/**
			 * @var $this->data
			 * Trigger event onAfterDisplayProduct
			 * Show content return by plugin directly into product page after display product title
			 */
			$this->data->event = new stdClass;
			$results = $this->dispatcher->trigger('onAfterDisplayProductTitle', array(&$productTemplate->template_desc, $params, $this->data));
			$this->data->event->afterDisplayTitle = trim(implode("\n", $results));

			/**
			 * @var $this->data
			 *
			 * Trigger event onBeforeDisplayProduct will display content before product display
			 */
			$results = $this->dispatcher->trigger('onBeforeDisplayProduct', array(&$productTemplate->template_desc, $params, $this->data));
			$this->data->event->beforeDisplayProduct = trim(implode("\n", $results));

			// For page heading
			if (Redshop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_HEADING') != '')
			{
				$pageheadingtag = Redshop::getConfig()->get('SEO_PAGE_HEADING');
				$pageheadingtag = str_replace("{productname}", $this->data->product_name, $pageheadingtag);
				$pageheadingtag = str_replace("{categoryname}", $this->data->category_name, $pageheadingtag);
				$pageheadingtag = str_replace("{manufacturer}", $this->data->manufacturer_name, $pageheadingtag);
				$pageheadingtag = str_replace("{productsku}", $this->data->product_number, $pageheadingtag);
				$pageheadingtag = str_replace("{productnumber}", $this->data->product_number, $pageheadingtag);
				$pageheadingtag = str_replace("{productshortdesc}", strip_tags($this->data->product_s_desc), $pageheadingtag);
			}

			if (trim($this->data->pageheading) != '' && Redshop::getConfig()->get('AUTOGENERATED_SEO') && Redshop::getConfig()->get('SEO_PAGE_HEADING') != '')
			{
				$pageheadingtag = $pageheadingtag . " " . $this->data->pageheading;
			}
			else
			{
				if (trim($this->data->pageheading) != '')
				{
					$pageheadingtag = $this->data->pageheading;
				}
			}

			$visited = array();
			$visited = $this->session->get('visited', $visited);

			if ($this->pid && !(in_array($this->pid, $visited)))
			{
				$this->model->updateVisited($this->pid);
				$visited[] = $this->pid;
				$this->session->set('visited', $visited);
			}

			// End
		}

		// Breadcrumb
		if ($this->pid)
		{
			RedshopHelperBreadcrumb::generate($this->pid);
		}

		$this->template = $productTemplate;
		$this->pageheadingtag = $pageheadingtag;
		$this->params = $params;

		$for = $this->input->getBool("for", false);

		if ($for)
		{
			parent::display('related');

			return;
		}

		parent::display($tpl);
	}
}

<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die ('restricted access');

jimport('joomla.application.component.view');
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'configuration.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'category.php';
require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'helpers' . DS . 'text_library.php';
require_once JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php';
require_once JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'helper.php';


class productViewproduct extends JView
{
	public function display($tpl = null)
	{
//   		global $mainframe;
		// Request variables
		$mainframe     = JFactory::getApplication();
		$prodhelperobj = new producthelper;
		$redTemplate   = new Redtemplate;
		$redhelper     = new redhelper;
		$texts         = new text_library;
		$dispatcher    =& JDispatcher::getInstance();

		$option   = JRequest::getVar('option', 'com_redshop');
		$Itemid   = JRequest::getVar('Itemid');
		$pid      = JRequest::getInt('pid');
		$cid      = JRequest::getVar('cid');
		$layout   = JRequest::getVar('layout');
		$template = JRequest::getVar('r_template');

		$pageheadingtag        = '';
		$document              = & JFactory::getDocument();
		$params                = & $mainframe->getParams($option);
		$menu_meta_keywords    = $params->get('menu-meta_keywords');
		$menu_meta_description = $params->get('menu-meta_description');
		$menu_robots           = $params->get('robots');

		$model   = $this->getModel('product');
		$session = JFactory::getSession();

		if (!$pid)
		{
			$pid = $params->get('productid');
		}
		// Include Javascript

		JHTML::Script('jquery.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('redBOX.js', 'components/com_redshop/assets/js/', false);

		JHTML::Script('json.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('attribute.js', 'components/com_redshop/assets/js/', false);
		JHTML::Script('common.js', 'components/com_redshop/assets/js/', false);

		// lightbox Javascript
		JHTML::Stylesheet('style.css', 'components/com_redshop/assets/css/');
		JHTML::Stylesheet('scrollable-navig.css', 'components/com_redshop/assets/css/');

		// reddesign start
		$CheckRedDesign = $redhelper->CheckIfRedDesign();

		if ($CheckRedDesign)
		{
			$chkprodesign = $redhelper->CheckIfRedProduct($pid);

			if ($chkprodesign)
			{
				$mainframe->Redirect('index.php?option=' . $option . '&view=reddesign&pid=' . $pid . '&cid=' . $cid . '&Itemid=' . $Itemid);
			}
		}
		// reddesign end


		if ($layout == "downloadproduct")
		{
			$this->setLayout('downloadproduct');

//			$data	=& $this->get('data');
		}
		else if ($layout == "compare")
		{
			$this->setLayout('compare');
		}
		else if ($layout == "viewajaxdetail")
		{
			$this->setLayout('viewajaxdetail');
			$data =& $this->get('data');

		}
		else if ($layout == "searchletter")
		{
			$this->setLayout('searchletter');
		}
		else
		{
			// ajax box
			if ($template == 'cartbox' && AJAX_CART_BOX == 1)
			{
				$this->loadTemplate('cartbox');
				exit;
			}
			else
			{
				$this->setLayout('default');
			}
			$data                     =& $this->get('data');
			$prodhelperobj_array_main = $prodhelperobj->getProductNetPrice($data->product_id);

			if ($data->published == 0)
			{
				JError::raiseError(404, sprintf(JText::_('COM_REDSHOP_PRODUCT_IS_NOT_PUBLISHED'), $data->product_name, $data->product_number));
			}

			if ($data->canonical_url != "")
			{
				$main_url  = JURI::root() . $data->canonical_url;
				$canonical = '<link rel="canonical" href="' . $main_url . '" />';
				$document->addCustomTag($canonical);
			}
			else if ($data->product_parent_id != 0 && $data->product_parent_id != "")
			{
				$product_parent_data = $prodhelperobj->getProductById($data->product_parent_id);

				if ($product_parent_data->canonical_url != "")
				{
					$main_url  = JURI::root() . $product_parent_data->canonical_url;
					$canonical = '<link rel="canonical" href="' . $main_url . '" />';
					$document->addCustomTag($canonical);
				}
				else
				{
					$main_url  = substr_replace(JURI::root(), "", -1) . JRoute::_('index.php?option=com_redshop&view=product&layout=detail&Itemid=' . $Itemid . '&pid=' . $data->product_parent_id, false);
					$canonical = '<link rel="canonical" href="' . $main_url . '" />';
					$document->addCustomTag($canonical);
				}
			}
			$productTemplate =& $model->getProductTemplate();

			/*
			 * Process the prepare Product plugins
			 */
			JPluginHelper::importPlugin('redshop_product');
			$results = $dispatcher->trigger('onPrepareProduct', array(& $productTemplate->template_desc, & $params, $data));

			// for page title
			if (AUTOGENERATED_SEO && SEO_PAGE_TITLE != '')
			{
				$pagetitletag = SEO_PAGE_TITLE;

				$pagetitletag = str_replace("{productname}", $data->product_name, $pagetitletag);
				$pagetitletag = str_replace("{categoryname}", $data->category_name, $pagetitletag);
				$pagetitletag = str_replace("{manufacturer}", $data->manufacturer_name, $pagetitletag);
				$pagetitletag = str_replace("{productsku}", $data->product_number, $pagetitletag);
				$pagetitletag = str_replace("{productnumber}", $data->product_number, $pagetitletag);
				$pagetitletag = str_replace("{shopname}", SHOP_NAME, $pagetitletag);
				$pagetitletag = str_replace("{productshortdesc}", strip_tags($data->product_s_desc), $pagetitletag);
				$pagetitletag = str_replace("{saleprice}", $prodhelperobj_array_main['product_price'], $pagetitletag);

				$parentcat = "";
				$parentid  = $prodhelperobj->getParentCategory($data->category_id);
				while ($parentid != 0)
				{
					$parentdetail = $prodhelperobj->getSection("category", $parentid);
					$parentcat    = $parentdetail->category_name . "  " . $parentcat;
					$parentid     = $prodhelperobj->getParentCategory($parentdetail->category_id);
				}
				$pagetitletag = str_replace("{parentcategoryloop}", $parentcat, $pagetitletag);

				$pagetitletag = $prodhelperobj->getProductNotForSaleComment($data, $pagetitletag);
			}

			if ($data->pagetitle != '' && AUTOGENERATED_SEO && SEO_PAGE_TITLE != '')
			{
				if ($data->append_to_global_seo == 'append')
				{
					$pagetitletag = $pagetitletag . " " . $data->pagetitle;
					$document->setTitle($pagetitletag);
					$document->setMetaData("og:title", $pagetitletag);

				}
				else if ($data->append_to_global_seo == 'prepend')
				{
					$pagetitletag = $data->pagetitle . " " . $pagetitletag;
					$document->setTitle($pagetitletag);
					$document->setMetaData("og:title", $pagetitletag);

				}
				else if ($data->append_to_global_seo == 'replace')
				{
					$document->setTitle($data->pagetitle);
					$document->setMetaData("og:title", $data->pagetitle);
				}
			}
			else
			{
				if ($data->pagetitle != '')
				{
					$document->setTitle($data->pagetitle);
					$document->setMetaData("og:title", $data->pagetitle);
				}
				else if (AUTOGENERATED_SEO && SEO_PAGE_TITLE != '')
				{
					$document->setTitle($pagetitletag);
					$document->setMetaData("og:title", $pagetitletag);
				}
				else
				{
					$document->setTitle($data->product_name . " | " . $data->category_name . " | " . $mainframe->getCfg('sitename') . " | " . $data->product_number);
					$document->setMetaData("og:title", $data->product_name . " | " . $data->category_name . " | " . $mainframe->getCfg('sitename') . " | " . $data->product_number);
				}
			}

			$uri    = JFactory::getURI();
			$scheme = $uri->getScheme();
			$host   = $uri->getHost();

			if ($data->product_thumb_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $data->product_thumb_image))
			{
				$document->setMetaData("og:image", $scheme . "://" . $host . "/components/com_redshop/assets/images/product/" . $data->product_thumb_image);

			}
			else if ($data->product_full_image && file_exists(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $data->product_full_image))
			{
				$document->setMetaData("og:image", $scheme . "://" . $host . "/components/com_redshop/assets/images/product/" . $data->product_full_image);
			}

			if (AUTOGENERATED_SEO && SEO_PAGE_KEYWORDS != '')
			{
				$pagekeywordstag = SEO_PAGE_KEYWORDS;
				$pagekeywordstag = str_replace("{productname}", $data->product_name, $pagekeywordstag);
				$pagekeywordstag = str_replace("{categoryname}", $data->category_name, $pagekeywordstag);
				$pagekeywordstag = str_replace("{manufacturer}", $data->manufacturer_name, $pagekeywordstag);
				$pagekeywordstag = str_replace("{productsku}", $data->product_number, $pagekeywordstag);
				$pagekeywordstag = str_replace("{productnumber}", $data->product_number, $pagekeywordstag);
				$pagekeywordstag = str_replace("{shopname}", SHOP_NAME, $pagekeywordstag);
				$pagekeywordstag = str_replace("{productshortdesc}", strip_tags($data->product_s_desc), $pagekeywordstag);
				$pagekeywordstag = str_replace("{saleprice}", $prodhelperobj_array_main['product_price'], $pagekeywordstag);
				$pagekeywordstag = $prodhelperobj->getProductNotForSaleComment($data, $pagekeywordstag);

				$document->setMetaData('keywords', $pagekeywordstag);
			}
			if (trim($data->metakey) != '' && AUTOGENERATED_SEO && SEO_PAGE_KEYWORDS != '')
			{
				if ($data->append_to_global_seo == 'append')
				{
					$pagekeywordstag = $pagekeywordstag . "," . trim($data->metakey);
					$document->setMetaData('keywords', $pagekeywordstag);
				}
				else if ($data->append_to_global_seo == 'prepend')
				{
					$pagetitletag = trim($data->metakey) . " " . $pagekeywordstag;
					$document->setMetaData('keywords', $pagekeywordstag);

				}
				else if ($data->append_to_global_seo == 'replace')
				{
					$document->setMetaData('keywords', $data->metakey);
				}
			}
			else
			{
				if (trim($data->metakey) != '')
				{
					$document->setMetaData('keywords', $data->metakey);
				}
				else
				{
					if (AUTOGENERATED_SEO && SEO_PAGE_KEYWORDS != '')
					{
						$document->setMetaData('keywords', $pagekeywordstag);
					}
					else if ($menu_meta_keywords != "")
					{
						$document->setMetaData('keywords', $menu_meta_keywords);
					}
					else
					{
						$document->setMetaData('keywords', $data->product_name . ", " . $data->category_name . ", " . SHOP_NAME . ", " . $data->product_number);
					}
				}
			}

			if (trim($data->metarobot_info) != '')
			{
				$document->setMetaData('robots', $data->metarobot_info);
			}
			else
			{
				if (AUTOGENERATED_SEO && SEO_PAGE_ROBOTS != '')
				{
					$pagerobotstag = SEO_PAGE_ROBOTS;
					$document->setMetaData('robots', $pagerobotstag);
				}
				else if ($menu_robots != "")
				{
					$document->setMetaData('robots', $menu_robots);
				}
				else
				{
					$document->setMetaData('robots', "INDEX,FOLLOW");
				}
			}

			// for meta description
			if (AUTOGENERATED_SEO && SEO_PAGE_DESCRIPTION != '')
			{

				if ($prodhelperobj_array_main['product_price_saving'] != "")
				{
					$product_price_saving_main = $prodhelperobj_array_main['product_price_saving'];
				}
				else
				{
					$product_price_saving_main = 0;
				}

				$pagedesctag = SEO_PAGE_DESCRIPTION;
				$pagedesctag = str_replace("{productname}", $data->product_name, $pagedesctag);
				$pagedesctag = str_replace("{categoryname}", $data->category_name, $pagedesctag);
				$pagedesctag = str_replace("{manufacturer}", $data->manufacturer_name, $pagedesctag);
				$pagedesctag = str_replace("{productsku}", $data->product_number, $pagedesctag);
				$pagedesctag = str_replace("{productnumber}", $data->product_number, $pagedesctag);
				$pagedesctag = str_replace("{shopname}", SHOP_NAME, $pagedesctag);
				$pagedesctag = str_replace("{productshortdesc}", strip_tags($data->product_s_desc), $pagedesctag);
				$pagedesctag = str_replace("{productdesc}", strip_tags($data->product_desc), $pagedesctag);
				$pagedesctag = str_replace("{saleprice}", $prodhelperobj_array_main['product_price'], $pagedesctag);
				$pagedesctag = str_replace("{saving}", $product_price_saving_main, $pagedesctag);
				$pagedesctag = $prodhelperobj->getProductNotForSaleComment($data, $pagedesctag);
			}

			if (trim($data->metadesc) != '' && AUTOGENERATED_SEO && SEO_PAGE_DESCRIPTION != '')
			{
				if ($data->append_to_global_seo == 'append')
				{
					$pagedesctag = $pagedesctag . " " . $data->metadesc;
					$document->setMetaData('description', $pagedesctag);
					$document->setMetaData("og:description", $pagedesctag);

				}
				else if ($data->append_to_global_seo == 'prepend')
				{
					$pagetitletag = trim($data->metadesc) . " " . $pagedesctag;
					$document->setMetaData('description', $pagedesctag);
					$document->setMetaData("og:description", $pagedesctag);

				}
				else if ($data->append_to_global_seo == 'replace')
				{
					$document->setMetaData('description', $data->metadesc);
					$document->setMetaData("og:description", $data->metadesc);
				}
			}
			else
			{
				if (trim($data->metadesc) != '')
				{
					$document->setMetaData('description', $data->metadesc);
					$document->setMetaData("og:description", $pagedesctag);

				}
				else if (AUTOGENERATED_SEO && SEO_PAGE_DESCRIPTION != '')
				{

					$document->setMetaData('description', $pagedesctag);
					$document->setMetaData("og:description", $pagedesctag);
				}
				else if ($menu_meta_description != "")
				{
					$document->setMetaData('description', $menu_meta_description);
					$document->setMetaData("og:description", $menu_meta_description);
				}
				else
				{
					$prodhelperobj_array = $prodhelperobj->getProductNetPrice($data->product_id);

					if ($prodhelperobj_array['product_price_saving'] != "")
					{
						$product_price_saving_main = $prodhelperobj_array['product_price_saving'];
					}
					else
					{
						$product_price_saving_main = 0;
					}
					$document->setMetaData('description', JText::_('COM_REDSHOP_META_BUY') . " " . $data->product_name . " " . JText::_('COM_REDSHOP_META_AT_ONLY') . " " . $prodhelperobj_array['product_price'] . " " . JText::_('COM_REDSHOP_META_SAVE') . " " . $product_price_saving_main);
					$document->setMetaData("og:description", JText::_('COM_REDSHOP_META_BUY') . " " . $data->product_name . " " . JText::_('COM_REDSHOP_META_AT_ONLY') . " " . $prodhelperobj_array['product_price'] . " " . JText::_('COM_REDSHOP_META_SAVE') . " " . $product_price_saving_main);
				}
			}

			/**
			 * @var $data
			 * Trigger event onAfterDisplayProduct
			 * Show content return by plugin directly into product page after display product title
			 */
			$data->event                    = new stdClass;
			$results                        = $dispatcher->trigger('onAfterDisplayProductTitle', array(& $productTemplate->template_desc, & $params, $data));
			$data->event->afterDisplayTitle = trim(implode("\n", $results));

			/**
			 * @var $data
			 *
			 * Trigger event onBeforeDisplayProduct will display content before product display
			 */
			$results                           = $dispatcher->trigger('onBeforeDisplayProduct', array(& $productTemplate->template_desc, & $params, $data));
			$data->event->beforeDisplayProduct = trim(implode("\n", $results));

			// for page heading
			if (AUTOGENERATED_SEO && SEO_PAGE_HEADING != '')
			{
				$pageheadingtag = SEO_PAGE_HEADING;
				$pageheadingtag = str_replace("{productname}", $data->product_name, $pageheadingtag);
				$pageheadingtag = str_replace("{categoryname}", $data->category_name, $pageheadingtag);
				$pageheadingtag = str_replace("{manufacturer}", $data->manufacturer_name, $pageheadingtag);
				$pageheadingtag = str_replace("{productsku}", $data->product_number, $pageheadingtag);
				$pageheadingtag = str_replace("{productnumber}", $data->product_number, $pageheadingtag);
				$pageheadingtag = str_replace("{productshortdesc}", strip_tags($data->product_s_desc), $pageheadingtag);
			}

			if (trim($data->pageheading) != '' && AUTOGENERATED_SEO && SEO_PAGE_HEADING != '')
			{
				$pageheadingtag = $pageheadingtag . " " . $data->pageheading;
			}
			else
			{
				if (trim($data->pageheading) != '')
				{
					$pageheadingtag = $data->pageheading;
				}
				else if (AUTOGENERATED_SEO && SEO_PAGE_HEADING != '')
				{
					$pageheadingtag = $pageheadingtag;
				}
			}

			$visited = array();
			$visited = $session->get('visited', $visited);

			if ($pid && !(in_array($pid, $visited)))
			{
				$visit     = $model->updateVisited($pid);
				$visited[] = $pid;
				$session->set('visited', $visited);
			}
			// End
		}

		// Breadcrumb
		if ($pid)
		{
			$prodhelperobj->generateBreadcrumb($pid);
		}
		// Breadcrumb end

		$this->assignRef('data', $data);
		$this->assignRef('template', $productTemplate);
		$this->assignRef('pageheadingtag', $pageheadingtag);
		$this->assignRef('params', $params);

		$for = JRequest::getWord("for", false);

		if ($for)
		{
			parent::display('related');

			return;
		}
		parent::display($tpl);
	}
}



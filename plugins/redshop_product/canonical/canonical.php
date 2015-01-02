<?php
/**
 * @package     RedSHOP
 * @subpackage  Plugin
 *
 * @copyright   Copyright (C) 2008 - 2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

// Load redSHOP Library
JLoader::import('redshop.library');

/**
 * Generate Canonical URL for products
 *
 * @since  1.3.3.1
 */
class PlgRedshop_ProductCanonical extends JPlugin
{
	/**
	 * Example prepare redSHOP Product method
	 *
	 * Method is called by the product view
	 *
	 * @param   string  &$template  The Product Template Data
	 * @param   object  &$params    The product params
	 * @param   object  $product    The product object
	 *
	 * @return  null
	 */
	public function onPrepareProduct(&$template, &$params, $product)
	{
		$app = JFactory::getApplication();
		$layout = $app->input->getCmd('layout', 'default');

		// Create canonical url only for default layout.
		if ('default' !== $layout)
		{
			return;
		}

		$url = '';

		// Check SELF Canonical URL first
		if ('' != trim($product->canonical_url))
		{
			$url = $product->canonical_url;
		}
		// Looking for Parent product to set as canonical.
		elseif ((int) $product->product_parent_id)
		{
			JLoader::load('RedshopHelperProduct');

			$productHelper = new producthelper;
			$parentProduct = $productHelper->getProductById($product->product_parent_id);

			$url = 'index.php?option=com_redshop&view=product&layout=detail'
					. '&Itemid=' . $app->input->getInt('Itemid', 0)
					. '&pid=' . $product->product_parent_id;

			if ('' != trim($parentProduct->canonical_url))
			{
				$url = $parentProduct->canonical_url;
			}
		}

		// Only add URL to canonical if available
		if (!empty($url))
		{
			if (JURI::isInternal($url))
			{
				$url = JURI::root() . $url;
			}

			JFactory::getDocument()->addCustomTag(
				'<link rel="canonical" href="' . $url . '" />'
			);
		}
	}
}

<?php
/**
 * @package     RedSHOP.Plugin
 * @subpackage  System.RedSHOP
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
use Joomla\CMS\Uri\Uri;

/**
 * PlgSystemRedSHOP class.
 *
 * @extends JPlugin
 *
 * @since   2.0.1
 */
class PlgSystemRedSHOP extends JPlugin
{
    /**
     * Auto load language
     *
     * @var  string
     */
    protected $autoloadLanguage = true;

    /**
     * onAfterDispatch function.
     *
     * @return void
     */
    public function onAfterDispatch()
    {
        if (!JFactory::getApplication()->isSite()) {
            return;
        }

        JLoader::import('redshop.library');

        RedshopHelperJs::init();
    }

    /**
     * onBeforeCompileHead function
     *
     * @return  void
     */
    public function onBeforeCompileHead()
    {
        if (class_exists('RedshopHelperConfig')) {
            RedshopHelperConfig::scriptDeclaration();
        }

        if (JFactory::getApplication()->input->get('option') != 'com_redshop') {
            return;
        }

        $doc = new RedshopHelperDocument;
        $doc->cleanHeader();
    }

    /**
     * onBeforeRender function.
     *
     * @return void
     */
    public function onAfterInitialise()
    {
        // Set product currency
        $session       = JFactory::getSession();
        $newCurrencyId = JFactory::getApplication()->input->getInt('product_currency', 0);

        if ($newCurrencyId) {
            $session->set('product_currency', $newCurrencyId);
        }
    }
    
    /**
     * After Display Product method
     *
     * Method is called by the product view
     *
     * @param   string  &$template  The Product Template Data
     * @param   object  $params     The product params
     * @param   object  $data       The product object Data
     *
     * @return  void
     */
    public function onAfterDisplayProduct(&$template, $params, $data)
    {
        $app    = \JFactory::getApplication();
        $url    = Uri::getInstance()->toString();
        $option = $app->input->get('option', '');
        $view   = $app->input->get('view', '');
        
        if (!$app->isClient('site'))
        {
            return;
        }
        
        $vatprice              = RedshopHelperProduct::getProductTax($data->product_id, $data->product_price);
        $currencySymbol        = Redshop::getConfig()->get('REDCURRENCY_SYMBOL');
        $isStock               = RedshopHelperStockroom::isStockExists($data->product_id);
        $discountPrice         = $data->discount_price + $vatprice;
        $normalPrice           = $data->product_price + $vatprice;
        
        $getProductDesc        = !empty($data->product_s_desc) ? $data->product_s_desc : $data->product_desc;
        
        $product_desc_clean    = str_replace(array( '\'', '"'), "", strip_tags($getProductDesc));
        $productNumber         = strip_tags($data->product_number);
        $getConfigUseStockRoom = Redshop::getConfig()->get('USE_STOCKROOM');
        $path                  = (REDSHOP_FRONT_IMAGES_ABSPATH . 'product/');
        
        $price                 = $data->product_on_sale ? $discountPrice : $normalPrice;
        $getStockroom          = $isStock ? 'http://schema.org/InStock' : 'https://schema.org/OutOfStock';
        
        if (isset($data->manufacturer_name))
        {
            $getBrand = '"brand": {
                     "@type": "Brand",
                     "name": "'.$data->manufacturer_name.'"
                     },';
        }
        
        if ($getConfigUseStockRoom && $option == 'com_redshop' && $view == 'product' )
        {
            $js = '
                    {
                    "@context": "schema.org",
                    "@type": "Product",
                    "name": "'.$data->product_name.'",
                    "sku": "'.$productNumber.'",
                    "mpn": "'.$productNumber.'",
                    "image": "'.$path . $data->product_full_image.'",
                    "description": "'.$product_desc_clean.'",
                    '.$getBrand.'
                    "offers": {
                        "@type": "Offer",
                        "priceCurrency": "'.$currencySymbol.'",
                        "price": "'.$price.'",
                        "availability": "'.$getStockroom.'",
                        "itemCondition": "http://schema.org/NewCondition",
                        "url": "'.$url.'",
                        "priceValidUntil": "01-01-2030"
                        }
                    }
                ';

            JFactory::getDocument()->addScriptDeclaration($js, 'application/ld+json');
        }
    }
}

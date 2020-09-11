<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JHTML::_('behavior.modal');

$app = JFactory::getApplication();

$Itemid                   = $app->input->getInt('Itemid');
$texpricemin              = $app->input->getFloat('texpricemin');
$texpricemax              = $app->input->getFloat('texpricemax');
$count                    = $app->input->getInt('count');
$image                    = $app->input->getString('image');
$thumbwidth               = $app->input->getInt('thumbwidth');
$thumbheight              = $app->input->getInt('thumbheight');
$show_price               = $app->input->getFloat('show_price');
$show_readmore            = $app->input->getBool('show_readmore');
$show_addtocart           = $app->input->getBool('show_addtocart');
$show_discountpricelayout = $app->input->getBool('show_discountpricelayout');

$k         = 0;
$configobj = Redconfiguration::getInstance();

?>
<table border="0" cellpadding="2" cellspacing="2">
    <?php
    for ($i = 0, $countPrdList = count($this->prdlist); $i < $countPrdList; $i++) {
        $row = $this->prdlist[$i];

        $link          = Redshop\IO\Route::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id);
        $pricetext     = '';
        $product_price = Redshop\Product\Price::getPrice($row->product_id);
        $tmpprcie      = $product_price;

        if ($product_price >= $texpricemin && $product_price <= $texpricemax && $count > 0) {
            $k++;
            $count--; ?>
            <tr>
                <td>
                    <?php $thum_image = Redshop\Product\Image\Image::getImage(
                        $row->product_id,
                        $link,
                        $thumbwidth,
                        $thumbheight
                    );
                    echo "<div class='mod_redshop_pricefilter'>";

                    if ($image) {
                        echo $thum_image . "<br>";
                    }

                    echo "<a href='" . $link . "'>" . $row->product_name . "</a><br>";

                    $productArr             = RedshopHelperProductPrice::getNetPrice($row->product_id);
                    $product_price_discount = $productArr['productPrice'] + $productArr['productVat'];

                    $taxexempt_addtocart = RedshopHelperCart::taxExemptAddToCart();

                    if (!$row->not_for_sale && $show_price && $taxexempt_addtocart) {
                        if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get(
                                    'DEFAULT_QUOTATION_MODE'
                                ) || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && SHOW_QUOTATION_PRICE))) {
                            if (!$product_price) {
                                $product_price_dis = RedshopHelperProductPrice::priceReplacement($product_price);
                            } else {
                                $product_price_dis = RedshopHelperProductPrice::formattedPrice($product_price);
                            }

                            $pricetext   = "";
                            $disply_text = "<div class='mod_redproducts_price'>" . $product_price_dis . "</div>";

                            if ($row->product_on_sale && $product_price_discount > 0) {
                                if ($product_price > $product_price_discount) {
                                    $disply_text = "";
                                    $s_price     = $product_price - $product_price_discount;
                                    $tmpprcie    = $product_price_discount;

                                    if ($show_discountpricelayout) {
                                        $pricetext = "<div id='mod_redoldprice' class='mod_redoldprice'>";
                                        $pricetext .= "<span style='text-decoration:line-through;'>" . RedshopHelperProductPrice::formattedPrice(
                                                $product_price
                                            ) . "</span></div>";
                                        $pricetext .= "<div id='mod_redmainprice' class='mod_redmainprice'>" . RedshopHelperProductPrice::formattedPrice(
                                                $product_price_discount
                                            ) . "</div>";
                                        $pricetext .= "<div id='mod_redsavedprice' class='mod_redsavedprice'>" . JText::_(
                                                'COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED'
                                            ) . ' ' . RedshopHelperProductPrice::formattedPrice($s_price) . "</div>";
                                    } else {
                                        $pricetext = "<div class='mod_redproducts_price'>" . RedshopHelperProductPrice::formattedPrice(
                                                $product_price
                                            ) . "</div>";
                                    }
                                }
                            }

                            echo $pricetext . $disply_text;
                        } else {
                            $product_price_dis = RedshopHelperProductPrice::priceReplacement($product_price);
                            echo "<div class='mod_redproducts_price'>" . $product_price_dis . "</div>";
                        }
                    }

                    if ($show_readmore) {
                        echo "<br><a href='" . $link . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>&nbsp;";
                    }

                    if ($show_addtocart) {
                        $addtocartform = Redshop\Cart\Render::replace($row->product_id);
                        echo "<div>" . $addtocartform . "<div>";
                    }

                    echo "</div>"; ?>
                </td>
            </tr>
            <?php
        } elseif (!$count) {
            break;
        }
    }

    if (!$k) {
        echo "<tr><td>" . JText::_('COM_REDSHOP_NO_PRODUCT_FOUND') . "</td></tr>";
    } ?>
</table>

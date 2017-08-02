<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;
JHTML::_('behavior.tooltip');
JHTML::_('behavior.modal');

$Itemid = JRequest::getInt('Itemid');

$texpricemin              = JRequest::getFloat('texpricemin');
$texpricemax              = JRequest::getFloat('texpricemax');
$count                    = JRequest::getInt('count');
$image                    = JRequest::getString('image');
$thumbwidth               = JRequest::getInt('thumbwidth');
$thumbheight              = JRequest::getInt('thumbheight');
$show_price               = JRequest::getFloat('show_price');
$show_readmore            = JRequest::getBool('show_readmore');
$show_addtocart           = JRequest::getBool('show_addtocart');
$show_discountpricelayout = JRequest::getBool('show_discountpricelayout');

$k = 0;
$configobj = Redconfiguration::getInstance();

// Get product helper
$producthelper = productHelper::getInstance();?>
<table border="0" cellpadding="2" cellspacing="2">
	<?php
	for ($i = 0, $countPrdList = count($this->prdlist); $i < $countPrdList; $i++)
	{
		$row = $this->prdlist[$i];

		$link          = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $row->product_id);
		$pricetext     = '';
		$product_price = $producthelper->getProductPrice($row->product_id);
		$tmpprcie      = $product_price;

		if ($product_price >= $texpricemin && $product_price <= $texpricemax && $count > 0)
		{
			$k++;
			$count--;?>
			<tr>
				<td>
					<?php    $thum_image = $producthelper->getProductImage($row->product_id, $link, $thumbwidth, $thumbheight);
					echo "<div class='mod_redshop_pricefilter'>";

					if ($image)
					{
						echo $thum_image . "<br>";
					}

					echo "<a href='" . $link . "'>" . $row->product_name . "</a><br>";

					$productArr = $producthelper->getProductNetPrice($row->product_id);
					$product_price_discount = $productArr['productPrice'] + $productArr['productVat'];

					$taxexempt_addtocart = $producthelper->taxexempt_addtocart();

					if (!$row->not_for_sale && $show_price && $taxexempt_addtocart)
					{
						if (Redshop::getConfig()->get('SHOW_PRICE') && (!Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') || (Redshop::getConfig()->get('DEFAULT_QUOTATION_MODE') && SHOW_QUOTATION_PRICE)))
						{
							if (!$product_price)
							{
								$product_price_dis = $producthelper->getPriceReplacement($product_price);
							}
							else
							{
								$product_price_dis = $producthelper->getProductFormattedPrice($product_price);
							}

							$pricetext   = "";
							$disply_text = "<div class='mod_redproducts_price'>" . $product_price_dis . "</div>";

							if ($row->product_on_sale && $product_price_discount > 0)
							{
								if ($product_price > $product_price_discount)
								{
									$disply_text = "";
									$s_price     = $product_price - $product_price_discount;
									$tmpprcie    = $product_price_discount;

									if ($show_discountpricelayout)
									{
										$pricetext = "<div id='mod_redoldprice' class='mod_redoldprice'>";
										$pricetext .= "<span style='text-decoration:line-through;'>" . $producthelper->getProductFormattedPrice($product_price) . "</span></div>";
										$pricetext .= "<div id='mod_redmainprice' class='mod_redmainprice'>" . $producthelper->getProductFormattedPrice($product_price_discount) . "</div>";
										$pricetext .= "<div id='mod_redsavedprice' class='mod_redsavedprice'>" . JText::_('COM_REDSHOP_PRODCUT_PRICE_YOU_SAVED') . ' ' . $producthelper->getProductFormattedPrice($s_price) . "</div>";
									}
									else
									{
										$pricetext = "<div class='mod_redproducts_price'>" . $producthelper->getProductFormattedPrice($product_price) . "</div>";
									}
								}
							}

							echo $pricetext . $disply_text;
						}
						else
						{
							$product_price_dis = $producthelper->getPriceReplacement($product_price);
							echo "<div class='mod_redproducts_price'>" . $product_price_dis . "</div>";
						}
					}

					if ($show_readmore)
					{
						echo "<br><a href='" . $link . "'>" . JText::_('COM_REDSHOP_READ_MORE') . "</a>&nbsp;";
					}

					if ($show_addtocart)
					{
						$addtocartform = $producthelper->replaceCartTemplate($row->product_id);
						echo "<div>" . $addtocartform . "<div>";
					}

					echo "</div>";    ?>
				</td>
			</tr>
		<?php
		}
		elseif (!$count)
		{
			break;
		}
	}

	if (!$k)
	{
		echo "<tr><td>" . JText::_('COM_REDSHOP_NO_PRODUCT_FOUND') . "</td></tr>";
	}    ?>
</table>

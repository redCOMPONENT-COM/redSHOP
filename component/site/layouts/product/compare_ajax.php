<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

/**
 * $displayData extract
 *
 * @param   object $form       A JForm object
 * @param   int    $product_id Id current product
 * @param   int    $modal      Flag use form in modal
 */
extract($displayData);

$redHelper     = redhelper::getInstance();
$productHelper = productHelper::getInstance();
$compare       = $displayData['object'];
$cmd           = JFactory::getApplication()->input->get('cmd');
$total         = $compare->getItemsTotal();
?>
<?php if (count($compare->getItems()) > 0) : ?>
    <ul id='compare_ul'>
		<?php foreach ($compare->getItems() as $data) : ?>
			<?php
			$productId  = $data['item']->productId;
			$categoryId = $data['item']->categoryId;
			$product    = RedshopHelperProduct::getProductById($productId);

			$ItemData  = $productHelper->getMenuInformation(0, 0, '', 'product&pid=' . $product->product_id);
			$catidmain = $product->cat_in_sefurl;

			if (count($ItemData) > 0)
			{
				$pItemid = $ItemData->id;
			}
			else
			{
				$pItemid = RedshopHelperUtility::getItemId($product->product_id, $catidmain);
			}

			$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $productId . '&cid=' . $categoryId . '&Itemid=' . $pItemid);
			?>
            <li>
                <span>
                    <a href="<?php echo $link ?>"><?php echo $product->product_name ?></a>
                </span>
                <span>
				<a id="removeCompare<?php echo $productId . '.' . $categoryId; ?>" href='javascript:;'
                   value="<?php echo $productId . '.' . $categoryId; ?>">
					<?php echo JText::_('COM_REDSHOP_DELETE'); ?>
				</a>
			</span>
            </li>
		<?php endforeach; ?>
    </ul>
    <div id="totalCompareProduct" style="display:none;"><?php echo $total ?></div>
<?php endif ?>

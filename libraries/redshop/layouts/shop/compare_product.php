<?php
/**
 * @package     Redshop.Site
 * @subpackage  Layout
 *
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_BASE') or die;

/**
 * Layout variables
 * -----------------
 * @var   array   $displayData     Layout data
 * @var   array   $compareProducts Compare products
 * @var   boolean $excludeData     Exclude table compare?
 * @var   integer $itemId          Menu item ID
 */

extract($displayData);

$idx = isset($compareProducts) ? (int) $compareProducts['idx'] : 0;
?>
    <ul id="compare_ul">
		<?php for ($i = 0; $i < $idx; $i++): ?>
			<?php $product = $this->getProductById($compareProducts[$i]["product_id"]); ?>
            <li>
				<?php echo $product->product_name ?>
                <a onClick='javascript:add_to_compare(<?php echo $compareProducts[$i]['product_id'] ?>, <?php echo $compareProducts[$i]['category_id'] ?>, "remove")'
                   href='javascript:void(0)'>
                    <?php echo JText::_('COM_REDSHOP_DELETE') ?>
                </a>
            </li>
		<?php endfor; ?>
    </ul>
    <div id='totalCompareProduct' style='display:none;'><?php echo $idx ?></div>
<?php if ($excludeData === false): ?>
    <table border="0" cellpadding="5" cellspacing="0" width="100%">
		<?php for ($i = 0; $i < $idx; $i++): ?>
			<?php
			$product = $this->getProductById($compareProducts[$i]["product_id"]);
			$link    = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $compareProducts[$i]["product_id"] . '&Itemid=' . $itemId, false);
			?>
            <tr valign="top">
                <td width="95%">
                    <span><a href="<?php echo $link ?>"><?php echo $product->product_name ?></a></span>
                </td>
                <td width="5%">
                    <span>
                        <a href="javascript:void(0);" onClick="javascript:remove_compare(<?php echo $compareProducts[$i]["product_id"] ?>,<?php echo $compareProducts[$i]["category_id"] ?>)">
                            <?php echo JText::_('COM_REDSHOP_DELETE') ?>
                        </a>
                    </span>
                </td>
            </tr>
		<?php endfor; ?>
    </table>
<?php endif;

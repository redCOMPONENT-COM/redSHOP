<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
JHTML::_('behavior.tooltip');
$model = $this->getModel('template_detail');

$categoryTags        = $model->availabletexts('category');
$categoryTagsCount   = count($categoryTags);

$newsletterTags      = $model->availabletexts('newsletter');
$newsletterTagsCount = count($newsletterTags);

$productTags         = $model->availabletexts('product');
$productTagsCount    = count($productTags);

$totalCount = $categoryTagsCount + $newsletterTagsCount + $productTagsCount;
?>

<?php if ($totalCount > 0) : ?>
<?php
	echo JHtml::_('tabs.start', 'template-library-items');
	echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_CATEGORY_TEXTLIBRARY_ITEMS'), 'category-items');
?>
	<table class="table table-hover table-striped">
		<?php for ($i = 0; $i < $categoryTagsCount; $i++) : ?>
		<tr>
			<td>{<?php echo $categoryTags[$i]->text_name; ?>} -- <?php echo $categoryTags[$i]->text_desc; ?></td>
		</tr>
		<?php endfor; ?>

		<?php echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_NEWSLETTER_TEXTLIBRARY_ITEMS'), 'newsletter-items'); ?>

		<?php for ($i = 0; $i < $newsletterTagsCount; $i++) : ?>
		<tr>
			<td>{<?php echo $newsletterTags[$i]->text_name; ?>} -- <?php echo $newsletterTags[$i]->text_desc; ?></td>
		</tr>
		<?php endfor; ?>

		<?php echo JHtml::_('tabs.panel', JText::_('COM_REDSHOP_PRODUCT_TEXTLIBRARY_ITEMS'), 'product-items'); ?>
		<?php for ($i = 0; $i < $productTagsCount; $i++) : ?>
		<tr>
			<td>{<?php echo $productTags[$i]->text_name; ?>} -- <?php echo $productTags[$i]->text_desc; ?></td>
		</tr>
		<?php endfor; ?>
	</table>
	<?php echo JHtml::_('tabs.end'); ?>
<?php else: ?>
	<?php echo JText::_('COM_REDSHOP_NO_TEXTLIBRARY_ITEMS'); ?>
<?php endif; ?>

<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>

<?php
echo JHtml::_('bootstrap.startTabSet', 'product-pane', array('active' => 'product'));
echo JHtml::_('bootstrap.addTab', 'product-pane', 'product', JText::_('COM_REDSHOP_PRODUCT', true));
?>
    <div class="row adminform">
        <div class="col-sm-6">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_PRODUCT_UNIT'),
					'content' => $this->loadTemplate('product_unit')
				)
			);
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_DOWNLOAD'),
					'content' => $this->loadTemplate('download')
				)
			);
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_WRAPPING_MANAGEMENT'),
					'content' => $this->loadTemplate('wrapping')
				)
			);
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_CATALOG_MANAGEMENT'),
					'content' => $this->loadTemplate('catalog')
				)
			);
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_COLOR_SAMPLE_MANAGEMENT'),
					'content' => $this->loadTemplate('color_sample')
				)
			);
			?>
        </div>

        <div class="col-sm-6">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_PRODUCT_TEMPLATE'),
					'content' => $this->loadTemplate('product_template_image_settings')
				)
			);
			?>
        </div>
    </div>
<?php echo JHtml::_('bootstrap.endTab'); ?>

<?php
echo JHtml::_('bootstrap.addTab', 'product-pane', 'accessory', JText::_('COM_REDSHOP_ACCESSORY_PRODUCT_TAB', true));
echo $this->loadTemplate('accessory_product');
echo JHtml::_('bootstrap.endTab');
echo JHtml::_('bootstrap.addTab', 'product-pane', 'related', JText::_('COM_REDSHOP_RELATED_PRODUCTS', true));
echo $this->loadTemplate('related_product');
echo JHtml::_('bootstrap.endTab');
echo JHtml::_('bootstrap.endTabSet');
?>
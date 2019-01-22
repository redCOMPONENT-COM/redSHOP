<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$productsFilter = array();
$productList    = RedshopEntityCategory::getInstance($this->item->id)->getProducts(true);

foreach ($productList as $product)
{
	$productsFilter[] = $product->product_id;
}

$registry     = new JRegistry;
$filterParams = $registry->loadString($this->item->product_filter_params);

?>
<style type="text/css">

</style>
<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_PRODUCT_FILTERS'); ?></h3>
            </div>
            <div class="box-body">
				<?php foreach ($this->form->getFieldset('filters') as $field) : ?>
                    <div class="control-group">
						<?php
						$options = array();

						if ($field->fieldname == 'product_attributes')
						{
							$options['product_ids'] = $productsFilter;
						}

						$value = $filterParams->get($field->fieldname);

						if ($field->type === 'Radio' && empty($value))
						{
							$value = 0;
						}

						$field->setValue($value, true);
						?>
						<?php echo $field->renderField($options); ?>
                    </div>
				<?php endforeach; ?>
            </div>
        </div>
    </div>
</div>


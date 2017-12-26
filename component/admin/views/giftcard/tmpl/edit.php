<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

JHtml::_('behavior.formvalidator');
JHtml::_('behavior.modal', 'a.joom-box');

$producthelper = productHelper::getInstance();

if ($this->item->giftcard_id)
{
	$this->form->setValue('giftcard_price', null, $producthelper->redpriceDecimal($this->item->giftcard_price));
	$this->form->setValue('giftcard_value', null, $producthelper->redpriceDecimal($this->item->giftcard_value));
}

JFactory::getDocument()->addScriptDeclaration('
	Joomla.submitbutton = function(task)
	{
		if (task == "giftcard.cancel" || document.formvalidator.isValid(document.getElementById("adminForm")))
		{
			Joomla.submitform(task);
		}
	};
');
?>
<form
        action="index.php?option=com_redshop&view=giftcard&task=giftcard.edit&giftcard_id=<?php echo $this->item->giftcard_id; ?>"
        method="post"
        id="adminForm"
        name="adminForm"
        class="adminform form-validate"
        enctype="multipart/form-data">

    <div class="row">
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_DETAIL') ?></h3>
                </div>
                <div class="box-body form-horizontal">
					<?php echo $this->form->renderField('giftcard_name') ?>
					<?php echo $this->form->renderField('customer_amount') ?>
					<?php echo $this->form->renderField('giftcard_price') ?>
					<?php echo $this->form->renderField('giftcard_value') ?>
					<?php echo $this->form->renderField('giftcard_validity') ?>
					<?php echo $this->form->renderField('free_shipping') ?>
					<?php echo $this->form->renderField('published') ?>
					<?php echo $this->form->renderField('giftcard_desc') ?>
                </div>
            </div>
			<?php if (Redshop::getConfig()->get('ECONOMIC_INTEGRATION')) : ?>
                <div class="box box-primary">
                    <div class="box-header with-border">
                        <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_ECONOMIC'); ?></h3>
                    </div>
                    <div class="panel-body">
						<?php echo $this->form->renderField('accountgroup_id') ?>
                    </div>
                </div>
			<?php endif; ?>
        </div>
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_GIFTCARD_BGIMAGE') ?></h3>
                </div>
                <div class="box-body form-vertical">
                    <div class="form-group">
						<?php echo RedshopHelperMediaImage::render(
							'giftcard_bgimage',
							'giftcard',
							$this->item->giftcard_id,
							'giftcard',
							$this->item->giftcard_bgimage,
							false
						) ?>
                    </div>
                </div>
            </div>
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_GIFTCARD_IMAGE') ?></h3>
                </div>
                <div class="box-body form-vertical">
                    <div class="form-group">
						<?php echo RedshopHelperMediaImage::render(
							'giftcard_image',
							'giftcard',
							$this->item->giftcard_id,
							'giftcard',
							$this->item->giftcard_image,
							false
						) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="task" value=""/>
	<?php echo $this->form->getInput('giftcard_id') ?>
	<?php echo JHtml::_('form.token'); ?>
</form>

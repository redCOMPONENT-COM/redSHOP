<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$db          = JFactory::getDBO();
$templateId  = $this->detail->product_template;
$product_id  = $this->detail->product_id;
$redTemplate = Redtemplate::getInstance();
$template    = RedshopHelperTemplate::getTemplate("product", $templateId);

if (count($template) == 0)
{
	return;
}

$template = $template[0]->template_desc;

$fieldModel = RedshopModel::getInstance('fields', 'RedshopModel');

$section = explode(',', '1,12,17');
$fields  = $fieldModel->getFieldInfoBySection($section);

$html = '';

foreach ($fields as $field)
{
	if (strstr($template, "{" . $field->name . "}"))
	{
		$sectionId = 0;
		$fieldName = '';

		if (12 != $field->section || (12 == $field->section && 15 == $field->type))
		{
			$sectionId = $field->section;
			$fieldName = $field->name;
		}

		$html .= RedshopHelperExtrafields::listAllField($sectionId, $product_id, $fieldName);
	}
}

$this->dispatcher->trigger('onRenderExtraFields', array($product_id, &$html));
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_FIELDS'); ?></h3>
            </div>
            <div class="box-body">
				<?php if (empty($html)): ?>
					<?php echo RedshopLayoutHelper::render(
						'system.message',
						array(
							'msgList'     => array(
								'info' => array(JText::_('COM_REDSHOP_PRODUCT_NO_EXTRA_FIELD_HINT'))
							),
							'showHeading' => false,
							'allowClose'  => false
						)
					);
					?>
				<?php else: ?>
					<?php echo $html; ?>
				<?php endif; ?>
            </div>
        </div>
    </div>
</div>

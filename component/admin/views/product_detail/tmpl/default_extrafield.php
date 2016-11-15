<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2016 redCOMPONENT.com. All rights reserved.
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
	if (strstr($template, "{" . $field->field_name . "}"))
	{
		$sectionId = 0;
		$fieldName = '';

		if (12 != $field->field_section || (12 == $field->field_section && 15 == $field->field_type))
		{
			$sectionId = $field->field_section;
			$fieldName = $field->field_name;
		}

		$html .= RedshopHelperExtrafields::listAllField($sectionId, $product_id, $fieldName);
	}
}

if (empty($html))
{
	echo RedshopLayoutHelper::render(
			'system.message',
			array(
				'msgList' => array(
								'info' => array(JText::_('COM_REDSHOP_PRODUCT_NO_EXTRA_FIELD_HINT'))
							),
				'showHeading' => false,
				'allowClose' => false
			)
		);
}

?>

<div class="row">
	<div class="col-sm-12">
		<div class="box box-primary">
			<div class="box-header with-border">
				<h3 class="box-title"><?php echo JText::_('COM_REDSHOP_FIELDS'); ?></h3>
			</div>
			<div class="box-body">
				<?php echo $html; ?>
			</div>
		</div>
	</div>
</div>

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
$sections = array(RedshopHelperExtrafields::SECTION_PRODUCT, RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, RedshopHelperExtrafields::SECTION_PRODUCT_FINDER_DATE_PICKER);

$html = '';

foreach ($sections as $section)
{
	$html .= RedshopHelperExtrafields::listAllField($section, 0, '');
}

$this->dispatcher->trigger('onRenderExtraFields', array($product_id, &$html));
?>
<?php if (empty($html)): ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_FIELDS'); ?></h3>
                </div>
                <div class="box-body">
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
                </div>
            </div>
        </div>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-sm-12">
			<?php echo $html; ?>
        </div>
    </div>
<?php endif; ?>
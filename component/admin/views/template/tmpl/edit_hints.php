<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

// Text library
$textLibraries = array(
	'category'   => RedshopHelperText::getTextLibraryData('category'),
	'newsletter' => RedshopHelperText::getTextLibraryData('newsletter'),
	'product'    => RedshopHelperText::getTextLibraryData('product')
);

$model = RedshopModel::getInstance('Template_Detail', 'RedshopModel');
?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo JText::_('COM_REDSHOP_MAIL_CENTER_HELPFUL_HINT'); ?></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-12">
                    <ul class="nav nav-tabs">
                        <li role="presentation" class="active">
                            <a href="#tags" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_AVAILABLE_TEMPLATE_TAGS') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#default_template" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_DEFAULT_TEMPLATE_DETAIL') ?>
                            </a>
                        </li>
						<?php foreach ($textLibraries as $section => $texts): ?>
							<?php if (!empty($texts)): ?>
                                <li role="presentation">
                                    <a href="#text_library_<?php echo $section ?>" role="tab" data-toggle="tab">
										<?php echo JText::_('COM_REDSHOP_' . strtoupper($section) . '_TEXTLIBRARY_ITEMS') ?>
                                    </a>
                                </li>
							<?php endif; ?>
						<?php endforeach; ?>
                    </ul>
                </div>
                <div class="col-md-12">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="tags">
                            <?php
                            switch ($this->item->template_section)
                            {
	                            case 'category':
		                            ?>
                                    <table class="adminlist table table-striped">
			                            <?php
			                            $tags_front = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_CATEGORY);
			                            $tags_admin = RedshopHelperExtrafields::getSectionFieldList(RedshopHelperExtrafields::SECTION_CATEGORY, 0);
			                            $tags       = array_merge((array) $tags_admin, (array) $tags_front);
			                            ?>
			                            <?php if (!empty($tags)): ?>
                                            <tr>
                                                <td>
                                                    <h3><?php echo JText::_("COM_REDSHOP_FIELDS") ?></h3>
						                            <?php foreach ($tags as $tag): ?>
                                                        <div style="margin-left:10px;">{<?php echo $tag->name ?>}
                                                            -- <?php echo $tag->title ?></div>
						                            <?php endforeach; ?>
                                                </td>
                                            </tr>
			                            <?php endif; ?>
			                            <?php
			                            $tags_front = RedshopHelperExtrafields::getSectionFieldList(1, 1);
			                            $tags_admin = RedshopHelperExtrafields::getSectionFieldList(1, 0);
			                            $tags       = array_merge((array) $tags_admin, (array) $tags_front);
			                            ?>
			                            <?php if (!empty($tags)): ?>
                                            <tr>
                                                <td>
                                                    <h3><?php echo JText::_("COM_REDSHOP_TEMPLATE_PRODUCT_FIELDS_TITLE") ?></h3>
						                            <?php foreach ($tags as $tag): ?>
                                                        <div style="margin-left:10px;">
                                                            {producttag:<?php echo $tag->name ?>}
                                                            -- <?php echo $tag->title ?></div>
						                            <?php endforeach; ?>
                                                </td>
                                            </tr>
			                            <?php endif; ?>
                                        <tr>
                                            <td><?php echo Redtemplate::getTemplateValues('category') ?></td>
                                        </tr>
                                        <tr>
                                            <td>
					                            <?php $availableAddtocart = $model->availableaddtocart('add_to_cart'); ?>
					                            <?php if (count($availableAddtocart) == 0): ?>
                                                    <strong><?php echo JText::_("COM_REDSHOP_NO_ADD_TO_CART_AVAILABLE"); ?></strong>
					                            <?php else: ?>
						                            <?php foreach ($availableAddtocart as $tag): ?>
                                                        <div style="margin-left:10px;">
                                                            {form_addtocart:<?php echo $tag->template_name ?>}
                                                            -- <?php echo JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT') ?></div>
						                            <?php endforeach; ?>
					                            <?php endif; ?>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
					                            <?php
					                            $related_product = RedshopHelperTemplate::getTemplate('related_product');
					                            if (count($related_product) == 0) echo JText::_("COM_REDSHOP_NO_RELATED_PRODUCT_LIGHTBOX_TEMPLATE_AVAILABLE");
					                            else echo JText::_("COM_REDSHOP_RELATED_PRODUCT_LIGHTBOX_TEMPLATE_AVAILABLE_HINT") . "<br />";
					                            for ($i = 0, $in = count($related_product); $i < $in; $i++)
					                            {
						                            echo '<br /><div style="margin-left:10px;">{related_product_lightbox:' . $related_product[$i]->template_name . '[:lightboxwidth][:lightboxheight]}</div><br />';

						                            if ($i == count($related_product) - 1)
						                            {
							                            echo JText::_("COM_REDSHOP_EXAMPLE_TEMPLATE");
							                            echo '<br /><div style="margin-left:10px;">{related_product_lightbox:' . $related_product[0]->template_name . ':600:300}</div>';
						                            }
					                            }
					                            ?>
                                            </td>
                                        </tr>
                                    </table>
		                            <?php
		                            break;
                                default:
                                    ?>
                                    <table class="table table-striped table-hover">
                                        <tr>
                                            <td><?php echo RedshopHelperTemplate::getTemplateValues($this->item->template_section) ?></td>
                                        </tr>
                                    </table>
                            <?php
                                    break;
                            }
                            ?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="default_template">
                            <?php $templateContent = RedshopHelperTemplate::getInstallSectionTemplate($this->item->template_section, true); ?>
                            <?php if (!empty($templateContent)): ?>
                                <?php echo $templateContent ?>
                            <?php endif; ?>
                        </div>
						<?php foreach ($textLibraries as $section => $texts): ?>
							<?php if (!empty($texts)): ?>
                                <div role="tabpanel" class="tab-pane" id="text_library_<?php echo $section ?>">
                                    <table class="table table-hover table-striped">
                                        <tbody>
										<?php foreach ($texts as $text): ?>
                                            <tr>
                                                <td width="30%"><strong class="text-info">{<?php echo $text->text_name ?>}</strong></td>
                                                <td><?php echo $text->text_desc ?></td>
                                            </tr>
										<?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
							<?php endif; ?>
						<?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

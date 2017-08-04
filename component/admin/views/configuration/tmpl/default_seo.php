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
<fieldset class="adminform">
    <div class="row">
        <div class="col-md-6">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_SEO_GENERAL_TAB'),
					'content' => $this->loadTemplate('seo_general')
				)
			);
			?>
        </div>
        <div class="col-md-6">
            <div class="box box-primary form-vertical">
                <div class="box-header with-border">
                    <h3 class="text-primary center"><?php echo JText::_('COM_REDSHOP_AVAILABLE_SEO_TAGS') ?></h3>
                </div>
                <div class="box-body">
					<?php
					echo JHtml::_('bootstrap.startTabSet', 'seo-pane', array('active' => 'tags'));
					echo JHtml::_('bootstrap.addTab', 'seo-pane', 'tags', JText::_('COM_REDSHOP_TITLE_AVAILABLE_SEO_TAGS', true));
					?>
                    <table class="table table-striped">
                        <tr>
                            <td width="10">
                                <span class="redshop_tags">{productname}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCT_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{manufacturer}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_MANUFACTURER_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{parentcategoryloop}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PARENT_CATEGORY_LOOP_SEO_DEC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{categoryname}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_CATEGORY_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{saleprice}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_SALEPRICE_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{saving}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_SAVING_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{shopname}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_SHOPNAME_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{productsku}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCTSKU_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{categoryshortdesc}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_CATEGORY_SHORT_DESCRIPTION') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{productshortdesc}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCT_SHORT_DESCRIPTION') ?>
                            </td>
                        </tr>
                    </table>
					<?php echo JHtml::_('bootstrap.endTab'); ?>
					<?php echo JHtml::_('bootstrap.addTab', 'seo-pane', 'headingtags', JText::_('COM_REDSHOP_HEADING_AVAILABLE_SEO_TAGS', true)); ?>
                    <table class="table table-striped">
                        <tr>
                            <td width="10">
                                <span class="redshop_tags">{productname}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCT_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{manufacturer}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_MANUFACTURER_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{categoryname}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_CATEGORY_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{productsku}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCTSKU_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{categoryshortdesc}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_CATEGORY_SHORT_DESCRIPTION') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{productshortdesc}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCT_SHORT_DESCRIPTION') ?>
                            </td>
                        </tr>
                    </table>
					<?php echo JHtml::_('bootstrap.endTab'); ?>
					<?php echo JHtml::_('bootstrap.addTab', 'seo-pane', 'desctags', JText::_('COM_REDSHOP_DESC_AVAILABLE_SEO_TAGS', true)); ?>
                    <table class="table table-striped">
                        <tr>
                            <td width="10">
                                <span class="redshop_tags">{productname}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCT_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{manufacturer}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_MANUFACTURER_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{categoryname}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_CATEGORY_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{saleprice}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_SALEPRICE_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{saving}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_SAVING_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{shopname}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_SHOPNAME_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{productsku}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCTSKU_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{categoryshortdesc}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_CATEGORY_SHORT_DESCRIPTION') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{productshortdesc}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCT_SHORT_DESCRIPTION') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{categorydesc}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_CATEGORY_DESCRIPTION') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{productdesc}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCT_DESCRIPTION') ?>
                            </td>
                        </tr>
                    </table>
					<?php echo JHtml::_('bootstrap.endTab'); ?>
					<?php echo JHtml::_('bootstrap.addTab', 'seo-pane', 'keywordtags', JText::_('COM_REDSHOP_KEYWORD_AVAILABLE_SEO_TAGS', true)); ?>
                    <table class="table table-striped">
                        <tr>
                            <td width="10">
                                <span class="redshop_tags">{productname}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCT_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{manufacturer}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_MANUFACTURER_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{categoryname}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_CATEGORY_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{saleprice}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_SALEPRICE_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{saving}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_SAVING_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{shopname}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_SHOPNAME_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{productsku}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCTSKU_SEO_DESC') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{categoryshortdesc}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_CATEGORY_SHORT_DESCRIPTION') ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <span class="redshop_tags">{productshortdesc}</span>
                            </td>
                            <td>
								<?php echo JText::_('COM_REDSHOP_PRODUCT_SHORT_DESCRIPTION') ?>
                            </td>
                        </tr>
                    </table>
					<?php echo JHtml::_('bootstrap.endTab'); ?>
					<?php echo JHtml::_('bootstrap.endTabSet'); ?>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-4">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_SEO_CATEGORY_TAB'),
					'content' => $this->loadTemplate('seo_category')
				)
			);
			?>
        </div>
        <div class="col-md-4">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_SEO_PRODUCT_TAB'),
					'content' => $this->loadTemplate('seo_product')
				)
			);
			?>
        </div>
        <div class="col-md-4">
			<?php
			echo RedshopLayoutHelper::render(
				'config.group',
				array(
					'title'   => JText::_('COM_REDSHOP_SEO_MANUFACTURER_TAB'),
					'content' => $this->loadTemplate('seo_manufacturer')
				)
			);
			?>
        </div>
    </div>
</fieldset>

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
?>
<div class="panel panel-default">
    <div class="panel-heading"><h3><?php echo JText::_('COM_REDSHOP_MAIL_CENTER_HELPFUL_HINT') ?></h3></div>
    <div class="panel-body">
        <div class="row">
            <div class="col-md-12">
                <ul class="nav nav-tabs">
                    <li role="presentation" class="active">
                        <a href="#twig_template" role="tab" data-toggle="tab">
							<?php echo JText::_('COM_REDSHOP_TWIG_TEMPLATE_HELP') ?>
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
                    <div class="alert alert-info">
                        <p><?php echo JText::_('COM_REDSHOP_TEMPLATE_TWIG_SUGGEST') ?></p>
                    </div>
                    <div role="tabpanel" class="tab-pane active" id="twig_template">
						<?php echo RedshopHelperTwig::renderTwigHelpBlock('collection', 'giftcards'); ?>
						<?php echo RedshopHelperTwig::renderTwigHelpBlock('giftcard'); ?>
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

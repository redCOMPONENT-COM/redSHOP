<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$model = $this->getModel('product_detail');
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box box-primary">
            <div class="box-header with-border">
                <h3 class="box-title"><?php echo JText::_('COM_REDSHOP_TEMPLATE_PAYMENT_METHOD'); ?></h3>
            </div>
            <div class="box-body">
                <table border="0">
                    <tr>
                        <td>
                            <fieldset class="adminform">
                                <table class="admintable" border="0">
                                    <tr>
                                        <td class="key">
                                            <label for="use_individual_payment_method0">
												<?php echo JText::_('COM_REDSHOP_USE_INDIVIDUAL_PAYMENT_METHOD'); ?>
                                            </label>
                                        </td>
                                        <td>
											<?php echo $this->lists['use_individual_payment_method']; ?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="key">
                                            <label for="discount_calc_method">
												<?php echo JText::_('COM_REDSHOP_SELECT_PAYMENT_METHOD'); ?>
                                            </label>
                                        </td>
                                        <td>
											<?php echo $this->lists['payment_methods']; ?>
                                        </td>
                                    </tr>
                                </table>
                            </fieldset>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>


<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;

$extraField = extra_field::getInstance();
?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading"><?php echo JText::_('COM_REDSHOP_MAIL_CENTER_HELPFUL_HINT'); ?></div>
        <div class="panel-body">
            <div class="row">
                <div class="col-md-4">
                    <ul class="nav nav-stacked nav-pills" style="border-right: 1px solid #ddd">
                        <li role="presentation" class="active">
                            <a href="#registrationmail" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_REGISTRATION_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#order" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_ORDER_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#invoicemail" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_INVOICE_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#orderstatusmail" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_ORDER_STATUS_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#catalogmail" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_CATALOG_SEND_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#catalogfirstreminder" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_CATALOG_FIRST_REMINDER') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#catalogsecreminder" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_CATALOG_SECOND_REMINDER') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#catalogcouponreminder" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_CATALOG_COUPON_REMINDER') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#catalogsamplefirstreminder" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_CATALOG_SAMPLE_FIRST_REMINDER') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#catalogsamplesecreminder" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_CATALOG_SAMPLE_SECOND_REMINDER') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#catalogsamplethirdreminder" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_CATALOG_SAMPLE_THIRD_REMINDER') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#catalogsamplecouponreminder" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_CATALOG_SAMPLE_COUPON_REMINDER') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#economicbookinvoice" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_ECONOMIC_INVOICE') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#askquestion" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_ASK_QUESTION_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#quotationmail" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_QUOTATION_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#downloadableproductmail" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_DOWNLOADABLE_PRODUCT_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#review_product" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_REVIEW_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#first_after_order_purchased" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_FIRST_MAIL_AFTER_ORDER_PURCHASED') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#giftcard" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_GIFTCARD_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#wishlist" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_WISHLIST_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#newsletter_confirmation" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_NEWSLETTER_CONFIRMATION') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#send_friend" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_SEND_FRIEND') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#quotation_registration" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_QUOTATION_REGISTRATION_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#request_tax_exempt" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_REQUEST_TAX_EXEMPT_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#product_subscription" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_PRODUCT_SUBSCRIPTION_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#tax_exempt_approval_disapproval" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_TAX_EXEMPT_APPROVAL_DISAPPROVAL_MAIL') ?>
                            </a>
                        </li>
                        <li role="presentation">
                            <a href="#catalog_order" role="tab" data-toggle="tab">
								<?php echo JText::_('COM_REDSHOP_CATALOG_ORDER_MAIL') ?>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col-md-8">
                    <div class="tab-content">
                        <div role="tabpanel" class="tab-pane active" id="registrationmail">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('registration', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="order">
							<?php
							$newbillingtag = '{billing_address_start}
                            <table border="0"><tbody>
                            <tr><td>{companyname_lbl}</td><td>{companyname}</td></tr>
                            <tr><td>{firstname_lbl}</td><td>{firstname}</td></tr>
                            <tr><td>{lastname_lbl}</td><td>{lastname}</td></tr>
                            <tr><td>{address_lbl}</td><td>{address}</td></tr>
                            <tr><td>{city_lbl}</td><td>{city}</td></tr>
                            <tr><td>{zip_lbl}</td><td>{zip}</td></tr>
                            <tr><td>{country_lbl}</td><td>{country}</td></tr>
                            <tr><td>{state_lbl}</td><td>{state}</td></tr>
                            <tr><td>{phone_lbl}</td><td>{phone}</td></tr>
                            <tr><td>{email_lbl}</td><td>{email}</td></tr>
                            <tr><td>{vatnumber_lbl}</td><td>{vatnumber}</td></tr>
                            <tr><td>{taxexempt_lbl}</td><td>{taxexempt}</td></tr>
                            <tr><td>{user_taxexempt_request_lbl}</td><td>{user_taxexempt_request}</td></tr>{billing_extrafield}
                            </tbody></table> {billing_address_end}';

							$newshippingtag = '{shipping_address_start}
                            <table border="0"><tbody>
                            <tr><td>{firstname_lbl}</td><td>{firstname}</td></tr>
                            <tr><td>{lastname_lbl}</td><td>{lastname}</td></tr>
                            <tr><td>{address_lbl}</td><td>{address}</td></tr>
                            <tr><td>{city_lbl}</td><td>{city}</td></tr>
                            <tr><td>{zip_lbl}</td><td>{zip}</td></tr>
                            <tr><td>{country_lbl}</td><td>{country}</td></tr>
                            <tr><td>{state_lbl}</td><td>{state}</td></tr>
                            <tr><td>{phone_lbl}</td><td>{phone}</td></tr>{shipping_extrafield}
                            </tbody></table> {shipping_address_end}';
							?>
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('order', 'mail'); ?></td>
                                </tr>
                                <tr>
                                    <td>
										<?php $tags = RedshopHelperExtrafields::getSectionFieldList(14, 1);
										if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
										else echo JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS");
										for ($i = 0, $in = count($tags); $i < $in; $i++)
										{
											echo '<span style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</span>';
										} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
										<?php $tags = RedshopHelperExtrafields::getSectionFieldList(15, 1);
										if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
										else echo JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS");
										for ($i = 0, $in = count($tags); $i < $in; $i++)
										{
											echo '<span style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</span>';
										} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="invoicemail">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('invoice', 'mail'); ?></td>
                                </tr>
                                <tr>
                                    <td>
										<?php $tags = RedshopHelperExtrafields::getSectionFieldList(14, 1);
										if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
										else echo JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS");
										for ($i = 0, $in = count($tags); $i < $in; $i++)
										{
											echo '<span style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</span>';
										} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
										<?php $tags = RedshopHelperExtrafields::getSectionFieldList(15, 1);
										if (count($tags) == 0) echo JText::_("COM_REDSHOP_NO_FIELDS_AVAILABLE");
										else echo JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS");
										for ($i = 0, $in = count($tags); $i < $in; $i++)
										{
											echo '<span style="margin-left:10px;">{' . $tags[$i]->name . '} -- ' . $tags[$i]->title . '</span>';
										} ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td><?php echo htmlentities($newbillingtag) . "<br><br>" . htmlentities($newshippingtag); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="orderstatusmail">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('order_status', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="catalogmail">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('catalog_send', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="catalogfirstreminder">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('catalog_first_reminder', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="catalogsecreminder">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('catalog_second_reminder', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="catalogcouponreminder">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('catalog_coupon_reminder', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="catalogsamplefirstreminder">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('catalog_sample_first_reminder', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="catalogsamplesecreminder">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('catalog_sample_second_reminder', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="catalogsamplethirdreminder">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('catalog_sample_third_reminder', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="catalogsamplecouponreminder">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('catalog_sample_coupon_reminder', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="economicbookinvoice">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('economic_invoice', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="askquestion">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('ask_question', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="quotationmail">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('quotation', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="downloadableproductmail">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('downloable_product', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="review_product">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('review_product', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="first_after_order_purchased">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('first_after_order_purchased', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="giftcard">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('giftcard', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="wishlist">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('wishlist', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="newsletter_confirmation">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('newsletter_confirmation', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="send_friend">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('send_friend', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="quotation_registration">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('quotation_registration', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="request_tax_exempt">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('request_tax_exempt', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="product_subscription">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('product_subscription', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="tax_exempt_approval_disapproval">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('tax_exempt_approval_disapproval', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="catalog_order">
                            <table class="adminlist table table-striped">
                                <tr>
                                    <td><?php echo RedshopHelperTemplate::getTemplateValues('catalog_order', 'mail'); ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

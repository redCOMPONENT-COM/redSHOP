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

$newBillingTags = array(
	'billing_address_start'      => '',
	'companyname_lbl'            => '',
	'companyname'                => '',
	'firstname_lbl'              => '',
	'firstname'                  => '',
	'lastname_lbl'               => '',
	'lastname'                   => '',
	'address_lbl'                => '',
	'address'                    => '',
	'city_lbl'                   => '',
	'city'                       => '',
	'zip_lbl'                    => '',
	'zip'                        => '',
	'country_lbl'                => '',
	'country'                    => '',
	'state_lbl'                  => '',
	'state'                      => '',
	'phone_lbl'                  => '',
	'phone'                      => '',
	'email_lbl'                  => '',
	'email'                      => '',
	'vatnumber_lbl'              => '',
	'vatnumber'                  => '',
	'taxexempt_lbl'              => '',
	'taxexempt'                  => '',
	'user_taxexempt_request_lbl' => '',
	'user_taxexempt_request'     => '',
	'billing_extrafield'         => '',
	'billing_address_end'        => ''
);

$newShippingTags = array(
	'shipping_address_start' => '',
	'companyname_lbl'        => '',
	'companyname'            => '',
	'firstname_lbl'          => '',
	'firstname'              => '',
	'lastname_lbl'           => '',
	'lastname'               => '',
	'address_lbl'            => '',
	'address'                => '',
	'city_lbl'               => '',
	'city'                   => '',
	'zip_lbl'                => '',
	'zip'                    => '',
	'country_lbl'            => '',
	'country'                => '',
	'state_lbl'              => '',
	'state'                  => '',
	'phone_lbl'              => '',
	'phone'                  => '',
	'shipping_extrafield'    => '',
	'shipping_address_end'   => ''
)
?>
<div class="row">
    <div class="panel panel-default">
        <div class="panel-heading"><h3><?php echo JText::_('COM_REDSHOP_MAIL_CENTER_HELPFUL_HINT') ?></h3></div>
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
									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_CATEGORY, JText::_("COM_REDSHOP_FIELDS")
									);

									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_PRODUCT, JText::_("COM_REDSHOP_TEMPLATE_PRODUCT_FIELDS_TITLE")
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array(
											'tags'   => RedshopHelperTemplate::getTemplateTags($this->item->template_section),
											'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_CATEGORY_HINT')
										)
									);

									$addToCartAvailable = RedshopHelperTemplate::getTemplate('add_to_cart');
									$tags               = array();
									foreach ($addToCartAvailable as $tag):
										$tags['form_addtocart:' . $tag->template_name] = JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint',
										array(
											'tags'   => $tags,
											'header' => JText::_('COM_REDSHOP_ADD_TO_CART')
										)
									);

									$availableTags = RedshopHelperTemplate::getTemplate('related_product');
									$tags          = array();
									foreach ($availableTags as $tag):
                                        $key = 'related_product_lightbox:' . $tag->template_name . '[:lightboxwidth][:lightboxheight]';
										$tags[$key] = JText::_("COM_REDSHOP_EXAMPLE_TEMPLATE") . ': {related_product_lightbox:' . $tag->template_name . ':600:300}';
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_RELATED_PRODUCT_LIGHTBOX_TEMPLATE_AVAILABLE_HINT')));
									?>
                                    <div class="help-block pull-right">
                                        <p>
                                            <strong></strong>:
                                        </p>
                                    </div>
									<?php
									break;
								case 'giftcard':
									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_GIFT_CARD_USER_FIELD, JText::_("COM_REDSHOP_GIFTCARD_USERFIELD")
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array(
											'tags'   => RedshopHelperTemplate::getTemplateTags($this->item->template_section),
											'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_GIFTCARD_HINT')
										)
									);

									break;
								case 'product':
									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_PRODUCT, JText::_("COM_REDSHOP_PRODUCT_FIELDS")
									);

									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, JText::_("COM_REDSHOP_PRODUCT_USERFIELD")
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array(
											'tags'   => RedshopHelperTemplate::getTemplateTags($this->item->template_section),
											'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_PRODUCT_HINT')
										)
									);

									$addToCartAvailable = RedshopHelperTemplate::getTemplate('add_to_cart');
									$tags               = array();
									foreach ($addToCartAvailable as $tag):
										$tags['form_addtocart:' . $tag->template_name] = JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_ADD_TO_CART')));

									$availableTags = RedshopHelperTemplate::getTemplate('attribute_template');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['attribute_template:' . $tag->template_name] = JText::_('COM_REDSHOP_ATTRIBUTE_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_ATTRIBUTE_HINT')));

									$availableTags = RedshopHelperTemplate::getTemplate('attributewithcart_template');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['attributewithcart_template:' . $tag->template_name] = JText::_('COM_REDSHOP_ATTRIBUTE_WITH_CART_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_ATTRIBUTE_WITH_CART_HINT')));

									$availableTags = RedshopHelperTemplate::getTemplate('related_product');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['related_product:' . $tag->template_name] = JText::_('COM_REDSHOP_RELATED_PRODUCT_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_RELATED_PRODUCT_HINT')));

									$availableTags = RedshopHelperTemplate::getTemplate('wrapper_template');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['wrapper_template:' . $tag->template_name] = JText::_('COM_REDSHOP_WRAPPER_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_WRAPPER_HINT')));

									break;
								case 'product_sample':
									echo RedshopHelperTemplate::renderFieldTagHints(RedshopHelperExtrafields::SECTION_COLOR_SAMPLE);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags($this->item->template_section))
									);

									break;
								case 'manufacturer':
									echo RedshopHelperTemplate::renderFieldTagHints(RedshopHelperExtrafields::SECTION_MANUFACTURER);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags($this->item->template_section))
									);

									break;
								case 'manufacturer_products':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags($this->item->template_section))
									);

									$addToCartAvailable = RedshopHelperTemplate::getTemplate('add_to_cart');
									$tags               = array();
									foreach ($addToCartAvailable as $tag):
										$tags['form_addtocart:' . $tag->template_name] = JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_ADD_TO_CART')));

									break;
								case 'categoryproduct':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('category_product'))
									);

									break;
								case 'catalog':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('catalogue'))
									);

									break;
								case 'order_detail':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('order_detail'))
									);

									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS, JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS")
									);

									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS, JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS")
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => $newBillingTags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_BILLING_HINT'))
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => $newShippingTags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPPING_HINT'))
									);

									break;
								case 'order_receipt':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('order_receipt'))
									);

									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS, JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS")
									);

									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS, JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS")
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => $newBillingTags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_BILLING_HINT'))
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => $newShippingTags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPPING_HINT'))
									);

									break;
								case 'order_print':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('order_print'))
									);

									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_PRIVATE_SHIPPING_ADDRESS, JText::_("COM_REDSHOP_CUSTOMER_SHIPPING_ADDRESS")
									);

									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_COMPANY_SHIPPING_ADDRESS, JText::_("COM_REDSHOP_COMPANY_SHIPPING_ADDRESS")
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => $newBillingTags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_BILLING_HINT'))
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => $newShippingTags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPPING_HINT'))
									);

									break;
								case 'order_list':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('orderlist'))
									);

									break;
								case 'related_product':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('related_product'))
									);

									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_PRODUCT, JText::_("COM_REDSHOP_PRODUCT_FIELDS")
									);

									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_PRODUCT_USERFIELD, JText::_("COM_REDSHOP_PRODUCT_USERFIELD")
									);

									break;
								case 'attribute_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('attribute'))
									);

									break;
								case 'attributewithcart_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('attribute_with_cart'))
									);

									$availableTags = RedshopHelperTemplate::getTemplate('add_to_cart');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['form_addtocart:' . $tag->template_name] = JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_ADD_TO_CART')));
									break;
								case 'accessory_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('accessory'))
									);
									break;
								case 'wrapper_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('wrapper'))
									);

									break;
								case 'wishlist_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('wishlist'))
									);

									break;
								case 'wishlist_mail_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('wishlist_mail'))
									);

									break;
								case 'ask_question_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('ask_question'))
									);

									break;
								case 'ajax_cart_detail_box':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array(
											'tags'   => RedshopHelperTemplate::getTemplateTags('ajax_product'),
											'header' => JText::_('COM_REDSHOP_AJAX_CART_BOX_DETAIL_TEMPLATE_HINT')
										)
									);

									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_USER_INFORMATIONS
									);

									$availableTags = RedshopHelperTemplate::getTemplate('add_to_cart');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['form_addtocart:' . $tag->template_name] = JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_ADD_TO_CART')));

									break;
								case 'redproductfinder':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('redproductfinder'))
									);

									$availableTags = RedshopHelperTemplate::getTemplate('add_to_cart');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['form_addtocart:' . $tag->template_name] = JText::_('COM_REDSHOP_ADD_TO_CART_TEMPLATE_AVAILABLE_HINT');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_ADD_TO_CART')));

									break;
								case 'account_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('account'))
									);
									break;
								case 'shippingbox':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('shipping_box'))
									);
									break;
								case 'onestep_checkout':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('onestep_checkout'))
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => $newBillingTags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_BILLING_HINT'))
									);

									$availableTags = RedshopHelperTemplate::getTemplate('checkout');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['checkout_template:' . $tag->template_name] = JText::_('COM_REDSHOP_CHECKOUT_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags));

									$availableTags = RedshopHelperTemplate::getTemplate('shippingbox');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['shippingbox_template:' . $tag->template_name] = JText::_('COM_REDSHOP_SHIPPING_BOX_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags));

									$availableTags = RedshopHelperTemplate::getTemplate('redshop_shipping');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['shipping_template:' . $tag->template_name] = JText::_('COM_REDSHOP_SHIPPING_METHOD_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags));

									$availableTags = RedshopHelperTemplate::getTemplate('redshop_payment');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['payment_template:' . $tag->template_name] = JText::_('COM_REDSHOP_PAYMENT_METHOD_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags));

									break;
								case 'change_cart_attribute':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('change_cart_attribute'))
									);

									$availableTags = RedshopHelperTemplate::getTemplate('attribute_template');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['attribute_template:' . $tag->template_name] = JText::_('COM_REDSHOP_ATTRIBUTE_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags));

									$availableTags = RedshopHelperTemplate::getTemplate('attributewithcart_template');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['attributewithcart_template:' . $tag->template_name] = JText::_('COM_REDSHOP_ATTRIBUTE_WITH_CART_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags));

									break;
								case 'product_content_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('product_content'))
									);

									break;
								case 'billing_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('billing'))
									);

									$availableTags = RedshopHelperTemplate::getTemplate('private_billing_template');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['private_billing_template:' . $tag->template_name] = JText::_('COM_REDSHOP_PRIVATE_BILLING_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_PRIVATE_BILLING_TEMPLATE')));

									$availableTags = RedshopHelperTemplate::getTemplate('company_billing_template');
									$tags          = array();
									foreach ($availableTags as $tag):
										$tags['company_billing_template:' . $tag->template_name] = JText::_('COM_REDSHOP_COMPANY_BILLING_TEMPLATE');
									endforeach;
									echo RedshopLayoutHelper::render('templates.tags_hint', array('tags' => $tags, 'header' => JText::_('COM_REDSHOP_COMPANY_BILLING_TEMPLATE')));

									break;
								case 'private_billing_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('private_billing'))
									);

									break;
								case 'company_billing_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('company_billing'))
									);
									echo RedshopHelperTemplate::renderFieldTagHints(
										RedshopHelperExtrafields::SECTION_COMPANY_BILLING_ADDRESS, JText::_("COM_REDSHOP_FIELDS")
									);

									break;
								case 'shipping_template':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('shipping'))
									);

									break;
								case 'stock_note':
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags('stock_note'))
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => $newBillingTags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_BILLING_HINT'))
									);

									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => $newShippingTags, 'header' => JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPPING_HINT'))
									);

									break;

								default:
									echo RedshopLayoutHelper::render(
										'templates.tags_hint',
										array('tags' => RedshopHelperTemplate::getTemplateTags($this->item->template_section))
									);

									break;
							}
							?>
                        </div>
                        <div role="tabpanel" class="tab-pane" id="default_template">
							<?php
							switch ($this->item->template_section)
							{
								case 'product_sample':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('catalog_sample', true);
									break;
								case 'manufacturer':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('manufacturer_listings', true);
									break;
								case 'categoryproduct':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('category_product_template', true);
									break;
								case 'giftcard_list':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('giftcard_listing', true);
									break;
								case 'quotation_request':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('quotation_request_template', true);
									break;
								case 'newsletter':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('newsletter1', true);
									break;
								case 'newsletter_product':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('newsletter_products', true);
									break;
								case 'related_product':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('related_products', true);
									break;
								case 'add_to_cart':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('add_to_cart1', true);
									break;
								case 'attribute_template':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('attributes', true);
									break;
								case 'attributewithcart_template':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('attributes_listing1', true);
									break;
								case 'accessory_template':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('accessory', true);
									break;
								case 'wrapper_template':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('wrapper', true);
									break;
								case 'wishlist_template':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('wishlist_list', true);
									break;
								case 'wishlist_mail_template':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('wishlist_mail', true);
									break;
								case 'ask_question_template':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('ask_question', true);
									break;
								case 'account_template':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('my_account_template', true);
									break;
								case 'redshop_payment':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('payment_method', true);
									break;
								case 'redshop_shipping':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('shipping_method', true);
									break;
								case 'shippingbox':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('shipping_box', true);
									break;
								case 'change_cart_attribute':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('change_cart_attribute_template', true);
									break;
								case 'product_content_template':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('product_content', true);
									break;
								case 'quotation_cart':
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate('quotation_cart_template', true);
									break;

								default:
									$templateContent = RedshopHelperTemplate::getInstallSectionTemplate($this->item->template_section, true);
									break;
							}
							?>
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

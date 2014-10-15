<?php
/**
 * @package     RedSHOP.Backend
 * @subpackage  Template
 *
 * @copyright   Copyright (C) 2005 - 2014 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
defined('_JEXEC') or die;
?>
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_COMPANY_BILLING_HINT'); ?></b><br /><br />
<b><?php echo JText::_('COM_REDSHOP_REQUIRED_TAG'); ?></b><br /><br />
{email_lbl}{email}
{retype_email_lbl}{retype_email}<br/>
{company_name_lbl}{company_name}
{vat_number_lbl}{vat_number}
{firstname_lbl}{firstname}
{lastname_lbl}{lastname}
{address_lbl}{address}
{zipcode_lbl}{zipcode}
{city_lbl}{city}
{country_lbl}{country}
{state_lbl}{state}
{phone_lbl}{phone}
{tax_exempt_lbl}{tax_exempt}<br/><br/>
<b><?php echo JText::_('COM_REDSHOP_OPTION_TAG'); ?></b><br /><br />
{ean_number_lbl}{ean_number}<br/>
{company_extrafield}
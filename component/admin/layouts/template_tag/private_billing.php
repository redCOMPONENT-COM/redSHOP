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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_PRIVATE_BILLING_HINT'); ?></b><br /><br />
<b><?php echo JText::_('COM_REDSHOP_REQUIRED_TAG'); ?></b><br /><br />
{email_lbl}{email}
{firstname_lbl}{firstname}
{lastname_lbl}{lastname}
{address_lbl}{address}
{zipcode_lbl}{zipcode}
{city_lbl}{city}
{country_lbl}{country}
{state_lbl}{state}
{phone_lbl}{phone}
{private_extrafield}<br/>
{retype_email_lbl}{retype_email}
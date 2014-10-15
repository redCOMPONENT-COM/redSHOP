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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_BILLING_HINT'); ?></b><br /><br />
<b><?php echo JText::_('COM_REDSHOP_REQUIRED_TAG'); ?></b><br /><br />
{account_creation_start}<br />
{username_lbl}{username}
{password_lbl}{password}
{confirm_password_lbl}{confirm_password}<br/>
{newsletter_signup_chk}{newsletter_signup_lbl}<br/>
{account_creation_end}<br/>
{required_lbl}<br/>
{sipping_same_as_billing_lbl} {sipping_same_as_billing}
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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPPING_TAG'); ?></b><br /><br />
<b><?php echo JText::_('COM_REDSHOP_REQUIRED_TAG'); ?></b><br /><br />
{firstname_st_lbl}{firstname_st}<br/>
{lastname_st_lbl}{lastname_st}<br/>
{address_st_lbl}{address_st}<br/>
{zipcode_st_lbl}{zipcode_st}<br/>
{city_st_lbl}{city_st}<br/>
{country_st_lbl}{country_st}<br/>
{state_st_lbl}{state_st}<br/>
{phone_st_lbl}{phone_st}<br/><br/>

<b><?php echo JText::_('COM_REDSHOP_OPTION_TAG'); ?></b><br /><br />
{extra_field_st_start}{extra_field_st}{extra_field_st_end}
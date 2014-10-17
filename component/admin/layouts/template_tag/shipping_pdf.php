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
<b><?php echo JText::_('COM_REDSHOP_TEMPLATE_TAG_SHIPPING_PDF_HINT'); ?></b><br /><br />
{order_information_lbl} {order_id_lbl} {order_id} {order_number_lbl} {order_number} {order_date_lbl} {order_date} <br />
{order_status_lbl} {order_status} {shipping_address_information_lbl} {shipping_firstname_lbl} {shipping_firstname} {shipping_lastname_lbl} {shipping_lastname} <br />
{shipping_address_lbl} {shipping_address} {shipping_zip_lbl} {shipping_zip} {shipping_city_lbl} {shipping_city} {shipping_country_lbl} {shipping_state_lbl} <br />
{shipping_phone_lbl} {company_name_lbl} {company_name} {shipping_country} {shipping_phone} {shipping_state}
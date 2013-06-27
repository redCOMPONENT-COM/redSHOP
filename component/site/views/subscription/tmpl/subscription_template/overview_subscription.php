<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  View
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */
?>
<a name="table"></a>
<div id="subscription_conent">
	<div class="tbl_price_box"></div>
	<table style="width: 100%;" class="tbl_buynow" id="tbl_buynow" border="0" cellpadding="0px" cellspacing="0px">
		<tbody>
		<tr class="tbl_price_header">
			<td colspan="4">
				<div style="text-align: center;" align="left"><strong> <span
							style="font-size: 32px; line-height: 70px;">{subscription_frontpage_introtext}</span>
					</strong></div>
			</td>
		</tr>
		<tr class="tbl_price_box_top">
			<td rowspan="2"
				style="vertical-align: middle; border-left: 2px solid #E8E8E8; background: transparent !important;"
				width="auto">
				<div style="position: relative;">
					<p style="text-align: center; padding-right: 45px;"><span style="font-size: 14px;">{subscription_frontpage_description}<br/></span>
					</p>
			</td>
			<td style="width: 80px;" valign="bottom">
				<div align="center"><span style="font-size: 14px;"><strong>{subscription_name:15}</strong></span></div>
			</td>
			<td style="width: 80px;" valign="bottom">
				<div align="center"><span style="font-size: 14px;"><strong>{subscription_name:16}</strong></span></div>
			</td>
		</tr>
		<tr class="tbl_price_box_bot">
			<td valign="top">
				<div align="center"><span style="font-size: 24px;">{subscription_price:15}</span></div>
			</td>
			<td valign="top">
				<div align="center"><span style="font-size: 24px;">{subscription_price:16}</span></div>
			</td>
		</tr>
		<!-- {subscription_loop_start} -->
		<tr class="tbl_price_heading">
			<td><strong>{subscription_main}</strong>{subscription_link_detail}</td>
			<td colspan="3"></td>
		</tr>
		<!-- {child_subscription_loop_start} -->
		<tr>
			<td>{child_subscription_name}</td>
			<td>{child_subscription_check:15}</td>
			<td>{child_subscription_check:16}</td>
		</tr>
		<!-- {child_subscription_loop_end} -->
		<!--{subscription_loop_end} -->
		<tr class="tbl_price_end">
			<td colspan="4">
				<div align="right">
					<div style="width: 30%;">
						<span>{subscription_add_to_cart:15}</span>
						<span>{subscription_add_to_cart:16}</span>

						<div style="clear: both;"></div>
					</div>
				</div>
			</td>
		</tr>
		</tbody>
	</table>
</div>

<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Payment.stripe
 * @copyright   Copyright (C) 2008-2015 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;

// Load baokim library
require_once dirname(__DIR__) . '/library/constants.php';
require_once dirname(__DIR__) . '/library/baokim_payment_pro.php';

$baokim = new BaoKimPaymentPro;
$banks = $baokim->get_seller_info();

$formInput = $displayData['formInput'];
$params    = $displayData['params'];
$action    = $formInput['action'];
$name      = $params->get('dataName', SHOP_NAME);
?>
<link type="text/css" rel="stylesheet" href="<?php echo JURI::root() . 'plugins/redshop_payment/baokim/media/css/main.css' ?>"/>
<h3><?php echo $name; ?></h3>
<div id="wrapper">
	<!-- nav -->
	<div class="nav">
		<div class="nav_title">Phương thức thanh toán</div>
	</div>
	<!--/ end nav -->

	<!-- payment -->
	<div class="payment_list">
		<div id="select_payment">
			<form method="post" action="" id="form-action">
				<div class="method row-fluid" id="2">
					<div class="icon"><img src="<?php echo JURI::root() . 'plugins/redshop_payment/baokim/media/images/creditcard.png'; ?>" border="0"/></div>
					<div class="info">
						<span class="title">Thanh toán trực tuyến bằng thẻ quốc tế <!--<img src="images/safe.png" border="0" style="vertical-align: bottom; margin-left: 5px;" />--></span>

						<div class="bank_list"
							<ul id="b_l">
								<?php echo $baokim->generateBankImage($banks, PAYMENT_METHOD_TYPE_CREDIT_CARD); ?>
							</ul>
							<div class="clr"></div>
						</div>
					</div>
					<div class="check_box"></div>
				</div>

				<div class="row-fluid method" id="3">
					<div class="icon"><img src="<?php echo JURI::root() . 'plugins/redshop_payment/baokim/media/images/transfer.png'; ?>" border="0"/></div>
					<div class="info">
						<span class="title">Chuyển khoản InternetBanking</span>
						<span class="desc">Chọn ngân hàng thanh toán</span>

						<div class="bank_list">
							<ul id="b_l">
								<?php echo $baokim->generateBankImage($banks, PAYMENT_METHOD_TYPE_INTERNET_BANKING); ?>
							</ul>
						</div>
					</div>
					<div class="check_box"></div>
				</div>
				<div class="row-fluid method" id="1">
					<div class="icon"><img src="<?php echo JURI::root() . 'plugins/redshop_payment/baokim/media/images/atm.png'; ?>" border="0"/></div>
					<div class="info">
						<span class="title">Thanh toán trực tuyến bằng thẻ ATM nội địa</span>
						<span class="desc">Chọn ngân hàng thanh toán</span>

						<div class="bank_list">
							<ul id="b_l">
								<?php echo $baokim->generateBankImage($banks, PAYMENT_METHOD_TYPE_LOCAL_CARD); ?>
							</ul>
							<div class="clr"></div>
						</div>
					</div>
					<div class="check_box"></div>
				</div>

				<div class="row-fluid method" id="0">
					<div class="icon"><img src="<?php echo JURI::root() . 'plugins/redshop_payment/baokim/media/images/sercurity.png'; ?>" border="0"/></div>
					<div class="info">
						<div class="bk_logo"><a href="http://baokim.vn" target="_blank"><img
									src="<?php echo JURI::root() . 'plugins/redshop_payment/baokim/media/images/bk_logo.png'; ?>" border="0"/></a></div>
						<span class="title">Thanh toán Bảo Kim</span>
						<span class="desc">Thanh toán bằng tài khoản Bảo Kim (Baokim.vn)</span>
					</div>
					<div class="check_box"></div>
				</div>
				<li class="mode">
					<div class="info1">
						<span class="title">Hình thức thanh toán</span>

						<div class="payment-mode">
							<input type="radio" checked="true" class="input-mode" name="payment_mode" value="1"><span class="desc-mode">Thanh toán trực tiếp</span>
						</div>

						<div class="payment-mode">
							<input type="radio" class="input-mode" name="payment_mode" value="2" ><span class="desc-mode">Thanh toán an toàn</span>
						</div>
						<div id="daykeep" >
							<span class="desc-mode" style="margin-right:5px;">Số ngày tạm giữ</span>
							<select name="escrow_timeout" class="daykeep">
								<option value=3>3 ngày</option>
								<option value=5>5 ngày</option>
								<option value=7>7 ngày</option>
							<select>
						</div>
					</div>

				</li>


				<input type="hidden" name="active_submit" value="submit"/>
				<input type="hidden" name="bank_payment_method_id" id="bank_payment_method_id" value=""/>
				<input type="hidden" name="payer_name" value="<?php echo $formInput['firstname'] . ' ' . $formInput['lastname']; ?>">
				<input type="hidden" name="payer_phone_no" value="<?php echo $formInput['phone']; ?>">
				<input type="hidden" name="payer_email" value="<?php echo $formInput['email']; ?>">
				<input type="hidden" name="address" value="<?php echo $formInput['street']; ?>">

				<div class="submit">
					<input type="submit" class="btn btn-success pm_submit" name="submit" value="Hoàn thành"/>
				</div>
			</form>
		</div>
	</div>
	<!-- end payment -->
</div>
<script>
	jQuery(function () {
		jQuery('.method').click(function () {
			jQuery(this).siblings().children().find('img').removeClass('img-active');
			jQuery('.method').removeClass('selected');
			jQuery('.check_box').removeClass('checked_box');
			jQuery(this).addClass('selected');
			jQuery('.selected .check_box').addClass('checked_box');
			var method = jQuery(this).attr('id');
			if (method != 0) {
				//jQuery('.mode').css('display','block');
				jQuery('.info1').slideDown();
				jQuery('.selected img').click(function () {
					jQuery('.method img').removeClass('img-active');
					jQuery(this).addClass('img-active');
					var id = jQuery(this).attr('id');
					jQuery('#bank_payment_method_id').val(id);

				});
			}
			else {
				//jQuery('.mode').css('display','none');
				jQuery('.info1').slideUp('slow');
				jQuery('.method img').removeClass('img-active');
			}
			jQuery('#form-action').attr('action', '<?php echo $action; ?>');
		});

		jQuery('.input-mode').click(function () {
			var a = jQuery(this).val();
			if (a == 2) {
				jQuery('#daykeep').css('display', 'block');
			}
			if (a == 1) {
				jQuery('#daykeep').css('display', 'none');
			}

		});
	});
</script>

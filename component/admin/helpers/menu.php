<?php
/**
 * @copyright Copyright (C) 2010 redCOMPONENT.com. All rights reserved.
 * @license GNU/GPL, see license.txt or http://www.gnu.org/copyleft/gpl.html
 * Developed by email@recomponent.com - redCOMPONENT.com
 *
 * redSHOP can be downloaded from www.redcomponent.com
 * redSHOP is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License 2
 * as published by the Free Software Foundation.
 *
 * You should have received a copy of the GNU General Public License
 * along with redSHOP; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 */
if( !defined( '_VALID_MOS' ) && !defined( '_JEXEC' ) ) die( 'Direct Access to '.basename(__FILE__).' is not allowed.' );
//jimport( 'joomla.plugin.helper' );

class leftmenu
{
	function  __construct()
	{
		jimport('joomla.html.pane');
		$option = JRequest::getVar('option');
		$view = JRequest::getVar('view');
		$redhelper = new redhelper();
		$stk = 0;
		$cnt = 6;
		if(USE_CONTAINER)
		{
			if(USE_STOCKROOM)
			{
				$stk = $cnt + 1;
				$counter = $cnt + 2;
			} else {
				$counter = $cnt + 1;
			}
		} else {
			if(USE_STOCKROOM)
			{
				$stk = $cnt;
				$counter = $cnt+1;
			} else {
				$counter = $cnt;
			}
		}

	    $acocnt = 11;
		if(ENABLE_BACKENDACCESS)
		{
			$acocnt = 12;
		}

		$ecocnt = 16;
		if(ECONOMIC_INTEGRATION)
		{
			$ecocnt = 17;

		}

		if(JPluginHelper::isEnabled('redshop_custom_views','rs_custom_views_date') || JPluginHelper::isEnabled('redshop_custom_views','rs_custom_views_person') || JPluginHelper::isEnabled('redshop_custom_views','rs_custom_views_company'))
		{
			$ecocnt = 18;
		}

		switch( $view )
		{


			case "product":
			case "product_detail":
			case "prices":
			case "mass_discount_detail":
			case "mass_discount":
				$selected=0;
				break;

			case "category":
				$selected=1;
				break;

			case "manufacturer":
			case "manufacturer_detail":
				$selected=2;
				break;

			case "media":
				$selected=3;
				break;

			case "order":
			case "order_detail":
			case "addorder_detail":
			case "orderstatus":
			case "orderstatus_detail":
			case "opsearch":
			case "barcode":
			case "orderreddesign": // reddesign
			 // reddesign
				$selected=4;
				break;

				case "quotation":
			case "quotation_detail":
				$selected=5;
				break;

			case "stockroom":
		    case "stockroom_listing":
		    case "stockimage":
				$selected=$cnt;
				break;

			case "container":
		    case "product_container":
				$selected=$stk;
				break;

            case "delivery":
				$selected=$counter;
				break;

			case "supplier":
		    case "supplier_detail":
				$selected=$counter+1;
				break;

			case "discount":
				$selected=$counter+2;
				break;

			case "giftcard":
		    case "giftcard_detail":
				$selected=$counter+3;
				break;

		    case "voucher":
				$selected=$counter+4;
				break;

			case "coupon":
		    case "coupon_detail":
				$selected=$counter+5;
				break;


			case "mail":
				$selected=$counter+6;
				break;


		    case "newsletter":
		    case "newslettersubscr":
				$selected=$counter+7;
				break;

                case "shipping":
                case "shipping_rate":
                $selected=$counter+8;
				break;

				case "shipping_box":
                 $selected=$counter+9;
				break;

			    case "shipping_detail":
			    $selected=$counter+10;
				break;



			    case "wrapper":
				$selected=$counter+11;
				break;

				case "user":
			    case "shopper_group":
				$selected=$counter+12;
				break;

				case "accessmanager":
				$selected=$counter+$acocnt +1;
				break;

				case "tax_group":
			case "tax_group_detail":
			case "tax":
				$selected=$counter+$acocnt+2;
				break;

					case "currency":
			case "currency_detail":
				$selected=$counter+$acocnt+3;
				break;

				case "country":
			case "country_detail":
				$selected=$counter+$acocnt+4;
				break;
			case "state":
			case "state_detail":
				$selected=$counter+$acocnt+5;
				break;
			case "zipcode":
			case "zipcode_detail":
				$selected=$counter+$acocnt+6;
				break;

			case "importexport":
			case "import":
			case "export":
			case "vmimport":
				$selected=$counter+$acocnt+7;
				break;

			case "xmlimport":
			case "xmlexport":
				$selected=$counter+$acocnt+8;
				break;


                case "fields":
		    	case "addressfields_listing":
				$selected=$counter+$acocnt+9;
				break;

				case "template":
				$selected=$counter+$acocnt+10;
				break;

			case "textlibrary":
				$selected=$counter+$acocnt+11;
				break;







			case "catalog":
			case "catalog_request":
				$selected=$counter+$acocnt+12;
				break;

			case "sample":
			case "sample_request":
				$selected=$counter+$acocnt+13;
				break;

				 case "producttags":
			case "producttags_detail":
				$selected=$counter+$acocnt+14;
				break;





			case "attribute_set":
			case "attribute_set_detail":
				$selected=$counter+$acocnt+15;
				break;
			case "integration":
				$selected=$counter+$acocnt+16;
				break;

			case "question":
			case "question_detail":
			case "answer":
			case "answer_detail":
				$selected=$counter+$acocnt+17;
				break;

			case "rating":
				$selected=$counter+$acocnt+18;
				break;



			case "accountgroup":
			case "accountgroup_detail":
				$selected=$counter+$acocnt+19;
				break;



				case "statistic":
			 	$selected=$counter+$acocnt+$ecocnt+3;
				break;

			    case "configuration":
				$selected=$counter+$acocnt+$ecocnt+4;
				break;

				case "customprint":
				$selected=$counter+$acocnt+$ecocnt+5;
				break;


			default:
				$selected=0;
				break;
		}


		$pane = @JPane::getInstance('sliders',array('startOffset'=>$selected));

        echo $pane->startPane( 'stat-pane' );
        ?>
       <table ><tr><td class="distitle"><?php echo JText::_( 'PRODUCT_MANAGEMENT');?></td></tr></table>
       <?php
		$title = JText::_( 'PRODUCTS' );

		$user = JFactory::getUser();


		echo $pane->startPanel( $title, 'NEW PRODUCT');


		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=product';
					echo '<a href="'.$link.'" title="'.JText::_( 'PRODUCT_LISTING' ).'">'.JText::_( 'PRODUCT_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=product&task=listing';
					echo '<a href="'.$link.'" title="'.JText::_( 'PRODUCT_PRICE_VIEW' ).'">'.JText::_( 'PRODUCT_PRICE_VIEW' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=product_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_PRODUCT' ).'">'.JText::_( 'ADD_PRODUCT' ).'</a>'; ?>
				</td>
			</tr>


			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=mass_discount_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_MASS_DISCOUNT' ).'">'.JText::_( 'ADD_MASS_DISCOUNT' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=mass_discount';
					echo '<a href="'.$link.'" title="'.JText::_( 'MASS_DISCOUNT' ).'">'.JText::_( 'MASS_DISCOUNT' ).'</a>'; ?>
				</td>
			</tr>
			<?php
			if(ECONOMIC_INTEGRATION == 1){ ?>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=product&layout=importproduct';//&task=importeconomic
					echo '<a href="'.$link.'" title="'.JText::_( 'IMPORT_PRODUCTS_TO_ECONOMIC' ).'">'.JText::_( 'IMPORT_PRODUCTS_TO_ECONOMIC' ).'</a>'; ?>
				</td>
			</tr>
			<?php
			if(ATTRIBUTE_AS_PRODUCT_IN_ECONOMIC == 1){ ?>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=product&layout=importattribute';//&task=importatteco
					echo '<a href="'.$link.'" title="'.JText::_( 'IMPORT_ATTRIBUTES_TO_ECONOMIC' ).'">'.JText::_( 'IMPORT_ATTRIBUTES_TO_ECONOMIC' ).'</a>'; ?>
				</td>
			</tr>
			<?php }
			}	?>
		</table>
		<?php

		echo $pane->endPanel();



		$title = JText::_( 'CATEGORY' );
		echo $pane->startPanel( $title, 'CATEGORY' );
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=category';
					echo '<a href="'.$link.'" title="'.JText::_( 'CATEGORY_LISTING' ).'">'.JText::_( 'CATEGORY_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=category_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_CATEGORY' ).'">'.JText::_( 'ADD_CATEGORY' ).'</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_( 'MANUFACTURER' );
			echo $pane->startPanel( $title, 'MANUFACTURER');	?>
		<table class="adminlist">
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=manufacturer';
					echo '<a href="'.$link.'" title="'.JText::_( 'MANUFACTURER_LISTING' ).'">'.JText::_( 'MANUFACTURER_LISTING' ).'</a>'; ?>
				</td></tr>
			<tr><td><?php
						$link 	=  'index.php?option='.$option.'&view=manufacturer_detail';
						echo '<a href="'.$link.'" title="'.JText::_( 'ADD_MANUFACTURER' ).'">'.JText::_( 'ADD_MANUFACTURER' ).'</a>'; ?>
				</td></tr>
		</table>
	<?php	echo $pane->endPanel();


		$title = JText::_( 'MEDIA' );
		echo $pane->startPanel( $title, 'MEDIA');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=media';
					echo '<a href="'.$link.'" title="'.JText::_( 'MEDIA_LISTING' ).'">'.JText::_( 'MEDIA_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=media_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_MEDIA_ITEM' ).'">'.JText::_( 'BULK_UPLOAD' ).'</a>'; ?>
				</td>
			</tr>
			</table>
		<?php
		echo $pane->endPanel();
		?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'ORDER' );?></td></tr></table>

		<?php 	$title = JText::_( 'ORDER' );
		echo $pane->startPanel( $title, 'ORDER');
		?>
		<table class="adminlist">
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=order';
					echo '<a href="'.$link.'" title="'.JText::_( 'ORDER_LISTING' ).'">'.JText::_( 'ORDER_LISTING' ).'</a>'; ?>
				</td></tr>
			<tr><td><?php
					$link =  'index.php?option='.$option.'&view=addorder_detail';
	   	   			$link = $redhelper->sslLink($link);
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_ORDER' ).'">'.JText::_( 'ADD_ORDER' ).'</a>'; ?>
				</td></tr>
			<tr><td><?php
					$link =  'index.php?option='.$option.'&view=order&layout=labellisting';
					echo '<a href="'.$link.'" title="'.JText::_( 'DOWNLOAD_LABEL' ).'">'.JText::_( 'DOWNLOAD_LABEL' ).'</a>'; ?>
				</td></tr>
		</table>
		<table class="adminlist">
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=orderstatus';
					echo '<a href="'.$link.'" title="'.JText::_( 'ORDERSTATUS_LISTING' ).'">'.JText::_( 'ORDERSTATUS_LISTING' ).'</a>'; ?>
				</td></tr>
			<!-- <tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=orderstatus_detail';
	   	   			$link = $redhelper->sslLink($link);
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_ORDERSTATUS' ).'">'.JText::_( 'ADD_ORDERSTATUS' ).'</a>'; ?>
				</td></tr>-->
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=opsearch';
					echo '<a href="'.$link.'" title="'.JText::_( 'PRODUCT_ORDER_SEARCH' ).'">'.JText::_( 'PRODUCT_ORDER_SEARCH' ).'</a>'; ?>
				</td></tr>
				<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=barcode';
					echo '<a href="'.$link.'" title="'.JText::_( 'BARCODE' ).'">'.JText::_( 'BARCODE' ).'</a>'; ?>
				</td></tr>
				<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=barcode&layout=barcode_order';
					echo '<a href="'.$link.'" title="'.JText::_( 'BARCODE_ORDER' ).'">'.JText::_( 'BARCODE_ORDER' ).'</a>'; ?>
				</td></tr>
<?php	// reddesign
		 require_once(JPATH_COMPONENT_ADMINISTRATOR.DS.'helpers'.DS.'helper.php');
		 $reddesignhelper = new reddesignhelper();
		 $CheckRedDesign = $reddesignhelper->CheckIfRedDesign();
		 if($CheckRedDesign)
		 {	?>
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=orderreddesign';
					echo '<a href="'.$link.'" title="'.JText::_( 'REDDESIGN_ORDER_LISTING' ).'">'.JText::_( 'REDDESIGN_ORDER_LISTING' ).'</a>'; ?>
				</td></tr>
<?php	}// reddesign end?>

		</table>
		<?php
		echo $pane->endPanel();


			$title = JText::_( 'QUOTATION' );
			echo $pane->startPanel( $title, 'QUOTATION'); ?>
			<table class="adminlist">
				<tr><td><?php
						$link 	=  'index.php?option='.$option.'&view=quotation';
						echo '<a href="'.$link.'" title="'.JText::_( 'QUOTATION_LISTING' ).'">'.JText::_( 'QUOTATION_LISTING' ).'</a>'; ?>
					</td></tr>
				<tr><td><?php
							$link 	=  'index.php?option='.$option.'&view=addquotation_detail';
							echo '<a href="'.$link.'" title="'.JText::_( 'ADD_QUOTATION' ).'">'.JText::_( 'ADD_QUOTATION' ).'</a>'; ?>
					</td></tr>
			</table>
		<?php	echo $pane->endPanel();

	if(USE_STOCKROOM != 0)
		{
			$title = JText::_( 'STOCKROOM' );
			echo $pane->startPanel( $title, 'STOCKROOM');
			?>
			<table class="adminlist">
				<tr>
					<td><?php
						$link 	=  'index.php?option='.$option.'&view=stockroom';
						echo '<a href="'.$link.'" title="'.JText::_( 'STOCKROOM_LISTING' ).'">'.JText::_( 'STOCKROOM_LISTING' ).'</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
						$link 	=  'index.php?option='.$option.'&view=stockroom_detail';
						echo '<a href="'.$link.'" title="'.JText::_( 'ADD_STOCKROOM' ).'">'.JText::_( 'ADD_STOCKROOM' ).'</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
						$link 	=  'index.php?option='.$option.'&view=stockroom_listing';
						echo '<a href="'.$link.'" title="'.JText::_( 'STOCKROOM_AMOUNT_LISTING' ).'">'.JText::_( 'STOCKROOM_AMOUNT_LISTING' ).'</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
						$link 	=  'index.php?option='.$option.'&view=stockimage';
						echo '<a href="'.$link.'" title="'.JText::_( 'STOCKIMAGE_LISTING' ).'">'.JText::_( 'STOCKIMAGE_LISTING' ).'</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
						$link 	=  'index.php?option='.$option.'&view=stockimage_detail';
						echo '<a href="'.$link.'" title="'.JText::_( 'ADD_STOCKIMAGE' ).'">'.JText::_( 'ADD_STOCKIMAGE' ).'</a>'; ?>
					</td>
				</tr><?php 
				if(ECONOMIC_INTEGRATION){?>
				<tr>
					<td><?php
						$link 	=  'index.php?option='.$option.'&view=stockroom_detail&layout=importstock';
						echo '<a href="'.$link.'" title="'.JText::_( 'IMPORT_STOCK_FROM_ECONOMIC' ).'">'.JText::_( 'IMPORT_STOCK_FROM_ECONOMIC' ).'</a>'; ?>
					</td>
				</tr>
				<?php }?>
			</table>
			<?php
			echo $pane->endPanel();
		}

			if(USE_CONTAINER != 0)
			{
				$title = JText::_( 'CONTAINER' );
				echo $pane->startPanel( $title, 'CONTAINER');?>
				<table class="adminlist">
					<tr><td><?php
							$link 	=  'index.php?option='.$option.'&view=container';
							echo '<a href="'.$link.'" title="'.JText::_( 'CONTAINER_LISTING' ).'">'.JText::_( 'CONTAINER_LISTING' ).'</a>'; ?>
						</td></tr>
					<tr><td><?php
							$link 	=  'index.php?option='.$option.'&view=container_detail';
							echo '<a href="'.$link.'" title="'.JText::_( 'ADD_CONTAINER' ).'">'.JText::_( 'ADD_CONTAINER' ).'</a>'; ?>
						</td></tr>
					<tr><td><?php
							$link 	=  'index.php?option='.$option.'&view=product_container';
							echo '<a href="'.$link.'" title="'.JText::_( 'CONTAINER_PRE_ORDER' ).'">'.JText::_( 'CONTAINER_PRE_ORDER' ).'</a>'; ?>
						</td></tr>
					<tr><td><?php
							$link 	=  'index.php?option='.$option.'&view=product_container&container=1';
							echo '<a href="'.$link.'" title="'.JText::_( 'CONTAINER_ORDER_PRODUCTS' ).'">'.JText::_( 'CONTAINER_ORDER_PRODUCTS' ).'</a>'; ?>
						</td></tr>
				</table>
				<?php

				echo $pane->endPanel();
			}

				$title = JText::_( 'DELIVERY_LISTING' );
		echo $pane->startPanel( $title, 'DELIVERY_LISTING');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=delivery';
					echo '<a href="'.$link.'" title="'.JText::_( 'DELIVERY_LISTING' ).'">'.JText::_( 'DELIVERY_LISTING' ).'</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_( 'SUPPLIER' );
		echo $pane->startPanel( $title, 'SUPPLIER');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=supplier';
					echo '<a href="'.$link.'" title="'.JText::_( 'SUPPLIER_LISTING' ).'">'.JText::_( 'SUPPLIER_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
						$link 	=  'index.php?option='.$option.'&view=supplier_detail';
						echo '<a href="'.$link.'" title="'.JText::_( 'ADD_SUPPLIER' ).'">'.JText::_( 'ADD_SUPPLIER' ).'</a>'; ?>
				</td>
			</tr>
		</table>
        <?php
		echo $pane->endPanel();

?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'DISCOUNT' );?></td></tr></table>

		<?php

			$title = JText::_( 'DISCOUNT' );
		echo $pane->startPanel( $title, 'DISCOUNT');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=discount';
					echo '<a href="'.$link.'" title="'.JText::_( 'DISCOUNT_LISTING' ).'">'.JText::_( 'DISCOUNT_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=discount_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_DISCOUNT' ).'">'.JText::_( 'ADD_DISCOUNT' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=discount&layout=product';
					echo '<a href="'.$link.'" title="'.JText::_( 'DISCOUNT_LISTING' ).'">'.JText::_( 'DISCOUNT_PRODUCT_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=discount_detail&layout=product';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_DISCOUNT' ).'">'.JText::_( 'ADD_DISCOUNT_PRODUCT' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=discountarea';
					echo '<a href="'.$link.'" title="'.JText::_( 'DISCOUNTAREA_LISTING' ).'">'.JText::_( 'DISCOUNTAREA_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=discountarea_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_DISCOUNTAREA' ).'">'.JText::_( 'ADD_DISCOUNTAREA_PRODUCT' ).'</a>'; ?>
				</td>
			</tr>
			</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_( 'GIFTCARD' );
		echo $pane->startPanel( $title, 'GIFTCARD');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=giftcard';
					echo '<a href="'.$link.'" title="'.JText::_( 'GIFTCARD_LISTING' ).'">'.JText::_( 'GIFTCARD_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
						$link 	=  'index.php?option='.$option.'&view=giftcard_detail';
						echo '<a href="'.$link.'" title="'.JText::_( 'ADD_GIFTCARD' ).'">'.JText::_( 'ADD_GIFTCARD' ).'</a>'; ?>
				</td>
			</tr>
		</table>
        <?php
		echo $pane->endPanel();

		$title = JText::_( 'VOUCHER' );
		echo $pane->startPanel( $title, 'VOUCHER');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=voucher';
					echo '<a href="'.$link.'" title="'.JText::_( 'VOUCHER_LISTING' ).'">'.JText::_( 'VOUCHER_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=voucher_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_VOUCHER' ).'">'.JText::_( 'ADD_VOUCHER' ).'</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
			echo $pane->endPanel();

				$title = JText::_( 'COUPON' );
		echo $pane->startPanel( $title, 'COUPON');?>
		<table class="adminlist">
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=coupon';
					echo '<a href="'.$link.'" title="'.JText::_( 'COUPON_LISTING' ).'">'.JText::_( 'COUPON_LISTING' ).'</a>'; ?>
				</td></tr>
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=coupon_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_COUPON' ).'">'.JText::_( 'ADD_COUPON' ).'</a>'; ?>
				</td></tr>
			</table>
		<?php
		echo $pane->endPanel();



		?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'COMMUNICATION' );?></td></tr></table>

		<?php
        	$title = JText::_( 'MAIL_CENTER' );
		echo $pane->startPanel( $title, 'MAIL_CENTER');?>
		<table class="adminlist">
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=mail';
					echo '<a href="'.$link.'" title="'.JText::_( 'MAIL_CENTER_LISTING' ).'">'.JText::_( 'MAIL_CENTER_LISTING' ).'</a>'; ?>
				</td></tr>
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=mail_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_MAIL_CENTER' ).'">'.JText::_( 'ADD_MAIL_CENTER' ).'</a>'; ?>
				</td></tr>
			</table>
	<?php	echo $pane->endPanel();

       	$title = JText::_( 'NEWSLETTER' );
		echo $pane->startPanel( $title, 'NEWSLETTER');		?>
		<table class="adminlist">
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=newsletter';
					echo '<a href="'.$link.'" title="'.JText::_( 'NEWSLETTER_LISTING' ).'">'.JText::_( 'NEWSLETTER_LISTING' ).'</a>'; ?>
				</td></tr>
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=newsletter_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_NEWSLETTER' ).'">'.JText::_( 'ADD_NEWSLETTER' ).'</a>'; ?>
				</td></tr>
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=newslettersubscr';
					echo '<a href="'.$link.'" title="'.JText::_( 'NEWSLETTER_SUBSCR_LISTING' ).'">'.JText::_( 'NEWSLETTER_SUBSCR_LISTING' ).'</a>'; ?>
				</td></tr>
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=newslettersubscr_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_NEWSLETTER_SUBSCR' ).'">'.JText::_( 'ADD_NEWSLETTER_SUBSCR' ).'</a>'; ?>
				</td></tr>
			<tr><td><?php
					$link 	=  'index.php?option='.$option.'&view=newsletter_detail&layout=statistics';
					echo '<a href="'.$link.'" title="'.JText::_( 'NEWSLETTER_STATISTICS' ).'">'.JText::_( 'NEWSLETTER_STATISTICS' ).'</a>'; ?>
				</td></tr>
		</table>
	<?php	echo $pane->endPanel();

		?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'SHIPPING' );?></td></tr></table>

		<?php

	$title = JText::_( 'SHIPPING_METHOD' );
		echo $pane->startPanel( $title, 'SHIPPING_METHOD');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=shipping';
					echo '<a href="'.$link.'" title="'.JText::_( 'SHIPPING_METHOD_LISTING' ).'">'.JText::_( 'SHIPPING_METHOD_LISTING' ).'</a>'; ?>
				</td>
			</tr>


			<?php
			if(ECONOMIC_INTEGRATION == 1){ ?>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=shipping&task=importeconomic';
					echo '<a href="'.$link.'" title="'.JText::_( 'IMPORT_RATES_TO_ECONOMIC' ).'">'.JText::_( 'IMPORT_RATES_TO_ECONOMIC' ).'</a>'; ?>
				</td>
			</tr>
			<?php }?>
		</table>
		<?php
		echo $pane->endPanel();


	    $title = JText::_( 'SHIPPING_BOX' );
		echo $pane->startPanel( $title, 'SHIPPING_BOX');
		?>
		<table class="adminlist">


			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=shipping_box';
					echo '<a href="'.$link.'" title="'.JText::_( 'SHIPPING_BOXES' ).'">'.JText::_( 'SHIPPING_BOXES' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=shipping_box_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_SHIPPING_BOXES' ).'">'.JText::_( 'ADD_SHIPPING_BOXES' ).'</a>'; ?>
				</td>
			</tr>

		</table>
		<?php
		echo $pane->endPanel();


         $title = JText::_( 'SHIPPING_DETAIL' );
		echo $pane->startPanel( $title, 'SHIPPING_DETAIL');
		?>
		<table class="adminlist">


				<tr>
				<td><?php
					$link 	=  'index.php?option=com_installer';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_SHIPPING_METHOD' ).'">'.JText::_( 'ADD_SHIPPING_METHOD' ).'</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_( 'WRAPPER' );
		echo $pane->startPanel( $title, 'WRAPPER');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=wrapper';
					echo '<a href="'.$link.'" title="'.JText::_( 'WRAPPER_LISTING' ).'">'.JText::_( 'WRAPPER_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=wrapper_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_WRAPPER' ).'">'.JText::_( 'ADD_WRAPPER' ).'</a>'; ?>
				</td>
			</tr>
			</table>
		<?php
		echo $pane->endPanel();

?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'USER' );?></td></tr></table>

		<?php

			$title = JText::_( 'USER' );
			echo $pane->startPanel( $title, 'USER'); ?>
			<table class="adminlist">

				<tr><td><?php
						$link 	=  'index.php?option='.$option.'&view=user';
						echo '<a href="'.$link.'" title="'.JText::_( 'USER_LISTING' ).'">'.JText::_( 'USER_LISTING' ).'</a>'; ?>
					</td></tr>
				<tr><td><?php
							$link 	=  'index.php?option='.$option.'&view=user_detail';
							echo '<a href="'.$link.'" title="'.JText::_( 'ADD_USER' ).'">'.JText::_( 'ADD_USER' ).'</a>'; ?>
					</td></tr>
				<tr><td><?php
							$link 	= 'index.php?option='.$option.'&view=user&sync=1';
							echo '<a href="javascript:userSync();" title="'.JText::_( 'USER_SYNC' ).'">'.JText::_( 'USER_SYNC' ).'</a>'; ?>
							<script type="text/javascript">
							function userSync(){
								if(confirm("<?php echo JText::_('DO_YOU_WANT_TO_SYNC');?>") == true)
									window.location = "<?php echo $link;?>";
							}</script>
					</td></tr>
				<tr><td><?php
							$link 	=  'index.php?option='.$option.'&view=shopper_group';
							echo '<a href="'.$link.'" title="'.JText::_( 'SHOPPER_GROUP_LISTING' ).'">'.JText::_( 'SHOPPER_GROUP_LISTING' ).'</a>'; ?>
					</td></tr>
				<tr><td><?php
							$link 	=  'index.php?option='.$option.'&view=shopper_group_detail';
							echo '<a href="'.$link.'" title="'.JText::_( 'ADD_SHOPPER_GROUP' ).'">'.JText::_( 'ADD_SHOPPER_GROUP' ).'</a>'; ?>
					</td></tr>
			</table>
		<?php	echo $pane->endPanel();

        if(ENABLE_BACKENDACCESS):
		$title = JText::_( 'ACCESS_MANAGER' );
		echo $pane->startPanel( $title, 'ACCESS_MANAGER');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=accessmanager';
					echo '<a href="'.$link.'" title="'.JText::_( 'ACCESS_MANAGER' ).'">'.JText::_( 'ACCESS_MANAGER' ).'</a>'; ?>
				</td>
			</tr>
		</table>
        <?php
		echo $pane->endPanel();
		endif;
?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'VAT_AND_CURRENCY' );?></td></tr></table>

		<?php
			$title = JText::_( 'TAX_GROUP' );
		echo $pane->startPanel( $title, 'TAX_GROUP');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=tax_group';
					echo '<a href="'.$link.'" title="'.JText::_( 'TAX_GROUP_LISTING' ).'">'.JText::_( 'TAX_GROUP_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=tax_group_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'TAX_GROUP_DETAIL' ).'">'.JText::_( 'TAX_GROUP_DETAIL' ).'</a>'; ?>
				</td>
			</tr>
			</table>
		<?php
		echo $pane->endPanel();

        $title = JText::_( 'CURRENCY' );
		echo $pane->startPanel( $title, 'CURRENCY');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=currency';
					echo '<a href="'.$link.'" title="'.JText::_( 'CURRENCY_LISTING' ).'">'.JText::_( 'CURRENCY_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
						$link 	=  'index.php?option='.$option.'&view=currency_detail';
						echo '<a href="'.$link.'" title="'.JText::_( 'ADD_CURRENCY' ).'">'.JText::_( 'ADD_CURRENCY' ).'</a>'; ?>
				</td>
			</tr>
		</table>
        <?php
		echo $pane->endPanel();
       $title = JText::_( 'COUNTRY' );
		echo $pane->startPanel( $title, 'COUNTRY');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=country';
					echo '<a href="'.$link.'" title="'.JText::_( 'COUNTRY_LISTING' ).'">'.JText::_( 'COUNTRY_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
						$link 	=  'index.php?option='.$option.'&view=country_detail';
						echo '<a href="'.$link.'" title="'.JText::_( 'ADD_COUNTRY' ).'">'.JText::_( 'ADD_COUNTRY' ).'</a>'; ?>
				</td>
			</tr>
		</table>
        <?php
		echo $pane->endPanel();

		$title = JText::_( 'STATE' );
		echo $pane->startPanel( $title, 'STATE');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=state';
					echo '<a href="'.$link.'" title="'.JText::_( 'STATE_LISTING' ).'">'.JText::_( 'STATE_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
						$link 	=  'index.php?option='.$option.'&view=state_detail';
						echo '<a href="'.$link.'" title="'.JText::_( 'ADD_STATE' ).'">'.JText::_( 'ADD_STATE' ).'</a>'; ?>
				</td>
			</tr>
		</table>
        <?php
		echo $pane->endPanel();
		$title = JText::_( 'ZIPCODE' );
		echo $pane->startPanel( $title, 'ZIPCODE');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=zipcode';
					echo '<a href="'.$link.'" title="'.JText::_( 'ZIPCODE_LISTING' ).'">'.JText::_( 'ZIPCODE_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
						$link 	=  'index.php?option='.$option.'&view=zipcode_detail';
						echo '<a href="'.$link.'" title="'.JText::_( 'ADD_ZIPCODE' ).'">'.JText::_( 'ADD_ZIPCODE' ).'</a>'; ?>
				</td>
			</tr>
		</table>
        <?php
		echo $pane->endPanel();
?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'IMPORT_EXPORT' );?></td></tr></table>
<?php

        $title = JText::_( 'IMPORT_EXPORT' );
		echo $pane->startPanel( $title, 'IMPORT_EXPORT');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
						$link 	=  'index.php?option='.$option.'&view=import';
						echo '<a href="'.$link.'" title="'.JText::_( 'DATA_IMPORT' ).'">'.JText::_( 'DATA_IMPORT' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
						$link 	=  'index.php?option='.$option.'&view=export';
						echo '<a href="'.$link.'" title="'.JText::_( 'DATA_EXPORT' ).'">'.JText::_( 'DATA_EXPORT' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
						$link 	= 'index.php?option='.$option.'&view=import&vm=1';
						echo '<a href="javascript:vmImport();" title="'.JText::_( 'IMPORT_FROM_VM' ).'">'.JText::_( 'IMPORT_FROM_VM' ).'</a>'; ?>
						<script type="text/javascript">
						function vmImport(){
							if(confirm("<?php echo JText::_('DO_YOU_WANT_TO_IMPORT_VM');?>") == true)
								window.location = "<?php echo $link;?>";
						}
						</script>
				</td>
			</tr>
			</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_( 'XML_IMPORT_EXPORT' );
		echo $pane->startPanel( $title, 'XML_IMPORT_EXPORT');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
						$link 	=  'index.php?option='.$option.'&view=xmlimport';
						echo '<a href="'.$link.'" title="'.JText::_( 'XML_IMPORT' ).'">'.JText::_( 'XML_IMPORT' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
						$link 	=  'index.php?option='.$option.'&view=xmlexport';
						echo '<a href="'.$link.'" title="'.JText::_( 'XML_EXPORT' ).'">'.JText::_( 'XML_EXPORT' ).'</a>'; ?>
				</td>
			</tr>
			</table>
		<?php
		echo $pane->endPanel();
   ?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'CUSTOMIZATION' );?></td></tr></table>

		<?php
	$title = JText::_( 'FIELDS' );
		echo $pane->startPanel( $title, 'FIELDS' );
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=fields';
					echo '<a href="'.$link.'" title="'.JText::_( 'FIELDS_LISTING' ).'">'.JText::_( 'FIELDS_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=fields_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_FIELD' ).'">'.JText::_( 'ADD_FIELD' ).'</a>'; ?>
				</td>
			</tr><!--
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=addressfields_listing';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADDRESS_FIELD_ORDERING_LISTING' ).'">'.JText::_( 'ADDRESS_FIELD_ORDERING_LISTING' ).'</a>';  ?>
				</td>
			</tr>

		--></table>
		<?php
		echo $pane->endPanel();


			$title = JText::_( 'TEMPLATE' );
			echo $pane->startPanel( $title, 'TEMPLATE');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=template';
					echo '<a href="'.$link.'" title="'.JText::_( 'TEMPLATE_LISTING' ).'">'.JText::_( 'TEMPLATE_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=template_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_TEMPLATE' ).'">'.JText::_( 'ADD_TEMPLATE' ).'</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();


		$title = JText::_( 'TEXT_LIBRARY' );
			echo $pane->startPanel( $title, 'TEXT_LIBRARY');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=textlibrary';
					echo '<a href="'.$link.'" title="'.JText::_( 'TEXT_LIBRARY_LISTING' ).'">'.JText::_( 'TEXT_LIBRARY_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=textlibrary_detail';
					echo '<a href="'.$link.'" title="'.JText::_( 'ADD_TEXT_LIBRARY_TAG' ).'">'.JText::_( 'ADD_TEXT_LIBRARY_TAG' ).'</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();



		$title = JText::_( 'CATALOG_MANAGEMENT' );
		echo $pane->startPanel( $title, 'CATALOG_MANAGEMENT');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=catalog';
					echo '<a href="'.$link.'" title="'.JText::_( 'CATALOG' ).'">'.JText::_( 'CATALOG' ).'</a>'; ?>
				</td>
			</tr>

			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=catalog_request';
					echo '<a href="'.$link.'" title="'.JText::_( 'CATALOG_REQUEST' ).'">'.JText::_( 'CATALOG_REQUEST' ).'</a>'; ?>
				</td>
			</tr>
			</table>
		<?php
		echo $pane->endPanel();

		$title = JText::_( 'COLOUR_SAMPLE_MANAGEMENT' );
		echo $pane->startPanel( $title, 'COLOUR_SAMPLE_MANAGEMENT');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=sample';
					echo '<a href="'.$link.'" title="'.JText::_( 'CATALOG_PRODUCT_SAMPLE' ).'">'.JText::_( 'CATALOG_PRODUCT_SAMPLE' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=sample_request';
					echo '<a href="'.$link.'" title="'.JText::_( 'SAMPLE_REQUEST' ).'">'.JText::_( 'SAMPLE_REQUEST' ).'</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

          	$title = JText::_( 'TAGS' );
		echo $pane->startPanel( $title, 'TAGS');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=producttags';
					echo '<a href="'.$link.'" title="'.JText::_( 'TAGS_LISTING' ).'">'.JText::_( 'TAGS_LISTING' ).'</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
			echo $pane->endPanel();



		$title = JText::_( 'ATTRIBUTE_BANK' );
		echo $pane->startPanel( $title, 'ATTRIBUTE_BANK');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=attribute_set';
					echo '<a href="'.$link.'" title="'.JText::_( 'ATTRIBUTE_SET_LISTING' ).'">'.JText::_( 'ATTRIBUTE_SET_LISTING' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
						$link 	=  'index.php?option='.$option.'&view=attribute_set_detail';
						echo '<a href="'.$link.'" title="'.JText::_( 'ADD_ATTRIBUTE_SET' ).'">'.JText::_( 'ADD_ATTRIBUTE_SET' ).'</a>'; ?>
				</td>
			</tr>
		</table>
        <?php
		echo $pane->endPanel();
		$title = JText::_( 'INTEGRATION' );
		echo $pane->startPanel( $title, 'INTEGRATION');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=integration&task=googlebase';
					echo '<a href="'.$link.'" title="'.JText::_( 'GOOGLEBASE' ).'">'.JText::_( 'GOOGLEBASE' ).'</a>'; ?>
				</td>
			</tr>
		</table>
        <?php
		echo $pane->endPanel();

   ?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'CUSTOMER_INPUT' );?></td></tr></table>

		<?php

        	$title = JText::_( 'QUESTION' );
		echo $pane->startPanel( $title, 'QUESTION');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=question';
					echo '<a href="'.$link.'" title="'.JText::_( 'QUESTION_LISTING' ).'">'.JText::_( 'QUESTION_LISTING' ).'</a>'; ?>
				</td>
			</tr>
		</table>
        <?php
		echo $pane->endPanel();


	    $title = JText::_( 'REVIEW' );
		echo $pane->startPanel( $title, 'REVIEW');
		?>
		<table class="adminlist">

	         <tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=rating';
					echo '<a href="'.$link.'" title="'.JText::_( 'RATING_REVIEW' ).'">'.JText::_( 'RATING_REVIEW' ).'</a>'; ?>
				</td>
			</tr>

		</table>
		<?php
		echo $pane->endPanel();



	if(ECONOMIC_INTEGRATION)
		{	?>
		<table><tr><td class="distitle"><?php echo JText::_( 'ACCOUNTING' );?></td></tr></table>
<?php
			$title = JText::_( 'ECONOMIC_ACCOUNT_GROUP' );
			echo $pane->startPanel( $title, 'ECONOMIC_ACCOUNT_GROUP');
			?>
			<table class="adminlist">
				<tr>
					<td><?php
							$link 	=  'index.php?option='.$option.'&view=accountgroup';
							echo '<a href="'.$link.'" title="'.JText::_( 'ACCOUNTGROUP_LISTING' ).'">'.JText::_( 'ACCOUNTGROUP_LISTING' ).'</a>'; ?>
					</td>
				</tr>
				<tr>
					<td><?php
							$link 	=  'index.php?option='.$option.'&view=accountgroup_detail';
							echo '<a href="'.$link.'" title="'.JText::_( 'ADD_ACCOUNTGROUP' ).'">'.JText::_( 'ADD_ACCOUNTGROUP' ).'</a>'; ?>
					</td>
				</tr>
				</table>
			<?php
			echo $pane->endPanel();
		}




?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'STATISTIC' );?></td></tr></table>
<?php
	$title = JText::_( 'STATISTIC' );
		echo $pane->startPanel( $title, 'STATISTIC' );
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	= JRoute::_( 'index.php?option='.$option.'&view=statistic');
					echo '<a href="'.$link.'" title="'.JText::_( 'TOTAL_VISITORS' ).'">'.JText::_( 'TOTAL_VISITORS' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	= JRoute::_( 'index.php?option='.$option.'&view=statistic&layout=pageview');
					echo '<a href="'.$link.'" title="'.JText::_( 'TOTAL_PAGEVIEWERS' ).'">'.JText::_( 'TOTAL_PAGEVIEWERS' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	= JRoute::_( 'index.php?option='.$option.'&view=statistic&layout=turnover');
					echo '<a href="'.$link.'" title="'.JText::_( 'TOTAL_TURNOVER' ).'">'.JText::_( 'TOTAL_TURNOVER' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	= JRoute::_( 'index.php?option='.$option.'&view=statistic&layout=avrgorder');
					echo '<a href="'.$link.'" title="'.JText::_( 'AVG_ORDER_AMOUNT_CUSTOMER' ).'">'.JText::_( 'AVG_ORDER_AMOUNT_CUSTOMER' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	= JRoute::_( 'index.php?option='.$option.'&view=statistic&layout=amountorder');
					echo '<a href="'.$link.'" title="'.JText::_( 'TOP_CUSTOMER_AMOUNT_OF_ORDER' ).'">'.JText::_( 'TOP_CUSTOMER_AMOUNT_OF_ORDER' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	= JRoute::_( 'index.php?option='.$option.'&view=statistic&layout=amountprice');
					echo '<a href="'.$link.'" title="'.JText::_( 'TOP_CUSTOMER_AMOUNT_OF_PRICE_PER_ORDER' ).'">'.JText::_( 'TOP_CUSTOMER_AMOUNT_OF_PRICE_PER_ORDER' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	= JRoute::_( 'index.php?option='.$option.'&view=statistic&layout=amountspent');
					echo '<a href="'.$link.'" title="'.JText::_( 'TOP_CUSTOMER_AMOUNT_SPENT_IN_TOTAL' ).'">'.JText::_( 'TOP_CUSTOMER_AMOUNT_SPENT_IN_TOTAL' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	= JRoute::_( 'index.php?option='.$option.'&view=statistic&layout=bestsell');
					echo '<a href="'.$link.'" title="'.JText::_( 'BEST_SELLERS' ).'">'.JText::_( 'BEST_SELLERS' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	= JRoute::_( 'index.php?option='.$option.'&view=statistic&layout=popularsell');
					echo '<a href="'.$link.'" title="'.JText::_( 'MOST_VISITED_PRODUCTS' ).'">'.JText::_( 'MOST_VISITED_PRODUCTS' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	= JRoute::_( 'index.php?option='.$option.'&view=statistic&layout=newprod');
					echo '<a href="'.$link.'" title="'.JText::_( 'NEWEST_PRODUCTS' ).'">'.JText::_( 'NEWEST_PRODUCTS' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	= JRoute::_( 'index.php?option='.$option.'&view=statistic&layout=neworder');
					echo '<a href="'.$link.'" title="'.JText::_( 'NEWEST_ORDERS' ).'">'.JText::_( 'NEWEST_ORDERS' ).'</a>'; ?>
				</td>
			</tr>

		</table>



		<?php
		echo $pane->endPanel();


	?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'CONFIG' );?></td></tr></table>

		<?php
		$title = JText::_( 'CONFIG' );
		echo $pane->startPanel( $title, 'CONFIG');
		?>
		<table class="adminlist">
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=configuration';
					echo '<a href="'.$link.'" title="'.JText::_( 'RESHOP_CONFIGURATION' ).'">'.JText::_( 'RESHOP_CONFIGURATION' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&wizard=1';
					echo '<a href="'.$link.'" title="'.JText::_( 'START_CONFIGURATION_WIZARD' ).'">'.JText::_( 'START_CONFIGURATION_WIZARD' ).'</a>'; ?>
				</td>
			</tr>
			<tr>
				<td><?php
					$link 	=  'index.php?option='.$option.'&view=configuration&layout=resettemplate';
					echo '<a href="'.$link.'" title="'.JText::_( 'RESET_TEMPLATE_LBL' ).'">'.JText::_( 'RESET_TEMPLATE_LBL' ).'</a>'; ?>
				</td>
			</tr>
		</table>
		<?php
		echo $pane->endPanel();

	if(JPluginHelper::isEnabled('redshop_custom_views','rs_custom_views_date') || JPluginHelper::isEnabled('redshop_custom_views','rs_custom_views_person') || JPluginHelper::isEnabled('redshop_custom_views','rs_custom_views_company'))
		{

			?>
		  <table><tr><td class="distitle"><?php echo JText::_( 'CUSTOM_VIEWS' );?></td></tr></table>

		<?php
			$title = JText::_( 'CUSTOM_VIEWS' );
			echo $pane->startPanel( $title, 'CUSTOM_VIEWS');
			?>
			<table class="adminlist">
				<?php
				if(JPluginHelper::isEnabled('redshop_custom_views','rs_custom_views_date'))
				{
				?>
					<tr>
						<td><?php
							$link 	= JRoute::_( 'index.php?option='.$option.'&view=customprint&printoption=rs_custom_views_date');
							echo '<a href="'.$link.'" title="'.JText::_( 'CUSTOM_VIEWS_DATE' ).'">'.JText::_( 'CUSTOM_VIEWS_DATE' ).'</a>'; ?>
						</td>
					</tr>
				<?php
				}
				?>

				<?php
				if(JPluginHelper::isEnabled('redshop_custom_views','rs_custom_views_person'))
				{
				?>
					<tr>
						<td><?php
							$link 	= JRoute::_( 'index.php?option='.$option.'&view=customprint&printoption=rs_custom_views_person');
							echo '<a href="'.$link.'" title="'.JText::_( 'CUSTOM_VIEWS_PERSON' ).'">'.JText::_( 'CUSTOM_VIEWS_PERSON' ).'</a>'; ?>
						</td>
					</tr>
				<?php
				}
				?>

				<?php
				if(JPluginHelper::isEnabled('redshop_custom_views','rs_custom_views_company'))
				{
				?>
					<tr>
						<td><?php
							$link 	= JRoute::_( 'index.php?option='.$option.'&view=customprint&printoption=rs_custom_views_company');
							echo '<a href="'.$link.'" title="'.JText::_( 'CUSTOM_VIEWS_COMPANY' ).'">'.JText::_( 'CUSTOM_VIEWS_COMPANY' ).'</a>'; ?>
						</td>
					</tr>
				<?php
				}
				?>
			</table>
	        <?php
			echo $pane->endPanel();
		}

		echo '</div>';
	}
}
?>

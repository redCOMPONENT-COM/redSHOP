<?php
class zoomproducthelper extends producthelper{


	function replaceProductImage($product,$imagename="",$linkimagename="",$link="",$width,$height,$Product_detail_is_light=2,$enableHover=0,$preselectedResult=array(),$suffixid=0)
	{
		$url = JURI::root();
		$imagename = trim($imagename);
		$linkimagename = trim($linkimagename);
		$product_id = $product->product_id;
		$redhelper   = new redhelper();

		$middlepath = REDSHOP_FRONT_IMAGES_RELPATH."product/";
		$product_image = $product->product_full_image;
		if($Product_detail_is_light != 2 )
		{
			if ($product->product_thumb_image && is_file ( $middlepath.$product->product_thumb_image ))
			{
				$product_image = $product->product_thumb_image;
			}
		}

		$altText = $this->getAltText ( 'product', $product_id, $product_image );
		if ($altText) {
			$product->product_name = $altText;
		}

		$title = " title='".$product->product_name."' ";
		$alt = " alt='".$product->product_name."' ";

		$cat_product_hover = false;
		if($enableHover && PRODUCT_HOVER_IMAGE_ENABLE)
		{
			$cat_product_hover = true;
		}

		$noimage = "noimage.jpg";

		$product_img = REDSHOP_FRONT_IMAGES_ABSPATH.$noimage;

		$product_hover_img = REDSHOP_FRONT_IMAGES_ABSPATH.$noimage;
		$linkimage = REDSHOP_FRONT_IMAGES_ABSPATH.$noimage;
		if($imagename!="")
		{
			$product_img = $redhelper->watermark('product',$imagename,$width,$height,WATERMARK_PRODUCT_THUMB_IMAGE,'0');
			if($cat_product_hover)
				 $product_hover_img = $redhelper->watermark('product',$imagename,PRODUCT_HOVER_IMAGE_WIDTH,PRODUCT_HOVER_IMAGE_HEIGHT,WATERMARK_PRODUCT_THUMB_IMAGE,'2');
			if($linkimagename!="")
			{
				$linkimage = $redhelper->watermark('product',$linkimagename,'','',WATERMARK_PRODUCT_IMAGE,'0');
			}
			else
			{
				$linkimage = $redhelper->watermark('product',$imagename,'','',WATERMARK_PRODUCT_IMAGE,'0');
				//$linkimage = $redhelper->watermark('product',$imagename,$width,$height,WATERMARK_PRODUCT_IMAGE);
			}
		}

		if(count($preselectedResult)>0)
		{
			//$product_img = $preselectedResult['mainImageResponse'];
			$product_img = $preselectedResult['product_mainimg'];
			$title = " title='".$preselectedResult['aTitleImageResponse']."' ";
			$linkimage = $preselectedResult['aHrefImageResponse'];
		}

		//Zoom start
		$commonid = ($suffixid) ? $product_id.'_'.$suffixid : $product_id;

		$thum_image = "<a class='jqzoom' href='".$linkimage."' rel=\"gal1\" id='zoom_img' '.$title.'><img id='main_image".$commonid."' src='".$product_img."' ".$title.$alt." /></a>";
		$thum_image = "<div class='clearfix redzoom'>".$thum_image."</div>";
		//Zoom end
		return $thum_image;
	}

	function getAdditionalImageforZoom($pid){

		$redhelper = new redhelper();
		$url = JUri::root();
		$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
		$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
		$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT;
		$pw_thumb = PRODUCT_MAIN_IMAGE;

		$media_image = $this->getAdditionMediaImage($pid,"product");
		$more_images = '';

		for($m=0; $m<count($media_image); $m++)
		{
			 $filename1=REDSHOP_FRONT_IMAGES_RELPATH."product/".$media_image[$m]->media_name;
			if ($media_image[$m]->media_name != $media_image[$m]->product_full_image && is_file($filename1))
			{
				$alttext = $this->getAltText('product', $media_image[$m]->section_id, '', $media_image[$m]->media_id );
				if (! $alttext) {
					$alttext = $media_image [$m]->media_name;
				}
				if ($media_image [$m]->media_name)
				{
					$thumb = $media_image [$m]->media_name;
						if(WATERMARK_PRODUCT_ADDITIONAL_IMAGE)
						{
							$pimg = $redhelper->watermark('product',$thumb,$mpw_thumb,$mph_thumb,WATERMARK_PRODUCT_ADDITIONAL_IMAGE,"1");
							$linkimage = $redhelper->watermark('product',$thumb,'','',WATERMARK_PRODUCT_ADDITIONAL_IMAGE,"0");
							$hoverimg_path=$redhelper->watermark('product',$thumb,ADDITIONAL_HOVER_IMAGE_WIDTH,ADDITIONAL_HOVER_IMAGE_HEIGHT,WATERMARK_PRODUCT_ADDITIONAL_IMAGE,'2');

						}
						else
						{
							$pimg=$url."components/com_redshop/helpers/thumb.php?filename=product/".$thumb."&newxsize=".$mpw_thumb."&newysize=".$mph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
							$linkimage=$url."components/com_redshop/assets/images/product/".$thumb;
						   $hoverimg_path=$url."components/com_redshop/helpers/thumb.php?filename=product/".$thumb."&newxsize=".ADDITIONAL_HOVER_IMAGE_WIDTH."&newysize=".ADDITIONAL_HOVER_IMAGE_HEIGHT."&swap=".USE_IMAGE_SIZE_SWAPPING;
						}

						if(WATERMARK_PRODUCT_ADDITIONAL_IMAGE)
							$img_path=$redhelper->watermark('product',$thumb,$pw_thumb,$ph_thumb,WATERMARK_PRODUCT_ADDITIONAL_IMAGE,'0');
						else
							$img_path=$url."components/com_redshop/helpers/thumb.php?filename=product/".$thumb."&newxsize=".$pw_thumb."&newysize=".$ph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
						$hovermore_images = $redhelper->watermark('product',$thumb,'','',WATERMARK_PRODUCT_ADDITIONAL_IMAGE,'0');

						$filename_org=JPATH_COMPONENT_SITE . "/assets/images/product/".$media_image[$m]->product_full_image;
						if(is_file($filename_org))
						{
							$thumb_original=$media_image[$m]->product_full_image;
						}
						else
						{
							$thumb_original=PRODUCT_DEFAULT_IMAGE;
						}
						if(WATERMARK_PRODUCT_THUMB_IMAGE)
							$img_path_org = $redhelper->watermark('product',$thumb_original,$pw_thumb,$ph_thumb,WATERMARK_PRODUCT_THUMB_IMAGE,'0');
						else
							$img_path_org=$url."components/com_redshop/helpers/thumb.php?filename=product/".$thumb_original."&newxsize=".$mpw_thumb."&newysize=".$mph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;

						$hovermore_org			=  $url."components/com_redshop/helpers/thumb.php?filename=product/".$thumb_original."&newxsize=".$pw_thumb."&newysize=".$ph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
						$prod_img_path_link=$url."components/com_redshop/assets/images/product/".$thumb;
						$more_images .= '<a href="javascript:void(0);" rel="{gallery: \'gal1\' , smallimage: \''.$img_path.'\' ,largeimage: \''.$prod_img_path_link.'\' }" >';
						$more_images .="<img src='".$pimg."' alt='".$alttext."' title='".$alttext."' style='cursor: pointer;'></a>";

				}
			}
		}

		if(count($media_image)>0)
		{
			if(count($media_image)==1)
			{
				//$filename_org=JPATH_COMPONENT_SITE . "/assets/images/product/".$product->product_full_image;
				$filename_org=JPATH_COMPONENT_SITE . "/assets/images/product/".$this->data->product_full_image;
				if(is_file($filename_org))
				{
					$thumb_original=$this->data->product_full_image;
				}
				/*else
				{
							$thumb_original=PRODUCT_DEFAULT_IMAGE;
				}*/
				if(WATERMARK_PRODUCT_THUMB_IMAGE)
				$img_path_org = $redhelper->watermark('product',$thumb_original,$mpw_thumb,$mph_thumb,WATERMARK_PRODUCT_THUMB_IMAGE,'0');
				else
				$img_path_org=$url."components/com_redshop/helpers/thumb.php?filename=product/".$thumb_original."&newxsize=".$mpw_thumb."&newysize=".$mph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
			}
			$prodmainimg="";
			$prod_img_path=$url."components/com_redshop/helpers/thumb.php?filename=product/".$thumb_original."&newxsize=".$pw_thumb."&newysize=".$ph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
			$prod_img_path_link=$url."components/com_redshop/assets/images/product/".$thumb_original;
			$prodmainimg .= '<a href="javascript:void(0);" rel="{gallery: \'gal1\' , smallimage: \''.$prod_img_path.'\' ,largeimage: \''.$prod_img_path_link.'\' }" >';
			$prodmainimg .="<img src='".$img_path_org."' alt='".$alttext."' title='".$alttext."' style='cursor: pointer;'></a>";
			if(is_file ( JPATH_COMPONENT_SITE . "/assets/images/product/".$thumb_original))
			$more_images .=$prodmainimg;
		}

		if(trim($more_images) != "")
			$more_images = '<div class="additional_image">'.$more_images.'</div>';

		return $more_images;
	}

	function displayAdditionalImage($product_id=0, $accessory_id=0, $relatedprd_id=0, $property_id=0, $subproperty_id=0, $main_imgwidth=0, $main_imgheight=0, $redview="", $redlayout="")
	{
		$redTemplate = new Redtemplate ();
		$stockroomhelper = new rsstockroomhelper();

		$uri	         =& JURI::getInstance();
		$url = $uri->toString( array('scheme', 'host', 'port'));
		$path = explode("/",$uri->getPath());

		if($path[1] != "plugins") $url .= "/".$path[1]."/"; else	$url .="/";

		$option = JRequest::getVar ( 'option','com_redshop' );
		$redhelper   = new redhelper();
		if($accessory_id!=0)
		{
			$accessory = $this->getProductAccessory($accessory_id);
			$product_id = $accessory[0]->child_product_id;
		}
		$product = $this->getProductById($product_id);
		$title = " title='".$product->product_name."' ";
		$producttemplate = $redTemplate->getTemplate ( "product", $product->product_template );

		// get template for stockroom status
	    if($accessory_id != 0)
		{
	        $template_desc =  $redTemplate->getTemplate ( "accessory_product" );
	        $template_desc =$template_desc[0]->template_desc;
		}else if($relatedprd_id!=0) {
			$template_desc =  $redTemplate->getTemplate ( "related_product" );
			$template_desc =$template_desc[0]->template_desc;
		} else {
	         $template_desc =  $producttemplate[0]->template_desc;
		}
		// End
		$producttemplate = $producttemplate[0]->template_desc;
		if($redlayout == 'categoryproduct' || $redlayout == 'detail')
		{
				if (strstr ( $producttemplate, "{product_thumb_image_3}" )) {
					$pimg_tag = '{product_thumb_image_3}';
					$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_3;
					$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_3;
				} elseif (strstr ( $producttemplate, "{product_thumb_image_2}" )) {
					$pimg_tag = '{product_thumb_image_2}';
					$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT_2;
					$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH_2;
				} elseif (strstr ( $producttemplate, "{product_thumb_image_1}" )) {
					$pimg_tag = '{product_thumb_image_1}';
					$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT;
					$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH;
				} else {
					$pimg_tag = '{product_thumb_image}';
					$ph_thumb = CATEGORY_PRODUCT_THUMB_HEIGHT;
					$pw_thumb = CATEGORY_PRODUCT_THUMB_WIDTH;
				}

		}
		else
		{
				if (strstr ( $producttemplate, "{product_thumb_image_3}" )) {
					$pimg_tag = '{product_thumb_image_3}';
					$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT_3;
					$pw_thumb = PRODUCT_MAIN_IMAGE_3;
				} elseif (strstr ( $producttemplate, "{product_thumb_image_2}" )) {
					$pimg_tag = '{product_thumb_image_2}';
					$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT_2;
					$pw_thumb = PRODUCT_MAIN_IMAGE_2;
				} elseif (strstr ( $producttemplate, "{product_thumb_image_1}" )) {
					$pimg_tag = '{product_thumb_image_1}';
					$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT;
					$pw_thumb = PRODUCT_MAIN_IMAGE;
				} else {
					$pimg_tag = '{product_thumb_image}';
					$ph_thumb = PRODUCT_MAIN_IMAGE_HEIGHT;
					$pw_thumb = PRODUCT_MAIN_IMAGE;
				}
		}

		if (strstr ( $producttemplate, "{more_images_3}" )) {
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_3;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_3;
		} elseif (strstr ( $producttemplate, "{more_images_2}" )) {
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT_2;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE_2;
		} elseif (strstr ( $producttemplate, "{more_images_1}" )) {
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
		} else {
			$mph_thumb = PRODUCT_ADDITIONAL_IMAGE_HEIGHT;
			$mpw_thumb = PRODUCT_ADDITIONAL_IMAGE;
		}
		if($main_imgwidth!=0 || $main_imgheight!=0)
		{
			$pw_thumb = $main_imgwidth;
			$ph_thumb = $main_imgheight;
		}
		$ImageAttributes  		= $this->getdisplaymainImage($product_id,$property_id,$subproperty_id,$pw_thumb,$ph_thumb,$redview);
		$aHrefImageResponse 	= $ImageAttributes['aHrefImageResponse'];
		$mainImageResponse		= $ImageAttributes['mainImageResponse'];
		$productmainimg			= $ImageAttributes['productmainimg'];
		$aTitleImageResponse	= $ImageAttributes['aTitleImageResponse'];
		$imagename				= $ImageAttributes['imagename'];
		$attrbimg				= $ImageAttributes['attrbimg'];
		$pr_number				= $ImageAttributes['pr_number'];
		$prodadditionImg		= "";
		$propadditionImg 		= "";
		$subpropadditionImg 	= "";

		$media_image = $this->getAdditionMediaImage($product_id,"product");
		$tmp_prodimg="";

		$val_prodadd=count($media_image);
		//Product Additional Image Start
			for($m = 0; $m < count ( $media_image ); $m ++)
			{
				$thumb = $media_image [$m]->media_name;
				$alttext = $this->getAltText ( 'product', $media_image [$m]->section_id, '', $media_image [$m]->media_id );
				if (! $alttext)
				{
					$alttext = $media_image [$m]->media_name;
				}
				if ($media_image [$m]->media_name != $media_image [$m]->product_full_image)
				{
					if ($media_image [$m]->media_name && is_file ( JPATH_COMPONENT_SITE . "/assets/images/product/".$media_image[$m]->media_name ))
					{
						if(WATERMARK_PRODUCT_ADDITIONAL_IMAGE)
							{
								$pimg = $redhelper->watermark('product',$thumb,$mpw_thumb,$mph_thumb,WATERMARK_PRODUCT_ADDITIONAL_IMAGE,"1");
								$linkimage = $redhelper->watermark('product',$thumb,'','',WATERMARK_PRODUCT_ADDITIONAL_IMAGE,"0");
							}
							else
							{
								$pimg=$url."components/com_redshop/helpers/thumb.php?filename=product/".$thumb."&newxsize=".$mpw_thumb."&newysize=".$mph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
								$linkimage=$url."components/com_redshop/assets/images/product/".$thumb;
							}
								if(WATERMARK_PRODUCT_ADDITIONAL_IMAGE)
									$img_path=$redhelper->watermark('product',$thumb,$pw_thumb,$ph_thumb,WATERMARK_PRODUCT_ADDITIONAL_IMAGE,'0');
								else
									$img_path=$url."components/com_redshop/helpers/thumb.php?filename=product/".$thumb."&newxsize=".$pw_thumb."&newysize=".$ph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
								$filename_thumb=JPATH_COMPONENT_SITE . "/assets/images/product/".$product->product_thumb_image;
								$filename_org=JPATH_COMPONENT_SITE . "/assets/images/product/".$media_image [$m]->product_full_image;
								if(is_file($filename_thumb))
								{
											$thumb_original=$product->product_thumb_image;
								}
								else if(is_file($filename_org))
								{
											$thumb_original=$media_image[$m]->product_full_image;
							    }
							    else
								{
											$thumb_original=PRODUCT_DEFAULT_IMAGE;
								}
								if(WATERMARK_PRODUCT_THUMB_IMAGE)
								$img_path_org = $redhelper->watermark('product',$thumb_original,$mpw_thumb,$mph_thumb,WATERMARK_PRODUCT_THUMB_IMAGE,'0');
								else
							    $img_path_org=$url."components/".$option."/helpers/thumb.php?filename=product/".$thumb_original."&newxsize=".$mpw_thumb."&newysize=".$mph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
							    $zoom_original=$url."components/com_redshop/assets/images/product/".$media_image[$m]->media_name;
								$prodadditionImg .= '<a href="javascript:void(0);" rel="{gallery: \'gal1\' , smallimage: \''.$img_path.'\' ,largeimage: \''.$zoom_original.'\' }" >';
								$prodadditionImg .="<img src='".$pimg."' alt='".$alttext."' title='".$alttext."' style='cursor: pointer;'></a>";
								$tmp_prodimg=$prodadditionImg;
					}
				}
			}
			if($val_prodadd>0)
			{
				if($val_prodadd==1)
				{
					$filename_thumb=JPATH_COMPONENT_SITE . "/assets/images/product/".$product->product_thumb_image;
					$filename_org=JPATH_COMPONENT_SITE . "/assets/images/product/".$product->product_full_image;
					$prodadditionImg="";
					if(is_file($filename_thumb))
					{
						$thumb_original=$product->product_thumb_image;
					}
					else if(is_file($filename_org))
					{
						$thumb_original=$product->product_full_image;
					}
					else
					{
						$thumb_original=PRODUCT_DEFAULT_IMAGE;
					}
					if(WATERMARK_PRODUCT_THUMB_IMAGE)
					$img_path_org = $redhelper->watermark('product',$thumb_original,$mpw_thumb,$mph_thumb,WATERMARK_PRODUCT_THUMB_IMAGE,'0');
					else
					$img_path_org=$url."components/".$option."/helpers/thumb.php?filename=product/".$thumb_original."&newxsize=".$mpw_thumb."&newysize=".$mph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
				}
				$prodmainimg="";
				$prod_img_path=$url."components/com_redshop/helpers/thumb.php?filename=product/".$thumb_original."&newxsize=".$pw_thumb."&newysize=".$ph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
				$prod_img_path_link=$url."components/com_redshop/assets/images/product/".$thumb_original;

				$prodmainimg = '<a href="javascript:void(0);" rel="{gallery: \'gal1\' , smallimage: \''.$prod_img_path.'\' ,largeimage: \''.$prod_img_path_link.'\' }" >';
				$prodmainimg .="<img src='".$img_path_org."' alt='".$alttext."' title='".$alttext."' style='cursor: pointer;'></a>";
				if(is_file ( JPATH_COMPONENT_SITE . "/assets/images/product/".$thumb_original))
				$prodadditionImg .= $prodmainimg;


			}
		//Product Additional Image End
		if($val_prodadd==0)
		{
			$prodadditionImg=" ";
			$propadditionImg=" ";
		}
		//Property Additional Image Start
		if($property_id>0)
		{
			$media_image = $this->getAdditionMediaImage($property_id,"property");
			$prop_add_img=count($media_image);
			$property_filename_org=JPATH_COMPONENT_SITE . "/assets/images/property/".$imagename;
			if(is_file($property_filename_org))
			{
						$property_thumb_original=$imagename;
						$property_img_path_org=$url."components/".$option."/helpers/thumb.php?filename=property/".$property_thumb_original."&newxsize=".$pw_thumb."&newysize=".$ph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
						$prop_mainimg_path=$url."components/".$option."/helpers/thumb.php?filename=property/".$property_thumb_original."&newxsize=".$mpw_thumb."&newysize=".$mph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
						$prod_img_path_link=$url."components/com_redshop/assets/images/property/".$property_thumb_original;
			}
			else
			{
						$property_thumb_original=$thumb_original;
						$property_img_path_org=$url."components/".$option."/helpers/thumb.php?filename=product/".$property_thumb_original."&newxsize=".$pw_thumb."&newysize=".$ph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
						$prop_mainimg_path=$url."components/".$option."/helpers/thumb.php?filename=product/".$property_thumb_original."&newxsize=".$mpw_thumb."&newysize=".$mph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
						$prod_img_path_link=$url."components/com_redshop/assets/images/product/".$property_thumb_original;
			}
		   if(count($media_image)==0)
           {
           		$propadditionImg=$tmp_prodimg;
           		$prop_mainimg="";
           		if($val_prodadd>0)
           		{
	           		$prop_mainimg .= '<a href="javascript:void(0);" rel="{gallery: \'gal1\' , smallimage: \''.$property_img_path_org.'\' ,largeimage: \''.$prod_img_path_link.'\' }" >';
					$prop_mainimg .="<img src='".$prop_mainimg_path."' alt='".$alttext."' title='".$alttext."' style='cursor: pointer;'></a>";
					$propadditionImg .=$prop_mainimg;
           		}
           }
           else
           {
          	   	for($m = 0; $m < count ( $media_image ); $m ++)
				{
		 			$thumb = $media_image [$m]->media_name;
					$alttext = $this->getAltText ( 'property', $media_image [$m]->section_id, '', $media_image [$m]->media_id );
					if (! $alttext)
					{
						$alttext = $thumb;
					}
					if ($thumb!=$media_image [$m]->property_image)
					{
						if ($thumb && is_file ( JPATH_COMPONENT_SITE . "/assets/images/property/".$thumb))
						{
								$imgs_path=$url."components/com_redshop/helpers/thumb.php?filename=property/".$thumb."&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
							    $prop_add_path_link=$url."components/com_redshop/assets/images/property/".$thumb;
								//zoom start
								$zoom_original_prop=$url."components/com_redshop/assets/images/product/".$media_image[$m]->media_name;
						    	//zoom end
								$propadditionImg .= '<a href="javascript:void(0);" rel="{gallery: \'gal1\' , smallimage: \''.$imgs_path.'\' ,largeimage: \''.$prop_add_path_link.'\' }" >';
								$propadditionImg .= "<img src='".$url."components/com_redshop/helpers/thumb.php?filename=property/".$thumb."&newxsize=" . $mpw_thumb . "&newysize=" . $mph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING . "' alt='" . $alttext . "' title='" . $alttext . "' style='cursor: pointer;'></a>";
							$tmppropadditionImg=$propadditionImg;
						}
					}
				}
				//zoom start
				$prop_mainimg="";
				$prop_mainimg .= '<a href="javascript:void(0);" rel="{gallery: \'gal1\' , smallimage: \''.$property_img_path_org.'\' ,largeimage: \''.$prod_img_path_link.'\' }" >';
				$prop_mainimg .="<img src='".$prop_mainimg_path."' alt='".$alttext."' title='".$alttext."' style='cursor: pointer;'></a>";
				$propadditionImg .=$prop_mainimg;
				//zoom end
			}
		}
		//Property Additional Image End

		//Sub-Property Additional Image Start
		if($subproperty_id>0)
		{
			//Display Sub-Property Number
			$media_image = $this->getAdditionMediaImage($subproperty_id,"subproperty");
			$subproperty_filename_org=JPATH_COMPONENT_SITE . "/assets/images/subcolor/".$imagename;
		    if(is_file($subproperty_filename_org))
			{
					$subproperty_thumb_original=$media_image [0]->subattribute_color_image;
					$subproperty_img_path_org=$url."components/".$option."/helpers/thumb.php?filename=subcolor/".$imagename."&newxsize=".$pw_thumb."&newysize=".$ph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
					$subprop_mainimg_path=$url."components/".$option."/helpers/thumb.php?filename=subcolor/".$imagename."&newxsize=".$mpw_thumb."&newysize=".$mph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
					$subprod_img_path_link=$url."components/com_redshop/assets/images/subcolor/".$imagename;
			}
			else
			{
					$subproperty_img_path_org=$property_img_path_org;
					$subprop_mainimg_path=$prop_mainimg_path;
					$subprod_img_path_link=$prod_img_path_link;
			}
			if(count($media_image)==0)
           	{
           		$subpropadditionImg=$tmppropadditionImg;
           		if($val_prodadd>0 || $prop_add_img>0)
           		{
           			$subprop_mainimg .= '<a href="javascript:void(0);" rel="{gallery: \'gal1\' , smallimage: \''.$subproperty_img_path_org.'\' ,largeimage: \''.$subprod_img_path_link.'\' }" >';
					$subprop_mainimg .="<img src='".$subprop_mainimg_path."' alt='".$alttext."' title='".$alttext."' style='cursor: pointer;'></a>";
					$subpropadditionImg .=$subprop_mainimg;
           		}

          	}
          	else
          	{
				for($m = 0; $m < count ( $media_image ); $m ++)
				{
					$thumb = $media_image [$m]->media_name;
					$alttext = $this->getAltText ( 'subproperty', $media_image [$m]->section_id, '', $media_image [$m]->media_id );
					if (! $alttext) {
						$alttext = $thumb;
					}
					if ($thumb!=$media_image [$m]->subattribute_color_image)
					{
						if ($thumb && is_file ( JPATH_COMPONENT_SITE . "/assets/images/property/".$thumb))
						{

								$imgs_path=$url."components/com_redshop/helpers/thumb.php?filename=property/".$thumb."&newxsize=" . $pw_thumb . "&newysize=" . $ph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
						    	$imgs_thumb_path=$url."components/com_redshop/helpers/thumb.php?filename=property/".$thumb."&newxsize=" . $mpw_thumb . "&newysize=" . $mph_thumb . "&swap=" . USE_IMAGE_SIZE_SWAPPING;
						    	$subimgs_path=$url."components/com_redshop/assets/images/property/".$thumb;
								$subpropadditionImg .= '<a href="javascript:void(0);" rel="{gallery: \'gal1\' , smallimage: \''.$imgs_path.'\' ,largeimage: \''.$subimgs_path.'\' }" >';
								$subpropadditionImg .= "<img src='".$imgs_thumb_path."' alt='" . $alttext . "' title='" . $alttext . "' style='cursor: pointer;'></a>";
						}
					}
				}

				$subprop_mainimg="";
				$subprop_mainimg .= '<a href="javascript:void(0);" rel="{gallery: \'gal1\' , smallimage: \''.$subproperty_img_path_org.'\' ,largeimage: \''.$subprod_img_path_link.'\' }" >';
				$subprop_mainimg .="<img src='".$subprop_mainimg_path."' alt='".$alttext."' title='".$alttext."' style='cursor: pointer;'></a>";
				$subpropadditionImg .=$subprop_mainimg;
				//zoom end
          	}

		}
		//Sub-Property Additional Image End
		$response = "";
		if($subpropadditionImg!="")
		{	if(trim($subpropadditionImg) != "")
			$response = $subpropadditionImg;
		}
		else if($propadditionImg!="")
		{
			if(trim($propadditionImg) != "")
			$response = $propadditionImg;
		}
		else if($prodadditionImg!="")
		{
			if(trim($prodadditionImg) != "")
			$response =$prodadditionImg;
		}

		$ProductAttributeDelivery = "";
		$attributeFlag = false;
		if($accessory_id==0)
		{
			if($subproperty_id)
			{
				$ProductAttributeDelivery = $this->getProductMinDeliveryTime($product_id,$subproperty_id,"subproperty",0);
				if($ProductAttributeDelivery)
					$attributeFlag = true;
			}
			if($property_id && $attributeFlag == false)
			{
				$ProductAttributeDelivery = $this->getProductMinDeliveryTime($product_id,$property_id,"property",0);
				if($ProductAttributeDelivery)
					$attributeFlag = true;
			}
			if($product_id && $attributeFlag == false)
			{
				$ProductAttributeDelivery = $this->getProductMinDeliveryTime($product_id);
			}
		}

		$stock_status = '';
		$stockamountTooltip = "";
		$productinstock = 0;
		$stockamountSrc = "";
		$stockImgFlag = false;
		if(USE_STOCKROOM==1 && $accessory_id==0)
		{
			if($subproperty_id)
			{
				$productinstock = $stockroomhelper->getStockAmountwithReserve($subproperty_id,"subproperty");
				$stockamountList = $stockroomhelper->getStockAmountImage($subproperty_id,"subproperty",$productinstock);
//				if(count($stockamountList)>0)
//				{
					$stockImgFlag = true;
//				}
			}
			if($property_id && $stockImgFlag == false)
			{
				$productinstock = $stockroomhelper->getStockAmountwithReserve($property_id,"property");
				$stockamountList = $stockroomhelper->getStockAmountImage($property_id,"property",$productinstock);
//				if(count($stockamountList)>0)
//				{
					$stockImgFlag = true;
//				}
			}
			if($product_id && $stockImgFlag == false)
			{
				$productinstock = $stockroomhelper->getStockAmountwithReserve($product_id);
				$stockamountList = $stockroomhelper->getStockAmountImage($product_id,"product",$productinstock);
			}
			if(count($stockamountList)>0)
			{
				$stockamountTooltip = $stockamountList[0]->stock_amount_image_tooltip;
				$stockamountSrc = $url.'components/com_redshop/assets/images/stockroom'.DS.$stockamountList[0]->stock_amount_image;
			}
		}

		// stockroom status code->Ushma

	    if(strstr($template_desc,"{stock_status"))
		{

		// for product stock
			$isStockExists = $stockroomhelper->isStockExists($product_id);
			if($property_id > 0)
			{
				$isPropertystock = false;
				$isPropertystock = $stockroomhelper->isStockExists($property_id,"property");
				$isStockExists = $isPropertystock;
				//if(!$isStockExists)
				//{
					$isSubpropertyStock = false;
					if($subproperty_id > 0 )
					{
						$isSubpropertyStock = $stockroomhelper->isStockExists($subproperty_id,'subproperty');
						$isStockExists = $isSubpropertyStock;
					} else {

							$sub_property = $this->getAttibuteSubProperty(0,$property_id);

							for($sub_j=0;$sub_j<count($sub_property);$sub_j++)
							{
								$isSubpropertyStock = $stockroomhelper->isStockExists($sub_property[$sub_j]->subattribute_color_id,'subproperty');
								if($isSubpropertyStock)
								{
									$isStockExists = $isSubpropertyStock;
									break;
								}
							}
					}
				//}

			}



			if($property_id == 0  && !$isStockExists)
			{
				// for cunt attributes
					$attributes_set = array();
					if($product->attribute_set_id > 0){
						$attributes_set = $this->getProductAttribute(0,$product->attribute_set_id,0,1);
					}
					$attributes = $this->getProductAttribute($product->product_id);
					$attributes = array_merge($attributes,$attributes_set);
					$totalatt = count($attributes);

				// for product stock
					$isStockExists = $stockroomhelper->isStockExists($product_id);

					if($totalatt>0 && !$isStockExists)
					{
						$property = $this->getAttibuteProperty(0,0,$product_id);
						for($att_j=0;$att_j<count($property);$att_j++)
						{
							$isSubpropertyStock = false;
							$sub_property = $this->getAttibuteSubProperty(0,$property[$att_j]->property_id);

							for($sub_j=0;$sub_j<count($sub_property);$sub_j++)
							{
								$isSubpropertyStock = $stockroomhelper->isStockExists($sub_property[$sub_j]->subattribute_color_id,'subproperty');
								if($isSubpropertyStock)
								{
									$isStockExists = $isSubpropertyStock;
									break;
								}
							}

							if($isSubpropertyStock)
							{
								break;
							}
							else
							{


								$isPropertystock = $stockroomhelper->isStockExists($property[$att_j]->property_id,"property");
								//echo $isPropertystock;die();
								if($isPropertystock)
								{
									$isStockExists = $isPropertystock;
									break;
								}
							}
						}
					}

					// for preproduct stock
					 $isPreorderStockExists = $stockroomhelper->isPreorderStockExists($product_id);
					if($totalatt>0 && !$isPreorderStockExists)
					{

						$property = $this->getAttibuteProperty(0,0,$product_id);
						for($att_j=0;$att_j<count($property);$att_j++)
						{
							$isSubpropertyStock = false;
							$sub_property = $this->getAttibuteSubProperty(0,$property[$att_j]->property_id);
							for($sub_j=0;$sub_j<count($sub_property);$sub_j++)
							{
								$isSubpropertyStock = $stockroomhelper->isPreorderStockExists($sub_property[$sub_j]->subattribute_color_id,'subproperty');
								if($isSubpropertyStock)
								{
									$isPreorderStockExists = $isSubpropertyStock;
									break;
								}
							}
							if($isSubpropertyStock)
							{
								break;
							}
							else
							{
								$isPropertystock = $stockroomhelper->isPreorderStockExists($property[$att_j]->property_id,"property");
								if($isPropertystock)
								{
									$isPreorderStockExists = $isPropertystock;
									break;
								}
							}
						}
					}
			}
			$product_preorder = $product->preorder;
			$stocktag= strstr($template_desc,"{stock_status");
			$newstocktag= explode("}", $stocktag );

			$realstocktag= $newstocktag[0]."}";

			$stock_tag = substr($newstocktag[0], 1);
			$sts_array= explode(":",$stock_tag);

			$avail_class = $sts_array[1];
			if($avail_class == "")
			{
				$avail_class = "available_stock_cls";
			}
			$out_stock_class = $sts_array[2];
			if($out_stock_class == "")
			{
				$out_stock_class = "out_stock_cls";
			}
			$pre_order_class = $sts_array[3];
			if($pre_order_class == "")
			{
				$pre_order_class = "pre_order_cls";
			}


			if(!$isStockExists)
			{
					if(($product_preorder == "global" && ALLOW_PRE_ORDER) || ($product_preorder == "yes") || ($product_preorder == "" && ALLOW_PRE_ORDER))
					{
					// for preproduct stock

						$isPreorderStockExists = $stockroomhelper->isPreorderStockExists($product_id);
						if($property_id > 0)
						{
							$isPropertystock = false;
							$isPropertystock = $stockroomhelper->isPreorderStockExists($property_id,"property");
							$isPreorderStockExists = $isPropertystock;

							//if(!$isPreorderStockExists)
							//{
								$isSubpropertyStock = false;
								if($subproperty_id > 0)
								{
										$isSubpropertyStock = $stockroomhelper->isPreorderStockExists($subproperty_id,'subproperty');
										$isPreorderStockExists = $isSubpropertyStock;
								} else {

										$sub_property = $this->getAttibuteSubProperty(0,$property_id);

										for($sub_j=0;$sub_j<count($sub_property);$sub_j++)
										{
											$isSubpropertyStock = $stockroomhelper->isPreorderStockExists($sub_property[$sub_j]->subattribute_color_id,'subproperty');
											if($isSubpropertyStock)
											{
												$isPreorderStockExists = $isSubpropertyStock;
												break;
											}
										}
								}
							//}

						}

						 if (!$isPreorderStockExists)
						 {
								$stock_status = "<span id='stock_status_div".$product_id."'><div id='".$out_stock_class."' class='".$out_stock_class."'>".JText::_('COM_REDSHOP_OUT_OF_STOCK')."</div></span>";
						 } else {
						 		$stock_status = "<span id='stock_status_div".$product_id."'><div id='".$pre_order_class."' class='".$pre_order_class."'>".JText::_('COM_REDSHOP_PRE_ORDER')."</div></span>";
						 }
					}else {

						$stock_status = "<span id='stock_status_div".$product_id."'><div id='".$out_stock_class."' class='".$out_stock_class."'>".JText::_('COM_REDSHOP_OUT_OF_STOCK')."</div></span>";
					}
			} else {
					$stock_status = "<span id='stock_status_div".$product_id."'><div id='".$avail_class."' class='".$avail_class."'>".JText::_('COM_REDSHOP_AVAILABLE_STOCK')."</div></span>";
			}

		}

		$ret 								= array();
		$ret['response'] 					= $response;
		$ret['aHrefImageResponse'] 			= $aHrefImageResponse;
		$ret['aTitleImageResponse'] 		= $aTitleImageResponse;
		$ret['mainImageResponse'] 			= $mainImageResponse;
		$ret['stockamountSrc'] 				= $stockamountSrc;
		$ret['stockamountTooltip'] 			= $stockamountTooltip;
		$ret['ProductAttributeDelivery'] 	= $ProductAttributeDelivery;
		$ret['attrbimg'] 		= $attrbimg;
		$ret['pr_number'] 		= $pr_number;
		$ret['productinstock'] 	= $productinstock;
		$ret['stock_status'] 	= $stock_status;
		$ret['product_mainimg'] = $productmainimg;
		$ret['ImageName'] 		= $imagename;
		//$ret['view']			=$view;
		return $ret;
	}

	function getdisplaymainImage($product_id=0,  $property_id=0, $subproperty_id=0, $pw_thumb=0, $ph_thumb=0, $redview="")
	{
		$uri	         =& JURI::getInstance();
		$url = $uri->toString( array('scheme', 'host', 'port'));
		$path = explode("/",$uri->getPath());
		if($path[1] != "plugins") $url .= "/".$path[1]."/"; else	$url .="/";


		$option 	= JRequest::getVar ( 'option','com_redshop' );
		$product 	= $this->getProductById($product_id);
		$redhelper   = new redhelper();
		$aHrefImageResponse		= '';
		$imagename 				= '';
		$aTitleImageResponse	= '';
		$mainImageResponse 		= '';
		$productmainimg			= '';
		$Arrreturn 				= array();
		$product 				= $this->getProductById($product_id);
		$type					= '';
		$pr_number				= $product->product_number;

		if (is_file ( JPATH_COMPONENT_SITE . "/assets/images/product/".$product->product_thumb_image ))
		{

			$type = 'product';
			$imagename			= $product->product_thumb_image;
			$aTitleImageResponse = $product->product_name;
			$attrbimg=$url."components/com_redshop/assets/images/product/".$product->product_thumb_image;
		}
		else if (is_file ( JPATH_COMPONENT_SITE . "/assets/images/product/".$product->product_full_image ))
		{
			$type = 'product';
			$imagename			= $product->product_full_image;
			$aTitleImageResponse = $product->product_name;
			$attrbimg=$url."components/com_redshop/assets/images/product/".$product->product_full_image;
		}
		else
	    {
			if (PRODUCT_DEFAULT_IMAGE && is_file(JPATH_COMPONENT_SITE. "/assets/images/product/".PRODUCT_DEFAULT_IMAGE ))
			{
				$type = 'product';
				$imagename			= PRODUCT_DEFAULT_IMAGE;
			    $aTitleImageResponse = PRODUCT_DEFAULT_IMAGE;
			    $attrbimg=$url."components/com_redshop/assets/images/product/".PRODUCT_DEFAULT_IMAGE;
			}
	    }

		if($property_id>0)
		{
			$property 	= $this->getAttibuteProperty($property_id);
			$pr_number	= $property[0]->property_number;
			if (count($property)>0 && is_file ( JPATH_COMPONENT_SITE . "/assets/images/property/".$property[0]->property_main_image ))
			{
				$type = 'property';
				$imagename			= $property[0]->property_main_image;
				$aTitleImageResponse = $property[0]->text;
			}
			//Display attribute image in cart
			if (count($property)>0 && is_file ( JPATH_COMPONENT_SITE . "/assets/images/product_attributes/".$property[0]->property_image ))
			{
				$attrbimg=$url."components/com_redshop/assets/images/product_attributes/".$property[0]->property_image;
			}
		}

		if($subproperty_id>0)
		{
			$subproperty = $this->getAttibuteSubProperty($subproperty_id);
			$pr_number	 = $subproperty[0]->subattribute_color_number;
			//Display Sub-Property Number
			if (count($subproperty)>0 && is_file ( JPATH_COMPONENT_SITE."/assets/images/subcolor/".$subproperty[0]->subattribute_color_image ))
			{
				$type = 'subcolor';
				$imagename			= $subproperty[0]->subattribute_color_image;
				$aTitleImageResponse = $subproperty[0]->text;
				$attrbimg=$url."components/com_redshop/assets/images/subcolor/".$subproperty[0]->subattribute_color_image;
			}
			//Subproperty image in cart

		}

		if(!empty($imagename) && !empty($type))
		{
			if((WATERMARK_PRODUCT_THUMB_IMAGE) && $type=='product')
			{
					$productmainimg = $redhelper->watermark('product',$imagename,$pw_thumb,$ph_thumb,WATERMARK_PRODUCT_THUMB_IMAGE,'0');
			}
			else
			{
					$productmainimg=$url."components/com_redshop/helpers/thumb.php?filename=$type/".$imagename."&newxsize=".$pw_thumb."&newysize=".$ph_thumb."&swap=".USE_IMAGE_SIZE_SWAPPING;
			}
			if((WATERMARK_PRODUCT_IMAGE) && $type=='product')
			{
					$aHrefImageResponse = $redhelper->watermark('product',$imagename,'','',WATERMARK_PRODUCT_IMAGE,'0');
			}
			else
			{
					$aHrefImageResponse = $url."components/com_redshop/assets/images/$type/".$imagename;
			}
			//zoom
		   $mainImageResponse = $productmainimg;
		}

		$Arrreturn['aHrefImageResponse']	= $aHrefImageResponse;
		$Arrreturn['mainImageResponse']		= $mainImageResponse;
		$Arrreturn['productmainimg']		= $productmainimg;
		$Arrreturn['aTitleImageResponse']	= $aTitleImageResponse;
		$Arrreturn['imagename']				= $imagename;
		$Arrreturn['type']					= $type;
		$Arrreturn['attrbimg']				= $attrbimg;
		$Arrreturn['pr_number']				= $pr_number;


		return $Arrreturn;
	}

}


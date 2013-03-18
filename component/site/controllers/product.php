<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

jimport('joomla.application.component.controller');
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');
require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'template.php');

/**
 * Product Controller
 *
 * @static
 * @package        redSHOP
 * @since          1.0
 */
class productController extends JController
{


	public function displayProductaddprice()
	{
		ob_clean();
		$get = JRequest::get('get');
		$data = array();

		$producthelper = new producthelper();
		$carthelper = new rsCarthelper();
		$total_attribute = 0;

		$product_id = $get['product_id'];
		$quantity = $get['qunatity'];

		$data['attribute_data'] = str_replace("::", "##", $get['attribute_data']);
		$data['property_data'] = str_replace("::", "##", $get['property_data']);
		$data['subproperty_data'] = str_replace("::", "##", $get['subproperty_data']);

		$data['accessory_data'] = $get['accessory_data'];
		$data['acc_quantity_data'] = $get['acc_quantity_data'];

		$data['acc_attribute_data'] = str_replace("::", "##", $get['acc_attribute_data']);
		$data['acc_property_data'] = str_replace("::", "##", $get['acc_property_data']);
		$data['acc_subproperty_data'] = str_replace("::", "##", $get['acc_subproperty_data']);

		$data['quantity'] = $quantity;

		$cartdata = $carthelper->generateAttributeArray($data);
		$retAttArr = $producthelper->makeAttributeCart($cartdata, $product_id, 0, '', $quantity);

		$ProductPriceArr = $producthelper->getProductNetPrice($product_id, 0, $quantity);

		$acccartdata = $carthelper->generateAccessoryArray($data);
//		print_r($acccartdata);
		$retAccArr = $producthelper->makeAccessoryCart($acccartdata, $product_id);
		$accessory_price = $retAccArr[1];
		$accessory_vat = $retAccArr[2];
//		echo "<pre>";print_r($retAccArr);
//		exit;

		$product_price = (($retAttArr[1] + $retAttArr[2]) * $quantity) + $accessory_price + $accessory_vat;
		$product_main_price = (($retAttArr[1] + $retAttArr[2]) * $quantity) + $accessory_price + $accessory_vat;
		$product_old_price = $ProductPriceArr['product_old_price'] * $quantity;
		$product_price_saving = $ProductPriceArr['product_price_saving'] * $quantity;
		$product_discount_price = $ProductPriceArr['product_discount_price'] * $quantity;
		$product_price_novat = ($retAttArr[1] * $quantity) + $accessory_price;
		$product_price_incl_vat = ($ProductPriceArr['product_price_incl_vat'] * $quantity) + $accessory_price + $accessory_vat;
		$price_excluding_vat = ($retAttArr[1] * $quantity) + $accessory_price;
		$seoProductPrice = $ProductPriceArr['seoProductPrice'] * $quantity;
		$seoProductSavingPrice = $ProductPriceArr['seoProductSavingPrice'] * $quantity;


		echo $product_price . ":" . $product_main_price . ":" . $product_old_price . ":" . $product_price_saving . ":" . $product_discount_price . ":" . $product_price_novat . ":" . $product_price_incl_vat . ":" . $price_excluding_vat . ":" . $seoProductPrice . ":" . $seoProductSavingPrice;
		exit;
	}

	public function writeReview()
	{
		$post = JRequest::get('post');
		$option = JRequest::getVar('option');
		$Itemid = JRequest::getVar('Itemid');
		$product_id = JRequest::getInt('product_id');
		$category_id = JRequest::getInt('category_id');
		$model = $this->getModel('product');
		$rate = JRequest::getVar('rate');
		$review_captcha = JRequest::getInt('review_captcha', -1, 'post');

		$session = JFactory::getSession();
		$hash = JRequest::getCmd('review_captcha_hash', null, 'post');
		$res = $session->get('session.simplemath' . $hash);
		$session->set('session.simplemath' . $hash, null);

		//$posted = JRequest::getInt('sm_answer', -1, 'post');

		//if($review_captcha==9)
		if ($res == $review_captcha)
		{
			if ($model->checkReview($post['email'], $product_id))
			{
				$msg = JText::_('COM_REDSHOP_REVIEW_ALREADY_EXIST');
			}
			elseif ($model->sendMailForReview($post))
			{
				$msg = JText::_('COM_REDSHOP_EMAIL_HAS_BEEN_SENT_SUCCESSFULLY');
			}
			else
			{
				$msg = JText::_('COM_REDSHOP_EMAIL_HAS_NOT_BEEN_SENT_SUCCESSFULLY');
			}
		}
		else
		{
			$msg = JText::_('COM_REDSHOP_IN_CORRECT_CAPTCHA');
		}

		$link = 'index.php?option=' . $option . '&view=product&pid=' . $product_id . '&cid=' . $category_id . '&Itemid=' . $Itemid;
		$this->setRedirect($link, $msg);

		//echo "here";exit;
	}


	public function displaySubProperty()
	{
		$propid = $subpropid = array();
		$get = JRequest::get('get');
		$producthelper = new producthelper();

		$product_id = $get['product_id'];
		$accessory_id = $get['accessory_id'];
		$relatedprd_id = $get['relatedprd_id'];
		$attribute_id = $get['attribute_id'];
		$isAjaxBox = $get['isAjaxBox'];
		if (isset($get['property_id']) && $get['property_id'])
		{
			$propid = explode(",", $get['property_id']);
		}
		if (isset($get['subproperty_id']) && $get['subproperty_id'])
		{
			$subpropid = explode(",", $get['subproperty_id']);
		}
		$subatthtml = htmlspecialchars_decode(base64_decode(JRequest::getVar('subatthtml', '', 'get', 'string', JREQUEST_ALLOWRAW)));

		$response = "";
		for ($i = 0; $i < count($propid); $i++)
		{
			$property_id = $propid[$i];
			$response .= $producthelper->replaceSubPropertyData($product_id, $accessory_id, $relatedprd_id, $attribute_id, $property_id, $subatthtml, $isAjaxBox, $subpropid);
		}
		echo $response;
		exit;
	}

	public function displayAdditionImage()
	{
		$url = JURI::base();
		$get = JRequest::get('get');
		$option = JRequest::getVar('option');
		$producthelper = new producthelper();

		$property_id = urldecode($get['property_id']);
		$subproperty_id = urldecode($get['subproperty_id']);

		$product_id = $get['product_id'];
		$accessory_id = $get['accessory_id'];
		$relatedprd_id = $get['relatedprd_id'];
		$main_imgwidth = $get['main_imgwidth'];
		$main_imgheight = $get['main_imgheight'];
		$redview = $get['redview'];
		$redlayout = $get['redlayout'];

		$dispatcher =& JDispatcher::getInstance();
		JPluginHelper::importPlugin('redshop_product');
		$pluginResults = $dispatcher->trigger('onBeforeImageLoad', array($get));

		if (!empty($pluginResults))
		{
			$mainImageResponse = $pluginResults[0]['mainImageResponse'];
			$result = $producthelper->displayAdditionalImage($product_id, $accessory_id, $relatedprd_id, $property_id, $subproperty_id);
		}
		else
		{
			$result = $producthelper->displayAdditionalImage($product_id, $accessory_id, $relatedprd_id, $property_id, $subproperty_id, $main_imgwidth, $main_imgheight, $redview, $redlayout);
			$mainImageResponse = $result['mainImageResponse'];
		}

		$response = $result['response'];
		$aHrefImageResponse = $result['aHrefImageResponse'];
		$aTitleImageResponse = $result['aTitleImageResponse'];
		$stockamountSrc = $result['stockamountSrc'];
		$stockamountTooltip = $result['stockamountTooltip'];
		$ProductAttributeDelivery = $result['ProductAttributeDelivery'];
		$attrbimg = $result['attrbimg'];
		$pr_number = $result['pr_number'];
		$productinstock = $result['productinstock'];
		$stock_status = $result['stock_status'];
		$ImageName = $result['ImageName'];
		$notifyStock = $result['notifyStock'];
		$product_availability_date_lbl = $result['product_availability_date_lbl'];
		$product_availability_date = $result['product_availability_date'];
		//$view = $result['view'];
		echo "`_`" . $response . "`_`" . $aHrefImageResponse . "`_`" . $aTitleImageResponse . "`_`" . $mainImageResponse . "`_`" . $stockamountSrc . "`_`" . $stockamountTooltip . "`_`" . $ProductAttributeDelivery . "`_`" . $product_img . "`_`" . $pr_number . "`_`" . $productinstock . "`_`" . $stock_status . "`_`" . $attrbimg . "`_`" . $notifyStock . "`_`" . $product_availability_date_lbl . "`_`" . $product_availability_date;
		exit;
	}

	/**
	 * Add to wishlist function
	 *
	 * @access public
	 * @return void
	 */
	public function addtowishlist()
	{
		ob_clean();
		global $mainframe;
		$extraField = new extraField();
		$section = 12;
		$row_data = $extraField->getSectionFieldList($section);
		// getVariables
		$cid = JRequest::getInt('cid');
		$producthelper = new producthelper();
		$user = & JFactory::getUser();

		$Itemid = JRequest::getVar('Itemid');

		$option = JRequest::getVar('option');

		$ajaxvar = JRequest::getVar('ajaxon');
		$mywid = JRequest::getVar('wid');
		if ($ajaxvar == 1 && ($mywid == 1 || $mywid == 2))
		{
			$post = JRequest::get('post');

			$post['product_id'] = JRequest::getVar('product_id');
			$proname = $producthelper->getProductById($post['product_id']);
			$post['view'] = JRequest::getVar('view');
			$post['task'] = JRequest::getVar('task');

			for ($i = 0; $i < count($row_data); $i++)
			{
				$field_name = $row_data[$i]->field_name;

				$type = $row_data[$i]->field_type;

				if (isset($post[$row_data[$i]->field_name]))

					$data_txt = $post[$row_data[$i]->field_name];
				else
					$data_txt = '';
				//$tmparray = explode('`',$data_txt);
				$tmpstr = strpbrk($data_txt, '`');
				if ($tmpstr)
				{
					$tmparray = explode('`', $data_txt);
					$tmp = new stdClass;
					$tmp = @array_merge($tmp, $tmparray);
					if (is_array($tmparray))
					{
						$data_txt = implode(",", $tmparray);
					}
				}
				//$cart[$idx][$field_name] = $data_txt;
				$post['productuserfield_' . $i] = $data_txt;

			}


		}
		else
		{
			$post = JRequest::get('post');

			$proname = $producthelper->getProductById($post['product_id']);
			for ($i = 0; $i < count($row_data); $i++)
			{
				$field_name = $row_data[$i]->field_name;

				$type = $row_data[$i]->field_type;

				if (isset($post[$row_data[$i]->field_name]))

					$data_txt = $post[$row_data[$i]->field_name];
				else
					$data_txt = '';
				//$tmparray = explode('`',$data_txt);
				$tmpstr = strpbrk($data_txt, '`');
				if ($tmpstr)
				{
					$tmparray = explode('`', $data_txt);
					$tmp = new stdClass;
					$tmp = @array_merge($tmp, $tmparray);
					if (is_array($tmparray))
					{
						$data_txt = implode(",", $tmparray);
					}
				}
				//$cart[$idx][$field_name] = $data_txt;
				$post['productuserfield_' . $i] = $data_txt;

			}
		}

		$rurl = "";
		if (isset($post['rurl']))
			$rurl = base64_decode($post['rurl']);
		// initiallize variable

		$post['user_id'] = $user->id;

		$post['cdate'] = time();

		$model = $this->getModel('product');
		if ($user->id && $ajaxvar != '1')
		{
			if ($model->checkWishlist($post['product_id']) == null)
			{
				if ($model->addToWishlist($post))
				{
					$mainframe->enqueueMessage(JText::_('COM_REDSHOP_WISHLIST_SAVE_SUCCESSFULLY'));
				}
				else
				{
					$mainframe->enqueueMessage(JText::_('COM_REDSHOP_ERROR_SAVING_WISHLIST'));
				}
			}
			else
			{
				$mainframe->enqueueMessage(JText::_('COM_REDSHOP_ALLREADY_ADDED_TO_WISHLIST'));
			}
		}
		else
		{
			// user can store wishlist in session
			if ($model->addtowishlist2session($post))
				$mainframe->enqueueMessage(JText::_('COM_REDSHOP_WISHLIST_SAVE_SUCCESSFULLY'));
			else
				$mainframe->enqueueMessage(JText::_('COM_REDSHOP_ALLREADY_ADDED_TO_WISHLIST'));
		}
		if ($ajaxvar == 1)
		{
			sleep(2);
			$getproductimage = $producthelper->getProductById($post['product_id']);
			$finalproductimgname = $getproductimage->product_full_image;
			if ($finalproductimgname != '')
			{
				$mainimg = "product/" . $finalproductimgname;
			}
			else
			{
				$mainimg = 'noimage.jpg';
			}
			//echo "<div id='my'><a href='index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=".JRequest::getVar('Itemid')."&pid=".$post['product_id']."'>".$proname->product_name."</a></div><br>:-:".$proname->product_name."";
			echo "<span id='basketWrap' ><a href='index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=" . JRequest::getVar('Itemid') . "&pid=" . $post['product_id'] . "'><img src='" . REDSHOP_FRONT_IMAGES_ABSPATH . $mainimg . "' height='30' width='30'/></a></span>:-:" . $proname->product_name . "";
			exit;
		}
		elseif ($mywid == 1)
		{
			$this->setRedirect('index.php?option=' . $option . 'wishlist=1&view=login&Itemid=' . $Itemid);

		}
		if ($rurl != "")
			$this->setRedirect($rurl);
		else
			$this->setRedirect('index.php?option=' . $option . '&view=product&pid=' . $post['product_id'] . '&cid=' . $cid . '&Itemid=' . $Itemid);
	}

	/**
	 * Add product tag function
	 *
	 * @access public
	 * @return void
	 */

	public function addProductTags()
	{
		global $mainframe;

		// getVariables
		$cid = JRequest::getInt('cid');
		$Itemid = JRequest::getVar('Itemid');
		$option = JRequest::getVar('option');

		$post = JRequest::get('post');

		// initiallize variable

		$tagnames = $post['tags_name'];
		$productid = $post['product_id'];
		$userid = $post['users_id'];

		$model = $this->getModel('product');

		$tagnames = preg_split(" ", $tagnames);

		for ($i = 0; $i < count($tagnames); $i++)
		{
			$tagname = $tagnames[$i];

			if ($model->checkProductTags($tagname, $productid) == null)
			{
				$tags = $model->getProductTags($tagname, $productid);

				if (count($tags) != 0)
				{
					foreach ($tags as $tag)
					{
						if ($tag->product_id == $productid)
						{
							if ($tag->users_id != $userid)
								$counter = $tag->tags_counter + 1;
						}
						else
							$counter = $tag->tags_counter + 1;

						$ntag['tags_id'] = $tag->tags_id;
						$ntag['tags_name'] = $tag->tags_name;
						$ntag['tags_counter'] = $counter;
						$ntag['published'] = $tag->published;
						$ntag['product_id'] = $productid;
						$ntag['users_id'] = $userid;
					}
				}
				else
				{
					$ntag['tags_id'] = 0;
					$ntag['tags_name'] = $tagname;
					$ntag['tags_counter'] = 1;
					$ntag['published'] = 1;
					$ntag['product_id'] = $productid;
					$ntag['users_id'] = $userid;
				}

				if ($tags = $model->addProductTags($ntag))
				{
					$model->addProductTagsXref($ntag, $tags);

					$mainframe->enqueueMessage($tagname . '&nbsp;' . JText::_('COM_REDSHOP_TAGS_ARE_ADDED'));

				}
				else
				{
					$mainframe->enqueueMessage($tagname . '&nbsp;' . JText::_('COM_REDSHOP_ERROR_ADDING_TAGS'));
				}

			}
			else
			{
				$mainframe->enqueueMessage($tagname . '&nbsp;' . JText::_('COM_REDSHOP_ALLREADY_ADDED'));
			}
		}

		$this->setRedirect('index.php?option=' . $option . '&view=product&pid=' . $post['product_id'] . '&cid=' . $cid . '&Itemid=' . $Itemid);
	}

	/**
	 * Add to compare function
	 *
	 * @access public
	 * @return product compare list through ajax
	 */
	public function addtocompare()
	{
		ob_clean();
		require_once(JPATH_COMPONENT_SITE . DS . 'helpers' . DS . 'product.php');

		$producthelper = new producthelper ();
		// getVariables
		$post = JRequest::get('REQUEST');

		// initiallize variable

		$model = $this->getModel('product');

		if ($post['cmd'] == 'add')
		{
			$checkCompare = $model->checkComparelist($post['pid']);

			$countCompare = $model->checkComparelist(0);

			if ($countCompare < PRODUCT_COMPARE_LIMIT)
			{
				if ($checkCompare)
				{
					if ($model->addtocompare($post))
					{
						$Message = "1`"; //.JText::_('COM_REDSHOP_PRODUCT_ADDED_TO_COMPARE_SUCCESSFULLY');
					}
					else
					{
						$Message = "1`" . JText::_('COM_REDSHOP_ERROR_ADDING_PRODUCT_TO_COMPARE');
					}
				}
				else
				{
					$Message = JText::_('COM_REDSHOP_ALLREADY_ADDED_TO_COMPARE');
				}
				$Message .= $producthelper->makeCompareProductDiv();
				echo $Message;
			}
			else
			{
				$Message = "0`" . JText::_('COM_REDSHOP_LIMIT_CROSS_TO_COMPARE');
				echo $Message;
			}
		}
		elseif ($post['cmd'] == 'remove')
		{
			$model->removeCompare($post['pid']);
			$Message = "1`" . $producthelper->makeCompareProductDiv();
			echo $Message;
		}
		exit;
	}

	/**
	 * remove compare function
	 *
	 * @access public
	 * @return void
	 */
	public function removecompare()
	{
		$post = JRequest::get('REQUEST');

		// initiallize variable

		$model = $this->getModel('product');
		$model->removeCompare($post['pid']);
		parent::display();
	}

	/**
	 * Download Product function
	 *
	 * @access public
	 * @return void
	 */
	public function downloadProduct()
	{
		$Itemid = JRequest::getVar('Itemid');
		$model = $this->getModel('product');

		$tid = JRequest::getCmd('download_id', "");

		$data = $model->downloadProduct($tid);

		// today at the end of the day
		$today = time();

		if (count($data) != 0)
		{
			$download_id = $data->download_id;

			// download Product end date
			$end_date = $data->end_date;

			if ($end_date == 0 || ($data->download_max != 0 && $today <= $end_date))
			{
				$msg = JText::_("COM_REDSHOP_DOWNLOADABLE_THIS_PRODUCT");

				$this->setRedirect("index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $download_id . "&Itemid=" . $Itemid, $msg);

			}
			else
			{
				$msg = JText::_("COM_REDSHOP_DOWNLOAD_LIMIT_OVER");
				$this->setRedirect("index.php?option=com_redshop&view=product&layout=downloadproduct&Itemid=" . $Itemid, $msg);
			}
		}
		else
		{
			$msg = JText::_("COM_REDSHOP_TOKEN_VERIFICATION_FAIL");
			$this->setRedirect("index.php?option=com_redshop&view=product&layout=downloadproduct&Itemid=" . $Itemid, $msg);
		}

	}

	/**
	 * Download function
	 *
	 * @access public
	 * @return void
	 */
	public function Download()
	{
		$post = JRequest::get('POST');

		$model = $this->getModel('product');

		$tid = $post['tid'];

		$data = $model->downloadProduct($tid);

		$limit = $data->download_max;

		// today at the end of the day
		$today = time();

		// download Product end date
		$end_date = $data->end_date;

		if ($end_date != 0 && ($limit == 0 || $today > $end_date))
		{
			$msg = JText::_("COM_REDSHOP_DOWNLOAD_LIMIT_OVER");
			$this->setRedirect("index.php?option=com_redshop&view=product&layout=downloadproduct", $msg);

		}
		elseif (isset($post['mainindex']) && isset($post['additional']))
		{
			$task = $post['mainindex'];

			$id = $post['additional'];

			if ($task == "main")
			{
				$finalname = $model->AdditionaldownloadProduct($id, 0, 1);

				$name = $finalname[0]->media_name;

			}
			elseif ($task == "additional")
			{
				$finalname = $model->AdditionaldownloadProduct(0, $id);

				$name = $finalname[0]->name;
			}

		}
		else
		{
			$msg = JText::_('COM_REDSHOP_NO_FILE_SELECTED');
			$this->setRedirect('index.php?option=com_redshop&view=product&layout=downloadproduct&tid=' . $tid, $msg);
			return;
		}


		if (isset($post['additional']) && $tid != "" && $end_date == 0 || ($limit != 0 && $today <= $end_date))
		{
			if ($model->setDownloadLimit($tid))
			{
				$baseURL = JURI::root();
				$tmp_name = JPATH_SITE . '/components/com_redshop/assets/download/product/' . $name;

				$tmp_type = strtolower(JFile::getExt($name));

				$downloadname = substr(basename($name), 11);

				switch ($tmp_type)
				{
					case "pdf":
						$ctype = "application/pdf";
						break;
					case "psd":
						$ctype = "application/psd";
						break;
					case "exe":
						$ctype = "application/octet-stream";
						break;
					case "zip":
						$ctype = "application/x-zip";
						break;
					case "doc":
						$ctype = "application/msword";
						break;
					case "xls":
						$ctype = "application/vnd.ms-excel";
						break;
					case "ppt":
						$ctype = "application/vnd.ms-powerpoint";
						break;
					case "gif":
						$ctype = "image/gif";
						break;
					case "png":
						$ctype = "image/png";
						break;
					case "jpeg":
					case "jpg":
						$ctype = "image/jpg";
						break;
					default:
						$ctype = "application/force-download";
				}

				ob_clean();

				header("Pragma: public");
				header('Expires: 0');
				header("Content-Type: $ctype", FALSE);
				header('Content-Length: ' . filesize($name));
				header('Content-Disposition: attachment; filename=' . $downloadname);


				// red file using chunksize
				$this->readfile_chunked($name);
				exit;
			}
		}
	}

	/**
	 * file read function
	 *
	 * @access public
	 * @return file data
	 */
	public function readfile_chunked($filename, $retbytes = true)
	{
		$chunksize = 10 * (1024 * 1024); // how many bytes per chunk
		$buffer = '';
		$cnt = 0;

		$handle = fopen($filename, 'rb');
		if ($handle === false)
		{
			return false;
		}
		while (!feof($handle))
		{
			$buffer = fread($handle, $chunksize);
			echo $buffer;
			ob_flush();
			flush();
			if ($retbytes)
			{
				$cnt += strlen($buffer);
			}
		}

		$status = fclose($handle);
		if ($retbytes && $status)
		{
			return $cnt; // return num. bytes delivered like readfile() does.
		}
		return $status;

	}

	/**
	 * ajax upload function
	 *
	 * @access public
	 * @return filename on successfull file upload
	 */
	public function ajaxupload()
	{
		$uploaddir = JPATH_COMPONENT_SITE . DS . 'assets' . DS . 'document' . DS . 'product' . DS;
		$name = JRequest::getVar('mname');
		$filename = time() . '_' . basename($_FILES[$name]['name']);
		$uploadfile = $uploaddir . $filename;
		if (move_uploaded_file($_FILES[$name]['tmp_name'], $uploadfile))
		{
			echo $filename;
		}
		else
		{
			// WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
			// Otherwise onSubmit event will not be fired
			echo "error";
		}
		exit;
	}

	/**
	 * ajax upload function
	 *
	 * @access public
	 * @return filename on successfull file download
	 */
	public function downloadDocument()
	{
		$fname = JRequest::getVar('fname', '', 'request', 'string');
		$fpath = REDSHOP_FRONT_DOCUMENT_RELPATH . 'product/' . $fname;
		if (is_file($fpath))
		{
			$tmp_type = strtolower(JFile::getExt($fpath));

			$downloadname = substr(basename($fpath), 11);

			switch ($tmp_type)
			{
				case "pdf":
					$ctype = "application/pdf";
					break;
				case "psd":
					$ctype = "application/psd";
					break;
				case "exe":
					$ctype = "application/octet-stream";
					break;
				case "zip":
					$ctype = "application/x-zip";
					break;
				case "doc":
					$ctype = "application/msword";
					break;
				case "xls":
					$ctype = "application/vnd.ms-excel";
					break;
				case "ppt":
					$ctype = "application/vnd.ms-powerpoint";
					break;
				case "gif":
					$ctype = "image/gif";
					break;
				case "png":
					$ctype = "image/png";
					break;
				case "jpeg":
				case "jpg":
					$ctype = "image/jpg";
					break;
				default:
					$ctype = "application/force-download";
			}

			ob_clean();

			header("Pragma: public");
			header('Expires: 0');
			header("Content-Type: $ctype", FALSE);
			header('Content-Length: ' . filesize($fpath));
			header('Content-Disposition: attachment; filename=' . $downloadname);


			// red file using chunksize
			$this->readfile_chunked($fpath);
			exit;
		}
	}


	public function gotochild()
	{
		$producthelper = new producthelper();
		$objhelper = new redhelper();

		$post = JRequest::get('post');

		$cid = $producthelper->getCategoryProduct($post['pid']);

		$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $post['pid']);

		if (count($ItemData) > 0)
		{
			$pItemid = $ItemData->id;
		}
		else
		{
			$pItemid = $objhelper->getItemid($product->product_id, $cid);
		}

		$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $post['pid'] . '&cid=' . $cid . '&Itemid=' . $pItemid, false);

		$this->setRedirect($link);
	}

	public function gotonavproduct()
	{
		$producthelper = new producthelper();
		$objhelper = new redhelper();

		$post = JRequest::get('post');

		$cid = $producthelper->getCategoryProduct($post['pid']);

		$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $post['pid']);

		if (count($ItemData) > 0)
		{
			$pItemid = $ItemData->id;
		}
		else
		{
			$pItemid = $objhelper->getItemid($product->product_id, $cid);
		}

		$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $post['pid'] . '&cid=' . $cid . '&Itemid=' . $pItemid, false);

		$this->setRedirect($link);
	}

	public function addNotifystock()
	{
		ob_clean();
		$model = $this->getModel('product');

		$post = JRequest::get('request');

		$product_id = $post['product_id'];
		$property_id = $post['property_id'];
		$subproperty_id = $post['subproperty_id'];

		$notify_user = $model->addNotifystock($product_id, $property_id, $subproperty_id);
		if ($notify_user)
		{
			echo $message = JText::_("COM_REDSHOP_STOCK_NOTIFICATION_ADDED_SUCCESSFULLY");
		}
		exit;
	}

}

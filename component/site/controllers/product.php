<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 *
 * @copyright   Copyright (C) 2008 - 2017 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;


/**
 * Product Controller.
 *
 * @package     RedSHOP.Frontend
 * @subpackage  Controller
 * @since       1.0
 */
class RedshopControllerProduct extends RedshopController
{
	/**
	 * @var JInput
	 */
	protected $input;

	/**
	 * Constructor.
	 *
	 * @param   array  $config  An optional associative array of configuration settings.
	 * Recognized key values include 'name', 'default_task', 'model_path', and
	 * 'view_path' (this list is not meant to be comprehensive).
	 *
	 * @since   1.5
	 */
	public function __construct($config = array())
	{
		$this->input = JFactory::getApplication()->input;

		// Article frontpage Editor product proxying:
		if ($this->input->get('layout') === 'element')
		{
			JSession::checkToken('request') or jexit(JText::_('JINVALID_TOKEN'));

			$config['base_path'] = JPATH_COMPONENT_ADMINISTRATOR;

			$lang = JFactory::getLanguage();
			$lang->load('com_redshop', JPATH_ADMINISTRATOR);

			JHtml::_('behavior.framework');
			JHtml::_('redshopjquery.framework');

			$document = JFactory::getDocument();

			if (version_compare(JVERSION, '3.0', '>='))
			{
				JHtml::_('formbehavior.chosen', 'select:not(".disableBootstrapChosen")', null, array('search_contains' => true));
				$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/j3ready.css');
			}

			$document->addStyleSheet(JURI::root() . 'administrator/components/com_redshop/assets/css/redshop.css');
			$this->input->set('layout', 'element');
		}

		parent::__construct($config);
	}

	/**
	 * Display Product add price
	 *
	 * @return  void
	 */
	public function displayProductaddprice()
	{
		ob_clean();

		$get  = $this->input->get->getArray();

		$producthelper   = productHelper::getInstance();
		$carthelper      = rsCarthelper::getInstance();
		$total_attribute = 0;

		$product_id = $get['product_id'];
		$quantity   = $get['qunatity'];

		$data = array();
		$data['attribute_data']       = str_replace("::", "##", $get['attribute_data']);
		$data['property_data']        = str_replace("::", "##", $get['property_data']);
		$data['subproperty_data']     = str_replace("::", "##", $get['subproperty_data']);
		$data['accessory_data']       = $get['accessory_data'];
		$data['acc_quantity_data']    = $get['acc_quantity_data'];
		$data['acc_attribute_data']   = str_replace("::", "##", $get['acc_attribute_data']);
		$data['acc_property_data']    = str_replace("::", "##", $get['acc_property_data']);
		$data['acc_subproperty_data'] = str_replace("::", "##", $get['acc_subproperty_data']);
		$data['quantity']             = $quantity;

		$cartdata  = $carthelper->generateAttributeArray($data);
		$retAttArr = $producthelper->makeAttributeCart($cartdata, $product_id, 0, '', $quantity);

		$ProductPriceArr = $producthelper->getProductNetPrice($product_id, 0, $quantity);

		$acccartdata     = $carthelper->generateAccessoryArray($data);
		$retAccArr       = $producthelper->makeAccessoryCart($acccartdata, $product_id);
		$accessory_price = $retAccArr[1];
		$accessory_vat   = $retAccArr[2];

		$product_price          = (($retAttArr[1] + $retAttArr[2]) * $quantity) + $accessory_price + $accessory_vat;
		$product_main_price     = (($retAttArr[1] + $retAttArr[2]) * $quantity) + $accessory_price + $accessory_vat;
		$product_old_price      = $ProductPriceArr['product_old_price'] * $quantity;
		$product_price_saving   = $ProductPriceArr['product_price_saving'] * $quantity;
		$product_discount_price = $ProductPriceArr['product_discount_price'] * $quantity;
		$product_price_novat    = ($retAttArr[1] * $quantity) + $accessory_price;
		$product_price_incl_vat = ($ProductPriceArr['product_price_incl_vat'] * $quantity) + $accessory_price + $accessory_vat;
		$price_excluding_vat    = ($retAttArr[1] * $quantity) + $accessory_price;
		$seoProductPrice        = $ProductPriceArr['seoProductPrice'] * $quantity;
		$seoProductSavingPrice  = $ProductPriceArr['seoProductSavingPrice'] * $quantity;

		echo $product_price . ":" . $product_main_price . ":" . $product_old_price . ":" . $product_price_saving . ":" . $product_discount_price . ":" . $product_price_novat . ":" . $product_price_incl_vat . ":" . $price_excluding_vat . ":" . $seoProductPrice . ":" . $seoProductSavingPrice;
		JFactory::getApplication()->close();
	}

	/**
	 * Display sub property
	 *
	 * @return  string
	 */
	public function displaySubProperty()
	{
		$propid        = $subpropid = array();
		$get           = $this->input->get->getArray();
		$producthelper = productHelper::getInstance();

		$product_id    = $get['product_id'];
		$accessory_id  = $get['accessory_id'];
		$relatedprd_id = $get['relatedprd_id'];
		$attribute_id  = $get['attribute_id'];
		$isAjaxBox     = $get['isAjaxBox'];

		if (isset($get['property_id']) && $get['property_id'])
		{
			$propid = explode(",", $get['property_id']);
		}

		if (isset($get['subproperty_id']) && $get['subproperty_id'])
		{
			$subpropid = explode(",", $get['subproperty_id']);
		}

		$subatthtml = htmlspecialchars_decode(base64_decode($this->input->get->get('subatthtml', '', 'raw')));

		$response = "";

		for ($i = 0, $in = count($propid); $i < $in; $i++)
		{
			$property_id = $propid[$i];
			$response .= $producthelper->replaceSubPropertyData($product_id, $accessory_id, $relatedprd_id, $attribute_id, $property_id, $subatthtml, $isAjaxBox, $subpropid);
		}

		echo $response;
		JFactory::getApplication()->close();
	}

	/**
	 * Display addition image
	 *
	 * @return  void
	 */
	public function displayAdditionImage()
	{
		$url           = JURI::base();
		$get           = $this->input->get->getArray();
		$producthelper = productHelper::getInstance();

		$property_id    = urldecode($get['property_id']);
		$subproperty_id = urldecode($get['subproperty_id']);

		$product_id     = $get['product_id'];
		$accessory_id   = $get['accessory_id'];
		$relatedprd_id  = $get['relatedprd_id'];
		$main_imgwidth  = $get['main_imgwidth'];
		$main_imgheight = $get['main_imgheight'];
		$redview        = $get['redview'];
		$redlayout      = $get['redlayout'];
		$pluginResults  = array();

		$dispatcher = RedshopHelperUtility::getDispatcher();
		JPluginHelper::importPlugin('redshop_product');
		$dispatcher->trigger('onBeforeImageLoad', array($get, &$pluginResults));

		if (!empty($pluginResults))
		{
			$mainImageResponse = $pluginResults['mainImageResponse'];
			$result            = RedshopHelperProductTag::displayAdditionalImage(
									$product_id,
									$accessory_id,
									$relatedprd_id,
									$property_id,
									$subproperty_id
								);

			if (isset($pluginResults['attrbimg']))
			{
				$result['attrbimg'] = $pluginResults['attrbimg'];
			}
		}
		else
		{
			$result            = RedshopHelperProductTag::displayAdditionalImage(
									$product_id,
									$accessory_id,
									$relatedprd_id,
									$property_id,
									$subproperty_id,
									$main_imgwidth,
									$main_imgheight,
									$redview,
									$redlayout
								);
			$mainImageResponse = $result['product_mainimg'];
		}

		$response                      = $result['response'];
		$aHrefImageResponse            = $result['aHrefImageResponse'];
		$aTitleImageResponse           = $result['aTitleImageResponse'];
		$stockamountSrc                = $result['stockamountSrc'];
		$stockamountTooltip            = $result['stockamountTooltip'];
		$ProductAttributeDelivery      = $result['ProductAttributeDelivery'];
		$attrbimg                      = $result['attrbimg'];
		$pr_number                     = $result['pr_number'];
		$productinstock                = $result['productinstock'];
		$stock_status                  = $result['stock_status'];
		$ImageName                     = $result['ImageName'];
		$notifyStock                   = $result['notifyStock'];
		$product_availability_date_lbl = $result['product_availability_date_lbl'];
		$product_availability_date     = $result['product_availability_date'];
		$additional_vids			   = $result['additional_vids'];

		echo "`_`" . $response
			. "`_`" . $aHrefImageResponse
			. "`_`" . $aTitleImageResponse
			. "`_`" . $mainImageResponse
			. "`_`" . $stockamountSrc
			. "`_`" . $stockamountTooltip
			. "`_`" . $ProductAttributeDelivery
			. "`_`" . ''
			. "`_`" . $pr_number
			. "`_`" . $productinstock
			. "`_`" . $stock_status
			. "`_`" . $attrbimg
			. "`_`" . $notifyStock
			. "`_`" . $product_availability_date_lbl
			. "`_`" . $product_availability_date
			. "`_`" . $additional_vids;
		JFactory::getApplication()->close();
	}

	/**
	 * Add to wishlist function
	 *
	 * @access public
	 * @return void
	 */
	public function addtowishlist()
	{
		$app           = JFactory::getApplication();
		$extraField    = extraField::getInstance();
		$productHelper = productHelper::getInstance();
		$user          = JFactory::getUser();

		ob_clean();
		$section  = 12;
		$row_data = $extraField->getSectionFieldList($section);

		// GetVariables
		$cid             = $this->input->getInt('cid', 0);
		$Itemid          = $this->input->getInt('Itemid', 0);
		$ajaxOn          = $this->input->getInt('ajaxon', 0);
		$wishlistId      = $this->input->getInt('wid', 0);
		$attributeIds    = $this->input->getString('attribute_id', '');
		$propertyIds     = $this->input->getString('property_id', '');
		$subAttributeIds = $this->input->getString('subattribute_id', '');

		if ($ajaxOn == 1 && ($wishlistId == 1 || $wishlistId == 2))
		{
			$post = $this->input->post->getArray();

			$post['product_id'] = $this->input->getInt('product_id', 0);
			$proname            = RedshopHelperProduct::getProductById($post['product_id']);
			$post['view']       = $this->input->getCmd('view', '');
			$post['task']       = $this->input->getCmd('task', '');
			$index              = 0;

			foreach ($row_data as $data)
			{
				$field_name = $data->field_name;
				$type = $data->field_type;

				if (isset($post[$field_name]))
				{
					$data_txt = $post[$field_name];
				}
				else
				{
					$data_txt = '';
				}

				$tmpstr = strpbrk($data_txt, '`');

				if ($tmpstr)
				{
					$tmparray = explode('`', $data_txt);
					$tmp      = new stdClass;
					$tmp      = @array_merge($tmp, $tmparray);

					if (is_array($tmparray))
					{
						$data_txt = implode(",", $tmparray);
					}
				}

				$post['productuserfield_' . $index] = $data_txt;

				$index++;
			}
		}
		else
		{
			$post    = $this->input->post->getArray();
			$proname = RedshopHelperProduct::getProductById($post['product_id']);

			for ($i = 0, $in = count($row_data); $i < $in; $i++)
			{
				$field_name = $row_data[$i]->field_name;

				$type = $row_data[$i]->field_type;

				if (isset($post[$field_name]))
				{
					$data_txt = $post[$field_name];
				}
				else
				{
					$data_txt = '';
				}

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

				$post['productuserfield_' . $i] = $data_txt;
			}
		}

		$rurl = "";

		if (isset($post['rurl']))
		{
			$rurl = base64_decode($post['rurl']);
		}

		// Initialize variable
		$post['user_id'] = $user->id;
		$post['cdate']   = time();

		if (!empty($attributeIds))
		{
			$post['attribute_id'] = $attributeIds;
		}

		if (!empty($propertyIds))
		{
			$post['property_id'] = $propertyIds;
		}

		if (!empty($subAttributeIds))
		{
			$post['subattribute_id'] = $subAttributeIds;
		}

		/** @var RedshopModelProduct $model */
		$model = $this->getModel('product');

		if ($user->id && $ajaxOn != '1')
		{
			if ($model->checkWishlist($post['product_id']) == null)
			{
				if ($model->addToWishlist($post))
				{
					$app->enqueueMessage(JText::_('COM_REDSHOP_WISHLIST_SAVE_SUCCESSFULLY'));
				}
				else
				{
					$app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_SAVING_WISHLIST'));
				}
			}
			else
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_ALLREADY_ADDED_TO_WISHLIST'));
			}
		}
		else
		{
			// User can store wishlist in session
			if ($model->addtowishlist2session($post))
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_WISHLIST_SAVE_SUCCESSFULLY'));
			}
			else
			{
				$app->enqueueMessage(JText::_('COM_REDSHOP_ALLREADY_ADDED_TO_WISHLIST'));
			}
		}

		if ($ajaxOn == 1)
		{
			sleep(2);
			$getproductimage = $productHelper->getProductById($post['product_id']);
			$finalproductimgname = $getproductimage->product_full_image;

			if ($finalproductimgname != '')
			{
				$mainimg = "product/" . $finalproductimgname;
			}
			else
			{
				$mainimg = 'noimage.jpg';
			}

			echo "<span id='basketWrap' ><a href='index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=" . $Itemid . "&pid=" . $post['product_id'] . "'><img src='" . REDSHOP_FRONT_IMAGES_ABSPATH . $mainimg . "' height='30' width='30'/></a></span>:-:" . $proname->product_name . "";
			JFactory::getApplication()->close();
		}
		elseif ($wishlistId == 1)
		{
			$this->setRedirect('index.php?option=com_redshopwishlist=1&view=login&Itemid=' . $Itemid);
		}

		if ($rurl != "")
		{
			$this->setRedirect($rurl);
		}

		$this->setRedirect('index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&cid=' . $cid . '&Itemid=' . $Itemid);
	}

	/**
	 * Add product tag function
	 *
	 * @access public
	 * @return void
	 */

	public function addProductTags()
	{
		$app = JFactory::getApplication();

		// GetVariables
		$cid    = $this->input->getInt('cid');
		$Itemid = $this->input->get('Itemid');
		$post   = $this->input->post->getArray();

		// Initiallize variable
		$tagnames = $post['tags_name'];
		$productid = $post['product_id'];
		$userid = $post['users_id'];

		$model = $this->getModel('product');

		$tagnames = explode(" ", $tagnames);

		for ($i = 0, $in = count($tagnames); $i < $in; $i++)
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

					$app->enqueueMessage($tagname . '&nbsp;' . JText::_('COM_REDSHOP_TAGS_ARE_ADDED'));
				}
				else
				{
					$app->enqueueMessage($tagname . '&nbsp;' . JText::_('COM_REDSHOP_ERROR_ADDING_TAGS'));
				}
			}
			else
			{
				$app->enqueueMessage($tagname . '&nbsp;' . JText::_('COM_REDSHOP_ALLREADY_ADDED'));
			}
		}

		$this->setRedirect('index.php?option=com_redshop&view=product&pid=' . $post['product_id'] . '&cid=' . $cid . '&Itemid=' . $Itemid);
	}

	/**
	 * Add to compare function
	 *
	 * @access public
	 * @return product compare list through ajax
	 */
	public function addToCompare()
	{
		$item = new stdClass;

		$item->productId  = $this->input->getInt('pid', null);
		$item->categoryId = $this->input->getInt('cid', null);

		$compare = new RedshopProductCompare();

		//ob_clean();

		try
		{
			if ($this->input->getCmd('cmd') == 'add')
			{
				//$compare->deleteItem();
				$compare->addItem($item);
			}
			elseif ($this->input->getCmd('cmd') == 'remove')
			{
				$compare->deleteItem($item);
			}

			$response = array(
				'success' => true,
				'html' => $compare->getAjaxResponse(),
				'total' => $compare->getItemsTotal()
			);
		}
		catch (Exception $e)
		{
			$response = array(
				'success' => false,
				'message' => $e->getMessage(),
				'html' => $compare->getAjaxResponse(),
				'total' => $compare->getItemsTotal()
			);
		}

		echo json_encode($response);
	}

	/**
	 * Remove compare from product list
	 *
	 * @access public
	 * @return void
	 */
	public function removeCompare()
	{
		$item = new stdClass;

		$item->productId  = $this->input->getInt('pid', 0);
		$item->categoryId = $this->input->getInt('cid', 0);

		$compare = new RedshopProductCompare();

		if ($item->productId)
		{
			$compare->deleteItem($item);
		}
		// Remove All
		else
		{
			$compare->deleteItem();
		}

		$Itemid = $this->input->getInt('Itemid', 0);

		$this->setRedirect(
			JRoute::_('index.php?option=com_redshop&view=product&layout=compare&Itemid=' . $Itemid, false),
			JText::_('COM_REDSHOP_PRODUCT_DELETED_FROM_COMPARE_SUCCESSFULLY')
		);
	}

	/**
	 * Download Product function
	 *
	 * @access public
	 * @return void
	 */
	public function downloadProduct()
	{
		$Itemid = $this->input->get('Itemid');
		$model = $this->getModel('product');

		$tid = $this->input->getCmd('download_id', "");

		$data = $model->downloadProduct($tid);

		// Today at the end of the day
		$today = time();

		if (count($data) != 0)
		{
			$download_id = $data->download_id;

			// Download Product end date
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
		$post = $this->input->post->getArray();

		$model = $this->getModel('product');

		$tid = $post['tid'];

		$data = $model->downloadProduct($tid);

		$limit = $data->download_max;

		// Today at the end of the day
		$today = time();

		// Download Product end date
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
				header("Content-Type: $ctype", false);
				header('Content-Length: ' . filesize($name));
				header('Content-Disposition: attachment; filename=' . $downloadname);

				// Red file using chunksize
				$this->readfile_chunked($name);
				JFactory::getApplication()->close();
			}
		}
	}

	/**
	 * File read function
	 *
	 * @param   string  $filename  file name
	 * @param   bool    $retbytes  retbytes
	 *
	 * @return bool|int
	 */
	public function readfile_chunked($filename, $retbytes = true)
	{
		// How many bytes per chunk
		$chunksize = 10 * (1024 * 1024);
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
			// Return num. bytes delivered like readfile() does.
			return $cnt;
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
		$uploadDir = JPATH_COMPONENT_SITE . '/assets/document/product/';
		$productId = $this->input->getInt('product_id', 0);
		$name      = $this->input->getCmd('mname', '') . '_' . $productId;

		if ($this->input->files)
		{
			$uploadFileData = $this->input->files->get($name);
			$fileExtension = JFile::getExt($uploadFileData['name']);
			$fileName = RedShopHelperImages::cleanFileName($uploadFileData['name']);

			$uploadFilePath = JPath::clean($uploadDir . $fileName);

			$legalExts = explode(',', Redshop::getConfig()->get('MEDIA_ALLOWED_MIME_TYPE'));

			// If Extension is not legal than don't upload file
			if (!in_array(strtolower($fileExtension), $legalExts))
			{
				echo '<li class="error">' . JText::_('COM_REDSHOP_FILE_EXTENSION_NOT_ALLOWED') . '</li>';
			}

			if (JFile::upload($uploadFileData['tmp_name'], $uploadFilePath))
			{
				$id                     = JFile::stripExt(JFile::getName($fileName));
				$sendData               = array();
				$sendData['id']         = $id;
				$sendData['product_id'] = $productId;
				$sendData['uniqueOl']   = $this->input->getString('uniqueOl', '');
				$sendData['fieldName']  = $this->input->getString('fieldName', '');
				$sendData['ajaxFlag']   = $this->input->getString('ajaxFlag', '');
				$sendData['fileName']   = $fileName;
				$sendData['action']     = JURI::root() . 'index.php?tmpl=component&option=com_redshop&view=product&task=removeAjaxUpload';
				$session = JFactory::getSession();
				$userDocuments = $session->get('userDocument', array());

				if (!isset($userDocuments[$productId]))
				{
					$userDocuments[$productId] = array();
				}

				$userDocuments[$productId][$id] = $sendData;
				$session->set('userDocument', $userDocuments);

				echo "<li id='uploadNameSpan" . $id . "' name='" . $fileName . "'>"
						. "<span>" . $fileName . "</span>"
						. "<a href='javascript:removeAjaxUpload(" . json_encode($sendData) . ");'>&nbsp;" . JText::_('COM_REDSHOP_DELETE') . "</a>"
					. "</li>";
			}
			else
			{
				// WARNING! DO NOT USE "FALSE" STRING AS A RESPONSE!
				// Otherwise onSubmit event will not be fired
				echo "error";
			}
		}
		else
		{
			echo '<li class="error">' . JText::_('COM_REDSHOP_NO_FILE_SELECTED') . '</li>';
		}
	}

	/**
	 * Function to remove Extra Field AJAX upload data
	 *
	 * @return  void
	 */
	public function removeAjaxUpload()
	{
		$id            = $this->input->getString('id', '');
		$productId     = $this->input->getInt('product_id', 0);
		$session       = JFactory::getSession();
		$userDocuments = $session->get('userDocument', array());
		$deleteFile    = true;

		if (isset($userDocuments[$productId]) && array_key_exists($id, $userDocuments[$productId]))
		{
			if ($cart = $session->get('cart'))
			{
				for ($i = 0; $i < $cart['idx']; $i++)
				{
					$fieldName = $userDocuments[$productId][$id]['fieldName'];
					$fileName = $userDocuments[$productId][$id]['fileName'];

					if (isset($cart[$i][$fieldName]))
					{
						$documents = explode(',', $cart[$i][$fieldName]);

						// File exists in cart, not delete then
						if (in_array($fileName, $documents))
						{
							$deleteFile = false;
							break;
						}
					}
				}
			}

			$filePath = JPATH_SITE . '/components/com_redshop/assets/document/product/' . $userDocuments[$productId][$id]['fileName'];
			unset($userDocuments[$productId][$id]);
			$session->set('userDocument', $userDocuments);

			if ($deleteFile && JFile::exists($filePath))
			{
				JFile::delete($filePath);
			}
		}
	}

	/**
	 * ajax upload function
	 *
	 * @access public
	 * @return filename on successfull file download
	 */
	public function downloadDocument()
	{
		$fname = $this->input->getString('fname', '');
		$fpath = REDSHOP_FRONT_DOCUMENT_RELPATH . 'product/' . $fname;

		if (JFile::exists($fpath))
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
			header("Content-Type: $ctype", false);
			header('Content-Length: ' . filesize($fpath));
			header('Content-Disposition: attachment; filename=' . $downloadname);

			// Red file using chunksize
			$this->readfile_chunked($fpath);
			JFactory::getApplication()->close();
		}
	}

	/**
	 *  Go to child
	 *
	 * @return void
	 */
	public function gotochild()
	{
		$producthelper = productHelper::getInstance();
		$objhelper = redhelper::getInstance();

		$post = $this->input->post->getArray();

		$cid = $producthelper->getCategoryProduct($post['pid']);

		$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $post['pid']);

		if (count($ItemData) > 0)
		{
			$pItemid = $ItemData->id;
		}
		else
		{
			$pItemid = RedshopHelperUtility::getItemId($post['pid'], $cid);
		}

		$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $post['pid'] . '&cid=' . $cid . '&Itemid=' . $pItemid, false);

		$this->setRedirect($link);
	}

	/**
	 * Go to nav Product
	 *
	 * @return  void
	 */
	public function gotonavproduct()
	{
		$producthelper = productHelper::getInstance();
		$objhelper = redhelper::getInstance();

		$post = $this->input->post->getArray();

		$cid = $producthelper->getCategoryProduct($post['pid']);

		$ItemData = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $post['pid']);

		if (count($ItemData) > 0)
		{
			$pItemid = $ItemData->id;
		}
		else
		{
			$pItemid = RedshopHelperUtility::getItemId($post['pid'], $cid);
		}

		$link = JRoute::_('index.php?option=com_redshop&view=product&pid=' . $post['pid'] . '&cid=' . $cid . '&Itemid=' . $pItemid, false);

		$this->setRedirect($link);
	}

	/**
	 * Add Notify stock
	 *
	 * @return void
	 */
	public function addNotifystock()
	{
		ob_clean();
		$model = $this->getModel('product');

		$post = $this->input->getArray();

		$product_id = $post['product_id'];
		$property_id = $post['property_id'];
		$subproperty_id = $post['subproperty_id'];

		$notify_user = $model->addNotifystock($product_id, $property_id, $subproperty_id);

		if ($notify_user)
		{
			echo $message = JText::_("COM_REDSHOP_STOCK_NOTIFICATION_ADDED_SUCCESSFULLY");
		}

		JFactory::getApplication()->close();
	}
}

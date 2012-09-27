<?php
/**
 * @version    2.5
 * @package    Joomla.Site
 * @subpackage com_redshop
 * @author     redWEB Aps
 * @copyright  com_redshop (C) 2008 - 2012 redCOMPONENT.com
 * @license    GNU/GPL, see LICENSE.php
 *             com_redshop can be downloaded from www.redcomponent.com
 *             com_redshop is free software; you can redistribute it and/or
 *             modify it under the terms of the GNU General Public License 2
 *             as published by the Free Software Foundation.
 *             com_redshop is distributed in the hope that it will be useful,
 *             but WITHOUT ANY WARRANTY; without even the implied warranty of
 *             MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *             GNU General Public License for more details.
 *             You should have received a copy of the GNU General Public License
 *             along with com_redshop; if not, write to the Free Software
 *             Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301, USA.
 **/
defined('_JEXEC') or die('Restricted access');

require_once JPATH_COMPONENT_ADMINISTRATOR . DS . 'core' . DS . 'controller.php';
require_once(JPATH_COMPONENT . DS . 'helpers' . DS . 'product.php');
require_once(JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_redshop' . DS . 'helpers' . DS . 'template.php');

/**
 * productController
 *
 * @package    Joomla.Site
 * @subpackage com_redshop
 *
 * Description N/A
 */
class productController extends RedshopCoreController
{
    public function displayProductaddprice()
    {
        ob_clean();
        $get  = $this->input->getArray($_GET);
        $data = array();

        $producthelper = new producthelper();
        $carthelper    = new rsCarthelper();

        $product_id = $get['product_id'];
        $quantity   = $get['qunatity'];

        $data['attribute_data']   = str_replace("::", "##", $get['attribute_data']);
        $data['property_data']    = str_replace("::", "##", $get['property_data']);
        $data['subproperty_data'] = str_replace("::", "##", $get['subproperty_data']);

        $data['accessory_data']    = $get['accessory_data'];
        $data['acc_quantity_data'] = $get['acc_quantity_data'];

        $data['acc_attribute_data']   = str_replace("::", "##", $get['acc_attribute_data']);
        $data['acc_property_data']    = str_replace("::", "##", $get['acc_property_data']);
        $data['acc_subproperty_data'] = str_replace("::", "##", $get['acc_subproperty_data']);

        $data['quantity'] = $quantity;

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
    }

    public function displaySubProperty()
    {
        $propid        = $subpropid = array();
        $get           = $this->input->getArray($_GET);
        $producthelper = new producthelper();

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
        $subatthtml = htmlspecialchars_decode(base64_decode($this->input->get->getString('subatthtml', '')));

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
        $get           = $this->input->getArray($_GET);
        $producthelper = new producthelper();

        $property_id    = urldecode($get['property_id']);
        $subproperty_id = urldecode($get['subproperty_id']);

        $product_id     = $get['product_id'];
        $accessory_id   = $get['accessory_id'];
        $relatedprd_id  = $get['relatedprd_id'];
        $main_imgwidth  = $get['main_imgwidth'];
        $main_imgheight = $get['main_imgheight'];
        $redview        = $get['redview'];
        $redlayout      = $get['redlayout'];

        $dispatcher = JDispatcher::getInstance();
        JPluginHelper::importPlugin('redshop_product');
        $pluginResults = $dispatcher->trigger('onBeforeImageLoad', array($get));

        if (!empty($pluginResults))
        {
            $mainImageResponse = $pluginResults[0]['mainImageResponse'];
            $result            = $producthelper->displayAdditionalImage($product_id, $accessory_id, $relatedprd_id, $property_id, $subproperty_id);
        }
        else
        {
            $result            = $producthelper->displayAdditionalImage($product_id, $accessory_id, $relatedprd_id, $property_id, $subproperty_id, $main_imgwidth, $main_imgheight, $redview, $redlayout);
            $mainImageResponse = $result['mainImageResponse'];
        }

        $response                 = $result['response'];
        $aHrefImageResponse       = $result['aHrefImageResponse'];
        $aTitleImageResponse      = $result['aTitleImageResponse'];
        $stockamountSrc           = $result['stockamountSrc'];
        $stockamountTooltip       = $result['stockamountTooltip'];
        $ProductAttributeDelivery = $result['ProductAttributeDelivery'];
        $attrbimg                 = $result['attrbimg'];
        $pr_number                = $result['pr_number'];
        $productinstock           = $result['productinstock'];
        $stock_status             = $result['stock_status'];
        $product_img              = $result['ImageName'];

        echo "`_`" . $response . "`_`" . $aHrefImageResponse . "`_`" . $aTitleImageResponse . "`_`" . $mainImageResponse . "`_`" . $stockamountSrc . "`_`" . $stockamountTooltip . "`_`" . $ProductAttributeDelivery . "`_`" . $product_img . "`_`" . $pr_number . "`_`" . $productinstock . "`_`" . $stock_status . "`_`" . $attrbimg;
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
        $extraField = new extraField();
        $section    = 12;
        $row_data   = $extraField->getSectionFieldList($section);
        // getVariables
        $cid           = $this->input->getInt('cid', null);
        $producthelper = new producthelper();
        $user          = JFactory::getUser();

        $item_id = $this->input->get('Itemid');

        $option = $this->input->get('option');

        $ajaxvar = $this->input->get('ajaxon');
        $mywid   = $this->input->get('wid');
        if ($ajaxvar == 1 && ($mywid == 1 || $mywid == 2))
        {

            $post = $this->input->get('post');

            $post['product_id'] = $this->input->get('product_id');
            $proname            = $producthelper->getProductById($post['product_id']);
            $post['view']       = $this->input->get('view');
            $post['task']       = $this->input->get('task');

            for ($i = 0; $i < count($row_data); $i++)
            {
                if (isset($post[$row_data[$i]->field_name]))

                {
                    $data_txt = $post[$row_data[$i]->field_name];
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

                $post['productuserfield_' . $i] = $data_txt;
            }
        }
        else
        {
            $post = $this->input->getArray($_POST);

            $proname = $producthelper->getProductById($post['product_id']);
            for ($i = 0; $i < count($row_data); $i++)
            {
                if (isset($post[$row_data[$i]->field_name]))

                {
                    $data_txt = $post[$row_data[$i]->field_name];
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

                $post['productuserfield_' . $i] = $data_txt;
            }
        }

        $rurl = "";
        if (isset($post['rurl']))
        {
            $rurl = base64_decode($post['rurl']);
        }
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
                    $this->app->enqueueMessage(JText::_('COM_REDSHOP_WISHLIST_SAVE_SUCCESSFULLY'));
                }
                else
                {
                    $this->app->enqueueMessage(JText::_('COM_REDSHOP_ERROR_SAVING_WISHLIST'));
                }
            }
            else
            {

                $this->app->enqueueMessage(JText::_('COM_REDSHOP_ALLREADY_ADDED_TO_WISHLIST'));
            }
        }
        else
        {
            // user can store wishlist in session
            if ($model->addtowishlist2session($post))
            {
                $this->app->enqueueMessage(JText::_('COM_REDSHOP_WISHLIST_SAVE_SUCCESSFULLY'));
            }
            else
            {
                $this->app->enqueueMessage(JText::_('COM_REDSHOP_ALLREADY_ADDED_TO_WISHLIST'));
            }
        }
        if ($ajaxvar == 1)
        {
            //sleep(2);
            $getproductimage     = $producthelper->getProductById($post['product_id']);
            $finalproductimgname = $getproductimage->product_full_image;
            if ($finalproductimgname != '')
            {
                $mainimg = "product/" . $finalproductimgname;
            }
            else
            {
                $mainimg = 'noimage.jpg';
            }

            echo "<span id='basketWrap' ><a href='index.php?view=wishlist&task=viewwishlist&option=com_redshop&Itemid=" . $item_id . "&pid=" . $post['product_id'] . "'><img src='" . REDSHOP_FRONT_IMAGES_ABSPATH . $mainimg . "' height='30' width='30'/></a></span>:-:" . $proname->product_name . "";
            exit;
        }
        elseif ($mywid == 1)
        {

            $this->setRedirect('index.php?option=' . $option . 'wishlist=1&view=login&Itemid=' . $item_id);
        }
        if ($rurl != "")
        {
            $this->setRedirect($rurl);
        }
        else
        {
            $this->setRedirect('index.php?option=' . $option . '&view=product&pid=' . $post['product_id'] . '&cid=' . $cid . '&Itemid=' . $item_id);
        }
    }

    /**
     * Add product tag function
     *
     * @access public
     * @return void
     */

    public function addProductTags()
    {
        // getVariables
        $cid     = $this->input->getInt('cid', null);
        $item_id = $this->input->get('Itemid');
        $option  = $this->input->get('option');

        $post = $this->input->getArray($_POST);

        // initiallize variable

        $tagnames  = $post['tags_name'];
        $productid = $post['product_id'];
        $userid    = $post['users_id'];

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
                            {
                                $counter = $tag->tags_counter + 1;
                            }
                        }
                        else
                        {
                            $counter = $tag->tags_counter + 1;
                        }

                        $ntag['tags_id']      = $tag->tags_id;
                        $ntag['tags_name']    = $tag->tags_name;
                        $ntag['tags_counter'] = $counter;
                        $ntag['published']    = $tag->published;
                        $ntag['product_id']   = $productid;
                        $ntag['users_id']     = $userid;
                    }
                }
                else
                {

                    $ntag['tags_id']      = 0;
                    $ntag['tags_name']    = $tagname;
                    $ntag['tags_counter'] = 1;
                    $ntag['published']    = 1;
                    $ntag['product_id']   = $productid;
                    $ntag['users_id']     = $userid;
                }

                if ($tags = $model->addProductTags($ntag))
                {

                    $model->addProductTagsXref($ntag, $tags);

                    $this->app->enqueueMessage($tagname . '&nbsp;' . JText::_('COM_REDSHOP_TAGS_ARE_ADDED'));
                }
                else
                {
                    $this->app->enqueueMessage($tagname . '&nbsp;' . JText::_('COM_REDSHOP_ERROR_ADDING_TAGS'));
                }
            }
            else
            {

                $this->app->enqueueMessage($tagname . '&nbsp;' . JText::_('COM_REDSHOP_ALLREADY_ADDED'));
            }
        }

        $this->setRedirect('index.php?option=' . $option . '&view=product&pid=' . $post['product_id'] . '&cid=' . $cid . '&Itemid=' . $item_id);
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
        $post = $this->input->getArray($_REQUEST);

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
        else if ($post['cmd'] == 'remove')
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
        $post = $this->input->getArray($_REQUEST);

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
        $item_id = $this->input->get('Itemid');
        $model   = $this->getModel('product');

        $tid = $this->input->getCmd('download_id', "");

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

                $this->setRedirect("index.php?option=com_redshop&view=product&layout=downloadproduct&tid=" . $download_id . "&Itemid=" . $item_id, $msg);
            }
            else
            {

                $msg = JText::_("COM_REDSHOP_DOWNLOAD_LIMIT_OVER");
                $this->setRedirect("index.php?option=com_redshop&view=product&layout=downloadproduct&Itemid=" . $item_id, $msg);
            }
        }
        else
        {

            $msg = JText::_("COM_REDSHOP_TOKEN_VERIFICATION_FAIL");
            $this->setRedirect("index.php?option=com_redshop&view=product&layout=downloadproduct&Itemid=" . $item_id, $msg);
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
        $post = $this->input->getArray($_POST);

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
        else if (isset($post['mainindex']) && isset($post['additional']))
        {

            $task = $post['mainindex'];

            $id = $post['additional'];

            if ($task == "main")
            {

                $finalname = $model->AdditionaldownloadProduct($id, 0, 1);

                $name = $finalname[0]->media_name;
            }
            else if ($task == "additional")
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
        $cnt       = 0;

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
        $uploaddir  = JPATH_COMPONENT_SITE . DS . 'assets' . DS . 'document' . DS . 'product' . DS;
        $name       = $this->input->get('mname');
        $filename   = time() . '_' . basename($_FILES[$name]['name']);
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
        $fname = $this->input->getString('fname', '');
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

    function gotochild()
    {

        $producthelper = new producthelper();
        $objhelper     = new redhelper();

        $post = $this->input->getArray($_POST);

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
        $objhelper     = new redhelper();

        $post = $this->input->getArray($_POST);

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
}

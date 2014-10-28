<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_products_slideshow
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die;

JLoader::import('redshop.library');

if (!defined('CLASS_DG'))
{
	class DgIparamsPG
	{
		var $id;

		var $ref;

		var $order;

		var $name;
	}

	class DGitemPG
    {
		var $next;

		var $prev;

		// Parent
		var $par;

		// First child DGitemPG
		var $firstc;

		var $params;

		var $cii;

		var $c_info;
	}

	class DgraphPG
	{
		// First DGitemPG
		var $first;

		function DgraphPG()
		{
			$this->first                = new DGitemPG;
			$this->first->next          = null;
			$this->first->prev          = null;
			$this->first->par           = null;
			$this->first->firstc        = null;
			$this->first->params        = new DgIparamsPG;
			$this->first->params->id    = 0;
			$this->first->params->ref   = - 1;
			$this->first->params->order = 0;
			$this->first->params->name  = '';
			$this->cii                  = 0;
			$this->c_info               = array();
		}

		function BuildGraph(&$arr_igr)
		{
			if ($arr_igr && is_array($arr_igr) && count($arr_igr) > 0)
			{
				foreach ($arr_igr as $akey => $curr_cigr)
				{
					$this->Add($curr_cigr, $this->first);
				}
			}
			else
			{
				return false;
			}
		}

		function Add($newgi, $currgi)
		{
			if ($newgi->params->ref == $currgi->params->ref)
			{
				if ($newgi->params->order < $currgi->params->order)
				{
					$newgi->next  = $currgi;
					$newgi->prev  = $currgi->prev;
					$newgi->par   = $currgi->par;
					$currgi->prev = $newgi;

					if ($newgi->prev)
					{
						$newgi->prev->next = $newgi;
					}
					else
					{
						$newgi->par->firstc = $newgi;
					}

					return $newgi;
				}
				else
				{
					if ($currgi->next)
					{
						return $this->Add($newgi, $currgi->next);
					}
					else
					{
						$currgi->next = $newgi;
						$newgi->prev  = $currgi;
						$newgi->par   = $currgi->par;
						$newgi->next  = null;

						return $newgi;
					}
				}
			}
			else
			{
				if ($newgi->params->ref == $currgi->params->id && !$currgi->firstc)
				{
					$currgi->firstc = $newgi;
					$newgi->next    = null;
					$newgi->prev    = null;
					$newgi->par     = $currgi;

					return $newgi;
				}
				else
				{
					$theNext = $this->Next($currgi);

					if ($theNext)
					{
						return $this->Add($newgi, $theNext);
					}
					else
					{
						return false;
					}
				}
			}
		}

		function GetCatInfo($curr_next)
		{
			$cnext = $this->Next($curr_next);

			if ($cnext)
			{
				$this->c_info[$this->cii]['id']   = $cnext->params->id;
				$this->c_info[$this->cii]['name'] = $cnext->params->name;
				$this->cii++;

				return $this->GetCatInfo($cnext);
			}
			else
			{
				return $this->c_info;
			}
		}

		function Next($currgi)
		{
			if ($currgi->firstc)
			{
				return $currgi->firstc;
			}
			elseif ($currgi->next)
			{
				return $currgi->next;
			}
			else
			{
				$thePnex = $this->Pnex($currgi);

				if ($thePnex)
				{
					return $thePnex;
				}
				else
				{
					return false;
				}
			}
		}

		function Pnex($currgi)
		{
			if ($currgi === $this->first)
			{
				return false;
			}
			else
			{
				if ($currgi->par->next)
				{
					return $currgi->par->next;
				}
				else
				{
					return $this->Pnex($currgi->par);
				}
			}
		}
	}

	function create_smart_xml_files($params)
	{
		$database  = JFactory::getDbo();
		$cat_id    = trim($params->get('category_id', '0'));
		$id        = explode(",", $cat_id);
		$load_curr = trim($params->get('load_curr', '1'));

		if ($load_curr == 1)
		{
			$curr_uri = & JFactory::getURI();
			$curr_uri_query = $curr_uri->getQuery(true);

			if (isset($curr_uri_query['option']) && $curr_uri_query['option'] == 'com_redshop')
			{
				if (isset($curr_uri_query['category_id']))
				{
					unset($id);
					$id = array(
						0 => $curr_uri_query['category_id']
					);
				}
			}
		}

		if ($id[0] != 0)
		{
			$query = "SELECT pc.category_id, pc.category_name, pc.published, pc.ordering,pc.ordering, px.category_parent_id  "
					. " FROM #__redshop_category pc, #__redshop_category_xref px "
					. " WHERE pc.published = '1' AND px.category_child_id = pc.category_id";
			$query .= " and (";

			for ($i = 0; $i < count($id) - 1; $i++)
			{
				$query .= "pc.category_id=" . (int) $id[$i] . " or ";
			}

			$query .= "pc.category_id=" . (int) $id[$i] . ")";
			$query .= " ORDER BY px.category_parent_id";
			$database->setQuery($query);
			$rows = $database->loadObjectList();

			for ($l = 0;$l < count($rows);$l++)
			{
				$c_id_name[$rows[$l]->category_id] = $rows[$l]->category_name;
			}

			$cats_info = array();
			$cii = 0;

			foreach ($id as $curr_id)
			{
				$cats_info[$cii]         = array();
				$cats_info[$cii]['id']   = $curr_id;
				$cats_info[$cii]['name'] = $c_id_name[$curr_id];
				$cii++;
			}
		}
		else
		{
			$query = "SELECT pc.category_name, pc.published, pc.ordering,pc.ordering, pc.category_id
					FROM #__redshop_category pc
					WHERE pc.published = 1
					ORDER BY pc.category_id";
			$database->setQuery($query);
			$databaserecord = $database->loadObjectList();
			$ci             = 0;
			$cgr_info       = array();

			for ($ci = 0;$ci < count($databaserecord);$ci++)
			{
				$cgr_info[$ci]                = new DGitemPG;
				$cgr_info[$ci]->next          = null;
				$cgr_info[$ci]->prev          = null;
				$cgr_info[$ci]->par           = null;
				$cgr_info[$ci]->firstc        = null;
				$cgr_info[$ci]->params->id    = $databaserecord[$ci]->category_id;
				$cgr_info[$ci]->params->ref   = $databaserecord[$ci]->category_parent_id;
				$cgr_info[$ci]->params->order = $databaserecord[$ci]->ordering;
				$cgr_info[$ci]->params->name  = $databaserecord[$ci]->category_name;
			}

			$cat_graph = new DgraphPG;
			$cat_graph->BuildGraph($cgr_info);
			$cats_info = array();
			$cats_info = $cat_graph->GetCatInfo($cat_graph->first);
		}

		$module_path       = dirname(__FILE__) . '/';
		$xml_data_filename = $module_path . 'data.xml';
		$xml_data_data     = '<?xml version="1.0" encoding="utf-8"?>
	<data>
	<channel>';
		$xml_data_data_btns = '';
		$c_name             = array();
		$module_path        = dirname(__FILE__) . '/';
		$get_catxml         = write_prodgallery_xml_data($cats_info, $params);

		if ($get_catxml['flag'])
		{
			$xml_data_data_btns .= $get_catxml['xml_data'];
		}

		$roundCorner       = trim($params->get('roundCorner', ''));
		$autoPlayTime      = trim($params->get('autoPlayTime', ''));
		$isHeightQuality   = trim($params->get('isHeightQuality', 'no'));
		$isHeightQuality   = ($isHeightQuality == "yes") ? 'true' : 'false';
		$blendMode         = trim($params->get('blendMode', ''));
		$transDuration     = trim($params->get('transDuration', ''));
		$windowOpen        = trim($params->get('windowOpen', ''));
		$btnSetMargin      = trim($params->get('btnSetMargin', ''));
		$btnDistance       = trim($params->get('btnDistance', ''));
		$titleBgColor      = trim($params->get('titleBgColor', ''));
		$titleTextColor    = trim($params->get('titleTextColor', ''));
		$titleBgAlpha      = trim($params->get('titleBgAlpha', ''));
		$titleMoveDuration = trim($params->get('titleMoveDuration', ''));
		$btnAlpha          = trim($params->get('btnAlpha', ''));
		$btnTextColor      = trim($params->get('btnTextColor', ''));
		$btnDefaultColor   = trim($params->get('btnDefaultColor', ''));
		$btnHoverColor     = trim($params->get('btnHoverColor', ''));
		$btnFocusColor     = trim($params->get('btnFocusColor', ''));
		$changImageMode    = trim($params->get('changImageMode', ''));
		$isShowBtn         = trim($params->get('isShowBtn', ''));
		$isShowBtn         = ($isShowBtn == "yes") ? 'true' : 'false';
		$isShowTitle       = trim($params->get('isShowTitle', ''));
		$isShowTitle       = ($isShowTitle == "yes") ? 'true' : 'false';
		$scaleMode         = trim($params->get('scaleMode', ''));
		$transform         = trim($params->get('transform', ''));
		$isShowAbout       = trim($params->get('isShowAbout', ''));
		$isShowAbout       = ($isShowAbout == "yes") ? 'true' : 'false';
		$titleFont         = trim($params->get('titleFont', ''));
		$xml_data_data     .= $xml_data_data_btns
		. '
	</channel>
	<config>
		<roundCorner>' . $roundCorner . '</roundCorner>
		<autoPlayTime>' . $autoPlayTime . '</autoPlayTime>
		<isHeightQuality>' . $isHeightQuality . '</isHeightQuality>
		<blendMode>' . $blendMode . '</blendMode>
		<transDuration>' . $transDuration . '</transDuration>
		<windowOpen>' . $windowOpen . '</windowOpen>
		<btnSetMargin>' . $btnSetMargin . '</btnSetMargin>
		<btnDistance>' . $btnDistance . '</btnDistance>
		<titleBgColor>' . $titleBgColor . '</titleBgColor>
		<titleTextColor>' . $titleTextColor . '</titleTextColor>
		<titleBgAlpha>' . $titleBgAlpha . '</titleBgAlpha>
		<titleMoveDuration>' . $titleMoveDuration . '</titleMoveDuration>
		<btnAlpha>' . $btnAlpha . '</btnAlpha>
		<btnTextColor>' . $btnTextColor . '</btnTextColor>
		<btnDefaultColor>' . $btnDefaultColor . '</btnDefaultColor>
		<btnHoverColor>' . $btnHoverColor . '</btnHoverColor>
		<btnFocusColor>' . $btnFocusColor . '</btnFocusColor>
		<changImageMode>' . $changImageMode . '</changImageMode>
		<isShowBtn>' . $isShowBtn . '</isShowBtn>
		<isShowTitle>' . $isShowTitle . '</isShowTitle>
		<scaleMode>' . $scaleMode . '</scaleMode>
		<transform>' . $transform . '</transform>
		<isShowAbout>' . $isShowAbout . '</isShowAbout>
		<titleFont>' . $titleFont . '</titleFont>
	</config>
	</data>';
		$xml_prodgallery_file = fopen($xml_data_filename, 'w');
		fwrite($xml_prodgallery_file, $xml_data_data);
		fclose($xml_prodgallery_file);
	}

	function write_prodgallery_xml_data($cat_arr, $params)
	{
		JLoader::load('RedshopHelperAdminImages');

		$catid_arr = array();

		for ($i = 0;$i < count($cat_arr);$i++)
		{
			$catid_arr[] = $cat_arr[$i]['id'];
		}

		$cat_id = implode(", ", $catid_arr);

		global $mosConfig_absolute_path, $sess;
		$database = JFactory::getDbo();

		$ret_array = array(
						'flag' => false,
						'xml_data' => ''
					);

		$imageWidth  = intval($params->get('imageWidth'));
		$imageHeight = intval($params->get('imageHeight'));
		$numbproduct = intval($params->get('numbproduct'));
		$loadtype    = trim($params->get('loadtype', 'random'));

		switch ($loadtype)
		{
			case 'newest':
				$query = 'SELECT distinct(x.product_id),x.category_id,p.* FROM #__redshop_product_category_xref AS x '
						. 'LEFT JOIN #__redshop_product AS p ON x.product_id = p.product_id '
						. 'WHERE p.published=1 '
						. 'AND x.category_id IN (' . $cat_id . ') '
						. 'ORDER BY p.publish_date DESC '
						. 'LIMIT ' . $numbproduct;
			break;
			case 'random':
				$query = 'SELECT distinct(x.product_id),x.category_id,p.* FROM #__redshop_product_category_xref AS x '
						. 'LEFT JOIN #__redshop_product AS p ON x.product_id = p.product_id '
						. 'WHERE p.published=1 ' . 'AND x.category_id IN (' . $cat_id . ') '
						. 'ORDER BY rand() '
						. 'LIMIT ' . $numbproduct;
			break;
			case 'mostsold':
				$query = "SELECT distinct(cx.product_id),cx.category_id,p.*,count(product_quantity) AS qty "
						. "FROM #__redshop_product AS p "
						. ",#__redshop_product_category_xref AS cx "
						. ",#__redshop_order_item AS oi "
						. "WHERE cx.product_id = p.product_id "
						. "AND p.published=1 "
						. "AND cx.category_id IN (" . $cat_id . ") "
						. "AND oi.product_id = p.product_id "
						. "GROUP BY(oi.product_id) "
						. "ORDER BY qty DESC LIMIT 0,$numbproduct";
			break;
			case 'special':
				$query = 'SELECT x.category_id,p.* FROM #__redshop_product_category_xref AS x '
						. 'LEFT JOIN #__redshop_product AS p ON x.product_id = p.product_id '
						. 'WHERE p.published=1 ' . 'AND p.product_special = 1 '
						. 'AND x.category_id IN (' . $cat_id . ') '
						. "GROUP BY(p.product_id) "
						. 'ORDER BY rand() DESC '
						. 'LIMIT ' . $numbproduct;
			break;
		}

		$xml_data = '';
		$database->setQuery($query);
		$rows          = $database->loadObjectList();
		$producthelper = new producthelper;
		$redhelper     = new redhelper;

		for ($k = 0;$k < count($rows);$k++)
		{
			$ret_array['flag'] = true;
			$price_txt         = '';
			$Itemid            = JRequest::getInt('Itemid');
			$ItemData          = $producthelper->getMenuInformation(0, 0, '', 'product&pid=' . $rows[$k]->product_id);

			if (count($ItemData) > 0)
			{
				$Itemid = $ItemData->id;
			}
			else
			{
				$Itemid = $redhelper->getItemid($rows[$k]->product_id);
			}

			if ($params->get('show_price') == "yes")
			{
				// Without vat price
				$productArr        = $producthelper->getProductNetPrice($rows[$k]->product_id, 0, 1);
				$product_price     = $productArr['productPrice'];
				$productVat        = $productArr['productVat'];

				// With vat price
				$product_price_vat = $product_price + $productVat;
				$price_txt         .= $params->get('price_text', ': ');
				$price_txt         .= ' ';

				$abs_price         = abs($rows[$k]->product_price);
				$pricetax          = $params->get('pricetax', 'yes');

				if ($pricetax == 'yes')
				{
					$abs_price = $product_price_vat;
				}
				else
				{
					$abs_price = $product_price;
				}

				$abs_price = $producthelper->getProductFormattedPrice($abs_price);
				$price_txt .= $abs_price;
			}

			$curr_link = JRoute::_('index.php?option=com_redshop&amp;view=product&amp;pid=' . $rows[$k]->product_id . '&amp;Itemid=' . $Itemid, true);

			$imgpath = RedShopHelperImages::getImagePath(
										$rows[$i]->product_full_image,
										'',
										'thumb',
										'product',
										$imageWidth,
										$imageHeight,
										USE_IMAGE_SIZE_SWAPPING
									);
			$pname = $rows[$k]->product_name;

			if (!is_file(REDSHOP_FRONT_IMAGES_RELPATH . "product/" . $rows[$k]->product_full_image))
			{
				$imgpath = REDSHOP_FRONT_IMAGES_ABSPATH . 'noimage.jpg';
			}

			$xml_data .= '<item>
			<link>' . $curr_link . '</link>
			<image>' . $imgpath . '</image>
			<title>' . urlencode($pname) . $price_txt . '</title>
			</item>';
		}

		$ret_array['xml_data'] = $xml_data;

		return $ret_array;
	}

	define('CLASS_DG', 1);
}

$bannerWidth     = intval($params->get('bannerWidth', 912));
$bannerHeight    = intval($params->get('bannerHeight', 700));
$imageWidth      = intval($params->get('imageWidth'));
$imageHeight     = intval($params->get('imageHeight'));
$backgroundColor = trim($params->get('backgroundColor', '#FFFFFF'));
$wmode           = trim($params->get('wmode', 'window'));
$id              = intval($params->get('category_id', 0));

// Include redshop config file.
require_once JPATH_SITE . '/administrator/components/com_redshop/helpers/redshop.cfg.php';
JLoader::load('RedshopHelperAdminConfiguration');

$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperHelper');

create_smart_xml_files($params);

?>
<div class="slideshow-stage">
<script language="javascript">AC_FL_RunContent = 0;</script>
<script src="<?php echo JURI::root();?>modules/mod_redshop_products_slideshow/AC_RunActiveContent.js"
	language="javascript"></script>
<script language="javascript">
if (AC_FL_RunContent == 0)
{
	alert("This page requires AC_RunActiveContent.js.");
}
else
{
	AC_FL_RunContent(
	'codebase', 'http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0',
	'width', '<?php echo $bannerWidth; ?>',
	'height', '<?php echo $bannerHeight; ?>',
	'src', '<?php echo JURI::root(); ?>modules/mod_redshop_products_slideshow/slideshow',
	'quality', 'high',
	'pluginspage', 'http://www.adobe.com/go/getflashplayer_cn',
	'align', 'middle',
	'play', 'true',
	'loop', 'true',
	'scale', 'showall',
	'wmode', '<?php echo $wmode; ?>',
	'devicefont', 'false',
	'flashvars','url=<?php echo JURI::root(); ?>modules/mod_redshop_products_slideshow/data.xml',
	'id', 'AnimatedLines',
	'bgcolor', '<?php echo $backgroundColor; ?>',
	'name', 'AnimatedLines',
	'menu', 'true',
	'allowFullScreen', 'false',
	'allowScriptAccess','sameDomain',
	'movie', '<?php echo JURI::root(); ?>modules/mod_redshop_products_slideshow/slideshow',
	'salign', ''
	);
}
</script>
<noscript>
<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000"
	    codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0"
	    width="<?php echo $bannerWidth;?>"
	    height="<?php echo $bannerHeight; ?>" id="AnimatedLines" align="middle">
	<param name="allowScriptAccess" value="sameDomain"/>
	<param name="allowFullScreen" value="false"/>
	<param name="flashvars"
	       value="url=modules/mod_redshop_products_slideshow/data.xml"/>
	<param name="movie" value="<?php echo JURI::root()?>modules/mod_redshop_products_slideshow/slideshow.swf"/>
	<param name="quality" value="high"/>
	<param name="bgcolor" value="<?php echo $backgroundColor;?>"/>
	<embed
		src="<?php echo JURI::root(); ?>modules/mod_redshop_products_slideshow/slideshow.swf"
		flashvars="url=modules/mod_redshop_products_slideshow/data.xml"
		quality="high"
		bgcolor="<?php echo $backgroundColor; ?>"
		width="<?php echo $bannerWidth; ?>"
		height="<?php echo $bannerHeight; ?>" name="AnimatedLines"
		align="middle" allowScriptAccess="sameDomain" allowFullScreen="false"
		type="application/x-shockwave-flash"
		pluginspage="http://www.adobe.com/go/getflashplayer_cn"/>
</object>
</noscript>
</div>

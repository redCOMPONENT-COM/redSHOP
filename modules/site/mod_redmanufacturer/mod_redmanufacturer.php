<?php
/**
 * @package     RedSHOP.Frontend
 * @subpackage  mod_redshop_redmanufacturer
 *
 * @copyright   Copyright (C) 2005 - 2013 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('_JEXEC') or die('Restricted access');

// Getting the configuration
require_once JPATH_ADMINISTRATOR . '/components/com_redshop/helpers/redshop.cfg.php';
JLoader::import('loadhelpers', JPATH_SITE . '/components/com_redshop');
JLoader::load('RedshopHelperAdminConfiguration');
$Redconfiguration = new Redconfiguration;
$Redconfiguration->defineDynamicVars();

// Getting the configuration
JLoader::load('RedshopHelperAdminCategory');

// get product helper
JLoader::load('RedshopHelperProduct');
JLoader::load('RedshopHelperAdminImages');

/**
 * This class sets all Parameters.
 * Must first call the MOS function, something like:
 * $params = mosParseParams( $module->params );
 * and send the $params variable to this class (productScroller)
 *
 * @param $params the results from mosParseParams( $module->params );
 *
 * @example $scroller = new productScroller($params);
 */
if (!class_exists('redManufacturer'))
{ // Prevent double class declaration

class redManufacturer
{
	/**
	 * @var $NumberOfProducts
	 */
	var $NumberOfProducts = 2;
	/**
	 * @var $featuredProducts
	 */
	var $featuredProducts = false;
	/**
	 * // scroll, alternate, slide
	 * @var $ScrollBehavior
	 */
	var $ScrollBehavior = '1'; //--------------
	/**
	 * @var $PS_DIRECTION
	 */
	var $ScrollDirection = 'no'; //----------------

	var $ScrollDelay = 'fast'; //-------------------
	/**
	 * @var $ScrollAlign
	 */
	var $ScrollAuto = '1'; //----------------------

	var $ScrollWrap = 'circular'; //----------------------

	var $PageLink = 'detail'; //-------------------

	var $ImageBorder = 'yes';

	var $ImageWidth = '100';

	var $ImageHeight = '100';

	var $ScrollWidth = '130';

	var $ScrollHeight = '120';

	var $params = null;
	/**
	 * set mammeters
	 */
	function redManufacturer(&$params)
	{
		$this->params = $params;
		// standard mammeters
		$this->show_product_name         = $params->get('show_product_name', 0);
		$this->show_link_on_product_name = $params->get('show_link_on_product_name', 0);
		$this->show_image                = $params->get('show_image', 1);
		$this->show_price                = $params->get('show_price', "yes");
		$this->NumberOfProducts          = $params->get('NumberOfProducts', $this->NumberOfProducts);
		$this->featuredProducts          = $params->get('featuredProducts', $this->featuredProducts);
		$this->ScrollBehavior            = $params->get('ScrollBehavior', $this->ScrollBehavior);
		$this->ScrollDirection           = $params->get('ScrollDirection', $this->ScrollDirection);
		$this->ImageBorder               = $params->get('ImageBorder', $this->ImageBorder);
		$this->ImageWidth                = $params->get('ImageWidth', $this->ImageWidth);
		$this->ImageHeight               = $params->get('ImageHeight', $this->ImageHeight);
		$this->ScrollWidth               = $params->get('ScrollWidth', $this->ScrollWidth);
		$this->ScrollHeight              = $params->get('ScrollHeight', $this->ScrollHeight);
		$this->ScrollDelay               = $params->get('ScrollDelay', $this->ScrollDelay);
		$this->ScrollWrap                = $params->get('ScrollWrap', $this->ScrollWrap);
		$this->ScrollAuto                = $params->get('ScrollAuto', $this->ScrollAuto);
		$this->PageLink                  = $params->get('PageLink', $this->PageLink);
		if ($this->ScrollDirection == 'yes')
			$this->ScrollDirection = 'true';
		else
			$this->ScrollDirection = 'false';
	}

	/**
	 * Display Product Data
	 */
function displayredManufacturer($limit = 0)
{
	$uri        = JURI::getInstance();
	$url        = $uri->root();
	$database   = JFactory::getDbo();
	$Itemid     = JRequest::getInt('Itemid', 0);
	$extra_data = new producthelper;

	$document = JFactory::getDocument();
	JHTML::Script('jquery-1.js', 'modules/mod_redmanufacturer/js/', false);
	JHTML::Script('jquery.js', 'modules/mod_redmanufacturer/js/', false);
	JHTML::Stylesheet('jquery.css', 'modules/mod_redmanufacturer/css/');

	echo $this->params->get('pretext', "");
	$qlimit = "";
	if ($limit > 0)
	{
		$qlimit = "LIMIT $limit";
	}
	$query = "SELECT m.media_name,ma.manufacturer_name,ma.manufacturer_id FROM #__redshop_manufacturer as ma "
		. "LEFT JOIN #__redshop_media AS m ON m.`section_id`=ma.manufacturer_id "
		. "WHERE m.media_section='manufacturer' "
		. "AND m.published=1 "
		. "AND ma.published=1 "
		. $qlimit;
	$database->setQuery($query);
	$rows = $database->loadObjectList();    ?>

	<script type="text/javascript">
		var dom1 = {};
		dom1.query = jQuery.noConflict(true);
		var mycarousel_itemList = [
			<?php	for($i=0;$i<count($rows);$i++)
					{
						$thumbUrl = RedShopHelperImages::getImagePath(
										$rows[$i]->media_name,
										'',
										'thumb',
										'manufacturer',
										$this->ImageWidth,
										$this->ImageHeight,
										USE_IMAGE_SIZE_SWAPPING
									);

						?>
			{url: "<?php echo $thumbUrl; ?>", title: '<?php echo $rows[$i]->manufacturer_name; ?>', ahref: '<a href="<?php echo JRoute::_('index.php?option=com_redshop&view=manufacturers&layout='.$this->PageLink.'&mid='.$rows[$i]->manufacturer_id.'&Itemid='.$Itemid);?>"  title="<?php echo $rows[$i]->manufacturer_name; ?>">'}
			<?php	if ($i < count($rows)-1)
					{	?>
			,
			<?php  	}
				} ?>
		];

		function mycarousel_itemVisibleInCallback(carousel, item, i, state, evt) {
			// The index() method calculates the index from a
			// given index who is out of the actual item range.

			var idx = carousel.index(i, mycarousel_itemList.length);
			carousel.add(i, mycarousel_getItemHTML(mycarousel_itemList[idx - 1]));
		}
		;

		function mycarousel_itemVisibleOutCallback(carousel, item, i, state, evt) {
			carousel.remove(i);
		}
		;

		/**
		 * Item html creation helper.
		 */
		function mycarousel_getItemHTML(item) {
			var displayItem = '';
			<?php	if($this->show_image==1)
					{ ?>
			displayItem = displayItem + item.ahref + '<img src="' + item.url + '" width="<?php echo $this->ImageWidth; ?>" height="<?php echo $this->ImageHeight; ?>" alt="' + item.title + '" /></a><br/>';
			<?php }
				if($this->show_link_on_product_name==1)
				{	?>
			displayItem = displayItem + item.ahref;

			<?php }
				if($this->show_product_name==1)
			{ ?>
			displayItem = displayItem + '<div align="center">' + item.title + '</div>';
			<?php }
				if($this->show_link_on_product_name==1)
					{	?>
			displayItem = displayItem + '</a>';

			<?php }?>

			return displayItem;
		}
		;

		function mycarousel_initCallback(carousel, item, i, state, evt) {
			var idx = carousel.index(i, mycarousel_itemList.length);
			carousel.startAuto(idx);
		}
		;

		dom1.query(function () {
			dom1.query('#mycarousel').jcarousel({
				wrap: '<?php echo $this->ScrollWrap;?>',
				scroll:<?php echo $this->ScrollBehavior;?>,
				auto:<?php echo $this->ScrollAuto;?>,
				animation: '<?php echo $this->ScrollDelay;?>',
				vertical:<?php echo $this->ScrollDirection;?>,
				itemVisibleInCallback: {onBeforeAnimation: mycarousel_itemVisibleInCallback},
				itemVisibleOutCallback: {onAfterAnimation: mycarousel_itemVisibleOutCallback},
				itemLastOutCallback: mycarousel_initCallback
			});
		});
	</script>
	<style type="text/css">
		.jcarousel-skin-tango .jcarousel-container {

		}

		.jcarousel-skin-tango .jcarousel-container-horizontal {
			width: <?php echo ($this->ScrollWidth+10); ?>px; /* Default 140 Aspect 1 image */
			height: <?php echo ($this->ScrollHeight+10); ?>px; /* Default 140 Aspect 1 image */
			padding-top: 10px;
			padding-bottom: 10px;
			padding-left: 0px;
			padding-right: 0px;
		}

			/* For vertical scrolling */
		.jcarousel-skin-tango .jcarousel-container-vertical {
			width: <?php echo ($this->ScrollWidth+10); ?>px;
			height: <?php echo ($this->ScrollHeight+10); ?>px;
			padding: 10px 10px;

		}

		.jcarousel-skin-tango .jcarousel-clip-horizontal {
			width: <?php echo $this->ScrollWidth; ?>px; /* Default 130 Aspect 1 image change acording to params */
			height: <?php echo $this->ScrollHeight; ?>px; /* Default 120 Aspect 1 image change acording to params */

		}

			/* For vertical scrolling */
		.jcarousel-skin-tango .jcarousel-clip-vertical {
			width: <?php echo $this->ScrollWidth; ?>px;
			height: <?php echo $this->ScrollHeight; ?>px;
		}

		.jcarousel-skin-tango .jcarousel-item {

			padding: 5px;
			width: <?php echo $this->ImageWidth; ?>px; /* Default image width */
			height: <?php echo $this->ImageHeight+25; ?>px; /* Default image height */
		<?php
			if($this->ImageBorder == 'yes')
			{
		?> border: 1px solid;
		<?php
		}
		?>
		}

		.jcarousel-skin-tango .jcarousel-item:hover {
			background-position: 0px -90px;
			cursor: pointer;
		}

		.jcarousel-skin-tango .jcarousel-item-horizontal {
			margin-right: 20px;
		}

			/* For vertical scrolling */
		.jcarousel-skin-tango .jcarousel-item-vertical {
			margin-bottom: 20px;
		}

		.jcarousel-skin-tango .jcarousel-item-placeholder {
			color: #000;
		}

	</style>
	<div class="jcarousel-skin-tango">
		<div style="display: block;" class="jcarousel-container jcarousel-container-horizontal">
			<div>
				<ul id="mycarousel" class="jcarousel-list jcarousel-list-horizontal">
					<?php
					for ($i = 0; $i < count($rows); $i++)
					{
						$thumbUrl = RedShopHelperImages::getImagePath(
										$rows[$i]->media_name,
										'',
										'thumb',
										'manufacturer',
										100,
										100,
										USE_IMAGE_SIZE_SWAPPING
									);
						?>
						<li jcarouselindex="<?php echo $i + 31; ?>"
						    class="jcarousel-item jcarousel-item-horizontal jcarousel-item-<?php echo $i + 31; ?> jcarousel-item-<?php echo $i + 31; ?>-horizontal">
							<a href='<?php echo $url; ?>index.php?option=com_redshop&view=manufacturers&layout=<?php echo $this->PageLink; ?>&mid=<?php echo $rows[$i]->manufacturer_id; ?>&Itemid=<?php echo $Itemid; ?>'
							   title='<?php echo $rows[$i]->manufacturer_name; ?>'>
							   <img src='<?php echo $thumbUrl; ?>' alt='<?php echo $rows[$i]->media_name; ?>' width='100' height='100'>
							</a>
						</li>

					<?php
					}
					?>
				</ul>

			</div>
		</div>
	</div>
<?php
} // end displayredScroller
}
} // end class productScroller

// start of Product Scroller Script
$manufacturer = new redManufacturer($params);

/**
 * Display Product Scroller
 **/
$manufacturer->displayredManufacturer($manufacturer->NumberOfProducts);

<?php
/**
 * @package     Redshop.Layouts
 * @subpackage  Plugin.Content.Redshop_Product
 * @copyright   Copyright (C) 2008-2016 redCOMPONENT.com. All rights reserved.
 * @license     GNU/GPL, see LICENSE
 */

defined('_JEXEC') or die;
extract($displayData);
?>
<input type='hidden' name='prd_main_imgwidth' id='prd_main_imgwidth' value='<?php echo $prodImageWidth ?>'>
<input type='hidden' name='prd_main_imgheight' id='prd_main_imgheight' value='<?php echo $prodImgHeight ?>'>;

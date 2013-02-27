<?php
// no direct access
defined('_JEXEC') or die('Restricted access');
$lang = & JFactory::getLanguage();
$app = &JFactory::getApplication();
$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::root().'administrator/modules/mod_redshop_cpicon/styles.css');

?>
<div id="cpanel">
<div class="icon-wrapper">
	<div class="icon">
		<a href="index.php?option=com_redshop">
			<img src="components/com_redshop/assets/images/redshopcart48.png" alt="redSHOP"><span>redSHOP</span></a>
	</div>
</div>
</div>

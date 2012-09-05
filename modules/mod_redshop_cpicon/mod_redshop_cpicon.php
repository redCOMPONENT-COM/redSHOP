<?php
// no direct access
defined('_JEXEC') or die('Restricted access');

// Ensure that user has access to this function.
$user = &JFactory::getUser();
if (!($user->usertype == 'Super Administrator' || $user->usertype == 'Administrator')) {
  // no display if not allowed
  return;
}

$lang = & JFactory::getLanguage();
$app = &JFactory::getApplication();
$document = &JFactory::getDocument();
$document->addStyleSheet( JURI::root().'administrator/modules/mod_redshop_cpicon/styles.css');

?>

<div id="modredshop_cpanel" style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;">

<div class="icon"><a href="index.php?option=com_redshop"> <img
	src="components/com_redshop/assets/images/redshopcart48.png"
	title="redSHOP" alt="redSHOP" /> <span>redSHOP</span> </a></div>

</div>




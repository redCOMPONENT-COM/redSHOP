<?php
/**
 * @package     redshop.modal.button.php
 *
 * @copyright   Copyright (C) 2023 - redSHOP for Joomla - All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 * @since       __DEPLOY_VERSION__
 */

defined('_JEXEC') or die;

use Joomla\CMS\HTML\HTMLHelper;

HTMLHelper::script('com_redshop/glightbox.js', ['relative' => true]);
HTMLHelper::stylesheet('com_redshop/glightbox.css', ['relative' => true]);

/**
 * Layout variables
 * =======================
 * @var  array  $displayData            // List of data.
 * @var  string $selector               // Class to trigger the modal
 * @var  string $params['buttonText']   // Text for the button
 * @var  string $params['buttonClass']  // Class for the buttom
 * @var  string $params['buttonId']     // Id for the buttom
 * @var  string $params['url']          // url for a iFrame modal
 * @var  string $params['imageThumbPath'] // Path used for <img src="imageThumbPath">
 * @var  string $params['imageMainPath']  // Path for the modal href src
 * @var  string $params['width']        // Width for the iframe - Does not work
 * @var  string $params['height']       // Height for the iframe - Does not work
 * @var  string $params['description']  // Optional Text for the modal description - "title:Xxxx; description:Xxxx"
 * @var  string $params['descPosition'] // Optional position for description - top, bottom, right, left - default bottom
 */

extract($displayData);

if (empty($selector)) {
    $selector = 'RedshopModalButton';
}

if (empty($params['buttonText'])) {
    $buttonText = '';
} else {
    $buttonText = $params['buttonText'];
}

if (empty($params['buttonClass'])) {
    $params['buttonClass'] = '';
}

if (empty($params['buttonId'])) {
    $params['buttonId'] = '';
}

if (empty($params['url'])) {
    $params['url'] = '';
} else {
    $urlOrImage = $params['url'];
}

if (empty($params['imageThumbPath'])) {
    $image = '';
} else {
    if (empty($imageAttributes)) {
        $imageAttributes = ['alt' => 'Image'];
    }
    $imageRender = RedshopLayoutHelper::render('joomla.html.image', ['src' => $params['imageThumbPath'], $imageAttributes]);
    $image       = $imageRender;
}

if (empty($params['imageMainPath'])) {
    $params['imageMainPath'] = '';
} else {
    $urlOrImage = $params['imageMainPath'];
}

if (empty($params['width'])) {
    $width = '';
} else {
    $width = 'width: "' . $params['width'] . '",';
}

if (empty($params['height'])) {
    $height = '';
} else {
    $height = 'height: "' . $params['height'] . '",';
}

if (empty($params['description'])) {
    $params['description'] = '';
    $descPosition          = '';
} else {
    if (empty($params['descPosition'])) {
        $descPosition = '"bottom",';
    } else {
        $descPosition = 'descPosition: "' . $params['descPosition'] . '",';
    }
}
?>

<a class="<?php echo $selector ?> <?php echo $params['buttonClass'] ?>" id="<?php echo $params['buttonId'] ?>"
    href="<?php echo $urlOrImage ?>" data-glightbox="<?php echo $params['description'] ?>">
    <?php echo $image ?>
    <?php echo $buttonText ?>
</a>

<script type="text/javascript">
    var lightboxDescription = GLightbox({
        selector: ".<?php echo $selector ?>",
        openEffect: "zoom",
        closeEffect: "zoom",
        <?php echo $descPosition ?>
        <?php echo $width ?>
        <?php echo $height ?>
    });
</script>

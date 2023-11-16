<?php
/**
 * @package     redshop.modal.a.image.php
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
 * @var  array  $displayData              // List of data.
 * @var  string $selector                 // Class to trigger the modal
 * @var  string $params['aClass']         // Class for the buttom
 * @var  string $params['aId']            // Id for the buttom
 * @var  string $params['url']            // url for a iFrame modal
 * @var  string $params['imageThumbPath'] // Path used for <img src="imageThumbPath">
 * @var  string $params['imageMainPath']  // Path for the modal
 * @var  string $params['width']          // Width for the iframe - Does not work
 * @var  string $params['height']         // Height for the iframe - Does not work
 * @var  string $params['description']    // Optional Text for the modal description - "title:Xxxx; description:Xxxx"
 * @var  string $params['descPosition']   // Optional position for description - top, bottom, right, left - default bottom
 * @var  string $imageAttributes[]        // Array of optional attributes e.g. ['alt' => 'Image name', 'xxx' => 'Xxxx']
 */

extract($displayData);

if (empty($selector)) {
    $selector = 'RedshopModalA';
}

if (empty($params['aClass'])) {
    $params['aClass'] = '';
}

if (empty($params['aId'])) {
    $params['aId'] = '';
}

if (empty($params['url'])) {
    $params['url'] = '';
}

if (empty($params['imageThumbPath'])) {
    $params['imageThumbPath'] = '';
}

if (empty($params['imageMainPath'])) {
    $params['imageMainPath'] = '';
}

if (empty($imageAttributes)) {
    $imageAttributes = ['alt' => 'Image'];
}

if (empty($params['width'])) {
    $params['width'] = '';
}

if (empty($params['height'])) {
    $params['height'] = '';
}

if (empty($params['description'])) {
    $params['description'] = '';
}

if (empty($params['descPosition'])) {
    $descPosition = '"bottom",';
} else {
    $descPosition = 'descPosition: "' . $params['descPosition'] . '"';
}

$image = RedshopLayoutHelper::render('joomla.html.image', ['src' => $params['imageThumbPath'], $imageAttributes]);
?>

<a class="<?php echo $selector ?> <?php echo $params['aClass'] ?>" id="<?php echo $params['aId'] ?>"
    href="<?php echo $params['imageMainPath'] ?>" data-glightbox="<?php echo $params['description'] ?>">
    <?php echo $image; ?>
</a>

<script type="text/javascript">
    var lightboxDescription = GLightbox({
        selector: ".<?php echo $selector ?>",
        openEffect: "zoom",
        closeEffect: "zoom",
        descPosition: <?php echo $descPosition ?>
    });
</script>

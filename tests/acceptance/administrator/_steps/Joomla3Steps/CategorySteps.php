<?php
/**
 * @package     RedShop
 * @subpackage  Step Class
 * @copyright   Copyright (C) 2008 - 2018 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */
use Step\AbstractStep;

class CategorySteps extends AbstractStep
{
    use Step\Traits\CheckIn, Step\Traits\Publish, Step\Traits\Delete;
}
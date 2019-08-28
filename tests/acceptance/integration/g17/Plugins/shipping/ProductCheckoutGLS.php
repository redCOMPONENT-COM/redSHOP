<?php
/**
 * @package     redSHOP
 * @subpackage  Cest
 * @copyright   Copyright (C) 2008 - 2019 redCOMPONENT.com. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

class ProductCheckoutGLS
{
    /**
     * @var \Faker\Generator
     * @since 2.1.3
     */
    public $faker;

    /**
     * @var string
     * @since 2.1.3
     */
    public $categoryName;

    /**
     * @var string
     * @since 2.1.3
     */
    public $productName;

    /**
     * @var string
     * @since 2.1.3
     */
    public $productNumber;

    /**
     * @var string
     * @since 2.1.3
     */
    public $productPrice;

    /**
     * ProductCheckoutGLS constructor.
     * @since 2.1.3
     */
    public function __construct()
    {
        $this->faker            = Faker\Factory::create();
        $this->categoryName     = $this->faker->bothify('CategoryName ?###?');
        $this->productName      = $this->faker->bothify('Testing Product ??####?');
        $this->productNumber    = $this->faker->numberBetween(999, 9999);
        $this->productPrice     = 100;
    }

}
<?php
use  \Cest\AbstractCest;

class CategoryCest extends AbstractCest
{
    /**
     * Method for set new data.
     *
     * @return  array
     */
    protected function prepareNewData()
    {
        return array(
            'name'        => $this->faker->bothify('Category Name ?##?'),
            'type'        => 'Total',
            'value'       => '100',
            'effect'      => 'Global',
            'amount_left' => '10'
        );
    }
}
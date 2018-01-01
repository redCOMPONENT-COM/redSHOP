<?php
use Cest\AbstractCest;
class CategoryCest extends AbstractCest
{
    use Cest\Traits\CheckIn, Cest\Traits\Publish, Cest\Traits\Delete;

    public $nameField = 'name';
    /**
     * Method for set new data.
     *
     * @return  array
     */
    protected function prepareNewData()
    {
        return array(
            'name'   => $this->faker->bothify('Testing Country ?##?'),
            'more_template' => 'grid',
            'template' => 'grid',
            'products_per_page' => $this->faker->numberBetween(1, 10),
        );
    }

    /**
     * Method for set new data.
     *
     * @return  array
     */
    protected function prepareEditData()
    {
        return array(
            'name'   => 'New'. $this->dataNew['name'],
            'more_template' => 'list',
            'template' => 'grid',
            'products_per_page' => $this->faker->numberBetween(1, 10),
        );
    }
}
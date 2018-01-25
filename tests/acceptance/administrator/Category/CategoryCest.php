<?php
use  \Cest\AbstractCest;

class CategoryCest extends AbstractCest
{
    use  Cest\Traits\CheckIn, Cest\Traits\Publish, Cest\Traits\Delete;

    /**
     * Name field, which is use for search
     *
     * @var string
     */
    public $nameField = 'name';

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

    /**
     * Abstract method for run after complete create item.
     *
     * @param   \AcceptanceTester  $tester    Tester
     * @param   Scenario           $scenario  Scenario
     *
     * @return  void
     *
     * @depends testItemCreate
     */
    public function afterTestItemCreate(\AcceptanceTester $tester, Scenario $scenario)
    {
        $tester->wantTo('Run after create category test suite');
    }

    /**
     * @return array
     */
    protected function prepareEditData()
    {
        return array(
            'name'        => 'New' . $this->dataNew['name'],
            'type'        => 'Total',
            'value'       => '100',
            'effect'      => 'Global',
            'amount_left' => '10'
        );
    }
}
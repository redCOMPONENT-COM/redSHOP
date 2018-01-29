<?php
use  \Cest\AbstractCest;
use AcceptanceTester\ProductManagerJoomla3Steps as ProductManagerSteps;
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
    public function deleteDataSave(\AcceptanceTester $tester,  $scenario)
    {
        $tester->wantTo('Run after create item with save button ');
        $tester = new CategorySteps($scenario);
        $tester->deleteItem('New' . $this->dataNew['name']);

    }

    /**
     * Abstract method for run after complete create item.
     *
     * @param   \AcceptanceTester  $tester    Tester
     * @param   Scenario           $scenario  Scenario
     *
     * @return  void
     *
     * @depends testItemCreateSaveClose
     */
    public function deleteDataSaveClose(\AcceptanceTester $tester,  $scenario)
    {
        $tester->wantTo('Run after create item with save button ');
        $tester = new CategorySteps($scenario);
        $tester->deleteItem('New' . $this->dataNew['name']);

    }


    /**
     * Abstract method for run after complete create item.
     *
     * @param   \AcceptanceTester  $tester    Tester
     * @param   Scenario           $scenario  Scenario
     *
     * @return  void
     *
     * @depends testItemCreateSaveNew
     */
    public function afterTestItemCreate(\AcceptanceTester $tester,  $scenario)
    {
        $tester->wantTo('Run after create category test suite');
        $tester = new CategorySteps($scenario);
        $nameCategoryChild = $this->faker->bothify('CategiryChild ?##? ');
        $productName  = $this->faker->bothify('ProductCategory ?##?');
        $productNameSecond  = $this->faker->bothify('Product ?##?');
        $productNumber = $this->faker->numberBetween(1,10000);
        $price = $this->faker->numberBetween(1,100);

        $tester->addCategoryChild('New' . $this->dataNew['name'], $nameCategoryChild, 3);

        $tester   = new ProductManagerSteps($scenario);
        $tester->createProductSaveClose($productName, 'New' . $this->dataNew['name'], $productNumber, $price);
        $tester->createProductSaveClose($productNameSecond, $nameCategoryChild, $productNameSecond, $price);
        
        $tester = new CategorySteps($scenario);
        $tester->addCategoryAccessories($this->dataNew['name'], 4, $productNameSecond);

        $tester   = new ProductManagerSteps($scenario);
        $tester->deleteProduct($productName);
        $tester->deleteProduct($productNameSecond);

        $tester = new CategorySteps($scenario);
        $tester->deleteItem($nameCategoryChild);
        $tester->deleteItem('New' . $this->dataNew['name']);
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
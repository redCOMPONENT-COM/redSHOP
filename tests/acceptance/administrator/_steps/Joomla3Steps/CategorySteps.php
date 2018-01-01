<?php

use Step\AbstractStep;

class CategorySteps extends  AbstractStep
{
    use Step\Traits\CheckIn, Step\Traits\Publish, Step\Traits\Delete;
}
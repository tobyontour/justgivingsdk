<?php

namespace JustGivingApi\Tests;

use PHPUnit\Framework\TestCase;
use JustGivingApi\Models\Model;

class DemoModel extends Model
{
    public $alpha;
    public $beta = [];
}

class ModelTest extends TestCase
{
    public function testArray()
    {
        $data = [
            'alpha' => 1,
            'beta' => [
                'delta' => 2,
                'epsilon' => 3
            ]
        ];

        $demo = new DemoModel($data);

        $this->assertEquals(1, $demo->alpha);
        $this->assertEquals(2, $demo->beta['delta']);
        $this->assertEquals(3, $demo->beta['epsilon']);
    }
}

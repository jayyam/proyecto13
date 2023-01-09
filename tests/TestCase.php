<?php

namespace Tests;

use App\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function assertDatabaseEmpty($table, $connection =null)
    {
        $total = $this>$this->getConnection($connection)->table($table)->count();
        $this->assertSame($total,sprintf(
            "Failed asserting the table [%] is empty, %s %s found.", $table, $total,str_plural('row', $total)
        ));

    }
}

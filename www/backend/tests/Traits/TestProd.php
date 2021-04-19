<?php

namespace Tests\Traits;

trait TestProd
{
    protected function skipTestIfNotProd($message = '')
    {
        if(!$this->isTestingProd()) {
            $this->markTestSkipped('Testes de produção');
        }
    }

    protected function isTestingProd()
    {
        return env('TESTING_PROD') !== false;
    }
}

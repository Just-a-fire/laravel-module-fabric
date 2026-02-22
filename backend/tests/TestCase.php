<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Выводим ошибку в консоль вместо HTML-страницы 500
        $this->withoutExceptionHandling([
            \Illuminate\Validation\ValidationException::class,
            \Illuminate\Auth\Access\AuthorizationException::class,
        ]);
    }
}

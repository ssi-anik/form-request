<?php

declare(strict_types=1);

namespace Anik\Form;

use Illuminate\Support\ServiceProvider;

class FormRequestServiceProvider extends ServiceProvider
{
    public function boot()
    {
        $this->app->resolving(FormRequest::class, function ($form, $app) {
            $form = FormRequest::createFrom($app['request'], $form);
            $form->setContainer($app);
        });

        $this->app->afterResolving(FormRequest::class, function (FormRequest $form) {
            $form->validate();
        });
    }
}

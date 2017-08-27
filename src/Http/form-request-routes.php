<?php

use Anik\Form\Http\TestFormRequest;

$app->get('form-request', function (TestFormRequest $request) {
	return $request->all();
});
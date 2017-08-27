<?php

class FormRequestTest extends TestCase
{
	public function testValidationExceptionAgeIntegerRaised () {
		$response = $this->call('get', "form-request", [
			'name' => 'ssi-anik',
			'age'  => 'abcd',
		]);
		$this->assertEquals(422, $response->getStatusCode());
	}

	public function testValidationPasses () {
		$request = [
			'name' => 'ssi-anik',
			'age'  => 12,
		];
		$response = $this->call('get', "form-request", $request);
		$this->assertEquals(200, $response->getStatusCode());
		$this->assertEquals($response->getContent(), json_encode($request));
	}

	public function testValidationExceptionRaiseForAuthorization () {
		$response = $this->call('get', "form-request", [
			'name' => 'poltu',
			'age'  => 12,
		]);
		$this->assertEquals(401, $response->getStatusCode());
	}

}

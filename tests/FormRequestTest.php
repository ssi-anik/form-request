<?php

namespace Anik\Form\Test;

use Anik\Form\FormRequestServiceProvider;
use Anik\Form\Test\Extensions\DontUseThisTestRequest;
use Anik\Form\Test\Extensions\DontUseThisTestRequestAuthorizationError;
use Anik\Testbench\TestCase;
use Laravel\Lumen\Routing\Router;

class FormRequestTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function serviceProviders(): array
    {
        return [FormRequestServiceProvider::class];
    }

    protected function routes(Router $router): void
    {
        $router->post('form-request[/{param}]', function (DontUseThisTestRequest $request, $param = null) {
            return response()->json([
                'all' => $request->all(),
                'validated' => $request->validated(),
                'route_info' => $request->route(),
                'param' => $request->route('param'),
            ]);
        });
    }

    public function testValidationExceptionForInvalidData()
    {
        $this->post('form-request', [
            'name' => 'form-request',
            'age' => 'test',
        ])->seeStatusCode(422);
    }

    public function testValidationExceptionDefaultResponseFormat()
    {
        $response = $this->post('form-request')->seeStatusCode(422)->response->getData(true);
        $this->assertIsString($response['message']);
        $this->assertIsArray($response['errors']);
    }

    public function testValidationExceptionWithMessageAndStatusCode()
    {
        $response = $this->post('form-request', [
            'error_message' => 'This should be error message',
            'status_code' => 400,
        ])->seeStatusCode(400)->response->getData(true);
        $this->assertIsString($response['message']);
        $this->assertSame('This should be error message', $response['message']);
    }

    public function testValidationPasses()
    {
        $response = $this->post("form-request/testing", [
            'name' => 'form-request',
            'age' => 12,
            'extra' => 'data',
        ])->seeStatusCode(200)->response->getContent();
        $response = json_decode($response, true);

        $this->assertSame('data', $response['all']['extra']);
        $this->assertIsArray($response['route_info']);
        $this->assertSame('testing', $response['param'], 'Found value: ' . $response['param']);
        $this->assertCount(2, $response['validated'], 'More than 2 is returned in the validated field');
    }

    public function testAuthorizationForRequest()
    {
        $this->post('form-request', ['unauthorized' => true])->seeStatusCode(403);
    }

    public function testMessages()
    {
        $response = $this->post('form-request', ['age' => 'age', 'message' => true])->response->getData(true);
        $this->assertSame('The age must be a number value', $response['errors']['age'][0]);
    }

    public function testAttributes()
    {
        $response = $this->post('form-request', ['age' => 'age', 'attribute' => true])->response->getData(true);

        $this->assertSame('The oldness must be a number.', $response['errors']['age'][0]);
    }

    public function testErrorResponse()
    {
        $response = $this->post('form-request', ['age' => 'age', 'response' => true])->response->getData(true);

        $this->assertArrayHasKey('faults', $response);
        $this->assertArrayHasKey('note', $response);
    }
}

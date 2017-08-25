<?php namespace Anik\Form;

use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

abstract class FormRequest
{
	protected $request;

	public function __construct (Request $request) {
		$this->request = $request;
		if (false === $this->authorize()) {
			throw new UnauthorizedException();
		}

		$validator = app('validator')->make($this->all(), $this->rules(), $this->messages());

		if ($validator->fails()) {
			throw new ValidationException($validator->errors());
		}
	}

	protected function authorize () {
		return true;
	}

	abstract protected function rules ();

	protected function messages () {
		return [];
	}

	public function __call ($method, $args) {
		return call_user_func([
			$this->request,
			$method,
		], $args);
	}
}
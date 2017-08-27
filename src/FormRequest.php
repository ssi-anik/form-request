<?php namespace Anik\Form;

use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;

abstract class FormRequest extends Request
{
	public function validate () {
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
}
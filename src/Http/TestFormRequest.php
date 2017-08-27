<?php namespace Anik\Form\Http;

use Anik\Form\FormRequest;

class TestFormRequest extends FormRequest
{
	public function authorize () {
		return $this->has('name') && $this->get('name') == 'ssi-anik';
	}

	public function rules () {
		return [
			'name'  => 'required',
			'age'   => 'integer',
			'money' => 'integer|size:1000',
		];
	}
}
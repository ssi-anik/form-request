<?php namespace Anik\Form;

use Illuminate\Contracts\Support\MessageBag;

class ValidationException extends \Exception
{
	private $messages;

	public function __construct (MessageBag $messageBag) {
		parent::__construct("Form couldn't be validated.", 422);
		$this->messages = $messageBag->toArray();
	}

	public function getResponse () {
		return $this->messages;
	}

	public function getStatusCode () {
		return 422;
	}
}
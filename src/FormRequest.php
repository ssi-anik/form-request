<?php

declare(strict_types=1);

namespace Anik\Form;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Laravel\Lumen\Http\Request;

abstract class FormRequest extends Request
{
    /**
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * @var \Illuminate\Contracts\Validation\Validator
     */
    protected $validator;

    protected function errorMessage(): string
    {
        return 'The given data was invalid.';
    }

    protected function statusCode(): int
    {
        return 422;
    }

    protected function errorResponse(): ?JsonResponse
    {
        return response()->json([
            'message' => $this->errorMessage(),
            'errors' => $this->validator->errors()->messages(),
        ], $this->statusCode());
    }

    protected function failedAuthorization(): void
    {
        throw new AuthorizationException();
    }

    protected function validationFailed(): void
    {
        throw new ValidationException($this->validator, $this->errorResponse());
    }

    /**
     * Allows to call any of validator methods
     * before the validation rules are actually evaluated.
     */
    protected function withValidator(): void
    {
        //
    }

    protected function validationPassed()
    {
        //
    }

    public function validated(): array
    {
        return $this->validator->validated();
    }

    public function validate(): void
    {
        if (false === $this->authorize()) {
            $this->failedAuthorization();
        }

        $this->validator = $this->app->make('validator')
                                     ->make($this->all(), $this->rules(), $this->messages(), $this->attributes());
        $this->withValidator();

        if ($this->validator->fails()) {
            $this->validationFailed();
        }

        $this->validationPassed();
    }

    public function setContainer($app)
    {
        $this->app = $app;
    }

    protected function authorize(): bool
    {
        return true;
    }

    abstract protected function rules(): array;

    protected function messages(): array
    {
        return [];
    }

    protected function attributes(): array
    {
        return [];
    }
}

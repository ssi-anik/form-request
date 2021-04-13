<?php

namespace Anik\Form\Test\Extensions;

use Anik\Form\FormRequest;
use Illuminate\Http\JsonResponse;

class DontUseThisTestRequest extends FormRequest
{
    protected function authorize(): bool
    {
        return $this->request->has('unauthorized') ? false : parent::authorize();
    }

    protected function errorMessage(): string
    {
        return $this->get('error_message') ?? parent::errorMessage();
    }

    protected function statusCode(): int
    {
        return $this->get('status_code') ?? parent::statusCode();
    }

    protected function errorResponse(): ?JsonResponse
    {
        if (!$this->has('response')) {
            return parent::errorResponse();
        }

        return response()->json([
            'note' => $this->errorMessage(),
            'faults' => $this->validator->errors()->messages(),
        ], $this->statusCode());
    }

    protected function rules(): array
    {
        return [
            'name' => 'required',
            'age' => 'required|numeric',
        ];
    }

    protected function messages(): array
    {
        if (!$this->has('message')) {
            return parent::messages();
        }

        return [
            'numeric' => 'The :attribute must be a number value',
        ];
    }

    protected function attributes(): array
    {
        if (!$this->has('attribute')) {
            return parent::attributes();
        }

        return [
            'age' => 'oldness',
        ];
    }
}

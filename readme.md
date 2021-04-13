Form-Request
[![codecov](https://codecov.io/gh/ssi-anik/form-request/branch/master/graph/badge.svg?token=1P5773S0SY)](https://codecov.io/gh/ssi-anik/form-request)
[![Total Downloads](https://poser.pugx.org/anik/form-request/downloads)](//packagist.org/packages/anik/form-request)
[![Latest Stable Version](https://poser.pugx.org/anik/form-request/v)](//packagist.org/packages/anik/form-request)
===

A package that helps developer to segregate the validation logic from controller to a separate dedicated class. Lumen
doesn't have any `FormRequest` class like Laravel. This will let you do that.

### Installation

1. Install the package by running `composer require anik/form-request` from your terminal being in the project
   directory.
2. Register `\Anik\Form\FormRequestServiceProvider::class` to your `bootstrap/app.php` as a provider.

```php
// bootstrap/app.php
$app->register(\Anik\Form\FormRequestServiceProvider::class);
```

### How to use?

- Create a class that extends `Anik\Form\FormRequest` class.
- You must override `rules` method of the `Anik\Form\FormRequest` class. Define your validation rules in it. Must return
  an **array**.
- You can define validation **messages** by overriding `messages` method. Default is `[]`.
- You can define custom pretty **attribute** names by overriding `attributes` method. Default is `[]`.
- You can override `authorize` method to define the authorization logic if the client is authorized to submit the form.
  Must return a boolean value. Default is `true`. When returning `false`, it'll
  raise `\Illuminate\Auth\Access\AuthorizationException` exception.
- If the validation fails, it will throw `Illuminate\Validation\ValidationException`.
    - By default, it returns response in `{"message": "The given data was invalid.", "errors": []}` format with status
      code `422`. Handle the exception in `app/Exceptions/Handler.php`'s `render` method if you want to modify the
      response.
    - Override the `statusCode` method to return the status of your choice. Must return `int`. Default is `422`.
    - Override the `errorMessage` method to return the message of your choice. Must return `string`. Default
      is `The given data was invalid.`
    - Override the `errorResponse` method to return response of your choice when the validation fails. Must return
      either of type `\Symfony\Component\HttpFoundation\Response` or `null`.
- Now you can inject your **Request** class through the method injections. All the methods
  of `Laravel\Lumen\Http\Request` class is available in your request class.
- The `FormRequest::validated()` method will return the validated data when the validation passes.

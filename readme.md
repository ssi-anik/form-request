## Form-Request
A package that helps developer to segregate the validation logic from controller to a separate dedicated class. Lumen doesn't have any `FormRequest` class like Laravel. This will let you do that. 

### Installation
First of all, you will need composer installed. By running `composer require anik/form-request` from your terminal will install the package inside your project.

### How to use?
1. Create a class that extends `Anik\Form\FormRequest`
2. Override `rule` method from `FormRequest` class. Define your validation rules inside that method.
3. You can define your messages by overriding `messages` method.
4. `authorize` method is also available to guard the request. Return `true` or `false` from this method. This will raise `Unauthorized` exception.
5. If the validation fails, it will throw exception of `Anik\Form\ValidationException` class. Handle it on your `app/Exception/Handle.php` file. `getResponse` method returns the messages.
6. Now you can use your **Request** class in method injections. All the methods from `Illuminate\Http\Request` class is available.

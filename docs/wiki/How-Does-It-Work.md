1. **Load the html form into the FormFactory**

    ```php
    $form = FormFactory::fromHtml($htmlForm, $defaultValues);
    ```

    - The FormFactory automatically creates default validators and filters for all input elements.
    - The FormFactory extracts additional custom validation rules and filters from the form.
    - The FormFactory optionally injects default data into the form input elements.

2. **Validate the form against submitted data**

    ```php
    $result = $form->validate($_POST);
    ```

    Under the hood it uses [zend-inputfilter](https://github.com/zendframework/zend-inputfilter) which makes all its
    [validators](http://framework.zend.com/manual/current/en/modules/zend.validator.set.html) and
    [filters](http://framework.zend.com/manual/current/en/modules/zend.filter.set.html) available to you.

3. **Render the form**

    ```php
    echo $form->asString($validationResult);
    ```

    Before rendering, the FormFactory removes any data validation attributes used to instantiate custom validation
    (e.g. `data-validators`, `data-filters`). This also removes possible sensitive data that was used to setup
    the validators.

    The `$validationResult` is optional and triggers the following tasks:
    - The FormFactory injects filtered submitted data into the input elements.
    - The FormFactory adds error messages next to the input elements.
    - The FormFactory sets the `aria-invalid="true"` attribute for invalid input elements.
    - The FormFactory adds the bootstrap `has-danger` css class to the parent element.

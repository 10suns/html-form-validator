<?php
/**
 * html-form-validator (https://github.com/xtreamwayz/html-form-validator)
 *
 * @see       https://github.com/xtreamwayz/html-form-validator for the canonical source repository
 * @copyright Copyright (c) 2016 Geert Eltink (https://xtreamwayz.com/)
 * @license   https://github.com/xtreamwayz/html-form-validator/blob/master/LICENSE.md MIT
 */

namespace Xtreamwayz\HTMLFormValidator;

interface ValidationResultInterface
{
    /**
     * ValidationResult constructor.
     *
     * @param array       $rawInputData
     * @param array       $validatedData
     * @param array       $errors
     * @param null|string $method
     * @param null|string $submitName
     */
    public function __construct(
        array $rawInputData,
        array $validatedData,
        array $errors,
        $method = null,
        $submitName = null
    );

    /**
     * Check if the validation was successful
     *
     * If there are no validation messages set and the request method is null or POST,
     * the validation result object is considered valid.
     *
     * @return bool
     */
    public function isValid();

    /**
     * Checks if submit button is clicked or return its name
     *
     * If a specific name is given it checks if a submit button is clicked and returns a boolean.
     * In case no name is given, it returns the name of the clicked button or null if no named was clicked.
     *
     * @param null|string $name
     *
     * @return null|boolean|string
     */
    public function isClicked($name = null);

    /**
     * Get validation messages
     *
     * @return array
     */
    public function getMessages();

    /**
     * Get the raw input values
     *
     * @return array
     */
    public function getRawValues();

    /**
     * Get the filtered input values
     *
     * @return array
     */
    public function getValues();
}

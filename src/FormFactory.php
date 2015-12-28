<?php

namespace Xtreamwayz\HTMLFormValidator;

use DOMDocument;
use DOMElement;
use Xtreamwayz\HTMLFormValidator\InputType;
use Zend\InputFilter\InputFilter;
use Zend\Validator;

class FormFactory
{
    private $document;

    /**
     * @var InputType\AbstractInputType[]
     */
    private $inputTypes = [
        'email'  => InputType\Email::class,
        'number' => InputType\Number::class,
        'text'   => InputType\Text::class,
    ];

    public function __construct($htmlForm)
    {
        // Create new doc
        $this->document = new DOMDocument('1.0', 'utf-8');

        // Don't add missing doctype, html and body
        $this->document->loadHTML($htmlForm, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);
    }

    public static function fromHtml($htmlForm)
    {
        return new self($htmlForm);
    }

    public function validate(array $data)
    {
        $inputFilter = new InputFilter();

        $elements = $this->document->getElementsByTagName('input');
        /** @var DOMElement $element */
        foreach ($elements as $element) {
            // Set some basic vars
            $name = $element->getAttribute('name');
            if (! $name) {
                // Silently continue, might be a submit input
                continue;
            }

            // Get element value
            $value = (isset($data[$name])) ? $data[$name] : $element->getAttribute('value');

            // Set input value
            $reuseSubmittedValue = filter_var(
                $element->getAttribute('data-reuse-submitted-value'),
                FILTER_VALIDATE_BOOLEAN
            );
            if ($reuseSubmittedValue) {
                $element->setAttribute('value', $value);
            }

            $type = $element->getAttribute('type');
            if (isset($this->inputTypes[$type])) {
                $validator = new $this->inputTypes[$type];
                $inputFilter->add($validator($element));
            }
        }

        $inputFilter->setData($data);
        $validationErrors = [];

        // Do some real validation
        if (! $inputFilter->isValid()) {
            foreach ($inputFilter->getInvalidInput() as $error) {
                $validationErrors[$error->getName()] = $error->getMessages();
            }
        }

        /* TODO: Set validated values
        foreach ($inputFilter->getValues() as $id => $value) {
            $element = $this->document->getElementById($id);
            $element->setAttribute('value', $value);
        }*/

        // Return validation result
        return new ValidationResult($inputFilter->getRawValues(), $inputFilter->getValues(), $validationErrors);
    }

    public function asString()
    {
        $this->document->formatOutput = true;

        return $this->document->saveHTML();
    }
}

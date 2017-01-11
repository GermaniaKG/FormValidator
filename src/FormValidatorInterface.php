<?php
namespace Germania\FormValidator;

interface FormValidatorInterface
{
    /**
     * Checks if the form has been sent, i.e. at least one required form field
     *
     * @return boolean
     */
    public function isSubmitted();


    /**
     * Returns TRUE if the form values are complete and valid.
     *
     * @return boolean
     */
    public function isValid();
}

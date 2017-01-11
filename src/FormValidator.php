<?php
namespace Germania\FormValidator;

class FormValidator
{

    const SUBMITTED = 1;
    const VALID = 2;

    /**
     * Holds status definition
     * @var int
     */
    protected $flags;


    /**
     * @var array
     */
    public $required_fields = array();

    /**
     * @var array
     */
    public $optional_fields = array();


    /**
     * @param array $required Array with field and filter definitions
     * @param array $optional Array with field and filter definitions, default: empty array
     */
    public function __construct(array $required, array $optional = array() )
    {
        $this->required_fields = $required;
        $this->optional_fields = $optional;
    }


    /**
     * @param array  $raw_user_input Ususally `$_POST`
     * @return array The filtered user input
     */
    public function __invoke( $raw_user_input )
    {
        // Reset status
        $this->setFlag(static::SUBMITTED, false);
        $this->setFlag(static::VALID, false);

        // Prepare result
        $filtered_input = filter_var_array( $raw_user_input, array_merge(
            $this->required_fields,
            $this->optional_fields
        ));

        // Evaluate result
        $is_empty_arr = array();
        foreach( $this->required_fields as $required => $filter_constant):
            $is_empty_arr[ $required ] = !empty($filtered_input[ $required ]);
        endforeach;

        $submitted = in_array( true, $is_empty_arr, "strict");
        $this->setFlag(static::SUBMITTED, $submitted);

        $valid = !in_array( false, $is_empty_arr, "strict");
        $this->setFlag(static::VALID, $valid);

        // Return filtered data
        return $filtered_input;
    }



    /**
     * Checks if the form has been sent.
     *
     * @return boolean
     */
    public function isSubmitted()
    {
        return $this->hasFlag( static::SUBMITTED );
    }


    /**
     * Returns TRUE if the form values are complete and valid.
     *
     * @return boolean
     */
    public function isValid()
    {
        return $this->hasFlag( static::SUBMITTED | static::VALID);
    }



    protected function hasFlag($flag)
    {
        return (($this->flags & $flag) == $flag);
    }

    protected function setFlag($flag, $value)
    {
        if($value) {
            $this->flags |= $flag;
        }
        else {
            $this->flags &= ~$flag;
        }
    }


}

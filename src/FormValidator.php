<?php
namespace Germania\FormValidator;

class FormValidator implements FormValidatorInterface
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
     * @var callable
     */
    public $input_container_factory = array();


    /**
     * @param array $required Array with field and filter definitions
     * @param array $optional Array with field and filter definitions, default: empty array
     * @param array $input_container_factory Optional callable that takes the filtered input and returns an InputContainer
     */
    public function __construct(array $required, array $optional = array(), callable $input_container_factory = null )
    {
        $this->required_fields = $required;
        $this->optional_fields = $optional;
        $this->input_container_factory = $input_container_factory
                                       ?: function( $filtered_input ) { return new InputContainer($filtered_input); };
    }


    /**
     * @param array  $raw_user_input Ususally `$_POST`
     * @return array The filtered user input
     */
    public function __invoke( $raw_user_input, callable $input_container_factory = null  )
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
        $factory = $input_container_factory ?: $this->input_container_factory;
        return $factory($filtered_input);
    }



    /**
     * @implements FormValidatorInterface
     * @return boolean
     */
    public function isSubmitted()
    {
        return $this->hasFlag( static::SUBMITTED );
    }


    /**
     * @implements FormValidatorInterface
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

    public function addRequired( $field, $flag)
    {
        $this->required_fields[ $field ] = $flag;
        $this->removeOptional( $field );
        return $this;
    }

    public function removeRequired( $field )
    {
        if (array_key_exists($field, $this->required_fields)):
            unset($this->required_fields[ $field ]);
        endif;
        return $this;
    }

    public function addOptional( $field, $flag)
    {
        $this->optional_fields[ $field ] = $flag;
        $this->removeRequired( $field );
        return $this;
    }


    public function removeOptional( $field )
    {
        if (array_key_exists($field, $this->optional_fields)):
            unset($this->optional_fields[ $field ]);
        endif;
        return $this;
    }

}

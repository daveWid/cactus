<?php
namespace DataMapper\Kohana;

/**
 * A Base object that adds in Kohana specific validation.
 *
 * @package    DataMapper
 * @author     Dave Widmer <dave@davewidmer.net>
 */
abstract class Object extends \DataMapper\Object
{
	/**
	 * @var   Validation   The validation object
	 */
	protected $validation = null;

	/**
	 * Cleans all of the "modified" fields
	 *
	 * @return   $this
	 */
	public function clean()
	{
		$this->validation = null;
		return parent::clean();
	}

	/**
	 * Checks to see if the data is valid
	 *
	 * @uses     Validation::check
	 * @return   boolean   Does this object contain valid data?
	 */
	public function validate()
	{
		if ($this->validation === null)
		{
			$this->validation = $this->validation_rules(new \Validation($this->data));
		}

		return $this->validation->check();
	}

	/**
	 * Gets any validation errors
	 *
	 * @uses    Validation::errors
	 * @param   type     $file        The path to the message file
	 * @param   boolean  $translate   Translate the errors?
	 * @return  array
	 */
	public function errors($file = null, $translate = true)
	{
		return $this->validation->errors($file, $translate);
	}

	/**
	 * Sets and returns validation for this object
	 *
	 * @param   Validation   $valid   The validation object to add rules to
	 * @return  Validation            A validation object for this data structure
	 */
	abstract protected function validation_rules(\Validation $valid);

}

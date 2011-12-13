<?php defined('SYSPATH') or die('No direct script access.');

interface Interface_DAO
{
	/**
	 * Checks to see if all the data is valid.
	 *
	 * @return  boolean
	 */
	public function valid();

	/**
	 * @var   boolean   Is this a new object?
	 */
	public $is_new;

	/**
	 * Cleans all of the data that was marked dirty.
	 *
     * @return   $this
	 */
	public function clean();
}

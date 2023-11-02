<?php

namespace TK\EazyCFCaptcha\Helper;

use ArrayAccess;

class EazyCFCaptchaExercise implements ArrayAccess {
	public $var1;
	public $var2;
	public $operator;
	public $bot_var;
	public $bot_operator;

	public $solution;

	protected $allowed_operators = array(
		'+',
		'-'
	);

	public function __construct($easy = false)
	{
		$this->operator = $this->randomOp();
		$this->var1 = rand( 10 * ($easy ? .1 : 1), 90 * ($easy ? .1 : 1) );
		$this->var2 = rand( 0, $easy && $this->operator === '-' ? $this->var1 : 9 );
		$this->bot_var = rand( 1, 90 );
		$this->bot_operator = $this->randomOp();
		$this->solution = $this->calcSolution($this->var1, $this->var2, $this->operator);
	}

	protected function randomOp()
	{
		return $this->allowed_operators[rand(0, count($this->allowed_operators) - 1)];
	}

	protected function calcSolution($a, $b, $op)
	{
		switch(trim($op)) {
			case '-': return $a - $b;
			case '+': return $a + $b;
		}
		return -1;
	}

	public function offsetExists ($offset)
	{
		return isset($this->$offset);
	}

	public function offsetGet ($offset)
	{
		return isset($this->$offset) ? $this->$offset : null;
	}

	public function offsetSet ($offset, $value)
	{
		if (!is_null($offset) && isset($this->$offset)) {
			$this->$offset = $value;
		}
	}

	public function offsetUnset ($offset)
	{
		$this->$offset = null;
	}
}

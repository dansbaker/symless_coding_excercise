<?php
require('vendor/autoload.php');

class game
{
	public $stones; //int - number of stones remaining in game
	public $max_move; //int - maximum number of stones removed in one move

	//construct the game with $stones amount of stones. Optionally override the defauly min and max moves.
	function __construct($stones, $max_move = 3)
	{
		$this->stones = $stones;
		$this->max_move = $max_move;
	}	

	//Indicate's whether the current position is "safe" - returns boolean true if the stone count favours the player
	public function is_position_winnable()
	{
		return ($this->stones % ($this->max_move + 1))  != 0;
	}

}


class testGame extends PHPUnit_Framework_TestCase
{
	function testIs_position_winnable()
	{
		//Test sample inputs for 1-3 stones per move (standard game rules) with known "safe" and "unsafe" positions (remaining stones)
		$max_move = 3;		
		$known_unwinnable_positions = Array(20, 16, 12, 8, 4); //Starting my turn with any of these numbers of stones left means that on the following move, my competitor can move to a safe/winnable position. He can always prevent me from reaching the next "safe" space.
		$known_winnable_positions = Array(21, 17, 13, 9, 2); //Selection of safe spaces.
		foreach($known_winnable_positions as $num_stones)
		{
			$game = new game($num_stones,$max_move);
			$this->assertTrue($game->is_position_winnable());
		}

		foreach($known_unwinnable_positions as $num_stones)
		{
			$game = new game($num_stones,$max_move);
			$this->assertFalse($game->is_position_winnable());
		}

		//Test games up to 100 stones and ensure that is_position_winnable returns true when $num_stones mod $max_move + 1 != 0.
		//Any of those positions will allow me to move to a "safe" position on my first move, making it impossible for the
		//opponent to reach a safe position.

		for($num_stones = 1; $num_stones <= 100; $num_stones ++)
		{
			$game = new game($num_stones,$max_move);
			if($num_stones % ($max_move + 1) != 0)
			{
				$this->assertTrue($game->is_position_winnable());
			}
			else
			{
				$this->assertFalse($game->is_position_winnable());
			}
		}
	}
}



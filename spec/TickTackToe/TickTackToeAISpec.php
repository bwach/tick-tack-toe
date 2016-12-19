<?php

namespace spec\TickTackToe;

use TickTackToe\MoveInterface;
use TickTackToe\TickTackToeAI;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TickTackToeAISpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType(TickTackToeAI::class);
        $this->shouldImplement(MoveInterface::class);
    }

    function it_throws_exception_on_wrong_state_array()
    {
        $improperGameState = [];

        $this->shouldThrow(\InvalidArgumentException::class)->during('makeMove', [$improperGameState, "X"]);
    }

    function it_throws_exception_on_wrong_team()
    {
        $properGameState = [
            ['X', '', ''],
            ['', '', ''],
            ['', '', '']
        ];

        $this->shouldThrow(\InvalidArgumentException::class)->during('makeMove', [$properGameState, "Q"]);
    }

    function it_throws_exception_on_wrong_move_order()
    {
        $properGameState = [
            ['X', '', ''],
            ['', '', ''],
            ['', '', '']
        ];

        $this->shouldThrow(\InvalidArgumentException::class)->during('makeMove', [$properGameState, "X"]);
    }

    function it_detects_winning_move()
    {
        $properGameState = [
            ['X', 'X', ''],
            ['', '', ''],
            ['O', 'O', '']
        ];

        $this->makeMove($properGameState, "X")->shouldReturn([0, 2, "X"]);

        $properGameState = [
            ['X', '', ''],
            ['', 'O', ''],
            ['X', '', '']
        ];

        $this->makeMove($properGameState, "O")->shouldReturn([1, 0, "O"]);

        $properGameState = [
            ['X', '', 'X'],
            ['', '', ''],
            ['O', 'O', '']
        ];

        $this->makeMove($properGameState, "X")->shouldReturn([0, 1, "X"]);

        $properGameState = [
            ['X', '', ''],
            ['', '', ''],
            ['O', 'O', 'X']
        ];

        $this->makeMove($properGameState, "X")->shouldReturn([1, 1, "X"]);

        $properGameState = [
            ['', '', 'X'],
            ['', '', ''],
            ['O', 'O', 'X']
        ];

        $this->makeMove($properGameState, "X")->shouldReturn([1, 2, "X"]);
    }

    function it_returns_proper_move_data()
    {
        $properGameState = [
            ['X', '', ''],
            ['', '', ''],
            ['', '', '']
        ];

        $this->makeMove($properGameState, "O")->shouldReturn([1, 1, "O"]);
    }
}

<?php

namespace TickTackToe;

class TickTackToeAI implements MoveInterface
{
    protected $simplifiedBoardState;

    /**
     * Makes a move using the $boardState
     * $boardState contains 2 dimensional array of the game field
     * X represents one team, O - the other team, empty string means field is
     * not yet taken.
     * example
     * [['X', 'O', '']
     * ['X', 'O', 'O']
     * ['', '', '']]
     * Returns an array, containing x and y coordinates for next move, and the unit that now occupies it.
     * Example: [2, 0, 'O'] - upper right corner - O player
     *
     * @param array $boardState Current board state
     * @param string $team Player unit representation
     *
     * @return array
     */
    public function makeMove(array $boardState, $team = self::TEAM_TICK)
    {
        if (!in_array($team, [self::TEAM_TACK, self::TEAM_TICK, self::TEAM_NONE])) {
            throw new \InvalidArgumentException("Invalid team");
        }

        $simplifiedBoardState = $this->simplifyBoardState($boardState, $team);

        if (!$this->validateBoardState($simplifiedBoardState)) {
            throw new \InvalidArgumentException("Invalid game state");
        }

        $move = null;

        if ($move = $this->winningMove($simplifiedBoardState, $team)) {
        } elseif ($move = $this->winStoppingMove($simplifiedBoardState, $team, $boardState)) {
        } elseif ($move = $this->normalMove($simplifiedBoardState, $team)) {
        }

        return $move;
    }

    protected function validateBoardState(array $simplifiedBoardState)
    {
        $sum = $this->calculateGameState($simplifiedBoardState);

        if ($sum > 0) // current team is at least one move ahead, forbid two moves in a row
        {
            return false;
        }

        return true;
    }

    /**
     * Checks for winning move and returns it if there is one
     *
     * @param array $simplifiedBoardState
     * @param $team
     * @return array|null
     */
    protected function winningMove(array $simplifiedBoardState, $team)
    {
        for ($x = 0; $x < 3; $x++) {
            if (array_sum($simplifiedBoardState[$x]) == 2 /* 2 ticks already in row */) {
                $row = $x;
                $column = !$simplifiedBoardState[$x][1] * 1 + !$simplifiedBoardState[$x][2] * 2;

                return [$row, $column, $team];
            } elseif ($simplifiedBoardState[0][$x] + $simplifiedBoardState[1][$x] + $simplifiedBoardState[2][$x] == 2 /* 2 ticks already in column */) {
                $row = !$simplifiedBoardState[1][$x] * 1 + !$simplifiedBoardState[2][$x] * 2;
                $column = $x;

                return [$row, $column, $team];
            }
        }

        if ($simplifiedBoardState[0][0] + $simplifiedBoardState[1][1] + $simplifiedBoardState[2][2] == 2 /* 2 ticks on diagonal */) {
            $row = !$simplifiedBoardState[1][1] * 1 + !$simplifiedBoardState[2][2] * 2;
            $column = !$simplifiedBoardState[1][1] * 1 + !$simplifiedBoardState[2][2] * 2;

            return [$row, $column, $team];
        } elseif ($simplifiedBoardState[0][2] + $simplifiedBoardState[1][1] + $simplifiedBoardState[2][0] == 2 /* 2 ticks on other diagonal */) {
            $row = !$simplifiedBoardState[1][1] * 1 + !$simplifiedBoardState[2][0] * 2;
            $column = !$simplifiedBoardState[0][2] * 2 + !$simplifiedBoardState[1][1] * 1;

            return [$row, $column, $team];
        }

        return null;
    }

    /**
     * Turns
     *
     * [['X', 'O', '']
     * ['X', 'O', 'O']
     * ['', '', '']]
     *
     * into
     *
     * [[1, -1, 0]
     * [1, -1, -1]
     * [0, 0, 0]]
     *
     * where 1 is current team, -1 i the other team
     *
     * @param $boardState
     * @param $team
     * @return array
     */
    protected function simplifyBoardState($boardState, $team)
    {
        if (count($boardState, COUNT_RECURSIVE) != 12 /* 3*3 + 3 */) {
            throw new \InvalidArgumentException("Invalid game state");
        }

        for ($x = 0; $x < 3; $x++) {
            for ($y = 0; $y < 3; $y++) {
                $cellState = $boardState[$x][$y];

                switch ($cellState) {
                    case $team:
                        $boardState[$x][$y] = 1;
                        break;
                    case self::TEAM_NONE:
                        $boardState[$x][$y] = 0;
                        break;
                    default:
                        $boardState[$x][$y] = -1;
                        break;
                }
            }
        }

        return $boardState;
    }

    /**
     * Checks if it can stop a winning move or take the middle (if it's the second move usually)
     *
     * @param array $simplifiedBoardState
     * @param $team
     * @param array $boardState
     * @return array|null
     */
    protected function winStoppingMove(array $simplifiedBoardState, $team, array $boardState)
    {
        $opposingTeam =  $team === self::TEAM_TICK ? self::TEAM_TACK : self::TEAM_TICK;
        $simplifiedBoardStateForOpposingTeam = $this->simplifyBoardState($boardState, $opposingTeam);

        $move = $this->winningMove($simplifiedBoardStateForOpposingTeam, $opposingTeam);

        if ($move) {
            $move[2] = $team;
        } elseif ($simplifiedBoardState[1][1] === 0 && $this->calculateGameState($simplifiedBoardState) < 0) {
            return [1, 1, $team];
        }

        return $move;
    }

    /**
     * Randomly selects free space for the next move
     *
     * @param array $simplifiedBoardState
     * @param $team
     * @return array
     */
    private function normalMove(array $simplifiedBoardState, $team)
    {
        $maxRolls = rand(9, 18);

        do {
            for ($x = 0; $x < 3; $x++) {
                for ($y = 0; $y < 3; $y++) {
                    if (!$simplifiedBoardState[$x][$y] && rand() % 2) {
                        return [$x, $y, $team];
                    }
                }
            }
        } while ($maxRolls--);

        for ($x = 0; $x < 3; $x++) {
            for ($y = 0; $y < 3; $y++) {
                if (!$simplifiedBoardState[$x][$y] && rand() % 2) {
                    return [$x, $y, $team];
                }
            }
        }

    }

    /**
     * Calculates the bias beteen number of ticks and toes on the board
     *
     * @param $simplifiedBoardState
     * @return int
     */
    private function calculateGameState($simplifiedBoardState)
    {
        $sumFunc = function ($carry, $item) {
            $carry += array_sum($item);
            return $carry;
        };

        return array_reduce($simplifiedBoardState, $sumFunc);
    }
}

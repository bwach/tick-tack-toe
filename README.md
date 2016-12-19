README
==============

Problem to solve
----------------
Tic Tac Toe
Customer wants and application - Tic Tac Toe game bot. The application should have an API that can be called to make the moves, and a web interface, where the application can be visible. Create a project which:
1. has a Tic Tac Toe game inside.
2. the application needs to have an API, which could be used to request next move from the application for the game.
3. the application needs to have web interface, where game could be played against the bot and viewed in the page. Example: player can select a move, and then the application makes a move using the same API created previously.
Application API Should implement

```
interface MoveInterface
{
    /**
    * Makes a move using the $boardState
    * $boardState contains 2 dimensional array of the game field
    * X represents one team, O - the other team, empty string means field is
    not yet taken.
    * example
    * [['X', 'O', '']
    * ['X', 'O', 'O']
    * ['', '', '']]
    * Returns an array, containing x and y coordinates for next move, and th
    e unit that now occupies it.
    * Example: [2, 0, 'O'] - upper right corner - O player
    *
    * @param array $boardState Current board state
    * @param string $playerUnit Player unit representation
    *
    * @return array
    */
    public function makeMove($boardState, $playerUnit = 'X');
}
```

Considerations:

Testing

Versioning (git bundle can be used)

Code complexity

Make sure to provide instructions how to view/run the application/service. If
you do not complete the whole exercise - make sure to submit whatever you ha
ve before deadline. The style of the web interface is irrelevant, as long as
it works the way it should.

Installation
------------

Use composer to install the dependencies

    composer install

Usage
-----

Project contains an "index.html" file containing the web interface to run the game. It uses jQuery to communicate with
the backend being the ai.php script next to index.html.

There is no "you won", the game can be played until the board is filled. Refreshing
the page starts a new game. Who plays first is selected randomly with 50% probability.

Tests
-----

Tests were written using __phpspec__. To run the test set just execute:

```
./bin/phpspec run -f pretty
```

Author
------

Bartłomiej Wach

bartlomiej.wach@yahoo.pl

skype: cozmo888

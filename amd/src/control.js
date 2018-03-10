/**
 * Front-end game state control.
 * @module mod_tictactoe/control
 */
define([
    'jquery',
    'mod_tictactoe/ui'
], function ($, ui) {
    return {
        init: function () {
            var tictactoe = {};

            // Human starts.
            tictactoe.status = 'running';
            tictactoe.turn = 'X';
            ui.switchViewTo("human");

            /*
             * Click on cell triggers AI's behavior.
             * If an empty cell is clicked when the game is running and its the human player's turn
             * get the indices of the clicked cell and push it to plugin back end to handle the state update.
             */
            $(".cell").each(function () {
                var $cell = $(this);
                $cell.click(function () {
                    if (tictactoe.status !== "running" || tictactoe.turn !== 'X' || $cell.hasClass('occupied')) {
                        return;
                    }

                    // Notify AI's turn.
                    tictactoe.turn = 'O';
                    ui.switchViewTo("ai");

                    // Fetch human action (selected cell).
                    var indx = parseInt($cell.data("indx"));

                    // Display human action.
                    ui.insertAt(indx, 'X');

                    // Request for the AI's next move.
                    var _state = {};
                    _state.validMove = true;
                    _state.isTerminal = false;
                    _state.movePosition = 1;

                    if (!_state.validMove) {
                        // Undo last human move and notify invalid move.
                        tictactoe.turn = "X";
                        ui.switchViewTo("try-again");
                        return;
                    }

                    if (_state.isTerminal) {
                        // The game just finished, set game status to finished and notify result.
                        tictactoe.status = "ended";
                        if (_state.result === "X-won") { // Human won.
                            ui.switchViewTo("won");
                        } else if (_state.result === "O-won") { // Human lost.
                            ui.switchViewTo("lost");
                        } else if (_state.result === "draw") { // It's a draw.
                            ui.switchViewTo("draw");
                        } else {
                            // Some sort of back end error happened. Notify?
                        }
                        return;
                    }

                    // The game is still running: display AI's action and notify human's turn.
                    ui.insertAt(_state.movePosition, 'O'); // Show AI move in board.
                    tictactoe.turn = "X";
                    ui.switchViewTo("human");
                });
            });
        }
    };
});

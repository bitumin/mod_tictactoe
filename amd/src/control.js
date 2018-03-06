/**
 * @module mod_tictactoe/control
 */
define([
    'jquery',
    'mod_tictactoe/ui'
], function ($, ui) {
    return {
        init: function () {
            // Light front-end game state control.
            var tictactoe = {};
            tictactoe.status = 'running';
            tictactoe.turn = 'X';
            ui.switchViewTo("human"); // Show that we are in the human turn.

            /*
             * Click on cell (onclick div.cell) behavior and control:
             * If an empty cell is clicked when the game is running and its the human player's turn
             * get the indices of the clicked cell and push it to plugin back end to handle the state update
             */
            $(".cell").each(function () {
                var $cell = $(this);
                $cell.click(function () {
                    if (
                        tictactoe.status === "running"
                        && tictactoe.turn === 'X'
                        && !$cell.hasClass('occupied')
                    ) {
                        tictactoe.turn = 'O'; // Switch to AI turn.
                        ui.switchViewTo("ai"); // Show that we are in the AI's turn.

                        var indx = parseInt($cell.data("indx")); // Fetch human move.
                        ui.insertAt(indx, 'X'); // Show fetched human move in the board.

                        // TODO: Push move to plugin external service

                        // TODO: Wait for back end movement validation and AI next move (new state). Example:
                        var _state = {}; // Received state with new AI move and board state information.
                        _state.validMove = true;
                        _state.isTerminal = false;
                        _state.movePosition = 1;

                        if (!_state.validMove) {
                            // TODO: undo last human move.

                            tictactoe.turn = "X";
                            ui.switchViewTo("try-again");

                            return;
                        }

                        // Human move validated, update the board with the AI's move or the end of the game status.
                        if (!_state.isTerminal) {
                            // The game is still running.
                            ui.insertAt(_state.movePosition, 'O'); // Show AI move in board.
                            tictactoe.turn = "X";
                            ui.switchViewTo("human");
                        } else {
                            // The game just finished.
                            tictactoe.status = "ended";
                            if (_state.result === "X-won") {
                                // Human won.
                                ui.switchViewTo("won");
                            } else if (_state.result === "O-won") {
                                // Human lost.
                                ui.switchViewTo("lost");
                            } else {
                                // It's a draw.
                                ui.switchViewTo("draw");
                            }
                        }
                    }
                });
            });
        }
    };
});

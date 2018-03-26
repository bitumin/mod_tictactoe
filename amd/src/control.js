/**
 * @module mod_tictactoe/control
 */
define([
    'jquery',
    'core/ajax',
    'core/notification',
    'mod_tictactoe/ui',
    'mod_tictactoe/game'
], function ($, ajax, notification, ui, game) {
    return {
        init: function () {
            // Human starts
            ui.switchViewTo("human");

            // Click on the game cells triggers the communication with the back-end.
            $(".cell").each(function () {
                var $cell = $(this);
                $cell.click(function () {
                    if (game.status !== "running" || game.turn !== 'X' || $cell.hasClass('occupied')) {
                        return;
                    }

                    // Human has clicked a cell, now it's the AI's turn.
                    game.turn = 'O';
                    ui.switchViewTo("ai");

                    // Get clicked cell index and display human action..
                    var indx = parseInt($cell.data("indx"));
                    ui.insertAt(indx, 'X');

                    // Send the human move to the back-end and wait for the response with the AI's next move.
                    ajax.call([{
                        methodname: 'mod_gallery_update_gallery_header',
                        args: {
                            tictactoe: {
                                gameid: game.id,
                                playermove: indx
                            }
                        },
                        fail: notification.exception
                    }])[0].done(function(response) {
                        // Response example:
                        var _state = {};
                        _state.validMove = true;
                        _state.isTerminal = false;
                        _state.movePosition = 1;

                        if (!_state.validMove) {
                            // Undo last human move and notify invalid move.
                            game.turn = "X";
                            ui.switchViewTo("try-again");
                            return;
                        }

                        if (_state.isTerminal) {
                            // The game just finished, set game status to finished and notify result.
                            game.status = "ended";
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
                        game.turn = "X";
                        ui.switchViewTo("human");
                    });
                });
            });
        }
    };
});

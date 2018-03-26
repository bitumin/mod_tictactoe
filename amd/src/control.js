/**
 * @module mod_tictactoe/control
 */
define([
    'jquery',
    'core/ajax',
    'core/notification',
    'mod_tictactoe/ui'
], function ($, ajax, notification, ui) {
    return {
        init: function (state) {
            var game = {};
            game.id = $('div[data-region="view-page"]').data('gameid');
            game.status = 'running';
            game.turn = state.turn;
            ui.switchViewTo("human");

            function setFinishedState(result) {
                // The game just finished, set game status to finished and notify result.
                game.status = "ended";
                if (result === "X-won") { // Human won.
                    ui.switchViewTo("won");
                } else if (result === "O-won") { // Human lost.
                    ui.switchViewTo("lost");
                } else if (result === "draw") { // It's a draw.
                    ui.switchViewTo("draw");
                }
            }

            if (state.finished) {
                setFinishedState(state.result);
                return;
            }

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
                    }])[0].done(function (response) {
                        // Response example:
                        var _state = {};
                        _state.validmove = true;
                        _state.finished = false;
                        _state.moveposition = 1;


                        if (!_state.validmove) {
                            // Undo last human move and notify invalid move.
                            game.turn = "X";
                            ui.switchViewTo("try-again");
                            return;
                        }

                        if (_state.finished) {
                            setFinishedState(_state.result);
                            return;
                        }

                        // The game is still running: display AI's action and notify human's turn.
                        ui.insertAt(_state.moveposition, 'O'); // Show AI move in board.
                        game.turn = "X";
                        ui.switchViewTo("human");
                    });
                });
            });
        }
    };
});

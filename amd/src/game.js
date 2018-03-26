/**
 * @module mod_tictactoe/game
 */
define([], function () {
    var game = {};
    game.id = $().val();
    game.status = 'running';
    game.turn = 'X';

    return game;
});

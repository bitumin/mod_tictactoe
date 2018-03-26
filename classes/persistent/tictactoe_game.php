<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Class for tictactoe game persistence.
 *
 * @package    mod_tictactoe
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tictactoe\persistent;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../locallib.php');

use core\persistent;
use core_user;
use lang_string;
use mod_tictactoe\game\state;
use stdClass;

/**
 * Class for loading/storing tictactoe games from the DB.
 *
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tictactoe_game extends persistent {
    const TABLE = 'tictactoe_game';

    /**
     * Return the definition of the properties of this model.
     *
     * @return array
     */
    protected static function define_properties() {
        return array(
            'tictactoeid' => array(
                'type' => PARAM_INT,
            ),
            'userid' => array(
                'type' => PARAM_INT,
            ),
            'state' => array(
                'type' => PARAM_TEXT,
            ),
            'timefinished' => array(
                'type' => PARAM_INT,
            ),
        );
    }

    /*
     * Custom setters and getters
     */

    /**
     * @param state $state
     * @throws \coding_exception
     */
    public function set_state($state) {
        $this->raw_set('state', serialize(get_object_vars($state)));
    }

    public function get_state() {
        return new state(unserialize($this->raw_get('state')));
    }

    /*
     * Extra properties validation
     */

    /**
     * @param int $value
     * @return true|lang_string
     * @throws \dml_exception
     */
    protected function validate_tictactoeid($value) {
        global $DB;
        if (!$DB->record_exists('tictactoe', ['id' => $value])) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return new lang_string('invalidrecord', 'error', 'gallery');
        }

        return true;
    }

    /**
     * @param $value
     * @return true|lang_string
     */
    protected function validate_userid($value) {
        if (!core_user::is_real_user($value, true)) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return new lang_string('invaliduserid', 'error');
        }

        return true;
    }

    /*
     * Relationships helpers
     */

    /**
     * @return tictactoe|false
     * @throws \coding_exception
     */
    public function get_tictactoe() {
        return tictactoe::get_record(['id' => $this->get('tictactoeid')]);
    }

    /**
     * @return bool|stdClass
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function get_player() {
        return core_user::get_user($this->get('userid'));
    }
}

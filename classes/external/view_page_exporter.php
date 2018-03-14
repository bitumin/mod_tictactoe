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
 * Tictactoe main view page exporter class
 *
 * @package    mod_tictactoe
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tictactoe\external;

defined('MOODLE_INTERNAL') || die();

use context_module;
use core\external\exporter;
use renderer_base;

/**
 * Class for tictactoe view page data.
 *
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class view_page_exporter extends exporter {
    protected static function define_related() {
        return array(
            'context' => 'context'
        );
    }

    protected static function define_other_properties() {
        return array(
            'contextid' => array(
                'type' => PARAM_INT,
            ),
            'level' => array(
                'type' => PARAM_TEXT,
            ),
        );
    }

    protected function get_other_values(renderer_base $output) {
        /** @var context_module $context */
        $context = $this->related['context'];

        $values = array();
        $values['contextid'] = $context->id;
        // Get this from the instance setting!
        $values['level'] = 'blind'; // novice, master

        return $values;
    }
}

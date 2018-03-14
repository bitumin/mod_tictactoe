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
 * Class for tictactoe activity instance persistence.
 *
 * @package    mod_tictactoe
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace mod_tictactoe\persistent;

defined('MOODLE_INTERNAL') || die();

require_once(__DIR__ . '/../../locallib.php');

use cm_info;
use context_module;
use core\persistent;
use lang_string;
use stdClass;

/**
 * Class for loading/storing tictactoe instances from the DB.
 *
 * @copyright  2018 Mitxel Moriana <moriana.mitxel@gmail.com>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class tictactoe extends persistent {
    const TABLE = 'tictactoe';

    /**
     * Return the definition of the properties of this model.
     *
     * @return array
     */
    protected static function define_properties() {
        /*
         * Do not declare id, timecreated or timemodified fields.
         * This fields are automatically handle by Moodle.
         */
        return array(
            'course' => array(
                'type' => PARAM_INT,
            ),
            'name' => array(
                'type' => PARAM_TEXT,
            ),
            'intro' => array(
                'type' => PARAM_RAW,
                'optional' => true,
                'null' => NULL_ALLOWED,
                'description' => 'Tictactoe module instance introduction text',
                'default' => null,
            ),
            'introformat' => array(
                'type' => PARAM_INT,
                'choices' => array(
                    FORMAT_MOODLE,
                    FORMAT_HTML,
                    FORMAT_PLAIN,
                    FORMAT_MARKDOWN
                ),
                'default' => FORMAT_MOODLE
            ),
        );
    }

    /*
     * Extra properties validation
     */

    /**
     * @param $value
     * @return true|lang_string
     * @throws \dml_exception
     */
    protected function validate_course($value) {
        global $DB;
        if (!$DB->record_exists('course', ['id' => $value])) {
            /** @noinspection ExceptionsAnnotatingAndHandlingInspection */
            return new lang_string('invalidcourseid', 'error');
        }

        return true;
    }

    /*
     * Relationships helpers
     */

    /**
     * @return stdClass
     * @throws \coding_exception
     * @throws \dml_exception
     */
    public function get_course_record() {
        return get_course($this->get('course'));
    }

    /**
     * @return false|stdClass
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function get_cm() {
        $instanceid = $this->get('id');
        $courseid = $this->get('course');

        // Try using fast modinfo first (uses cache).
        $modinfo = get_fast_modinfo($courseid);
        if (null !== $modinfo && isset($modinfo->instances['gallery'][$instanceid])) {
            /** @var cm_info $cminfo */
            $cminfo = $modinfo->instances['gallery'][$instanceid];
            return $cminfo->get_course_module_record();
        }

        // Default to helper (uses db query).
        if ($cm = get_coursemodule_from_instance('gallery', $instanceid, $courseid)) {
            return $cm;
        }

        return false;
    }

    /**
     * @return false|context_module
     * @throws \coding_exception
     * @throws \moodle_exception
     */
    public function get_context() {
        if (!$cm = $this->get_cm()) {
            return false;
        }

        return context_module::instance($cm->id);
    }
}

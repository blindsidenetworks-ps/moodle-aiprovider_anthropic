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

namespace aiprovider_anthropic;

use core_ai\hook\after_ai_provider_form_hook;

/**
 * Hook listener for Anthropic provider.
 *
 * @package    aiprovider_anthropic
 * @copyright  2026 Blindside Networks
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jesus Federico  (jesus [at] blindsidenetworks [dt] com)
 */
class hook_listener {
    /**
     * Add provider-level settings fields.
     *
     * @param after_ai_provider_form_hook $hook The setup form hook.
     */
    public static function set_form_definition_for_aiprovider_anthropic(after_ai_provider_form_hook $hook): void {
        if ($hook->plugin !== 'aiprovider_anthropic') {
            return;
        }

        $mform = $hook->mform;

        $mform->addElement(
            'passwordunmask',
            'apikey',
            get_string('apikey', 'aiprovider_anthropic'),
            ['size' => 75],
        );
        $mform->addHelpButton('apikey', 'apikey', 'aiprovider_anthropic');
        $mform->addRule('apikey', get_string('required'), 'required', null, 'client');

        $mform->addElement(
            'text',
            'apiversion',
            get_string('apiversion', 'aiprovider_anthropic'),
            'maxlength="32" size="20"',
        );
        $mform->setType('apiversion', PARAM_TEXT);
        $mform->setDefault('apiversion', '2023-06-01');
        $mform->addHelpButton('apiversion', 'apiversion', 'aiprovider_anthropic');
    }
}

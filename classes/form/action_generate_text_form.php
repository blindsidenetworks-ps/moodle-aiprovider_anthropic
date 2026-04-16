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

namespace aiprovider_anthropic\form;

use core_ai\form\action_settings_form;

/**
 * Anthropic action settings form for text actions.
 *
 * @package    aiprovider_anthropic
 * @copyright  2026 Blindside Networks
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jesus Federico  (jesus [at] blindsidenetworks [dt] com)
 */
class action_generate_text_form extends action_settings_form {
    /** @var array Action-level configuration. */
    protected array $actionconfig;
    /** @var string|null Return URL. */
    protected ?string $returnurl;
    /** @var string Action name. */
    protected string $actionname;
    /** @var string Action class. */
    protected string $action;
    /** @var int Provider instance ID. */
    protected int $providerid;
    /** @var string Provider component name. */
    protected string $providername;

    #[\Override]
    protected function definition(): void {
        $mform = $this->_form;

        $this->actionconfig = $this->_customdata['actionconfig']['settings'] ?? [];
        $this->returnurl = $this->_customdata['returnurl'] ?? null;
        $this->actionname = $this->_customdata['actionname'];
        $this->action = $this->_customdata['action'];
        $this->providerid = $this->_customdata['providerid'] ?? 0;
        $this->providername = $this->_customdata['providername'] ?? 'aiprovider_anthropic';

        $mform->addElement('header', 'generalsettingsheader', get_string('general', 'core'));

        $mform->addElement(
            'text',
            'endpoint',
            get_string("action:{$this->actionname}:endpoint", 'aiprovider_anthropic'),
            'maxlength="255" size="30"',
        );
        $mform->setType('endpoint', PARAM_URL);
        $mform->addRule('endpoint', null, 'required', null, 'client');
        $mform->setDefault('endpoint', $this->actionconfig['endpoint'] ?? 'https://api.anthropic.com/v1/messages');

        $mform->addElement(
            'text',
            'model',
            get_string("action:{$this->actionname}:model", 'aiprovider_anthropic'),
            'maxlength="128" size="30"',
        );
        $mform->setType('model', PARAM_TEXT);
        $mform->addRule('model', null, 'required', null, 'client');
        $mform->setDefault('model', $this->actionconfig['model'] ?? 'claude-sonnet-4-20250514');
        $mform->addHelpButton('model', "action:{$this->actionname}:model", 'aiprovider_anthropic');

        $mform->addElement(
            'textarea',
            'systeminstruction',
            get_string("action:{$this->actionname}:systeminstruction", 'aiprovider_anthropic'),
            'wrap="virtual" rows="5" cols="20"',
        );
        $mform->setType('systeminstruction', PARAM_TEXT);
        $mform->setDefault('systeminstruction', $this->actionconfig['systeminstruction'] ?? $this->action::get_system_instruction());
        $mform->addHelpButton('systeminstruction', "action:{$this->actionname}:systeminstruction", 'aiprovider_anthropic');

        $mform->addElement('text', 'max_tokens', get_string('settings_max_tokens', 'aiprovider_anthropic'));
        $mform->setType('max_tokens', PARAM_INT);
        $mform->setDefault('max_tokens', $this->actionconfig['max_tokens'] ?? 1024);

        $mform->addElement('text', 'temperature', get_string('settings_temperature', 'aiprovider_anthropic'));
        $mform->setType('temperature', PARAM_FLOAT);
        $mform->setDefault('temperature', $this->actionconfig['temperature'] ?? 0.7);

        $mform->addElement('text', 'top_p', get_string('settings_top_p', 'aiprovider_anthropic'));
        $mform->setType('top_p', PARAM_FLOAT);
        $mform->setDefault('top_p', $this->actionconfig['top_p'] ?? 1.0);

        $mform->addElement('text', 'top_k', get_string('settings_top_k', 'aiprovider_anthropic'));
        $mform->setType('top_k', PARAM_INT);
        $mform->setDefault('top_k', $this->actionconfig['top_k'] ?? 0);

        if ($this->returnurl) {
            $mform->addElement('hidden', 'returnurl', $this->returnurl);
            $mform->setType('returnurl', PARAM_LOCALURL);
        }

        $mform->addElement('hidden', 'action', $this->action);
        $mform->setType('action', PARAM_TEXT);

        $mform->addElement('hidden', 'provider', $this->providername);
        $mform->setType('provider', PARAM_TEXT);

        $mform->addElement('hidden', 'providerid', $this->providerid);
        $mform->setType('providerid', PARAM_INT);

        $this->set_data($this->actionconfig);
    }

    #[\Override]
    public function validation($data, $files): array {
        $errors = parent::validation($data, $files);

        if ((int) ($data['max_tokens'] ?? 0) <= 0) {
            $errors['max_tokens'] = get_string('error:invalidmax', 'aiprovider_anthropic');
        }

        $temperature = (float) ($data['temperature'] ?? 0.0);
        if ($temperature < 0.0 || $temperature > 1.0) {
            $errors['temperature'] = get_string('error:invalidtemperature', 'aiprovider_anthropic');
        }

        $topp = (float) ($data['top_p'] ?? 0.0);
        if ($topp < 0.0 || $topp > 1.0) {
            $errors['top_p'] = get_string('error:invalidtopp', 'aiprovider_anthropic');
        }

        if ((int) ($data['top_k'] ?? 0) < 0) {
            $errors['top_k'] = get_string('error:invalidtopk', 'aiprovider_anthropic');
        }

        return $errors;
    }
}

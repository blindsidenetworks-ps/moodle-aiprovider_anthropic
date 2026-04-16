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
 * Strings for component aiprovider_anthropic, language 'en'.
 *
 * @package    aiprovider_anthropic
 * @copyright  2026 Blindside Networks
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jesus Federico  (jesus [at] blindsidenetworks [dt] com)
 */

$string['pluginname'] = 'Anthropic API provider';

$string['apikey'] = 'Anthropic API key';
$string['apikey_help'] = 'Get a key from your Anthropic console.';
$string['apiversion'] = 'Anthropic API version';
$string['apiversion_help'] = 'HTTP header value for anthropic-version. The default 2023-06-01 is recommended.';

$string['action:generate_text:endpoint'] = 'API endpoint';
$string['action:generate_text:model'] = 'AI model';
$string['action:generate_text:model_help'] = 'The model used to generate text, for example claude-sonnet-4-20250514.';
$string['action:generate_text:systeminstruction'] = 'System instruction';
$string['action:generate_text:systeminstruction_help'] = 'This instruction is sent to the model together with the user prompt.';

$string['action:summarise_text:endpoint'] = 'API endpoint';
$string['action:summarise_text:model'] = 'AI model';
$string['action:summarise_text:model_help'] = 'The model used to summarise the provided text, for example claude-sonnet-4-20250514.';
$string['action:summarise_text:systeminstruction'] = 'System instruction';
$string['action:summarise_text:systeminstruction_help'] = 'This instruction is sent to the model together with the user prompt.';

$string['action:explain_text:endpoint'] = 'API endpoint';
$string['action:explain_text:model'] = 'AI model';
$string['action:explain_text:model_help'] = 'The model used to explain the provided text.';
$string['action:explain_text:systeminstruction'] = 'System instruction';
$string['action:explain_text:systeminstruction_help'] = 'This instruction is sent to the model together with the user prompt.';

$string['settings_max_tokens'] = 'max_tokens';
$string['settings_temperature'] = 'temperature';
$string['settings_top_p'] = 'top_p';
$string['settings_top_k'] = 'top_k';

$string['error:invalidmax'] = 'max_tokens must be greater than 0.';
$string['error:invalidtemperature'] = 'temperature must be between 0 and 1.';
$string['error:invalidtopp'] = 'top_p must be between 0 and 1.';
$string['error:invalidtopk'] = 'top_k must be 0 or greater.';

$string['privacy:metadata'] = 'The Anthropic API provider plugin does not store any personal data.';
$string['privacy:metadata:aiprovider_anthropic:externalpurpose'] = 'This information is sent to Anthropic in order for a response to be generated. Your Anthropic account settings may change how Anthropic stores and retains this data.';
$string['privacy:metadata:aiprovider_anthropic:model'] = 'The model used to generate the response.';
$string['privacy:metadata:aiprovider_anthropic:prompttext'] = 'The user entered text prompt used to generate the response.';

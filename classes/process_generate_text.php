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

use GuzzleHttp\Psr7\Request;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

/**
 * Process generate_text action via Anthropic.
 *
 * @package    aiprovider_anthropic
 * @copyright  2026 Blindside Networks
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jesus Federico  (jesus [at] blindsidenetworks [dt] com)
 */
class process_generate_text extends abstract_processor {
    #[\Override]
    protected function get_system_instruction(): string {
        return $this->provider->actionconfig[$this->action::class]['settings']['systeminstruction'];
    }

    #[\Override]
    protected function create_request_object(string $userid): RequestInterface {
        $prompt = (string) $this->action->get_configuration('prompttext');

        $usercontent = new \stdClass();
        $usercontent->type = 'text';
        $usercontent->text = $prompt;

        $usermessage = new \stdClass();
        $usermessage->role = 'user';
        $usermessage->content = [$usercontent];

        $requestobj = new \stdClass();
        $requestobj->model = $this->get_model();
        $requestobj->messages = [$usermessage];
        $requestobj->metadata = (object) ['user_id' => $userid];

        $systeminstruction = trim($this->get_system_instruction());
        if ($systeminstruction !== '') {
            $requestobj->system = $systeminstruction;
        }

        foreach ($this->get_model_settings() as $setting => $value) {
            $requestobj->$setting = $value;
        }

        return new Request(
            method: 'POST',
            uri: '',
            headers: [
                'Content-Type' => 'application/json',
            ],
            body: json_encode($requestobj),
        );
    }

    #[\Override]
    protected function handle_api_success(ResponseInterface $response): array {
        $bodyobj = json_decode($response->getBody()->getContents());

        $textparts = [];
        if (!empty($bodyobj->content) && is_array($bodyobj->content)) {
            foreach ($bodyobj->content as $part) {
                if (($part->type ?? '') === 'text') {
                    $textparts[] = (string) ($part->text ?? '');
                }
            }
        }

        return [
            'success' => true,
            'id' => $bodyobj->id ?? '',
            'fingerprint' => null,
            'generatedcontent' => trim(implode("\n", $textparts)),
            'finishreason' => $bodyobj->stop_reason ?? null,
            'prompttokens' => $bodyobj->usage->input_tokens ?? 0,
            'completiontokens' => $bodyobj->usage->output_tokens ?? 0,
            'model' => $bodyobj->model ?? $this->get_model(),
        ];
    }
}

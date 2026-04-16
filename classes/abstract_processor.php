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

use core\http_client;
use core_ai\process_base;
use GuzzleHttp\Exception\RequestException;
use GuzzleHttp\Psr7\Uri;
use GuzzleHttp\RequestOptions;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\UriInterface;

/**
 * Base Anthropic action processor.
 *
 * @package    aiprovider_anthropic
 * @copyright  2026 Blindside Networks
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jesus Federico  (jesus [at] blindsidenetworks [dt] com)
 */
abstract class abstract_processor extends process_base {
    /**
     * Get endpoint URI.
     *
     * @return UriInterface
     */
    protected function get_endpoint(): UriInterface {
        return new Uri($this->provider->actionconfig[$this->action::class]['settings']['endpoint']);
    }

    /**
     * Get model name.
     *
     * @return string
     */
    protected function get_model(): string {
        return $this->provider->actionconfig[$this->action::class]['settings']['model'];
    }

    /**
     * Get system instructions for the action.
     *
     * @return string
     */
    protected function get_system_instruction(): string {
        return $this->action::get_system_instruction();
    }

    /**
     * Get numeric model settings used by Anthropic Messages API.
     *
     * @return array
     */
    protected function get_model_settings(): array {
        $settings = $this->provider->actionconfig[$this->action::class]['settings'];

        return [
            'max_tokens' => (int) ($settings['max_tokens'] ?? 1024),
            'temperature' => (float) ($settings['temperature'] ?? 0.7),
            'top_p' => (float) ($settings['top_p'] ?? 1.0),
            'top_k' => (int) ($settings['top_k'] ?? 0),
        ];
    }

    /**
     * Build API request.
     *
     * @param string $userid Generated user hash.
     * @return RequestInterface
     */
    abstract protected function create_request_object(string $userid): RequestInterface;

    /**
     * Parse successful API response.
     *
     * @param ResponseInterface $response HTTP response.
     * @return array
     */
    abstract protected function handle_api_success(ResponseInterface $response): array;

    #[\Override]
    protected function query_ai_api(): array {
        $request = $this->create_request_object(
            userid: $this->provider->generate_userid((string) $this->action->get_configuration('userid')),
        );
        $request = $this->provider->add_authentication_headers($request);

        $client = \core\di::get(http_client::class);
        try {
            $response = $client->send($request, [
                'base_uri' => $this->get_endpoint(),
                RequestOptions::HTTP_ERRORS => false,
            ]);
        } catch (RequestException $e) {
            return \core_ai\error\factory::create($e->getCode(), $e->getMessage())->get_error_details();
        }

        if ($response->getStatusCode() === 200) {
            return $this->handle_api_success($response);
        }

        return $this->handle_api_error($response);
    }

    /**
     * Parse error response.
     *
     * @param ResponseInterface $response HTTP response.
     * @return array
     */
    protected function handle_api_error(ResponseInterface $response): array {
        $status = $response->getStatusCode();

        if ($status >= 500 && $status < 600) {
            $errormessage = $response->getReasonPhrase();
        } else {
            $bodyobj = json_decode($response->getBody()->getContents());
            $errormessage = $bodyobj->error->message ?? $response->getReasonPhrase();
        }

        return \core_ai\error\factory::create($status, $errormessage)->get_error_details();
    }
}

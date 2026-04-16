# Anthropic AI Provider

Anthropic AI provider for Moodle core AI.

This contributed plugin adds a first-class AI provider that integrates with Anthropic's Messages API and supports the standard Moodle AI actions:

- generate text
- summarise text
- explain text

## Features

- Anthropic API authentication via `x-api-key`
- Configurable Anthropic API version header
- Per-action configuration for endpoint, model, system instruction, and sampling settings
- Compatible with Moodle's core AI provider management UI

## Requirements

- Moodle core AI subsystem enabled
- PHP 8.1+ and a Moodle version compatible with this plugin branch
- Anthropic API key

## Installation

1. Copy the plugin into `ai/provider/anthropic`.
2. Visit Site administration to complete the Moodle upgrade.
3. Add a new AI provider instance for Anthropic.
4. Enter the Anthropic API key and configure the action settings.
5. Enable the actions you want to expose.

## Configuration

Provider-level settings:

- `apikey`: Anthropic API key
- `apiversion`: Anthropic API version header, default `2023-06-01`

Action-level settings:

- `endpoint`: Anthropic Messages API endpoint
- `model`: Anthropic model name
- `systeminstruction`: System prompt sent with the request
- `max_tokens`, `temperature`, `top_p`, `top_k`: request tuning options

## Notes

- This plugin is intended as a contributed provider, separate from Moodle core.
- The default endpoint is `https://api.anthropic.com/v1/messages`.
- Summarise and explain actions reuse the text generation pipeline.

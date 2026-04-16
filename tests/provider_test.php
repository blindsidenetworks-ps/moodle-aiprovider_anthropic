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

/**
 * Test Anthropic provider methods.
 *
 * @package    aiprovider_anthropic
 * @copyright  2026 Blindside Networks
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 * @author     Jesus Federico  (jesus [at] blindsidenetworks [dt] com)
 *
 * @covers     \aiprovider_anthropic\provider
 */
final class provider_test extends \advanced_testcase {
    /** @var \core_ai\manager */
    private $manager;

    /** @var \core_ai\provider */
    private $provider;

    /**
     * Set up test state.
     */
    public function setUp(): void {
        parent::setUp();
        $this->resetAfterTest();

        $this->manager = \core\di::get(\core_ai\manager::class);
        $config = ['data' => 'goeshere'];
        $this->provider = $this->manager->create_provider_instance(
            classname: '\\aiprovider_anthropic\\provider',
            name: 'dummy',
            config: $config,
        );
    }

    /**
     * Test supported actions.
     */
    public function test_get_action_list(): void {
        $actionlist = $this->provider->get_action_list();
        $this->assertIsArray($actionlist);
        $this->assertCount(3, $actionlist);
        $this->assertContains(\core_ai\aiactions\generate_text::class, $actionlist);
        $this->assertContains(\core_ai\aiactions\summarise_text::class, $actionlist);
        $this->assertContains(\core_ai\aiactions\explain_text::class, $actionlist);
    }

    /**
     * Test provider configuration check.
     */
    public function test_is_provider_configured(): void {
        $configured = $this->manager->create_provider_instance(
            classname: '\\aiprovider_anthropic\\provider',
            name: 'configured',
            config: ['apikey' => 'abc123'],
        );
        $this->assertTrue($configured->is_provider_configured());

        $notconfigured = $this->manager->create_provider_instance(
            classname: '\\aiprovider_anthropic\\provider',
            name: 'notconfigured',
            config: [],
        );
        $this->assertFalse($notconfigured->is_provider_configured());
    }
}

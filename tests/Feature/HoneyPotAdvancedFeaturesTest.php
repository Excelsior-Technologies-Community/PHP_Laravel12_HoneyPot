<?php

namespace Tests\Feature;

use App\Models\BlockedIp;
use App\Models\SpamAttempt;
use App\Services\SpamSecurityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HoneyPotAdvancedFeaturesTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_render_bot_attack_simulator_page()
    {
        $response = $this->get(route('spam.simulator'));

        $response->assertStatus(200);
        $response->assertSee('Bot Attack Simulator');
    }

    /** @test */
    public function it_can_run_bot_attack_simulation_and_auto_blacklist()
    {
        $response = $this->post(route('spam.simulator.run'), [
            'scenario' => 'bot_batch',
            'ip_address' => '198.51.100.99',
            'count' => 3,
        ]);

        $response->assertRedirect(route('spam.simulator'));
        $this->assertDatabaseCount('spam_attempts', 3);
        $this->assertDatabaseHas('blocked_ips', [
            'ip_address' => '198.51.100.99',
        ]);
    }

    /** @test */
    public function it_returns_json_response_for_api_simulation_requests()
    {
        $response = $this->postJson(route('spam.simulator.run'), [
            'scenario' => 'hidden_trap',
            'ip_address' => '203.0.113.88',
            'count' => 1,
        ]);

        $response->assertStatus(200);
        $response->assertJson([
            'success' => true,
            'scenario' => 'hidden_trap',
            'ip_address' => '203.0.113.88',
            'attempts_created' => 1,
        ]);
    }

    /** @test */
    public function it_can_render_anti_spam_analytics_page()
    {
        SpamAttempt::create([
            'ip_address' => '192.0.2.1',
            'user_agent' => 'TestBot/1.0',
            'reason' => 'Honeypot spam protection triggered',
            'route' => 'contact',
            'request_method' => 'POST',
            'attempted_at' => now(),
        ]);

        $response = $this->get(route('spam.analytics'));

        $response->assertStatus(200);
        $response->assertSee('Anti-Spam Threat Analytics');
    }

    /** @test */
    public function it_returns_json_threat_analytics_data()
    {
        SpamAttempt::create([
            'ip_address' => '192.0.2.1',
            'user_agent' => 'TestBot/1.0',
            'reason' => 'Honeypot spam protection triggered',
            'route' => 'contact',
            'request_method' => 'POST',
            'attempted_at' => now(),
        ]);

        $response = $this->get(route('spam.analytics.data'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'summary' => [
                'total_attempts',
                'today_attempts',
                'auto_blocked',
                'total_blocked',
            ],
            'trend_labels',
            'trend_data',
            'reasons',
            'user_agents',
            'ip_leaderboard',
        ]);
    }

    /** @test */
    public function it_enforces_auto_blacklisting_policy_after_3_attempts()
    {
        $service = new SpamSecurityService();
        $ip = '198.51.100.77';

        // 2 attempts -> Not blacklisted
        SpamAttempt::create(['ip_address' => $ip, 'attempted_at' => now()]);
        SpamAttempt::create(['ip_address' => $ip, 'attempted_at' => now()]);

        $this->assertFalse($service->evaluateAutoBlacklistPolicy($ip));
        $this->assertDatabaseMissing('blocked_ips', ['ip_address' => $ip]);

        // 3rd attempt -> Auto-blacklisted
        SpamAttempt::create(['ip_address' => $ip, 'attempted_at' => now()]);

        $this->assertTrue($service->evaluateAutoBlacklistPolicy($ip));
        $this->assertDatabaseHas('blocked_ips', ['ip_address' => $ip]);
    }
}

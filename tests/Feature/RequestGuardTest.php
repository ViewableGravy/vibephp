<?php

use App\Ai\Agents\VibePhpRuntime;
use Laravel\Ai\Prompts\AgentPrompt;

test('serves the about page through the model for legitimate subpages', function () {
    VibePhpRuntime::fake(fn (string $prompt) => [
        'status' => 200,
        'headers' => [],
        'body' => '<h1>About</h1>',
    ]);

    $this->get('/about')->assertOk();

    VibePhpRuntime::assertPrompted(fn (AgentPrompt $prompt) => $prompt->contains('Entry script: about.php'));
});

test('returns a cheap response to Uptime Kuma without invoking the model', function () {
    $this->withHeader('User-Agent', 'Uptime-Kuma/1.21.3')->get('/')->assertNoContent();

    VibePhpRuntime::assertNeverPrompted();
});

test('serves health and crawler utility endpoints without invoking the model', function () {
    $this->get('/health')->assertOk()->assertSee('ok');
    $this->get('/robots.txt')->assertOk()->assertSee('Disallow: /');
    $this->get('/favicon.ico')->assertNoContent();

    VibePhpRuntime::assertNeverPrompted();
});

test('does not send unknown scanner paths to the model front controller', function () {
    config()->set('vibe.strict_front_controller', true);

    $this->get('/wp-login.php')->assertNotFound();

    VibePhpRuntime::assertNeverPrompted();
});

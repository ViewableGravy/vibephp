<?php

use App\Ai\Agents\VibePhpRuntime;

test('the application returns a successful response', function () {
    VibePhpRuntime::fake(fn (string $prompt) => [
        'status' => 200,
        'headers' => [],
        'body' => 'ok',
    ]);

    $response = $this->get('/');

    $response->assertStatus(200);
});

<?php

use App\Models\User;
use App\Notifications\NewFollowerNotification;
use Illuminate\Support\Facades\Notification;

it('creates a follow and sends notification', function () {
    Notification::fake();
    $a = User::factory()->create();
    $b = User::factory()->create();

    $this->actingAs($a)
        ->post("/u/{$b->username}/follow")
        ->assertOk()
        ->assertJson(['following' => true, 'followers_count' => 1]);

    expect($a->fresh()->isFollowing($b))->toBeTrue();
    Notification::assertSentTo($b, NewFollowerNotification::class);
});

it('does not double-follow', function () {
    $a = User::factory()->create();
    $b = User::factory()->create();

    $this->actingAs($a)->post("/u/{$b->username}/follow");
    $this->actingAs($a)->post("/u/{$b->username}/follow")
        ->assertOk()
        ->assertJson(['followers_count' => 1]);
});

it('unfollows', function () {
    $a = User::factory()->create();
    $b = User::factory()->create();
    $a->follow($b);

    $this->actingAs($a)
        ->delete("/u/{$b->username}/follow")
        ->assertOk()
        ->assertJson(['following' => false, 'followers_count' => 0]);

    expect($a->fresh()->isFollowing($b))->toBeFalse();
});

it('rejects self-follow', function () {
    $a = User::factory()->create();
    $this->actingAs($a)->post("/u/{$a->username}/follow")->assertStatus(422);
});

it('requires auth', function () {
    $b = User::factory()->create();
    $this->post("/u/{$b->username}/follow")->assertRedirect();
});

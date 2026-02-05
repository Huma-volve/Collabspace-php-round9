<?php

use App\Models\Meeting;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);
test('test get all meeting',function(){

    Meeting::factory()->count(10)->create();

    $response=$this->getJson('api/meeting/all');
$response
->assertStatus(200)
->assertJsonPath('success',true)
->assertJsonPath('message','Meetings retrieved successfully')
 ->assertJsonCount(10,'data');
});

test('test get all meeting not found',function(){

    $response=$this->getJson("api/meeting/all");
    $response
    ->assertStatus(200)
    ->assertJsonPath('success',true)
    ->assertJsonPath('message','No meetings found')
     ->assertJsonCount(0,'data');
});

use App\Models\User;

test('can create meeting', function () {

    // Arrange
    $user = User::factory()->create();

    $data = [
        'subject' => 'Team Meeting',
        'note' => 'Discuss project updates',
        'date' => now()->format('Y-m-d'),
        'start_time' => now()->format('H:i'),
        'end_time' => now()->addHour()->format('H:i'),
        'users' => [$user->id],
    ];

    // Act
    $response = $this->postJson('/api/meeting/create', $data);

    // Assert
    $response->assertStatus(201)
             ->assertJsonPath('success', true);

    $this->assertDatabaseHas('meetings', [
        'subject' => 'Team Meeting',
    ]);
});

test('test get single meeting',function(){
    $meeting=Meeting::factory()->create();

    $response=$this->getJson("api/meeting/{$meeting->id}");
    $response
    ->assertStatus(200)
    ->assertJsonPath('success',true)
    ->assertJsonPath('message','Meeting retrieved successfully')
    ->assertJsonPath('data.id',$meeting->id);
});



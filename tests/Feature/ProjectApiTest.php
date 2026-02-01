<?php

use App\Models\Project;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);
use App\Models\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;





test('Add_Projects',function(){
    $this->withoutExceptionHandling();
$data=Project::factory()->make()->toArray();
$response=$this->post('/api/projects',$data);

$response->assertStatus(201);
});


test('Get_All_Projects', function(){
    $data=Project::factory(3)->create();
     $response = $this->get('/api/projects');

     if ($response->status() == 404) {
         $this->withoutExceptionHandling();
     }

     $response->assertStatus(200);
     $response->assertJsonCount(3,'data');
});

test('Get_One_Project',function(){
    $project=Project::factory()->create();
    $response=$this->get("/api/projects/{$project->id}");
    if($response->status()==404){
        $this->withExceptionHandling();
    }
    $response->assertStatus(200);
    $response->assertJsonPath('data.id', $project->id);
});

test('one_project',function(){
$project=Project::factory()->create();
$response=$this->get(route('show',$project->id));
$response->assertStatus(200);
});


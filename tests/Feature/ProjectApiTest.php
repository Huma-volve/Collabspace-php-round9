<?php

use App\Models\File;
use App\Models\Project;
use App\Models\Task;
use App\Models\Team;
use Illuminate\Foundation\Testing\RefreshDatabase;
uses(RefreshDatabase::class);
use App\Models\User;
// use Illuminate\Foundation\Testing\RefreshDatabase;





test('Add_Projects',function(){
$data=Project::factory()->make()->toArray();
$response=$this->post('/api/projects',$data);

$response->assertStatus(201);
});


test('Get_All_Projects', function(){
    $user=User::factory()->create();
    $data=Project::factory(3)->create();
     $response = $this->actingAs($user)->get('/api/projects');

     if ($response->status() == 404) {
         $this->withoutExceptionHandling();
     }

     $response->assertStatus(200);
     $response->assertJsonCount(3,'data');
});

test('Get_One_Project',function(){
    $user=User::factory()->create();
    $project=Project::factory()->create();
    $response=$this->actingAs($user)->get("/api/projects/{$project->id}");
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

test('Get_All_Project_With_Tasks',function(){
$project=Project::factory()->create();
Task::factory()->create([
'project_id'=>$project->id,
'status'=>'todo',
]);
Task::factory()->create([
'project_id'=>$project->id,
'status'=>'progress',
]);
Task::factory()->create([
'project_id'=>$project->id,
'status'=>'review',
]);
Task::factory()->create([
'project_id'=>$project->id,
'status'=>'completed',
]);
$response=$this->getJson("/api/projects/{$project->id}/getprojectwithtasks");
$response->assertStatus(200);
});


test('Get_All_Project_With_Teams',function(){
$project=Project::factory()->create();
$team=Team::factory()->create();
$project->teams()->attach($team->id);
$response=$this->get("/api/projects/{$project->id}/getprojectwithteams");
$response->assertStatus(200);
});

test('Get_All_Project_With_Files',function(){
    $user=User::factory()->create();
$project=Project::factory()->create();
File::factory()->create([
    'fileable_id'=>$project->id
]);
$response=$this->actingAs($user)->get("/api/projects/{$project->id}/getprojectwithfiles");
$response->assertStatus(200);
});

test('Update Project',function(){
$project=Project::factory()->create();
$response=$this->post("/api/projects/{$project->id}/update");
$response->assertStatus(200);
});




// test('Register',function(){
// $user=User::factory()->make()->create();
// $response=$this->post('/api/register',$user);
// $response->assertStatus(201);
// });

test('Register', function () {

    $userData = User::factory()->make()->toArray();
    $userData['password'] = 'password';
    $userData['password_confirmation'] = 'password';

    $response = $this->postJson('/api/register', $userData);

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'status',
                'data'=>[
                    'token'
                ],
                 'message'
             ]);
});
test('login',function(){
$user=User::factory()->create([
'password'=>bcrypt('123123123')
]);
$response=$this->post('/api/login',[
'email'=>$user->email,
'password'=>'123123123'
]);
$response->assertStatus(200);
});

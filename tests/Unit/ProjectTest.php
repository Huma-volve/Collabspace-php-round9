<?php

use App\Http\Controllers\Api\ProjectController;
use App\Models\Project;

test('example', function () {
    expect(true)->toBeTrue();
});

test('sum',function(){
    $project=new ProjectController();
    $sum=$project->sum(3,4);
    $this->assertEquals(7,$sum);

});
test('failed_sum',function(){
    $project=new ProjectController();
    $sum=$project->sum(3,4);
    $this->assertNotEquals(6,$sum);
});


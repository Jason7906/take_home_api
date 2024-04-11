<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Project;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProjectController;
class ProjectApiTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void
{
    parent::setUp();
    Route::get('/projects', [ProjectController::class, 'index']);
}
    public function test_can_get_projects_with_highest_prices()
    {
        // 建立測試資料
        $project1 = Project::factory()->create(['type' => '大樓', 'manager_department' => '資料部', 'name' =>'第一天廈']);
        $project2 = Project::factory()->create(['type' => '別墅', 'manager_department' => '行銷部','name' =>'第二大廈']);
        $project3 = Project::factory()->create(['type' => '房子', 'manager_department' => '特殊部','name' =>'第三大廈']);
        $project4 = Project::factory()->create(['type' => '大樓', 'manager_department' => '資料部','name' =>'第四大廈']);
        // 建立交易資料
        $project1->transactions()->createMany([
            ['price' => 100],
            ['price' => 200],
            ['price' => 300],
            ['price' => 400],
        ]);
        $project2->transactions()->create(['price' => 300]);
        $project3->transactions()->create(['price' => 500]);
        $project4->transactions()->create(['price' => 250]);
        // 呼叫 API 端點
        $response = $this->get('/projects');

        // 驗證回應
        $response->assertStatus(200)
                 ->assertJsonCount(2)
                 ->assertJsonFragment([
                     'project_name' => $project1->name,
                     'highest_price' => 400
                 ])
                 ->assertJsonFragment([
                    'project_name' => $project4->name,
                    'highest_price' => 250
                ])
                 ->assertJsonMissing([
                     'project_name' => $project2->name
                 ])
                 ->assertJsonMissing([
                    'project_name' => $project3->name
                ]);
    }
    public function test_can_handle_no_projects_found()
{
    
    // 建立測試資料
    $project1 = Project::factory()->create(['type' => '大樓', 'manager_department' => '行銷部', 'name' =>'第一天廈']);
    $project2 = Project::factory()->create(['type' => '別墅', 'manager_department' => '行銷部','name' =>'第二大廈']);
    $project3 = Project::factory()->create(['type' => '房子', 'manager_department' => '特殊部','name' =>'第三大廈']);
    $project4 = Project::factory()->create(['type' => '大樓', 'manager_department' => '特殊部','name' =>'第四大廈']);
    // 建立交易資料
    $project1->transactions()->createMany([
        ['price' => 100],
        ['price' => 200],
        ['price' => 300],
        ['price' => 400],
    ]);
    $project2->transactions()->create(['price' => 300]);
    $project3->transactions()->create(['price' => 500]);
    $project4->transactions()->create(['price' => 250]);
    // 呼叫 API 
    $response = $this->get('/projects');

    // 驗證回應
    $response->assertStatus(200)
             ->assertJsonCount(0); // JSON是否為空
}
}

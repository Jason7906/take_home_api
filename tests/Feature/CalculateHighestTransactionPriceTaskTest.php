<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Tasks\CalculateHighestTransactionPriceTask;
use App\Models\Project;
use App\Models\HighestTransactionPrice;

class CalculateHighestTransactionPriceTaskTest extends TestCase
{
    use RefreshDatabase;

    public function test_task_executes_successfully()
    {
        // 建立測試資料
        $project1 = Project::factory()->create(['type' => '大樓', 'manager_department' => '資料部']);
        $project2 = Project::factory()->create(['type' => '大樓', 'manager_department' => '資料部']);
        $project3 = Project::factory()->create(['type' => '大樓', 'manager_department' => '資料部']);

        $project1->transactions()->create(['price' => 100]);
        $project2->transactions()->createMany([
            ['price' => 200],
            ['price' => 300],
        ]);
        // 執行調度任務
        $task = new CalculateHighestTransactionPriceTask();
        $task->execute();

        // 驗證最終表中是否保存了正確的資訊   
        $this->assertEquals(100, HighestTransactionPrice::where('project_id', $project1->id)->first()->highest_price);
        $this->assertEquals(300, HighestTransactionPrice::where('project_id', $project2->id)->first()->highest_price);
        // project3 沒有交易，所以 highest_price 應為空
        $this->assertNull(HighestTransactionPrice::where('project_id', $project3->id)->first()->highest_price);

        // 驗證任務執行後是否正確記錄了日誌
        $this->assertLogHas('CalculateHighestTransactionPriceTask executed successfully.');
    }


    protected function assertLogHas($message)
    {
        $this->assertStringContainsString($message, file_get_contents(storage_path('logs/laravel.log')));
    }
}
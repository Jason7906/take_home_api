<?php

namespace App\Tasks;
use App\Models\HighestTransactionPrice;
use App\Models\Project;
use Illuminate\Support\Facades\Log;

class CalculateHighestTransactionPriceTask
{
    public function __construct()
    {
        // 在此處初始化任何需要的設置
    }

    public function execute()
    {
        try {
            // 找出建案類型為大樓且管理員部門為資料部的建案
            $projects = Project::where('type', '大樓')
                ->where('manager_department', '資料部')
                ->get();

            foreach ($projects as $project) {
                // 計算各別建案最高交易單價
                $maxPrice = $project->transactions()->max('price');

                // 將最高交易單價和其他資訊一起存入最終表
                HighestTransactionPrice::updateOrCreate(
                    ['project_id' => $project->id],
                    [
                        'manager_department' => $project->manager_department,
                        'highest_price'=> $maxPrice,
                    ]
                );
            }

            Log::info('CalculateHighestTransactionPriceTask executed successfully.');
        } catch (\Exception $e) {
            // 處理任何可能的異常
            Log::error('Error occurred while executing CalculateHighestTransactionPriceTask: ' . $e->getMessage());
        }
    }
}
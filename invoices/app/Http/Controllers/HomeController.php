<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        // ExampleController.php

        $datapaid = Invoice::whereBetween( 'invoice_data', ['2023-09-01' , '2023-10-30'])->where('value_status' , 2)->get();
        $count1 = $datapaid->count();
        // dd($count1);
        $chartjs = app()->chartjs
        ->name('lineChartTest')
        ->type('line')
        ->size(['width' => 400, 'height' => 200])
        ->labels(['January', 'February', 'March', 'April', 'May', 'June', 'July'])
        ->datasets([
            [
                "label" => "الفواتير المدفوعة",
                // 'backgroundColor' => "rgba(38, 185, 154, 0.31)",
                'borderColor' => "#008000",
                "pointBorderColor" => "#008000",
                "pointBackgroundColor" => "#008000",
                // "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "#008000",
                'data' => [$count1 ,2,4,6,8,10,13]
            ],
            [
                "label" => "الفواتير غير المدفوعة",
                // 'backgroundColor' => "rgba(139,0,0, 0.31)",
                'borderColor' => "#FF0000",
                "pointBorderColor" => "#FF0000",
                "pointBackgroundColor" => "#FF0000",
                // "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "#FF0000",
                'data' => [12, 33, 44, 44, 55, 23, 40],
            ],
            [
                "label" => "الفواتير المدفوعة جزئيا",
                // 'backgroundColor' => "rgba(255,255,0, 0.31)",
                'borderColor' => "#FFFF00",
                "pointBorderColor" => "#FFFF00",
                "pointBackgroundColor" => "#FFFF00",
                // "pointHoverBackgroundColor" => "#fff",
                "pointHoverBorderColor" => "#FFFF00",
                'data' => [17, 60, 24, 33, 5, 15, 50],
            ]
        ])
        ->options([]);


        return view('home', compact('chartjs'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use DB;
use PhpParser\JsonDecoder;
use TCPDF;

// use PDF; // at the top of the file


class Generate_pdf extends Controller
{
    //

    public function generate(Request $request){
        $layout = new Layout();
        return $layout->test();
    }
    public function download(Request $request){
        return view('test');
    }

    public function jspdf(){

    }

    public function data_resolver($id){
        $pdf = Pdf::find($id);
        $landmarks = [];
        $counter = 0;
        $x = json_decode($pdf->landmarks);
        foreach($x as $landmark){
            // $landmark = json_decode($landmark);
            $landmarks[$counter] = [
                "landmark" => $landmark->attributes->landmark,
                "distance" => $landmark->attributes->distance,
                "drive" => $landmark->attributes->drive,
            ];
            $counter++;
        }
        /// / / / / /
        $payment_plans = [];
        $counter = 0;
        $x = json_decode($pdf->payment_plan);
        if(count($x) > 0){

            foreach($x as $payment_plan){
                // $landmark = json_decode($landmark);
                $payment_plans[$counter] = [
                    "installment" => $payment_plan->attributes->installment,
                    "milestone" => $payment_plan->attributes->milestone,
                    "payment" => $payment_plan->attributes->payment,
                ];
                $counter++;
            }
        }
        /// / / / / /
        $projections = [];
        $x = json_decode($pdf->projections);
        if(count($x) > 0){
            $minimum_yearly_occupancy = $x[0]->attributes->minimum_rate_per_night * 292;
            $short_term_holiday_managment_fee = ($minimum_yearly_occupancy * 15) / 100;
            $total_yearly_deductions =$short_term_holiday_managment_fee + $x[0]->attributes->yearly_service_charge;
            $net_profit_after_deduction = $minimum_yearly_occupancy - $total_yearly_deductions;
            $long_term_net_profit_after_deduction = $x[0]->attributes->rental_rate_per_year - $x[0]->attributes->yearly_service_charge;
            $projections = [
                'property_price' => $x[0]->attributes->property_price,
                'minimum_rate_per_night' => $x[0]->attributes->minimum_rate_per_night,
                'yearly_service_charge' => $x[0]->attributes->yearly_service_charge,
                'rental_rate_per_year' => $x[0]->attributes->rental_rate_per_year,
                'minimum_yearly_occupancy' =>$minimum_yearly_occupancy,
                'short_term_holiday_managment_fee' => $short_term_holiday_managment_fee,
                'total_yearly_deductions' => $total_yearly_deductions,
                'net_profit_after_deduction' => $net_profit_after_deduction,
                'roi' => ($net_profit_after_deduction / $x[0]->attributes->property_price) * 100,
                "long_term_net_profit_after_deduction" =>$long_term_net_profit_after_deduction,
                "long_term_roi" => ($long_term_net_profit_after_deduction / $x[0]->attributes->property_price) * 100

            ];
        }


        $data = [
            "title" => $pdf->title,
            "cover" => $pdf->cover,
            "project_logo" => $pdf->project_logo,
            "last" => $pdf->last,
            "developer_logo" => $pdf->developer->logo ?? null,
            'area_title' => $pdf->area->title,
            'area_description' => $pdf->area->description,
            'area_img_1' => $pdf->area->img1,
            'area_img_2' => $pdf->area->img2,
            'area_img_3' => $pdf->area->img3,
            'area_img_4' => $pdf->area->img4,
            'landmarks' => $landmarks,
            'payment_plans' => $payment_plans,
            'projections' => $projections,

        ];
        return $data;
    }

    public function page_resolver($id){
        $pdf = Pdf::find($id);
        $pages = json_decode($pdf->pages);
        $result = [];
        $counter = 0;
        foreach($pages as $page){
            $result[$counter] = [
                'layout' => $page->layout,
                'data' => $page->attributes,
            ];
            $counter++;
        }
        // dd($result);
        return $result;

    }



    public function generate_test($id){
        $data = $this->data_resolver($id);
        $pages = $this->page_resolver($id);
        $layout = new Layout();
        $view = $layout->page_coordinator(['global_data'=>$data,'pages'=>$pages]);

        return $this->createView($view);
    }
    public function createView($view)
    {
        $viewName = 'pdf-'.uniqid();

        $viewContent = $view;

        $viewPath = resource_path('views/' . $viewName . '.blade.php');

        File::put($viewPath, $viewContent);

        return $viewName;
    }

    public function populate_view($viewName){

    }

    public function deleteView()
    {
        $viewName = Request()->post('view_name');

        $viewPath = resource_path('views/' . $viewName . '.blade.php');

        if (File::exists($viewPath)) {
            File::delete($viewPath);
        }

        return redirect()->route('home');
    }
}

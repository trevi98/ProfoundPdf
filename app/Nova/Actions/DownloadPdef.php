<?php

namespace App\Nova\Actions;

use App\Http\Controllers\Generate_pdf;
use App\Jobs\MyDelayedJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class DownloadPdef extends Action
{
    use InteractsWithQueue, Queueable;

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        //
        foreach($models as $model){
            $pdf = new Generate_pdf();
            $view = $pdf->generate_test($model->id);
            // dd($view);
            $ch = curl_init("localhost:3000?url=".$view);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            $data = curl_exec($ch);
            curl_close($ch);
            // sleep(5);
            // $ch = curl_init("localhost:3000?url=".$view."&name=".$model->title." brochure");
            // Redirect::to("localhost:3000?url=".$view."&name=".$model->title." brochure");
            return Action::redirect("http://145.14.158.172/testdown?view=".$view."&name=".$model->title." brocure");
            // MyDelayedJob::dispatch()->delay(now()->addSeconds(2));



        }
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}

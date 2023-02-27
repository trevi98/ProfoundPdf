<?php
namespace App\Nova\Fieldsx;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Laravel\Nova\Fields\Text;



class CustomFieldxxz extends Text
{
    public function fillAttribute(Request $request, $requestAttribute, $model, $attribute)
    {
        // Store the data in the model you want
        DB::table('tests')->insert([
            'test' => $request[$requestAttribute],
            'f' => 1
        ]);
    }
}


?>

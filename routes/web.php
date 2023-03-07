<?php

use App\Http\Controllers\Generate_pdf;
use Illuminate\Support\Facades\Route;
use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('test',[Generate_pdf::class,'generate'])->name('test');
Route::get('testing',[Generate_pdf::class,'download'])->name('down');
Route::get('test_pdf',[Generate_pdf::class,'jspdf'])->name('jspdf');
Route::get('generate_test',[Generate_pdf::class,'generate_test'])->name('generate_test');
Route::get('test_page',function(){
    $x = new Generate_pdf();
    $x->page_resolver(1);
});
Route::get('view_test',function(){
    return view('pdf-640712d9298d5');
})->name('view_test');
Route::get('download_view',function(){
    return view(Request()->get('view'));
});
Route::get('testdown',function(){

    return view('download_page');
});

Route::get('/map', function () {
    // code to generate the map image goes here

$client = new Client();

$response = $client->get('https://maps.googleapis.com/maps/api/staticmap', [
    'query' => [
        'center' => 'business+bay+dubai',
        'zoom' => '14',
        'size' => '900x900',
        'key' => 'AIzaSyCvGhi0l35a6aYW-Scq8QY8Aiq3jdaMJHE',
        'style' => 'feature:water|element:geometry|color:0xcccccc',
        'markers' => 'color:red|label:B|business+bay+dubai'
    ]
]);

$image = $response->getBody()->getContents();

file_put_contents('map.png', $image);
return response($image)->header('Content-Type', 'image/png');

});



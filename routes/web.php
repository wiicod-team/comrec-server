<?php

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

Route::get('reset_password/{token}', ['as' => 'password.reset', function($token)
{
    // implement your reset password route here!
}]);

Route::get('/', function () {
    return view('welcome');
});
Route::get('/my-ip', function (\Illuminate\Http\Request $request) {
    $request = $request ?? request();
    $xForwardedFor = $request->header('x-forwarded-for');
    if (empty($xForwardedFor)) {
        // Si está vacío, tome la IP del request.
        $ip = $request->ip();
    } else {
        // Si no, viene de API gateway y se transforma para usar.
        $ips = is_array($xForwardedFor) ? $xForwardedFor : explode(', ', $xForwardedFor);
        $ip = $ips[0];
    }

    \Illuminate\Support\Facades\Log::debug("ip de l'appellant : ".$ip);
    return $ip;
});


Route::get('/img/{model}/{image}', function ($model, $image) {

    return \App\Helpers\RestHelper::getFile('img',$model,$image);
    //return Storage::get("stock-images/".$type."/".$image); //will ensure a jpg is always returned
})->where('image', '.*');

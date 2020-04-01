<?php

use Dingo\Api\Routing\Router;

/** @var Router $api */
$api = app(Router::class);

$api->version('v1', function (Router $api) {
  /*  $api->group(['prefix' => 'auth'], function(Router $api) {
        $api->post('signup', 'App\\Api\\V1\\Controllers\\SignUpController@signUp');
        $api->post('login', 'App\\Api\\V1\\Controllers\\LoginController@login');

        $api->post('recovery', 'App\\Api\\V1\\Controllers\\ForgotPasswordController@sendResetEmail');
        $api->post('reset', 'App\\Api\\V1\\Controllers\\ResetPasswordController@resetPassword');

        $api->post('logout', 'App\\Api\\V1\\Controllers\\LogoutController@logout');
        $api->post('refresh', 'App\\Api\\V1\\Controllers\\RefreshController@refresh');
        $api->get('me', 'App\\Api\\V1\\Controllers\\UserController@me');
    });*/

    $api->group(['middleware' => 'jwt.auth'], function(Router $api) {
        $api->get('protected', function() {
            return response()->json([
                'message' => 'Access to protected resources granted! You are seeing this text as you provided the token correctly.'
            ]);
        });

        $api->get('refresh', [
            'middleware' => 'jwt.refresh',
            function() {
                return response()->json([
                    'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                ]);
            }
        ]);
    });

    $api->get('hello', function() {
        return response()->json([
            'message' => 'This is a simple example of item returned by your APIs. Everyone can see it.'
        ]);
    });
    $api->get('retrieve-bills', function() {
        $rep = (new \App\Helpers\BvsApi())->fetch_bills();
        return response()->json($rep);
    });


    $api->group(['namespace' => 'App\Api\V1\Controllers'], function (Router $api) {
        $api->group(['prefix' => 'auth'], function (Router $api) {
            $api->post('signup', 'Auth\AuthController@signup');
            $api->post('signin', 'Auth\AuthController@signin');
            $api->post('oauth', 'Auth\AuthController@oauth_login');
            $api->get('me', 'Auth\AuthController@getAuthenticatedUser');
            $api->put('updateUser', 'Auth\AuthController@putMe');


            $api->post('recovery', 'Auth\PasswordResetController@sendResetToken');
            $api->post('verify', 'Auth\PasswordResetController@verify');
            $api->post('reset', 'Auth\PasswordResetController@reset');

            $api->post('activateEmail', 'Auth\AccountVerificationController@activateEmail');
            $api->post('activatePhone', 'Auth\AccountVerificationController@activatePhone');

            $api->post('logout', 'LogoutController@logout');
            $api->post('refresh', 'RefreshController@refresh');
        });

        $api->group(['middleware' => 'api'], function (Router $api) {

            $api->group(['prefix' => 'users'], function(Router $api) {
                $api->get('me', 'UserController@me');
                $api->post('updateMe', 'Auth\AuthController@updateMe');
                $api->post('set-pin-code', 'Auth\AuthController@setPinCode');
            });


            $api->resource("bills", 'BillController');
            $api->resource("customers", 'CustomerController');
            $api->resource("customer_users", 'CustomerUserController');
            $api->resource("receipts", 'ReceiptController');
            $api->resource("permissions", 'PermissionController');
            $api->resource("roles", 'RoleController');
            $api->resource("users", 'UserController');


           /* $api->get('refresh', [
                'middleware' => 'jwt.refresh',
                function () {
                    return response()->json([
                        'message' => 'By accessing this endpoint, you can refresh your access token at each request. Check out this response headers!'
                    ]);
                }
            ]);*/
        });
    });
});

<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Conversation;

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/comments', function(Request $request) {
	$comments = App\Models\Comment::orderBy('created_at', 'desc')->with('author')->get();
	$ids = $comments->lists('user_id')->unique()->sort()->values();
	$authors = App\Models\User::select('id','nickname')->whereIn('id', $ids)->get();

	return response()->json($comments)->header('Access-Control-Allow-Origin', '*');
	return response()->json($comments)->setCallback($request->input('callback'));
});

$app->post('/comments', function(Request $request) {
	$author = App\Models\User::findOrFail($request->input('user_id'));
	$author->comments()->create(['text' => $request->input('text')]);

	$comments = App\Models\Comment::orderBy('created_at', 'desc')->with('author')->get()->all();
	return response($comments)->header('Access-Control-Allow-Origin', '*');
});

$app->get('/users', function(Request $request) {
	$users = App\Models\User::select('id', 'nickname')->get();

	return response()->json($users)->header('Access-Control-Allow-Origin', '*');//->setCallback($request->input('callback'));
});

$app->get('/messages/{user_id}', function($user_id) {
	$conversations = Conversation::byUser($user_id)->with('messages','user_one','user_two')->get();
	return response($conversations);
});

$app->post('/auth/login', ['uses' => 'AuthController@login']);
$app->post('/auth/logout', ['uses' => 'AuthController@logout']);

//$app->post('/auth/logout', function(Request $request) {
	// if ($user = App\User::where('remember_token', $request->input('remember_token'))->first()) {
	// 	$user->remember_token = null;
	// 	$user->save();
	// 	return response(['logout'])->header('Access-Control-Allow-Origin', '*');
	// }
	// return response([$request->input('remember_token')])->header('Access-Control-Allow-Origin', '*');
	// return null;
//});

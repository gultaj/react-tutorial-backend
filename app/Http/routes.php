<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;

$app->get('/', function () use ($app) {


    return $app->version();
});

$app->post('/test', function (Request $request) {
	return response($request->headers->get('token'))->header('Access-Control-Allow-Origin', '*');
});

$app->get('/comments', function(Request $request) {
	$comments = App\Models\Comment::orderBy('created_at', 'desc')->with('author')->get();
	$ids = $comments->lists('user_id')->unique()->sort()->values();
	$authors = App\Models\User::select('id','nickname')->whereIn('id', $ids)->get();

	return response()->json($comments)->header('Access-Control-Allow-Origin', '*');
});

$app->post('/comments', function(Request $request) {
	$author = App\Models\User::findOrFail($request->input('user_id'));
	$author->comments()->create(['text' => $request->input('text')]);

	$comments = App\Models\Comment::orderBy('created_at', 'desc')->with('author')->get()->all();
	return response($comments)->header('Access-Control-Allow-Origin', '*');
});

$app->get('/users', function(Request $request) {
	$users = App\Models\User::select('id', 'nickname')->get();

	return response()->json($users)->header('Access-Control-Allow-Origin', '*');
});

$app->post('/conversations', function(Request $request) {
	$user = User::where('remember_token', $request->input('token'))->first();
	// return response($user)->header('Access-Control-Allow-Origin', '*');
	$conversations = $user->conversations()->withoutUser($user->id)->messagesCount()->get();
	// return DB::getQueryLog();
	$conversations = $conversations->each(function($item) {
		$item['user'] = $item['users']->first();
		unset($item['users']);
		return $item;
	});
	return response($conversations)->header('Access-Control-Allow-Origin', '*');
});

$app->get('/messages/{conv_id}', function($conv_id) {
	$messages = Conversation::find($conv_id)->messages;
	return response($messages)->header('Access-Control-Allow-Origin', '*');
});

$app->post('/auth/login', ['uses' => 'AuthController@login']);
$app->post('/auth/logout', ['uses' => 'AuthController@logout']);
$app->post('/auth/register', ['uses' => 'AuthController@register']);
$app->post('/auth/user', ['uses' => 'AuthController@getUser']);


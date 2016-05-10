<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;

$app->get('/', function () use ($app) {


    return $app->version();
});
$app->group(['middleware' => 'auth'], function ($app) {
	$app->post('/test', function (Request $request) {
	return response(['user' => Auth::user()])->header('Access-Control-Allow-Origin', '*');
		return response(['token' => $request->headers->get('X-Token')])->header('Access-Control-Allow-Origin', '*')
		->header('Access-Control-Allow-Headers', 'Content-Type, X-Token');
	});
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
$app->group(['middleware' => 'auth'], function ($app) {
	$app->post('/conversations', function(Request $request) {
		$user = Auth::user();

		$conversations = $user->conversations()->withoutUser($user->id)->messagesCount()->get();

		$conversations = $conversations->each(function($item) {
			$item['user'] = $item['users']->first();
			unset($item['users']);
			return $item;
		});
		return response(['success' => true, 'conversations' => $conversations])
			->header('Access-Control-Allow-Origin', '*');
	});
	$app->post('/conversations/last_id', function(Request $request) {
		$conv_id = Auth::user()->messages()->orderBy('created_at', 'desc')->first()->conversation_id;
		return response(['success' => true, 'conv_id' => $conv_id])->header('Access-Control-Allow-Origin', '*');
	});
	
	$app->post('/messages/{conv_id}', function(Request $request, $conv_id) {
		$messages = Conversation::find($conv_id)->messages;
		return response(['success' => true, 'messages' => $messages])
			->header('Access-Control-Allow-Origin', '*');
	});
});


$app->post('/auth/login', ['uses' => 'AuthController@login']);
$app->post('/auth/logout', ['uses' => 'AuthController@logout']);
$app->post('/auth/register', ['uses' => 'AuthController@register']);
$app->post('/auth/user', ['uses' => 'AuthController@getUser']);


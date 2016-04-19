<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;

$app->get('/', function () use ($app) {

	// $user = User::with('conversations.messages')->find(1);
	// return DB::getQueryLog();
	// return $user;
	// return $user->conversations()->users()->get();

	$conversations = User::find(1)->conversations()->withoutUser(1)->messagesCount()->get();
	// $conversations = Conversation::find(2)->messages;
	return $conversations;

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

$app->get('/conversations/{user_id}', function($user_id) {
	$conversations = Conversation::where('user_one', $user_id)->select('user_two', 'id')->with('user')->get();

	// $conversations->transform(function($item, $key) {
	// 	return $item['user_two'];
	// });
	// $conversations = Conversation::byUser($user_id)->with('user_one','user_two')->get();
	return response($conversations)->header('Access-Control-Allow-Origin', '*');
});

$app->post('/auth/login', ['uses' => 'AuthController@login']);
$app->post('/auth/logout', ['uses' => 'AuthController@logout']);


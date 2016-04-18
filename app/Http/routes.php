<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Message;
use App\Models\Conversation;

$app->get('/', function () use ($app) {

	// $user = User::find(1);
	// $toUser = User::find(10);
	// $conversation = Conversation::create();
	// $conversation->users()->saveMany([$user, $toUser]);
	// $message = Message::create(['message' => 'Привет']);
	// $message->user()->associate($user)->save();
	// $message->sender()->associate($toUser)->save();
	// $message->conversation()->associate($conversation->id)->save();
	$user = User::with(['conversations' => function($query) {
		$query->where('user_id', 10);
	}])->find(1);

	return $user;
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


<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/comments', function(Request $request) {
	$comments = App\Comment::orderBy('created_at', 'desc')->with('author')->get();
	$ids = $comments->lists('user_id')->unique()->sort()->values();
	$authors = App\User::select('id','nickname')->whereIn('id', $ids)->get();

	return response()->json($comments)->header('Access-Control-Allow-Origin', '*');
	return response()->json($comments)->setCallback($request->input('callback'));
});

$app->post('/comments', function(Request $request) {
	$author = App\User::findOrFail($request->input('user_id'));
	$author->comments()->create(['text' => $request->input('text')]);

	$comments = App\Comment::orderBy('created_at', 'desc')->with('author')->get()->all();
	return response($comments)->header('Access-Control-Allow-Origin', '*');
});

$app->get('/users', function(Request $request) {
	$users = App\User::select('id', 'nickname')->get();

	return response()->json($users)->header('Access-Control-Allow-Origin', '*');//->setCallback($request->input('callback'));
});

$app->post('/auth/login', function(Request $request) {
	if ($user = App\User::where('email', $request->input('email'))->first()) {
		if (Hash::check($request->input('password'), $user->password)) {
			$user->remember_token = bin2hex(random_bytes(50));
			$user->save();
			$user = collect($user)->only('id', 'nickname', 'remember_token');
			return response($user)->header('Access-Control-Allow-Origin', '*');
		}
	}
	return null;
});

$app->post('/auth/logout', function(Request $request) {
	if ($user = App\User::where('remember_token', $request->input('remember_token'))->first()) {
		$user->remember_token = null;
		$user->save();
		return response(['logout'])->header('Access-Control-Allow-Origin', '*');
	}
	return response([$request->input('remember_token')])->header('Access-Control-Allow-Origin', '*');
	return null;
});

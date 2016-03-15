<?php
use Illuminate\Http\Request;

$app->get('/', function () use ($app) {
    return $app->version();
});

$app->get('/comments', function(Request $request) {
	$comments = App\Comment::orderBy('created_at', 'desc')->with('author')->get()->all();

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

	return response()->json($users)->setCallback($request->input('callback'));
});

$app->post('/auth/login', function(Request $request) {
	return response(Auth::check());
	$a = Auth::attempt([
		'email' => 'Linda12@Hermann.com',
		'password' => 'secret',
		'active' => '1'
	]);
	return  $a;
	// 	return response(['success']);
	// 	// return response(Auth::user())->header('Access-Control-Allow-Origin', '*');

	// return null;
});

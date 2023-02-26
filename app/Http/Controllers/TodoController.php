<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\TodoService;

class TodoController extends Controller
{
    private TodoService $todoService;
	public function __construct() {
		$this->todoService = new TodoService();
	}

	public function displayTodo()
	{
		$todos = $this->todoService->getAll();
		return response()->json($todos);
	}

	public function createTodo(Request $request)
	{
		$request->validate([
			'title'=>'required|string|min:3'
		]);

		$data = [
			'title'=>$request->post('title'),
		];

		$dataSaved = [
			'title'=>$data['title'],
			'created_at'=>time()
		];

		$id = $this->todoService->store($dataSaved);

		return response()->json($id);
	}


	public function updateTodo(Request $request)
	{
		$request->validate([
			'id'=>'required|string',
			'title'=>'string',
		]);

		$todoId = $request->post('id');
		$titl = $request->post('title');
		$todo = $this->todoService->getById($todoId);

		$todo = $this->todoService->update($todo, $titl);

		$todo = $this->todoService->getById($todoId);

		return response()->json($todo);
	}


	public function deleteTodo(Request $request)
	{
		$request->validate([
			'id'=>'required'
		]);

		$todoId = $request->id;

        $todo = $this->todoService->deleteTodo($todoId);

		return response()->json([
			'message'=> 'Success delete todo '.$todoId
		]);
	}
}

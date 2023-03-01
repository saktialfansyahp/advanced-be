<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Request;
use App\Services\TodoService;

class TodoController extends Controller
{
    private TodoService $todoService;
	public function __construct() {
		$this->todoService = new TodoService();
	}

    public function byId(Request $request)
    {
        $id = $request->id;
        try {
            $result = $this->todoService->getById($id);
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result);
    }

	public function displayTodo()
	{
		try {
            $result = $this->todoService->getAll();
        } catch (Exception $e) {
            $result = [
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response()->json($result);
	}

	public function createTodo(Request $request)
	{
        $data = $request->only(['title']);

        $result = ['status' => 201];

        try {
            $result['data'] = $this->todoService->store($data);
        } catch (Exception $e) {
            $result = [
                'status' =>'422',
                'error' => $e->getMessage(),
            ];
        }
        return response()->json($result, $result['status']);
	}

	public function updateTodo(Request $request, $id)
	{
		$data = $request->all();

        $updatedTodo = $this->todoService->update($id, $data);

        return response()->json([
            'status' => 'success',
            'message' => 'Todo updated successfully',
            'data' => $updatedTodo,
        ], 200);
	}


	public function deleteTodo($id)
	{
        $todo = $this->todoService->delete($id);

		return response()->json([
			'message'=> 'Success delete todo '.$id
		]);
	}
}

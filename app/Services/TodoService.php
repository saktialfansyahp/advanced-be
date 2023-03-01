<?php

namespace App\Services;

use App\Repositories\TodoRepository;
use Illuminate\Support\Facades\Validator;
use InvalidArgumentException;

class TodoService{
    private TodoRepository $todoRepository;
	public function __construct()
	{
		$this->todoRepository = new TodoRepository('todo');
	}
    public function getAll()
    {
        $todo = $this->todoRepository->getAll();
        return $todo;
    }
    public function getById($id)
	{
		$task = $this->todoRepository->getById($id);
		return $task;
	}
    public function store($data) : Object
    {
        $validator = Validator::make($data, [
            'title' => 'required'
        ]);
        if ($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }
        $result = $this->todoRepository->store($data);
        return $result;
    }
    public function update($id, $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required'
        ]);
        if ($validator->fails()){
            throw new InvalidArgumentException($validator->errors()->first());
        }
        $todo = $this->todoRepository->getById($id);
        if (!$todo) {
            return response()->json(['error' => 'Todo not found'], 404);
        }
        $this->todoRepository->update($data, $id);
        return $todo->fresh();
    }
    public function delete($id)
    {
        if(!$id)
		{
			return response()->json([
				'error' => 'Todo not found'
			], 404);
		}
        $task = $this->todoRepository->delete($id);
        return $task;
    }
}

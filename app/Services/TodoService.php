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
    public function update($id, array $data)
    {
        $todo = $this->todoRepository->getById($id);
        $todo = $this->todoRepository->save($todo, $data);
        return $todo;
    }
    public function deleteTodo(string $todoId)
    {
        if(!$todoId)
		{
			return response()->json([
				"message"=> "Task ".$todoId." tidak ada"
			], 401);
		}
        $task = $this->todoRepository->delete($todoId);
        return $task;
    }
}

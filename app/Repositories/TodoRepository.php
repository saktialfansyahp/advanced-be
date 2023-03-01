<?php

namespace App\Repositories;

use App\Models\Todo;

class TodoRepository
{
    private Todo $todo;
	public function __construct()
	{
		$this->todo = new Todo(['title']);
	}
    public function getAll() : Object
    {
        $todo = Todo::get();
        return $todo;
    }
    public function getById($id)
	{
		$task = Todo::find($id);
		return $task;
	}
    public function store($data) : Object
    {
        $dataBaru = new $this->todo;
        $dataBaru->title = $data['title'];
        $dataBaru->save();
        return $dataBaru->fresh();
    }
    // public function store($data)
    // {
    //     $todo = Todo::create([
    //         'title' => $data['title']
    //     ]);
    //     return $todo;
    // }
    public function delete(string $todoId)
	{
        $id = Todo::destroy($todoId);
		return $id;
	}
    public function save(Todo $todo, array $data)
    {
        $todo->update($data);
        return $todo;
    }
}

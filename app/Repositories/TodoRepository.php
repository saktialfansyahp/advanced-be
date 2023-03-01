<?php

namespace App\Repositories;

use App\Models\Todo;

class TodoRepository
{
    private Todo $todo;
	public function __construct()
	{
		$this->todo = new Todo();
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
    public function update($data, $id)
    {
        $todo = Todo::find($id);
        $todo->update($data);
    }
    public function delete($id)
	{
        $todo = Todo::find($id);
		$todo->delete();
	}
    public function save(Todo $todo, array $data)
    {
        $todo->update($data);
        return $todo;
    }
}

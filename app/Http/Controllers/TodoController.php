<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Todo;

class TodoController extends Controller
{
    public function index(){
        //$todos=Todo::all();
        $todos_active = Todo::where('statu',0)->orderBy('statu','ASC')->orderBy('priority','DESC')->orderBy('id','ASC')->get();
        $todos_completed = Todo::where('statu',1)->orderBy('statu','ASC')->orderBy('priority','DESC')->orderBy('id','ASC')->get();
        return view('index')->with(['todos_active' => $todos_active, 'todos_completed' => $todos_completed]);
    }

    public function add(Request $request){
        $todo=Todo::create(['title' => $request->title, 'priority' => $request->priority, 'statu' => 0 ]);
        return $todo;
    }

    public function completeTodo($id=null){
        $todo=Todo::where('id',$id)->update(['statu'=> 1]);
        $todoData = Todo::find($id);
        return $todoData;
    }

    public function unCompleteTodo($id=null){
        $todo=Todo::where('id',$id)->update(['statu'=> 0]);
        $todoData = Todo::find($id);
        return $todoData;
    }

    public function deleteTodo($id=null){
        $todo=Todo::destroy($id);
        return $todo;
    }

    public function priorityTodo($id=null,$priority=null){
        $todo=Todo::where('id',$id)->update(['priority'=> $priority]);
        return $todo;
    }

}

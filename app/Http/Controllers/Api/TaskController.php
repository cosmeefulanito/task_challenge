<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Iluminate\Support\Facades\Validator;

use App\Models\Task;
use App\Models\User;

class TaskController extends Controller
{

    public function list(){
        $Task = Task::all();
        return response()->json($Task);
    }

    public function create(Request $request){
        $rules = [
            'name' => 'required|min:5',
            'description' => 'required|min:10|max:100',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $task = new Task($request->input());
        $task->save();

        return response()->json([
            'status' => true,
            'message' => 'Tarea creada exitosamente'
        ], 200);
    }


    public function update(Request $request, $id){
        $rules = [
            'name' => 'required|min:5',
            'description' => 'required|min:10|max:100',
        ];

        $validator = \Validator::make($request->all(), $rules);

        if($validator->fails()){
            return response()->json([
                'status' => false,
                'msg' => 'Error de validaciones',
                'errors' => $validator->errors()->all()
            ], 400);
        }

        $task = Task::find($id);

        if(is_null($task)){
            return response()->json([
                'status' => false,
                'msg' => 'Tarea no encontrada'
            ], 404);
        }

        $task->update($request->all());

        return response()->json([
            'status' => true,
            'msg' => 'Tarea actualizada correctamente',
            'data' => $task
        ], 200);



    }

    public function show($id){
        $Task = Task::findOrFail($id);
        return response()->json([
            'status' => true,
            'data' => $Task
        ]);
    }

    public function remove($id){
        $task = Task::findOrFail($id);
        $task->delete();

        return response()->json([
            'status' => true,
            'msg' => 'Tarea eliminada correctamente'
        ]);
    }

    public function assignTaskToUser(Request $request, $taskId, $userId){
        $task = Task::findOrFail($taskId);
        $user = User::findOrFail($userId);

        $task->user_id = $userId;
        $task->save();

        return response()->json([
            'status' => true,
            'msg' => 'Tarea asignada correctamente'
        ]);
    }
}

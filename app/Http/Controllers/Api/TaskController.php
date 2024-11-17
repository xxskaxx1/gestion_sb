<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\User;
use App\Models\status;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use DB;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Task::where('status', 1)->paginate();
        $task = DB::table('task as t')
        ->select('t.*','s.name','s.color','u.name as nombre')
        ->join('status as s','s.id','t.id_status')
        ->join('users as u','u.id','t.user_creador')
        ->where('status', 1)->paginate();
        return response()->json($task);
    }

    public function store(Request $request)
    {   
        $tbl_task = new Task();
        $tbl_task->titulo = $request->titulo;
        $tbl_task->descripcion = $request->descripcion;
        $tbl_task->fec_vencimiento = $request->fec_vencimiento;
        $tbl_task->user_creador = Auth()->user()->id;
        $tbl_task->id_status = 1;
        $tbl_task->save();
        return response()->json($tbl_task,201);
    }

    public function show($task)
    {
        return Task::find($task);
    }

    public function update(Request $request)
    {
        $tbl_task = Task::where('id', $request->id)->first();
        $tbl_task->titulo = $request->titulo;
        $tbl_task->descripcion = $request->descripcion;
        $tbl_task->fec_vencimiento = $request->fec_vencimiento;
        $tbl_task->id_status;
        $tbl_task->save();
        return response()->json($tbl_task,201);
    }

    public function getStatus()
    {
        $status = status::where('id', '<>', 1)->get();
        return response()->json($status);
    }

    public function updateStatus(Request $request)
    {
        $tbl_task = Task::where('id', $request->id)->first();
        $tbl_task->id_status = $request->id_status;
        $tbl_task->save();
        return response()->json($tbl_task,201);
    }

    public function delete($task)
    {
        $tbl_task = Task::where('id', $task)->first();
        $tbl_task->status = 0;
        $tbl_task->save();
        return response()->json($tbl_task,201);
    }

    public function getByTitle(Request $request)
    {
        return Task::where('titulo','like','%'.$request->titulo.'%')->where('status', 1)->paginate();
    }

    public function getByDescription(Request $request)
    {
        return Task::where('descripcion','like','%'.$request->descripcion.'%')->where('status', 1)->paginate();
    }
}

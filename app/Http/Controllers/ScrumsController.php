<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Update;
use App\Scrum;
use App\Task;
use App\Leave;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
class ScrumsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function adminIndex()
    {
        $users = User::where('is_admin',0)->get();
        return view('admin.scrums.index',compact('users'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function employeeIndex()
    {
        $task = Task::where('user_id',\Auth::user()->id)->whereDay('created_at',date('d'))->first();
        $user_id = \Auth::user()->id;
        return view('employee.index',compact('task','user_id'));
    }

    /**
     * updated resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function employeeTaskUpdate(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'response_from_employee' => 'required',
            'user_id' => 'required|integer',
        ]);
        
        if ($validator->fails())
        {
            return response()->json([
               'errors' => $validator->errors()->all()
            ]);
        }
        $task = Task::where('user_id',$request->user_id);
        $task->update($request->all());
        return 'New Update Sucessfully Created !!';
    }

    /**
     * Display the specified resource.
     *
     * 
     * @return \Illuminate\Http\Response
     */
    public function employeeTaskHistory()
    {
        $date = \Carbon\Carbon::today()->subDays(30);
        $tasks = Task::where('created_at', '>=', $date)->where('user_id',\Auth::user()->id)->paginate(7);
        return view('employee.task_history',compact('tasks'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminScrumStore(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'todo' => 'required',
            'user_id' => 'required|integer',
            'done' => 'required',
            'roadblock' => 'required',
        ]);
        
        if ($validator->fails())
        {
            return response()->json([
               'errors' => $validator->errors()->all()
            ]);
        }
        Scrum::create($request->all());
        return response()->json(['status' => '200', 'message' => 'Task Created Successfully !!!']);
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminTask($id)
    {
        // return date('d');
        $scrum = Task::where('user_id',$id)->whereDay('created_at',date('d'))->first();
        $user_id = $id;
        $employee = User::select('full_name','avatar')->where('id',$id)->first();
        return view('admin.scrums.scrum',compact('scrum','user_id','employee'));
    }
    /**
     * updated resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function adminTaskUpdate(Request $request)
    {
        // return $request;
        $validator = \Validator::make($request->all(), [
            'assign_to_employee' => 'required',
            'user_id' => 'required|integer',
        ]);
        
        if ($validator->fails())
        {
            return response()->json([
               'errors' => $validator->errors()->all()
            ]);
        }
        $task = Task::where('user_id',$request->user_id);
        $task->update($request->except('user_id'));
        return 'Task Updated Sucessfully !!';
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminUpdatesHistory($id)
    {
        // $date = \Carbon\Carbon::today()->subDays(30);
        $updates = Update::where('user_id',$id)->paginate(7);
        $employee = User::where('id',$id)->first();
        return view('admin.updates.update_history',compact('updates','employee'));
    }
    
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function adminScrumHistory($id)
    {
        // $date = \Carbon\Carbon::today()->subDays(30);
        $scrums = Scrum::where('user_id',$id)->paginate(7);
        $employee = User::where('id',$id)->first();
        return view('admin.scrums.scrums_history',compact('scrums','employee'));
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function myHistoryEmployeeScrums()
    {
        // $date = \Carbon\Carbon::today()->subDays(30);
        $scrums = Scrum::where('user_id',\Auth::user()->id)->paginate(7);
        return view('employee.scrums_history',compact('scrums'));
    }
       /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leaveRequestStore(Request $request)
    {
        Leave::create($request->all());
        return "You Request Sent Wait Response Of Manager!!!";
    }
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leaveResponseStore(Request $request, $id)
    {
        // return ('response accept');
        $leave = Leave::where('id',$id);
       $leave->update([
           'leave_response' => $request->leave_response,
           'status' => 'accept',
       ]);
        return "Thank You For Response, Successfully Accepted !!!";
    }
     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leaveResponseRejectStore(Request $request)
    {
        // return ('response reject');
        $leave = Leave::where('user_id',$request->user_id)->where('status','pending');
       $leave->update([
        'leave_response' => $request->leave_response,
           'status' => 'reject',
       ]);
        return "Thank You For Response, Successfully Rejected !!!";
    }
    /**
     * seen the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function leaveResponseSeen($id)
    {
        $leave = Leave::where('id',$id);
        $leave->update([
        'request_seen' => '1',
       ]);
       return "message seen";
    }
   
}

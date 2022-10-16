<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Student;
use App\Models\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $dataLimit=(isset($request->dataLimit)?$request->dataLimit:10);
        $pageNumber=(isset($request->pageNumber)?$request->pageNumber:1);
        $totalPage=DB::select('CALL students_totalPage('.$dataLimit.');');
        $data=DB::select('CALL students_get('.$pageNumber.','.$dataLimit.');');

        return response()->json([
            'status' => true,
            'data' => $data,
            'totalPage' => $totalPage
        ], 200);
    }

    public function store(Request $request)
    {
        $validateData = Validator::make($request->all(),[
            'tcno' => 'required',
            'name' => 'required',
            'surname' => 'required',
            'school_id' => 'required',
            'school_no' => 'required',
        ]);

        if($validateData->fails()){
            return response()->json([
                'status' => false,
                'message' => 'validation error',
                'errors' => $validateData->errors()
            ], 401);
        }

        $data=Student::create([
            'tcno' => $request->tcno,
            'name' => $request->name,
            'surname' => $request->surname,
            'school_id' => $request->school_id,
            'school_no' => $request->school_no,
        ]);

        Log::create([
            'auth' => Auth::id(),
            'operation' => 'insert',
            'table' => 'students',
            'table_item_id' => $data->id
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Create Successfully',
        ], 200);
    }

    public function show($id)
    {
        $data = Student::find($id);
        if($data!=null){
            return response()->json([
                'status' => true,
                'data' => $data,
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'data not found',
            ], 401);
        }
    }

    public function update($id,Request $request)
    {
        $data = Student::find($id);
        if($data!=null){
            $data->update($request->all());

            Log::create([
                'auth' => Auth::id(),
                'operation' => 'update',
                'table' => 'students',
                'table_item_id' => $id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Update Successfully',
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'data not found',
            ], 401);
        }
    }

    public function destroy($id)
    {
        $data = Student::find($id);
        if($data!=null){
            $data->delete();

            Log::create([
                'auth' => Auth::id(),
                'operation' => 'delete',
                'table' => 'students',
                'table_item_id' => $id
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Delete Successfully',
            ], 200);
        }else{
            return response()->json([
                'status' => false,
                'message' => 'data not found',
            ], 401);
        }
    }
}

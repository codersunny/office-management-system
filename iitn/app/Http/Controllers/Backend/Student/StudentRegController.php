<?php

namespace App\Http\Controllers\Backend\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AssignStudent;
use App\Models\User;


use App\Models\StudentYear;
use App\Models\StudentGroup;
use App\Models\StudentShift;
use PDF;
use DB;

class StudentRegController extends Controller
{
    public function StudentRegView(){
        $data['years']=StudentYear::all();
        $data['groups']=StudentGroup::all();
        $data['allData'] = User::where('usertype', 'Student')->get();
    	return view('backend.student.student_reg.student_view',$data);

    }


    public function StudentRegAdd(){
    	$data['years'] = StudentYear::all();
    	$data['groups'] = StudentGroup::all();
    	$data['shifts'] = StudentShift::all();
    	return view('backend.student.student_reg.student_add',$data);
    }




    public function StudentRegStore(Request $request){
    	DB::transaction(function() use($request){
    	$checkYear = StudentYear::find($request->year_id)->name;
    	$student = User::where('usertype','Student')->orderBy('id','DESC')->first();

    	if ($student == null) {
    		$firstReg = 0;
    		$studentId = $firstReg+1;
    		if ($studentId < 10) {
    			$id_no = '000'.$studentId;
    		}elseif ($studentId < 100) {
    			$id_no = '00'.$studentId;
    		}elseif ($studentId < 1000) {
    			$id_no = '0'.$studentId;
    		}
    	}else{
     $student = User::where('usertype','Student')->orderBy('id','DESC')->first()->id;
     	$studentId = $student+1;
     	if ($studentId < 10) {
    			$id_no = '000'.$studentId;
    		}elseif ($studentId < 100) {
    			$id_no = '00'.$studentId;
    		}elseif ($studentId < 1000) {
    			$id_no = '0'.$studentId;
    		}

    	} // end else 

    	$final_id_no = $checkYear.$id_no;
    	$user = new User();
    	$code = rand(0000,9999);
    	$user->id_no = $final_id_no;
    	$user->password = bcrypt($code);
    	$user->usertype = 'Student';
    	$user->code = $code;
    	$user->name = $request->name;
    	$user->fname = $request->fname;
    	$user->mname = $request->mname;
    	$user->mobile = $request->mobile;
    	$user->address = $request->address;
    	$user->gender = $request->gender;
    	$user->religion = $request->religion;
    	$user->dob = date('Y-m-d',strtotime($request->dob));
        $user->year_id = StudentYear::find($request->year_id)->value('name') ;
        $user->group_id = StudentGroup::find($request->group_id)->value('name') ;
        $user->shift_id = StudentShift::find($request->shift_id)->value('name') ;
    	if ($request->file('image')) {
    		$file = $request->file('image');
    		$filename = date('YmdHi').$file->getClientOriginalName();
    		$file->move(public_path('upload/student_images'),$filename);
    		$user['image'] = $filename;
    	}
 	    $user->save();

    	});


    	$notification = array(
    		'message' => 'Student Registration Inserted Successfully',
    		'alert-type' => 'success'
    	);

    	return redirect()->route('student.registration.view')->with($notification);

    } // End Method 

    public function StudentRegEdit($id){
        $data['years'] = StudentYear::all();
    	$data['groups'] = StudentGroup::all();
    	$data['shifts'] = StudentShift::all();

        $data['editData'] = User::where('id', $id)->first();
    	return view('backend.student.student_reg.student_edit',$data);
    }

    public function StudentRegUpdate(Request $request, $id){
        DB::transaction(function() use($request, $id){

            $user =User::where('id', $id)->first();
            $user->name = $request->name;
            $user->fname = $request->fname;
            $user->mname = $request->mname;
            $user->mobile = $request->mobile;
            $user->address = $request->address;
            $user->gender = $request->gender;
            $user->religion = $request->religion;
            $user->dob = date('Y-m-d',strtotime($request->dob));
            $user->year_id = $request->year_id ;
            $user->group_id = $request->group_id ;
            $user->shift_id =$request->shift_id ;
            if ($request->file('image')) {
                $file = $request->file('image');
                @unlink(public_path('upload/student_images/'.$user->image));
                $filename = date('YmdHi').$file->getClientOriginalName();
                $file->move(public_path('upload/student_images'),$filename);
                $user['image'] = $filename;
            }
                $user->save();
    
            });
    
    
            $notification = array(
                'message' => 'Student Registration Updated Successfully',
                'alert-type' => 'success'
            );
    
            return redirect()->route('student.registration.view')->with($notification);
    
    } //End method

    public function StudentRegDelete($id){
        $user = User::find($id);
        $user-> delete();

        $notification = array(
            'message' => 'User Deleted Successfully',
            'alert-type' => 'info'
        );

        return redirect()->route('student.registration.view')->with($notification);
    }

    public function StudentRegDetails($id){
        $data['details'] =User::where('id', $id)->first();
        $pdf = PDF::loadView('backend.student.student_reg.student_details_pdf', $data);
        $pdf->SetProtection(['copy', 'print'], '', 'pass');
        return $pdf->stream('document.pdf');
    }


}

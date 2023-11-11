<?php

namespace App\Http\Controllers\AA;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CSSController extends Controller{

    function index(){
        $class_subject_students = DB::table('class_subject_students')
        ->join('users', 'class_subject_students.id', '=', 'users.id')
        ->join('class_subjects', 'class_subject_students.cs_id', '=', 'class_subjects.cs_id')
        ->join('classes', 'class_subjects.class_id', '=', 'class_subjects.class_id')
        ->join('subjects', 'class_subjects.subject_id', '=', 'class_subjects.subject_id')
        ->select('users.name as user_name','users.student_code as student_code','class_subject_students.*', 'class_subjects.*' , 'classes.*', 'subjects.*')
        ->get();
        $users = DB::table('users')->get();
        $subjects = DB::table('subjects')->get();
        $classes = DB::table('classes')->get();
        $class_subjects = DB::table('class_subjects')
        ->join('subjects', 'class_subjects.subject_id', '=', 'subjects.subject_id')
        ->groupBy('class_subjects.class_id','class_subjects.cs_id','class_subjects.subject_id','subjects.subject_id', 'subjects.subject_name','subjects.exam_times', 'subjects.ep_id', 'subjects.major_id' )
        ->get();
        return view('academic_affairs.class-subject-student.index',
        ['class_subject_students' => $class_subject_students,
         'users' => $users, 'class_subjects'=> $class_subjects, 'classes' => $classes,'subjects' => $subjects]);
    }

    function createCSS(Request $request){
        $name = $request->input('name');
        $cs_name = $request->input('cs_name');
        $result = DB::table('class_subject_students')
        ->join('users', 'class_subject_students.id', '=', 'users.id')
        ->join('class_subjects', 'class_subject_students.cs_id', '=', 'class_subjects.cs_id')
        ->select('class_subject_students.*', 'users.*','class_subjects.*')->insert([
            'id' => $name,
            'cs_id' => $cs_name,
        ]);
        if($result){
            flash()->addSuccess('Thêm thành công!');
            return redirect()->route('aa-classes-subjects');
        }else {
            flash()->addError('Thêm thất bại!');
            return redirect()->route('aa-classes-subjects');
        }
    }

    function deleteCSSById(Request $request){
        $css_id = $request->input('css_id');
        $result = DB::table('class_subject_students')->where('css_id', '=', $css_id)->delete();
        if($result){
            flash()->addSuccess('Xóa thành công!');
            return redirect()->route('aa-classes-subjects');
        }else {
            flash()->addError('Xóa thất bại!');
            return redirect()->route('aa-classes-subjects');
        }
    }

    function updateCSSById(Request $request)
    {
        $css_id = $request->input('css_id');
        $name = $request->input('name');
        $cs_name = $request->input('cs_name');
        $result = DB::table('class_subject_students')->where('css_id', '=', $css_id)->update([
            'id' => $name,
            'cs_id' => $cs_name,
        ]);
        if($result){
            flash()->addSuccess('Cập nhật thành công!');
            return redirect()->route('aa-classes-subjects');
        }else {
            flash()->addError('Cập nhật thất bại!');
            return redirect()->route('aa-classes-subjects');
        }
    }

    function edit(Request $request){
        $cs_id = $request->input('cs_id');
        $class_subject_students = DB::table('class_subject_students')
        ->join('users', 'class_subject_students.id', '=', 'users.id')
        ->join('class_subjects', 'class_subject_students.cs_id', '=', 'class_subjects.cs_id')
        ->select('class_subject_students.*', 'users.*','class_subjects.*')
        ->where('cs_id', '=', $cs_id)->get();
        $users = DB::table('users')->get();
        $class_subjects = DB::table('class_subjects')->get();
        return view('academic_affairs.class-subject-student.index', ['class_subject_students' => $class_subject_students, 'users' => $users, 'class_subjects' => $class_subjects]);


        }
}

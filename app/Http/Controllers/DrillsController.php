<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Drill;
use Illuminate\Support\Facades\Auth;

//use Illuminate\Support\Facades\Log;

class DrillsController extends Controller
{
  public function index(){
    $drills = Drill::all();
    return view('drills.index', ['drills' => $drills]); //値を渡したい（view側で使う）は第二引数に渡す。
  }

  public function new()
  {
    return view('drills.new');
  }

  public function mypage()
  {
    $drills = Auth::user()->drills()->get();
    return view('drills.mypage', compact('drills'));
  }

  public function create(Request $request)
  {
    $request->validate([
      'title' => 'required|string|max:255', //required|string|max:255
      'category_name' => 'required|string|max:255',
      'problem0' => 'required|string|max:255',
      'problem1' => 'string|nullable|max:255',
      'problem2' => 'string|nullable|max:255',
      'problem3' => 'string|nullable|max:255',
      'problem4' => 'string|nullable|max:255',
      'problem5' => 'string|nullable|max:255',
      'problem6' => 'string|nullable|max:255',
      'problem7' => 'string|nullable|max:255',
      'problem8' => 'string|nullable|max:255',
      'problem9' => 'string|nullable|max:255'
    ]);

    $drill = new Drill;
    //$drill->fill($request->all())->save();//$fillableを使うことで、他のものが変更されることがなくなる。
    Auth::user()->drills()->save($drill->fill($request->all()));

    return redirect('/drills/new')->with('flash_message', __('Registered'));
  }

  public function show($id) {
    if(!ctype_digit($id)) {
      return redirect('/drills/new')->with('flash_message', __('Invalid operation was performed'));
    }
    $drill = Drill::find($id);
    return view('drills.show', ['drill' => $drill]);
  }

  public function edit($id){ //パラメーターが自動的に入ってくる
    //GETパラメータが数字かどうかをチェックする。
    if(!ctype_digit($id)){
      return redirect('/drills/new')->with('flash_message', __('Invalid operation was performed.'));
    }
    //$drill = Drill::find($id);
    $drill = Auth::user()->drills()->find($id);
    return view('drills.edit', ['drill' => $drill]);
  }

  public function update(Request $request, $id) {
    if(!ctype_digit($id)) {
      return redirect('/drills/new')->with('flash_message', __('Invalid operation was performed'));
    }
      $drill = Drill::find($id);
      $drill->fill($request->all())->save();

      return redirect('/drills')->with('flash_message', __('Registered.'));
  }

  public function destroy($id) {
    if(!ctype_digit($id)) {
      return redirect('/drills/new')->with('flash_message', __('Invalid operation was performed'));
    }
    //Drill::find($id)->delete();
    Auth::user()->drills()->find($id)->delete();
    return redirect('/drills')->with('flash_message', __('Deleted.'));
  }
}

//   public function showProfile($id)
//   {
//       Log::info('Showing user profile for user: '.$id);

//       return view('user.profile', ['user' => User::findOrFail($id)]);
//   }

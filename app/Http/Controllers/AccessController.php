<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Auth;

class AccessController extends Controller
{
    public function adminLogin(Request $request)
    {
        try
        {   

            $data = $request->all();
            if(Auth::attempt(['email'=> $data['email'], 'password'=>$data['password']]))
            {
                $notification = array(
                    'messege'=>'Successfully Logged In',
                    'alert-type'=>'success'
                );

                return redirect()->route('dashboard')->with($notification);

            }                

            $notification = array(
                'messege'=>'Email Or Password Invalid',
                'alert-type'=>'error'
            );

            return redirect()->back()->with($notification);

        }catch(Exception $e){
            return response()->json(['status'=>false, 'code'=>$e->getCode(), 'message'=>$e->getMessage()],500);
        }
    }

    public function adminLogout()
    {   

        Auth::logout();

        $notification = array(
            'messege'=>'Successfully Logged Out',
            'alert-type'=>'success'
        );

        return redirect('/')->with($notification);

    }
}

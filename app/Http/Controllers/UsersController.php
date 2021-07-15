<?php

namespace App\Http\Controllers;

use App\Rules\MatchOldPassword;
use Illuminate\Http\Request;
use App\User;
use App\Profile;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function index()
    {
        $users = User::with('profile')->get();
        return view('users.index',compact('users'));
    }
//    public function Adminlogin(Request $request)
//    {
//        if($request->isMethod('post')) {
//            $data=$request->all();
//              $passwordcheck= Hash::make($data['password']);
//
//               $adminCount =User::where(['email'=>$data['email']])->count();
//            if ($adminCount>0){
//                return redirect()->route('user.index');
//            }else{
//
//                return redirect()->back()->with('error','Invalid username or password');
//            }
//        }
//        return view('backend.adminlogin');
//    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

$this->validate($request,[
    "name" => "required",
    'email' => 'unique:users,email',
    'password'=>'required',

    ]) ;
    //$user=User::all();
 //   if ($request->email == $user->email) {
    //   return view('this email regiterd');}
 //   else
  //  {
$password=  Hash::make($request->password);
    $user= User::create([
        "name"=> $request->name,
        "email"=>$request->email,
        'password' =>$password
,
     ]);
   // }
     $profile=Profile::create([
    'user_id'=>$user->id,
    'avatar'=>'upload\avatar\1.png'
]);
    return redirect('/users');
    }



    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $user=User::find($id);
        $userPost=$user->posts()->count();
        if($userPost>1) {
            return redirect()->route('user.index');
        }
        if(Auth::check()) {
            return redirect()->route('user.index');
        }
        User::find($id)->delete();
        return redirect()->route('user.index');
    }


    public function admin($id)
    {
        $users=User::find($id);
        $users->admin=1;
        $users->save();
        return back();
    }

    public function notAdmin($id)
    {

        $users=User::find($id);
        $users->admin=0;
        $users->save();
        return back();
    }

    public function changepassword()
    {


        return view('users.password');

    }
//    public function updatepwd(Request $request){
//        $data=$request->all();
//        $current_pwd=$request->current_pwd;
//        $user_id=Auth::user()->id;
//        $check_pwd=User::where('id',$user_id)->first();
//        if (Hash::check($current_pwd,$check_pwd->password)) {
//            echo "true" ; die;
//        } else {
//            echo "false" ; die;
//        }


//    }
    public function updatenewpwd(Request $request)
    {
        {
            $request->validate([
                'current_password' => ['required', new MatchOldPassword],
                'new_password' => ['required'],
                'new_confirm_password' => ['same:new_password'],
            ]);

            User::find(auth()->user()->id)->update(['password' => Hash::make($request->new_password)]);

            return redirect()->route('user.index');
        }


    }


}

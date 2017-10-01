<?php

namespace App\Http\Controllers;
use App\Events\AfterCreateAcount;
use Tymon\JWTAuth\Exceptions\JWTException;
use Illuminate\Support\Facades\Mail;
use App\Mail\userRegister;
use Illuminate\Http\Request;
use App\User;
use Validator;
use JWTAuth;

class UserController extends Controller
{
    
    public function register(Request $request)
    {
         //Validasi dengan mengembalikan response
        $rules = [
            'username'    => 'required|min:3|unique:users|regex:/^[A-Za-z0-9_]+$/',
            'email'       => 'required|email|unique:users',
            'password'   => 'required|min:8',
         ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails())
        {
            return response()->json(['errors' => $validator->getMessageBag()->toArray()],400);
        }

        //Simpan Di Database
        $user = new User;
        $user->username     = $request->json('username');
        $user->email        = $request->json('email');
        $user->password    = bcrypt($request->json('password'));
        $user->token       = str_random(20);
        $user->save();

       
        //Jalankan Event
        event(new AfterCreateAcount($user));

        //Setelah Menyimpan Kirim Respond
        return response()->json([ 'message' => 'check_email_to_verify' ],200);
    }

    public function sendVerification($username)
    {
        User::where('username', $username)->update([
          'created_at' => date('Y-m-d h:i:s'),
        ]);

        $user = User::where('username',$username)->get();
        if ($user->count() == 0)
        {
          return response()->json(['error'=>'not_found'],404);
        }

        $email = $user->first()->email;

        Mail::to($email)->send(new userRegister($user->first()));
        return response()->json(['message'=>'success'], 200);
    }

    public function verify($token, $date, $id){

        $user       = User::find($id);
        $datenow    = date("Y-m-d", strtotime($user->created_at));

        if($date != $datenow && $user->status != '1')
        {
            User::where('id', $id)->forceDelete(); //-> Apabila Sudah 24 jam maka harus verifikasi ulang
            return response()->json([ 'message' => 'Silahkan Registrasi Ulang' ], 404);
        }

        if ($user->status == '1'){
          return abort('404');
        }

        if ($user->token == $token && $user->id == $id)
        {

            $status = 1;
            $user->update([
                'status' => $status
                ]);
            //Membuat Token
            $token = JWTAuth::fromUser($user);

            return redirect("http://localhost:8000");
        }

    }

    public function login(Request $request){

        $rules = [
            'email' => 'required|email',
            'password' => 'required'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->getMessageBag()->toArray()],400);
        }

        $credentials = $request->only(['email','password']);
        
        try{
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 401);
            }
        }catch (JWTException $e){
            return response()->json(['error' => 'could_not_create_token'], 500);   
        }

        $idUser = $request->user()->id;
        $user = User::find($idUser);
        $status = $user->status;
        $uid = $user->username;

        if ($status != 0) {
            $user->session_start = date('Y-m-d H:i:s');
            $user->save();

            return response()->json([
                'status' => "Ok",
                'id' => $idUser,
                'token' => $token,
            ],200);
        }else{
            $username = $user->username;
            return response()->json([
                "message" => "verifikasi_email_anda",
                "username" => $username
            ],403);
        }
    }

}

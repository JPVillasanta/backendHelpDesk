<?php
namespace App\Http\Controllers;
use App\Models\{ApiToken,User};use Illuminate\Http\Request;use Illuminate\Support\Facades\Hash;use Illuminate\Support\Str;use Illuminate\Validation\ValidationException;
class AuthController {
 public function login(Request $r){$d=$r->validate(['email'=>'required|email','password'=>'required|string']);$u=User::where('email',$d['email'])->first();if(!$u||!Hash::check($d['password'],$u->password))throw ValidationException::withMessages(['email'=>['Email or password is incorrect.']]);$plain=Str::random(64);ApiToken::create(['user_id'=>$u->id,'token_hash'=>hash('sha256',$plain),'expires_at'=>now()->addDays(7)]);return response()->json(['token'=>$plain,'user'=>$u->only('id','name','email','role')]);}
 public function me(Request $r){return ['user'=>$r->user()->only('id','name','email','role')];}
 public function logout(Request $r){ApiToken::where('token_hash',hash('sha256',$r->bearerToken()))->delete();return ['message'=>'Logged out.'];}
}
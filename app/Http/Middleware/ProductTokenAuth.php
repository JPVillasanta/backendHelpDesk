<?php
namespace App\Http\Middleware;
use App\Models\User;use Closure;use Illuminate\Http\Request;
class ProductTokenAuth {public function handle(Request $request,Closure $next){$plain=$request->bearerToken();$token=$plain?\App\Models\ApiToken::where('token_hash',hash('sha256',$plain))->where('expires_at','>',now())->first():null;if(!$token)return response()->json(['message'=>'Unauthenticated.'],401);$request->setUserResolver(fn()=>User::find($token->user_id));$token->forceFill(['last_used_at'=>now()])->save();return $next($request);}}
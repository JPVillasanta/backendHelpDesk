<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class ApiToken extends Model {protected $fillable=['user_id','token_hash','expires_at','last_used_at'];protected $casts=['expires_at'=>'datetime','last_used_at'=>'datetime'];}
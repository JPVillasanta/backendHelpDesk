<?php
namespace App\Models;use Illuminate\Database\Eloquent\Model;
class Product extends Model{protected $fillable=['name','description','price','image_url','user_id'];protected function casts():array{return ['price'=>'decimal:2'];}public function user(){return $this->belongsTo(User::class);}}
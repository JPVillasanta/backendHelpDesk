<?php
namespace App\Http\Controllers;
use App\Models\Product;use Illuminate\Http\Request;
class ProductController {
 private function scope(Request $r){return $r->user()->role==='admin'?Product::query():Product::where('user_id',$r->user()->id);}
 public function index(Request $r){$s=trim((string)$r->query('search',''));return $this->scope($r)->with('user:id,name')->when($s,fn($q)=>$q->where(fn($w)=>$w->where('name','like',"%{$s}%")->orWhere('description','like',"%{$s}%")))->latest()->paginate(10);}
 public function store(Request $r){$d=$r->validate(['name'=>'required|string|max:150','description'=>'nullable|string|max:5000','price'=>'required|numeric|min:0','image_url'=>'nullable|url|max:2048']);$d['user_id']=$r->user()->id;return response()->json(['product'=>Product::create($d)],201);}
 public function show(Request $r,Product $product){$this->authorizeOwner($r,$product);return ['product'=>$product->load('user:id,name')];}
 public function update(Request $r,Product $product){$this->authorizeOwner($r,$product);$product->update($r->validate(['name'=>'sometimes|required|string|max:150','description'=>'sometimes|nullable|string|max:5000','price'=>'sometimes|required|numeric|min:0','image_url'=>'sometimes|nullable|url|max:2048']));return ['product'=>$product->fresh()];}
 public function destroy(Request $r,Product $product){$this->authorizeOwner($r,$product);$product->delete();return ['message'=>'Product deleted.'];}
 private function authorizeOwner(Request $r,Product $p):void{abort_unless($r->user()->role==='admin'||$p->user_id===$r->user()->id,404);}
}
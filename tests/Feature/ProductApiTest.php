<?php
namespace Tests\Feature;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
class ProductApiTest extends TestCase {
 use RefreshDatabase;
 private function tokenFor(User $user):string {
  return $this->postJson('/api/v1/login',['email'=>$user->email,'password'=>'password'])->assertOk()->json('token');
 }
 public function test_user_can_create_a_product_but_cannot_read_another_users_product():void {
  $owner=User::factory()->create(['password'=>'password']);
  $other=User::factory()->create(['password'=>'password']);
  $ownerToken=$this->tokenFor($owner);
  $created=$this->withToken($ownerToken)->postJson('/api/v1/products',['name'=>'Desk Lamp','description'=>'LED lamp','price'=>499.5])->assertCreated();
  $id=$created->json('product.id');
  $this->assertSame($owner->id,$created->json('product.user_id'));
  $otherToken=$this->tokenFor($other);
  $this->withToken($otherToken)->getJson('/api/v1/products')->assertOk()->assertJsonCount(0,'data');
  $this->withToken($otherToken)->getJson('/api/v1/products/'.$id)->assertNotFound();
 }
 public function test_admin_can_see_all_products_and_stats():void {
  $admin=User::factory()->create(['password'=>'password','role'=>'admin']);
  $owner=User::factory()->create(['password'=>'password']);
  $ownerToken=$this->tokenFor($owner);
  $this->withToken($ownerToken)->postJson('/api/v1/products',['name'=>'Notebook','price'=>75])->assertCreated();
  $adminToken=$this->tokenFor($admin);
  $this->withToken($adminToken)->getJson('/api/v1/admin/products')->assertOk()->assertJsonCount(1,'data');
  $this->withToken($adminToken)->getJson('/api/v1/admin/stats')->assertOk()->assertJsonPath('products',1);
 }
}
<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create the maker role
        Role::create(['name' => 'maker']);
    }

    public function test_create_product()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $user->roles()->attach(Role::where('name', 'maker')->first());

        $response = $this->actingAs($user)->post('/product', [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'type' => 'handmade',
            'material' => 'wood',
            'production_time' => 5,
            'complexity' => 'simple',
            'durability' => 'high',
            'unique_features' => 'None',
            'contains_external_links' => false,
        ]);

        $response->assertStatus(201);
        $this->assertDatabaseHas('products', [
            'name' => 'Test Product',
            'description' => 'This is a test product',
        ]);
    }

    public function test_non_maker_cannot_create_product()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/product', [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'type' => 'handmade',
            'material' => 'wood',
            'production_time' => 5,
            'complexity' => 'simple',
            'durability' => 'high',
            'unique_features' => 'None',
            'contains_external_links' => false,
        ]);

        $response->assertStatus(403);
    }

    public function test_view_product()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->get('/product/' . $product->product_id);

        $response->assertStatus(200);
        $response->assertJson([
            'name' => $product->name,
            'description' => $product->description,
        ]);
    }

    public function test_update_product()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $product = Product::factory()->create(['maker_id' => $user->id]);

        $response = $this->actingAs($user)->put('/product/' . $product->product_id, [
            'name' => 'Updated Product',
            'description' => 'This is an updated test product',
            'type' => 'handmade',
            'material' => 'wood',
            'production_time' => 5,
            'complexity' => 'simple',
            'durability' => 'high',
            'unique_features' => 'None',
            'contains_external_links' => false,
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('products', [
            'name' => 'Updated Product',
            'description' => 'This is an updated test product',
        ]);
    }

    public function test_delete_product()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $product = Product::factory()->create(['maker_id' => $user->id]);

        $response = $this->actingAs($user)->delete('/product/' . $product->product_id);

        $response->assertStatus(204);
        $this->assertDatabaseMissing('products', [
            'id' => $product->product_id,
        ]);
    }

    public function test_non_owner_cannot_update_product()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->put('/product/' . $product->product_id, [
            'name' => 'Updated Product',
            'description' => 'This is an updated test product',
            'type' => 'handmade',
            'material' => 'wood',
            'production_time' => 5,
            'complexity' => 'simple',
            'durability' => 'high',
            'unique_features' => 'None',
            'contains_external_links' => false,
        ]);

        $response->assertStatus(403);
    }

    public function test_non_owner_cannot_delete_product()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->delete('/product/' . $product->product_id);

        $response->assertStatus(403);
    }
}

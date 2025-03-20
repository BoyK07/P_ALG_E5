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

    public function test_create_product()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        Role::create(['name' => 'maker']);
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

        $product = Product::where('name', 'Test Product')->first();

        $response->assertRedirect(route('product.show', $product->product_id));
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

        $response->assertForbidden();
    }

    public function test_view_product()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->get('/product/' . $product->product_id);

        $response->assertOk();
        $response->assertSee($product->name);
        $response->assertSee($product->description);
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

        $response->assertRedirect(route('product.show', $product->product_id));
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

        $response->assertRedirect(route('product.index'));
        $this->assertDatabaseMissing('products', [
            'product_id' => $product->product_id,
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

        $response->assertForbidden();
    }

    public function test_non_owner_cannot_delete_product()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        $product = Product::factory()->create();

        $response = $this->actingAs($user)->delete('/product/' . $product->product_id);

        $response->assertForbidden();
    }

    public function test_product_creation_requires_name()
    {
        /** 
         * @var User $user
         */
        $user = User::factory()->create();
        Role::create(['name' => 'maker']);
        $user->roles()->attach(Role::where('name', 'maker')->first());

        $response = $this->actingAs($user)->post('/product', [
            // Missing name
            'description' => 'This is a test product',
            'type' => 'handmade',
            'material' => 'wood',
            'production_time' => 5,
            'complexity' => 'simple',
            'durability' => 'high',
            'contains_external_links' => false,
        ]);

        $response->assertSessionHasErrors('name');
    }

    public function test_product_creation_rejects_invalid_production_time()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();
        Role::create(['name' => 'maker']);
        $user->roles()->attach(Role::where('name', 'maker')->first());

        $response = $this->actingAs($user)->post('/product', [
            'name' => 'Test Product',
            'description' => 'This is a test product',
            'type' => 'handmade',
            'material' => 'wood',
            'production_time' => -1, // Invalid value
            'complexity' => 'simple',
            'durability' => 'high',
            'contains_external_links' => false,
        ]);

        $response->assertSessionHasErrors('production_time');
    }

    public function test_admin_can_update_any_product()
    {
        /**
         * @var User $admin
         */
        $admin = User::factory()->create();
        Role::create(['name' => 'admin']);
        $admin->roles()->attach(Role::where('name', 'admin')->first());
        $maker = User::factory()->create();

        $product = Product::factory()->create(['maker_id' => $maker->id]);

        $response = $this->actingAs($admin)->put('/product/' . $product->product_id, [
            'name' => 'Admin Updated Product',
            'description' => 'This was updated by an admin',
            'type' => 'handmade',
            'material' => 'wood',
            'production_time' => 5,
            'complexity' => 'simple',
            'durability' => 'high',
            'contains_external_links' => false,
        ]);

        $response->assertRedirect(route('product.show', $product->product_id));
    }

    public function test_view_nonexistent_product_returns_404()
    {
        /**
         * @var User $user
         */
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/product/99999');

        $response->assertStatus(404);
    }
}

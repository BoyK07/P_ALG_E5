<?php

namespace Database\Seeders;

use App\Models\Moderation;
use App\Models\Notification;
use App\Models\Order;
use App\Models\OrderStatusHistory;
use App\Models\Product;
use App\Models\Report;
use App\Models\Review;
use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $adminRole = Role::factory()->admin()->create();
        $moderatorRole = Role::factory()->moderator()->create();
        $makerRole = Role::factory()->maker()->create();
        $buyerRole = Role::factory()->buyer()->create();

        // Create admin user
        $admin = User::factory()->create([
            'name' => 'Admin User',
            'username' => 'admin',
            'email' => 'admin@example.com',
        ]);
        $admin->roles()->attach($adminRole);

        // Create moderator user
        $moderator = User::factory()->create([
            'name' => 'Moderator User',
            'username' => 'moderator',
            'email' => 'moderator@example.com',
        ]);
        $moderator->roles()->attach($moderatorRole);

        // Create 5 makers
        $makers = User::factory()->count(5)->maker()->create();
        foreach ($makers as $maker) {
            $maker->roles()->attach($makerRole);
        }

        // Create 10 buyers
        $buyers = User::factory()->count(10)->create();
        foreach ($buyers as $buyer) {
            $buyer->roles()->attach($buyerRole);
        }

        // Create test user with both maker and buyer roles
        $testUser = User::factory()->maker()->create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
        ]);
        $testUser->roles()->attach([$makerRole->role_id, $buyerRole->role_id]);

        // Create products for each maker (3-5 products per maker)
        $allMakers = $makers->push($testUser);
        $products = collect();

        foreach ($allMakers as $maker) {
            $makerProducts = Product::factory()
                ->count(fake()->numberBetween(3, 5))
                ->create(['maker_id' => $maker->id]);

            $products = $products->merge($makerProducts);
        }

        // Create orders
        $allBuyers = $buyers->push($testUser);
        $orders = collect();

        foreach ($allBuyers as $buyer) {
            $buyerOrders = Order::factory()
                ->count(fake()->numberBetween(1, 5))
                ->create([
                    'buyer_id' => $buyer->id,
                    'product_id' => $products->random()->product_id,
                ]);

            $orders = $orders->merge($buyerOrders);
        }

        // Create order status history
        foreach ($orders as $order) {
            $statusCount = fake()->numberBetween(1, 3);

            for ($i = 0; $i < $statusCount; $i++) {
                OrderStatusHistory::factory()->create([
                    'order_id' => $order->order_id,
                ]);
            }
        }

        // Create reviews for completed orders
        $completedOrders = $orders->where('status', 'delivered');
        foreach ($completedOrders as $order) {
            // 80% chance to have a review
            if (fake()->boolean(80)) {
                Review::factory()->create([
                    'order_id' => $order->order_id,
                ]);
            }
        }

        // Create notifications
        foreach ($allBuyers as $user) {
            Notification::factory()
                ->count(fake()->numberBetween(2, 8))
                ->create(['user_id' => $user->id]);
        }

        // Create moderations by moderators
        $moderators = collect([$admin, $moderator]);
        foreach ($products->random(5) as $product) {
            Moderation::factory()->create([
                'product_id' => $product->product_id,
                'moderator_id' => $moderators->random()->id,
            ]);
        }

        // Create reports from random buyers
        foreach ($products->random(8) as $product) {
            Report::factory()->create([
                'user_id' => $allBuyers->random()->id,
                'product_id' => $product->product_id,
            ]);
        }
    }
}
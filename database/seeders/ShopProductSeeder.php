<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use App\Models\Shop;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class ShopProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // Create vendor users
        echo "Creating vendor users...\n";
        $vendors = [];
        for ($i = 1; $i <= 20; $i++) {
            $vendors[] = User::create([
                'name' => 'Vendor ' . $i,
                'email' => "vendor{$i}@example.com",
                'password' => Hash::make('password123'),
                'role' => 'vendor',
            ]);
        }
        echo "✓ Created 20 vendor users\n";

        // Create shops
        echo "\nCreating shops...\n";
        $shopNames = [
            'Tech Haven', 'Fashion Forward', 'Home Essentials', 'Sports Zone',
            'Beauty Box', 'Book Worm', 'Gaming Galaxy', 'Gift Gallery',
            'Tool Time', 'Garden Paradise', 'Watch World', 'Shoe Palace',
            'Art Studio', 'Music Corner', 'Kitchen Pro', 'Electro Mart',
            'Style Studio', 'Fitness First', 'Pet Paradise', 'Auto Accessories'
        ];

        $shopDescriptions = [
            'Your one-stop shop for quality products',
            'Premium items at competitive prices',
            'Trusted by thousands of customers',
            'Bringing you the best selections',
            'Quality and satisfaction guaranteed',
        ];

        $shopImages = [
            'mock-images/shop1.jpg',
            'mock-images/shop2.jpg',
            'mock-images/shop3.jpg',
            'mock-images/shop4.jpg',
            'mock-images/shop5.jpg',
            'mock-images/shop6.jpg',
            'mock-images/shop7.jpg',
            'mock-images/shop8.jpg',
            'mock-images/shop9.jpg',
            'mock-images/shop10.jpg',
        ];

        $shops = [];
        foreach ($vendors as $index => $vendor) {
            $shops[] = Shop::create([
                'owner_id' => $vendor->id,
                'name' => $shopNames[$index],
                'description' => $faker->sentence(15),
                'logo' => $shopImages[$index % count($shopImages)],
                'status' => 'approved',
            ]);
        }
        echo "✓ Created 20 shops\n";

        // Create shop-specific categories
        echo "\nCreating shop categories...\n";
        $shopCategories = [];
        foreach ($shops as $shop) {
            for ($i = 0; $i < 3; $i++) {
                $shopCategories[] = Category::create([
                    'shop_id' => $shop->id,
                    'name' => $faker->words(2, true),
                    'description' => $faker->sentence(6),
                ]);
            }
        }
        echo "✓ Created shop categories\n";

        // Create products
        echo "\nCreating products...\n";
        $productImages = [
            'mock-images/product1.jpg',
            'mock-images/product2.jpg',
            'mock-images/product3.jpg',
            'mock-images/product4.jpg',
            'mock-images/product5.jpg',
            'mock-images/product6.jpg',
            'mock-images/product7.jpg',
            'mock-images/product8.jpg',
            'mock-images/product9.jpg',
            'mock-images/product10.jpg',
            'mock-images/product11.jpg',
            'mock-images/product12.jpg',
            'mock-images/product13.jpg',
            'mock-images/product14.jpg',
            'mock-images/product15.jpg',
        ];

        $productCount = 0;
        foreach ($shops as $shop) {
            // Get categories for this shop
            $shopCats = Category::where('shop_id', $shop->id)->get();

            // Create 20-30 products per shop
            $numProducts = rand(20, 30);
            for ($i = 0; $i < $numProducts; $i++) {
                $productCount++;
                
                // Create images array properly
                $numImages = rand(1, 3);
                $images = [];
                for ($j = 0; $j < $numImages; $j++) {
                    $images[] = $productImages[array_rand($productImages)];
                }

                Product::create([
                    'shop_id' => $shop->id,
                    'category_id' => $shopCats->random()->id,
                    'name' => ucfirst($faker->words(rand(2, 4), true)),
                    'description' => $faker->sentence(12),
                    'price' => $faker->randomFloat(2, 10, 500),
                    'quantity' => rand(10, 200),
                    'images' => $images, // Pass as array, Laravel will cast to JSON
                    'status' => $faker->randomElement(['active', 'active', 'active', 'inactive']),
                ]);
            }
        }
        echo "✓ Created {$productCount} products\n";

        echo "\n✅ Seeding completed successfully!\n";
        echo "📊 Summary:\n";
        echo "   - 20 vendor users\n";
        echo "   - 20 shops\n";
        echo "   - {$productCount} products\n";
        echo "\n🔐 Login credentials:\n";
        echo "   - Email: vendor1@example.com (or vendor2@example.com, etc.)\n";
        echo "   - Password: password123\n";
    }
}

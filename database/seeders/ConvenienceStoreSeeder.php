<?php

namespace Database\Seeders;

use App\Models\Batch;
use App\Models\Category;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Supplier;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class ConvenienceStoreSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Categories
        $categories = [
            'Beverages' => 'Drinks, Juices, and Water',
            'Snacks' => 'Chips, Chocolates, and Biscuits',
            'Pantry' => 'Instant Noodles, Bread, and Canned Goods',
            'Personal Care' => 'Toiletries and Hygiene Products',
            'Household' => 'Cleaning Supplies and Batteries',
        ];

        $categoryModels = [];
        foreach ($categories as $name => $desc) {
            $categoryModels[$name] = Category::create([
                'category_name' => $name,
                'description' => $desc,
            ]);
        }

        // 2. Suppliers
        $suppliersList = [
            ['name' => 'Metro Distribution', 'contact' => 'Alan Smith', 'phone' => '012-3456789', 'email' => 'sales@metrodist.com', 'address' => '123 Industrial Park, KL'],
            ['name' => 'Global Snacks Trading', 'contact' => 'Sarah Lee', 'phone' => '016-9876543', 'email' => 'orders@globalsnacks.com', 'address' => '45 Commercial Road, PJ'],
            ['name' => 'Fresh Essentials', 'contact' => 'Mike Tan', 'phone' => '019-1122334', 'email' => 'support@freshessentials.my', 'address' => '88 Market Lane, Penang'],
        ];

        $supplierModels = [];
        foreach ($suppliersList as $s) {
            $supplierModels[] = Supplier::create([
                'supplier_name' => $s['name'],
                'contact_person' => $s['contact'],
                'phone' => $s['phone'],
                'email' => $s['email'],
                'address' => $s['address'],
            ]);
        }

        // 3. Products
        $productsList = [
            // Beverages
            ['name' => 'Coca Cola 320ml', 'cat' => 'Beverages', 'price' => 2.50, 'alert' => 24, 'desc' => 'Carbonated cola drink'],
            ['name' => '100 Plus 500ml', 'cat' => 'Beverages', 'price' => 3.20, 'alert' => 24, 'desc' => 'Isotonic sports drink'],
            ['name' => 'Mineral Water 1.5L', 'cat' => 'Beverages', 'price' => 2.00, 'alert' => 12, 'desc' => 'Natural mineral water'],
            ['name' => 'Nescafe Tarik 240ml', 'cat' => 'Beverages', 'price' => 2.80, 'alert' => 24, 'desc' => 'Canned coffee drink'],

            // Snacks
            ['name' => 'Mister Potato Crisps', 'cat' => 'Snacks', 'price' => 4.50, 'alert' => 10, 'desc' => 'Original flavour potato crisps 160g'],
            ['name' => 'KitKat 4 Fingers', 'cat' => 'Snacks', 'price' => 2.20, 'alert' => 20, 'desc' => 'Chocolate covered wafer'],
            ['name' => 'Oreo Vanilla 137g', 'cat' => 'Snacks', 'price' => 3.80, 'alert' => 15, 'desc' => 'Sandwich cookies with vanilla cream'],
            ['name' => 'Maggi Hot Cup Curry', 'cat' => 'Pantry', 'price' => 2.50, 'alert' => 20, 'desc' => 'Instant noodles cup'],

            // Pantry & Others
            ['name' => 'Gardenia Bread', 'cat' => 'Pantry', 'price' => 4.00, 'alert' => 10, 'desc' => 'Fresh white bread 400g'],
            ['name' => 'Hup Seng Crackers', 'cat' => 'Pantry', 'price' => 5.50, 'alert' => 10, 'desc' => 'Cream crackers 428g'],
            ['name' => 'Colgate Toothpaste', 'cat' => 'Personal Care', 'price' => 12.90, 'alert' => 5, 'desc' => 'Anticavity toothpaste 250g'],
            ['name' => 'AA Batteries (4pcs)', 'cat' => 'Household', 'price' => 8.50, 'alert' => 10, 'desc' => 'Alkaline batteries'],
        ];

        $createdProducts = [];

        foreach ($productsList as $p) {
            $catId = $categoryModels[$p['cat']]->category_id;
            $supId = $supplierModels[array_rand($supplierModels)]->supplier_id;

            $createdProducts[] = Product::create([
                'product_name' => $p['name'],
                'description' => $p['desc'],
                'category_id' => $catId,
                'supplier_id' => $supId,
                'unit_price' => $p['price'],
                'lowstock_alert' => $p['alert'],
            ]);
        }

        // 4. Batches & Sales
        foreach ($createdProducts as $product) {
            // Create 1-2 Batches per product
            $numBatches = rand(1, 2);
            for ($i = 0; $i < $numBatches; $i++) {
                $qty = rand(20, 100);

                // Add valid expiry dates (Future dates, 1-24 months from now)
                $expiryDate = Carbon::now()->addMonths(rand(1, 24))->endOfMonth();

                $batchNumber = 'BATCH-'.strtoupper(mb_substr($product->product_name, 0, 3)).'-'.rand(1000, 9999);

                $batch = Batch::create([
                    'product_id' => $product->product_id,
                    'batch_number' => $batchNumber,
                    'quantity' => $qty,
                    'expiry_date' => $expiryDate,
                    'qr_code_path' => null,
                ]);

                // Create Sales for this batch (simulate past sales)
                // Deduct from batch quantity?
                // In a real seeder, we usually set the *current* quantity.
                // So if we have sales, it implies the original stock was higher.
                // Let's just record sales and assume the 'quantity' in batch is the *remaining* stock.

                $numSales = rand(0, 5);
                for ($j = 0; $j < $numSales; $j++) {
                    $soldQty = rand(1, 5);
                    Sale::create([
                        'batch_id' => $batch->batch_id,
                        'quantity' => $soldQty,
                        'unit_price' => $product->unit_price,
                        'total_amount' => $soldQty * $product->unit_price,
                        'date' => Carbon::now()->subDays(rand(1, 30)),
                        'payment_method' => ['cash', 'card', 'online'][rand(0, 2)],
                    ]);
                }
            }
        }
    }
}

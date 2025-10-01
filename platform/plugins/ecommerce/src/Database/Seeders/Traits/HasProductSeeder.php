<?php

namespace Botble\Ecommerce\Database\Seeders\Traits;

use Botble\Base\Enums\BaseStatusEnum;
use Botble\Base\Facades\MetaBox;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Models\Brand;
use Botble\Ecommerce\Models\Order;
use Botble\Ecommerce\Models\OrderAddress;
use Botble\Ecommerce\Models\OrderHistory;
use Botble\Ecommerce\Models\OrderProduct;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductAttributeSet;
use Botble\Ecommerce\Models\ProductCategory;
use Botble\Ecommerce\Models\ProductCollection;
use Botble\Ecommerce\Models\ProductFile;
use Botble\Ecommerce\Models\ProductLabel;
use Botble\Ecommerce\Models\ProductTag;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Ecommerce\Models\ProductVariationItem;
use Botble\Ecommerce\Models\Shipment;
use Botble\Ecommerce\Models\ShipmentHistory;
use Botble\Ecommerce\Models\Tax;
use Botble\Ecommerce\Models\Wishlist;
use Botble\Ecommerce\Services\Products\StoreProductService;
use Botble\Faq\Models\Faq;
use Botble\Media\Facades\RvMedia;
use Botble\Payment\Models\Payment;
use Botble\Slug\Facades\SlugHelper;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

trait HasProductSeeder
{
    /**
     * Generate a unique barcode for products
     *
     * @return string
     */
    protected function generateUniqueBarcode(): string
    {
        // Generate a random 12-digit EAN-13 compatible barcode
        // The first 12 digits are random, and the 13th is a checksum
        $barcode = '';
        for ($i = 0; $i < 12; $i++) {
            $barcode .= mt_rand(0, 9);
        }

        // Calculate checksum for EAN-13
        $sum = 0;
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $barcode[$i] * ($i % 2 === 0 ? 1 : 3);
        }
        $checksum = (10 - ($sum % 10)) % 10;

        // Append checksum to create a valid EAN-13 barcode
        $barcode .= $checksum;

        // Check if barcode already exists in the database
        if (Product::query()->where('barcode', $barcode)->exists()) {
            // If it exists, generate a new one recursively
            return $this->generateUniqueBarcode();
        }

        return $barcode;
    }

    public function createProducts(array $products, bool $truncate = true): void
    {
        if ($truncate) {
            Product::query()->truncate();
            DB::table('ec_product_with_attribute_set')->truncate();
            DB::table('ec_product_variations')->truncate();
            DB::table('ec_product_variation_items')->truncate();
            DB::table('ec_product_collection_products')->truncate();
            DB::table('ec_product_label_products')->truncate();
            DB::table('ec_product_category_product')->truncate();
            DB::table('ec_product_related_relations')->truncate();
            DB::table('ec_tax_products')->truncate();
            Wishlist::query()->truncate();
            Order::query()->truncate();
            OrderAddress::query()->truncate();
            OrderProduct::query()->truncate();
            OrderHistory::query()->truncate();
            Shipment::query()->truncate();
            ShipmentHistory::query()->truncate();
            Payment::query()->truncate();
            ProductFile::query()->truncate();
        }

        $faker = $this->fake();

        $categoryIds = ProductCategory::query()->pluck('id');
        $categoriesCount = $categoryIds->count();
        $tagIds = ProductTag::query()->pluck('id');
        $tagsCount = $tagIds->count();
        $collectionIds = ProductCollection::query()->pluck('id');
        $labelIds = ProductLabel::query()->pluck('id');
        $taxIds = Tax::query()->pluck('id');
        $brandIds = Brand::query()->pluck('id');
        $faqIds = is_plugin_active('faq') ? Faq::query()->pluck('id') : collect();
        $faqsCount = $faqIds->count();

        $insertedProducts = collect();

        foreach ($products as $key => $item) {
            if (isset($item['images'])) {
                $item['images'] = is_array($item['images']) ? json_encode($item['images']) : $item['images'];
            }

            $productName = $item['name'];

            $key = $key + 1;

            if (! isset($item['product_type'])) {
                $item['product_type'] = ProductTypeEnum::PHYSICAL;

                if ($this->hasDigitalProducts() && $key % 4 == 0) {
                    $item['product_type'] = ProductTypeEnum::DIGITAL;
                    $item['name'] .= ' (' . ProductTypeEnum::DIGITAL()->label() . ')';
                }
            }

            $item['status'] = BaseStatusEnum::PUBLISHED;
            $item['views'] = $faker->numberBetween(1000, 200000);
            $item['quantity'] = $faker->numberBetween(10, 20);
            $item['length'] = $faker->numberBetween(10, 20);
            $item['wide'] = $faker->numberBetween(10, 20);
            $item['height'] = $faker->numberBetween(10, 20);
            $item['weight'] = $faker->numberBetween(500, 900);
            $item['with_storehouse_management'] = true;
            if ($brandIds->isNotEmpty()) {
                $item['brand_id'] = $brandIds->random();
            }
            $item['sku'] = strtoupper(Str::random(2)) . '-' . $faker->numberBetween(100, 200);

            if (! isset($item['price'])) {
                $item['price'] = $faker->numberBetween(250, 2500);
            }

            if (! isset($item['sale_price'])) {
                $item['sale_price'] = $item['price'] - $faker->numberBetween(1, $item['price'] - 2);
            }

            if (! isset($item['is_featured'])) {
                $item['is_featured'] = $faker->boolean();
            }

            // Generate and assign a unique barcode if not already set
            if (! isset($item['barcode'])) {
                $item['barcode'] = $this->generateUniqueBarcode();
            }

            /**
             * @var Product $product
             */
            $product = Product::query()->create(Arr::except($item, ['metadata']));

            $this->createMetadata($product, $item);

            $insertedProducts->push($product);

            if ($collectionIds->isNotEmpty()) {
                $product->productCollections()->sync([$collectionIds->random()]);
            }

            if ($key % 3 == 0 && $labelIds->isNotEmpty()) {
                $product->productLabels()->sync([$labelIds->random()]);
            }

            if ($categoriesCount) {
                $product->categories()->sync($categoryIds->random($categoriesCount > 4 ? 4 : 1)->all());
            }

            if ($tagsCount) {
                $product->tags()->sync($tagIds->random($tagsCount > 3 ? 3 : 1)->all());
            }

            if ($taxIds->isNotEmpty()) {
                $product->taxes()->sync([$taxIds->random()]);
            }

            if (! $product->is_variation) {
                SlugHelper::createSlug($product, $productName);
            }

            if ($faqIds->isNotEmpty()) {
                MetaBox::saveMetaBoxData(
                    $product,
                    'faq_ids',
                    $faqIds->random($faqsCount >= 5 ? 5 : 1)->all()
                );
            }
        }

        $storeProductService = app(StoreProductService::class);

        if ($this->hasDigitalProducts()) {
            Storage::disk('local')->deleteDirectory(Product::getDigitalProductFilesDirectory());
        }

        $productCount = $insertedProducts->count();

        $productAttributeSets = ProductAttributeSet::query()->with('attributes')->get();

        $productAttributeSetsCount = $productAttributeSets->count();

        foreach ($insertedProducts as $key => $product) {
            $key = $key + 1;

            $product->crossSales()->sync(array_unique([
                $this->random(1, $productCount, [$key]),
                $this->random(1, $productCount, [$key]),
                $this->random(1, $productCount, [$key]),
                $this->random(1, $productCount, [$key]),
                $this->random(1, $productCount, [$key]),
                $this->random(1, $productCount, [$key]),
                $this->random(1, $productCount, [$key]),
            ]));

            if ($faker->boolean()) {
                $selectedProductAttributeSets = $productAttributeSets->take(2);

                if ($key >= (int) ($productCount / 2) && $productAttributeSetsCount > 2) {
                    $selectedProductAttributeSets = $productAttributeSets->skip(2)->take(2);
                }

                $product->productAttributeSets()->sync($selectedProductAttributeSets->pluck('id')->all());

                for ($j = 1; $j <= $faker->numberBetween(1, 5); $j++) {
                    $variation = Product::query()->create([
                        'name' => $product->name,
                        'status' => BaseStatusEnum::PUBLISHED,
                        'sku' => $product->sku . '-A' . $j,
                        'barcode' => $this->generateUniqueBarcode(), // Generate unique barcode for each variation
                        'quantity' => $product->quantity,
                        'weight' => $product->weight,
                        'height' => $product->height,
                        'wide' => $product->wide,
                        'length' => $product->length,
                        'price' => $product->price,
                        'sale_price' => $key % 4 == 0 ? ($product->price - $product->price * $faker->numberBetween(
                            10,
                            30
                        ) / 100) : null,
                        'brand_id' => $product->brand_id,
                        'with_storehouse_management' => $product->with_storehouse_management,
                        'is_variation' => true,
                        'images' => json_encode([$product->images[$j] ?? Arr::first($product->images)]),
                        'product_type' => $product->product_type,
                    ]);

                    $productVariation = ProductVariation::query()->create([
                        'product_id' => $variation->getKey(),
                        'configurable_product_id' => $product->getKey(),
                        'is_default' => $j == 1,
                    ]);

                    if ($productVariation->is_default) {
                        $product->update([
                            'sku' => $variation->sku,
                            'sale_price' => $variation->sale_price,
                        ]);
                    }

                    $selectedProductAttributeSets->each(
                        function (ProductAttributeSet $productAttributeSet) use ($faker, $productVariation): void {
                            /**
                             * @var Collection $attributes
                             */
                            $attributes = $productAttributeSet->attributes;
                            ProductVariationItem::query()->create([
                                'attribute_id' => $attributes->random()->id,
                                'variation_id' => $productVariation->id,
                            ]);
                        }
                    );
                }
            }
        }

        if ($this->hasDigitalProducts()) {
            foreach (Product::query()->where('product_type', ProductTypeEnum::DIGITAL)->get() as $product) {
                foreach ($product->images as $index => $img) {
                    if ($index > 1) {
                        continue;
                    }

                    $productFile = RvMedia::getRealPath($img);

                    if (! File::exists($productFile)) {
                        continue;
                    }

                    $fileUpload = new UploadedFile(
                        $productFile,
                        basename($img),
                        RvMedia::getMimeType($productFile),
                        null,
                        true
                    );

                    $productFileData = $storeProductService->saveProductFile($fileUpload);

                    $product->productFiles()->create($productFileData);
                }
            }
        }
    }

    protected function hasDigitalProducts(): bool
    {
        return true;
    }
}

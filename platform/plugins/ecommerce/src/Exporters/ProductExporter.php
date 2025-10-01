<?php

namespace Botble\Ecommerce\Exporters;

use Botble\DataSynchronize\Exporter\ExportColumn;
use Botble\DataSynchronize\Exporter\ExportCounter;
use Botble\DataSynchronize\Exporter\Exporter;
use Botble\Ecommerce\Enums\ProductTypeEnum;
use Botble\Ecommerce\Facades\EcommerceHelper;
use Botble\Ecommerce\Models\Product;
use Botble\Ecommerce\Models\ProductVariation;
use Botble\Language\Facades\Language;
use Botble\Media\Facades\RvMedia;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ProductExporter extends Exporter
{
    protected bool $isMarketplaceActive;

    protected bool $isEnabledDigital;

    protected array $supportedLocales = [];

    protected ?string $defaultLanguage = null;

    protected int $chunkSize = 400;

    protected bool $useChunkedExport = true;

    protected bool $includeVariations = true;

    protected bool $streamingMode = false;

    public function __construct()
    {
        $this->isMarketplaceActive = is_plugin_active('marketplace');
        $this->isEnabledDigital = EcommerceHelper::isEnabledSupportDigitalProducts();

        if (defined('LANGUAGE_MODULE_SCREEN_NAME') && defined('LANGUAGE_ADVANCED_MODULE_SCREEN_NAME')) {
            $this->supportedLocales = Language::getSupportedLocales();
            $this->defaultLanguage = Language::getDefaultLanguage(['lang_code'])?->lang_code;
        }

    }

    public function getLabel(): string
    {
        return trans('plugins/ecommerce::products.name');
    }

    public function columns(): array
    {
        $columns = [
            ExportColumn::make('id')->label('ID'),
            ExportColumn::make('name'),
            ExportColumn::make('description'),
            ExportColumn::make('slug'),
            ExportColumn::make('url')->label('URL'),
            ExportColumn::make('sku')->label('SKU'),
            ExportColumn::make('categories'),
            ExportColumn::make('status'),
            ExportColumn::make('is_featured'),
            ExportColumn::make('brand'),
            ExportColumn::make('product_collections'),
            ExportColumn::make('labels'),
            ExportColumn::make('taxes'),
            ExportColumn::make('image'),
            ExportColumn::make('images'),
            ExportColumn::make('price'),
            ExportColumn::make('product_attributes'),
            ExportColumn::make('import_type'),
            ExportColumn::make('is_variation_default'),
            ExportColumn::make('stock_status'),
            ExportColumn::make('with_storehouse_management'),
            ExportColumn::make('quantity'),
            ExportColumn::make('allow_checkout_when_out_of_stock'),
            ExportColumn::make('sale_price'),
            ExportColumn::make('start_date_sale_price'),
            ExportColumn::make('end_date_sale_price'),
            ExportColumn::make('weight'),
            ExportColumn::make('length'),
            ExportColumn::make('wide'),
            ExportColumn::make('height'),
            ExportColumn::make('cost_per_item'),
            ExportColumn::make('barcode'),
            ExportColumn::make('content'),
            ExportColumn::make('tags'),
            ExportColumn::make('generate_license_code'),
            ExportColumn::make('minimum_order_quantity'),
            ExportColumn::make('maximum_order_quantity'),
            ExportColumn::make('order'),
        ];

        if ($this->isEnabledDigital) {
            $columns[] = ExportColumn::make('product_type');
        }

        if ($this->isMarketplaceActive) {
            $columns[] = ExportColumn::make('vendor');
        }

        foreach ($this->supportedLocales as $locale) {
            if ($locale['lang_code'] !== $this->defaultLanguage) {
                $columns[] = ExportColumn::make("name_{$locale['lang_code']}")
                    ->label('Name (' . strtoupper($locale['lang_code']) . ')');
                $columns[] = ExportColumn::make("description_{$locale['lang_code']}")
                    ->label('Description (' . strtoupper($locale['lang_code']) . ')');
                $columns[] = ExportColumn::make("content_{$locale['lang_code']}")
                    ->label('Content (' . strtoupper($locale['lang_code']) . ')');
            }
        }

        return $columns;
    }

    public function collection(): Collection
    {
        if ($this->streamingMode) {
            return $this->getStreamingCollection();
        }

        if ($this->useChunkedExport) {
            return $this->getChunkedCollection();
        }

        return $this->getAllProducts();
    }

    protected function getChunkedCollection(): Collection
    {
        $products = collect();
        $with = $this->getRelationships();

        DB::disableQueryLog();
        ini_set('max_execution_time', 0);

        $processedCount = 0;

        $this->getProductQuery()
            ->select($this->getSelectColumns())
            ->where('is_variation', 0)
            ->with($with)
            ->chunk($this->chunkSize, function ($collection) use (&$products, &$processedCount): void {
                $products = $products->concat($this->productResults($collection));

                $processedCount += $collection->count();

                if ($processedCount % 1000 === 0) {
                    $this->freeMemory();
                }
            });

        DB::enableQueryLog();

        return $products;
    }

    protected function getStreamingCollection(): Collection
    {
        $products = collect();
        $with = $this->getRelationships();

        DB::disableQueryLog();
        ini_set('memory_limit', '1G');

        $processedCount = 0;

        $this->getProductQuery()
            ->select($this->getSelectColumns())
            ->where('is_variation', 0)
            ->with($with)
            ->orderBy('id')
            ->chunkById($this->chunkSize, function ($collection) use (&$products, &$processedCount): void {
                $results = $this->productResults($collection);

                foreach ($results as $result) {
                    $products->push($result);
                }

                $processedCount += $collection->count();

                if ($processedCount % 500 === 0) {
                    $this->freeMemory();
                }
            });

        DB::enableQueryLog();

        return $products;
    }

    public function formatProductRow(Product $product): array
    {
        $productAttributes = [];

        if (! $product->is_variation) {
            $productAttributes = $product->productAttributeSets->pluck('title')->all();
        }

        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'slug' => $product->slug,
            'url' => $product->url,
            'sku' => $product->sku,
            'categories' => implode(',', $product->categories->pluck('name')->all()),
            'status' => $product->status->getValue(),
            'is_featured' => $product->is_featured,
            'brand' => $product->brand?->name,
            'product_collections' => implode(',', $product->productCollections->pluck('name')->all()),
            'labels' => implode(',', $product->productLabels->pluck('name')->all()),
            'taxes' => implode(',', $product->taxes->pluck('title')->all()),
            'image' => RvMedia::getImageUrl($product->image),
            'images' => collect($product->images)->map(fn ($value) => RvMedia::getImageUrl($value))->implode(','),
            'price' => $product->price,
            'product_attributes' => implode(',', $productAttributes),
            'import_type' => 'product',
            'is_variation_default' => $product->is_variation_default ?? false,
            'stock_status' => $product->stock_status->getValue(),
            'with_storehouse_management' => $product->with_storehouse_management,
            'quantity' => $product->quantity,
            'allow_checkout_when_out_of_stock' => $product->allow_checkout_when_out_of_stock,
            'sale_price' => $product->sale_price,
            'start_date_sale_price' => $product->start_date,
            'end_date_sale_price' => $product->end_date,
            'weight' => $product->weight,
            'length' => $product->length,
            'wide' => $product->wide,
            'height' => $product->height,
            'cost_per_item' => $product->cost_per_item,
            'barcode' => $product->barcode,
            'content' => $product->content,
            'tags' => implode(',', $product->tags->pluck('name')->all()),
            'generate_license_code' => $product->generate_license_code,
            'minimum_order_quantity' => $product->minimum_order_quantity,
            'maximum_order_quantity' => $product->maximum_order_quantity,
            'order' => (int) $product->order ?: 0,
        ];

        if ($this->isEnabledDigital) {
            $result['product_type'] = $product->product_type;
        }

        if ($this->isMarketplaceActive) {
            $result['vendor'] = $product->store?->id ? $product->store->name : null;
        }

        foreach ($this->supportedLocales as $properties) {
            if ($properties['lang_code'] != $this->defaultLanguage) {
                $translation = $product->translations->where('lang_code', $properties['lang_code'])->first();

                $result['name_' . $properties['lang_code']] = $translation ? $translation->name : '';
                $result['description_' . $properties['lang_code']] = $translation ? $translation->description : '';
                $result['content_' . $properties['lang_code']] = $translation ? $translation->content : '';
            }
        }

        return $result;
    }

    protected function getAllProducts(): Collection
    {
        $products = collect();
        $with = $this->getRelationships();

        $this->getProductQuery()
            ->select(['*'])
            ->where('is_variation', 0)
            ->with($with)
            ->chunk($this->chunkSize, function ($collection) use (&$products): void {
                $products = $products->concat($this->productResults($collection));
            });

        return $products;
    }

    public function getRelationships(): array
    {
        $with = [
            'categories',
            'slugable',
            'brand',
            'taxes',
            'productLabels',
            'productCollections',
            'variations',
            'variations.product',
            'variations.configurableProduct',
            'variations.productAttributes.productAttributeSet',
            'tags',
            'productAttributeSets',
        ];

        if ($this->isMarketplaceActive) {
            $with[] = 'store';
        }

        if (count($this->supportedLocales)) {
            $with[] = 'translations';
        }

        return $with;
    }

    protected function getSelectColumns(): array
    {
        return ['*'];
    }

    protected function freeMemory(): void
    {
        if (gc_enabled()) {
            gc_collect_cycles();
        }
    }

    public function counters(): array
    {
        $products = $this->getProductsCount();
        $variations = $this->getVariationsCount();

        return [
            ExportCounter::make()
                ->label(trans('plugins/ecommerce::products.export.total_items'))
                ->value(number_format($products + $variations)),
            ExportCounter::make()
                ->label(trans('plugins/ecommerce::products.export.total_products'))
                ->value(number_format($products)),
            ExportCounter::make()
                ->label(trans('plugins/ecommerce::products.export.total_variations'))
                ->value(number_format($variations)),
        ];
    }

    public function hasDataToExport(): bool
    {
        return Product::query()->exists();
    }

    public function productResults(Collection $products): array
    {
        $results = [];

        foreach ($products as $product) {
            $productAttributes = [];

            if (! $product->is_variation) {
                $productAttributes = $product->productAttributeSets->pluck('title')->all();
            }

            $result = [
                'id' => $product->id,
                'name' => $product->name,
                'description' => $product->description,
                'slug' => $product->slug,
                'url' => $product->url,
                'sku' => $product->sku,
                'categories' => implode(',', $product->categories->pluck('name')->all()),
                'status' => $product->status->getValue(),
                'is_featured' => $product->is_featured,
                'brand' => $product->brand?->name,
                'product_collections' => implode(',', $product->productCollections->pluck('name')->all()),
                'labels' => implode(',', $product->productLabels->pluck('name')->all()),
                'taxes' => implode(',', $product->taxes->pluck('title')->all()),
                'image' => RvMedia::getImageUrl($product->image),
                'images' => collect($product->images)->map(fn ($value) => RvMedia::getImageUrl($value))->implode(','),
                'price' => $product->price,
                'product_attributes' => implode(',', $productAttributes),
                'import_type' => 'product',
                'is_variation_default' => $product->is_variation_default ?? false,
                'stock_status' => $product->stock_status->getValue(),
                'with_storehouse_management' => $product->with_storehouse_management,
                'quantity' => $product->quantity,
                'allow_checkout_when_out_of_stock' => $product->allow_checkout_when_out_of_stock,
                'sale_price' => $product->sale_price,
                'start_date_sale_price' => $product->start_date,
                'end_date_sale_price' => $product->end_date,
                'weight' => $product->weight,
                'length' => $product->length,
                'wide' => $product->wide,
                'height' => $product->height,
                'cost_per_item' => $product->cost_per_item,
                'barcode' => $product->barcode,
                'content' => $product->content,
                'tags' => implode(',', $product->tags->pluck('name')->all()),
                'generate_license_code' => $product->generate_license_code,
                'minimum_order_quantity' => $product->minimum_order_quantity,
                'maximum_order_quantity' => $product->maximum_order_quantity,
                'order' => (int) $product->order ?: 0,
            ];

            if ($this->isEnabledDigital) {
                $result['product_type'] = $product->product_type;
            }

            if ($this->isMarketplaceActive) {
                $result['vendor'] = $product->store?->id ? $product->store->name : null;
            }

            foreach ($this->supportedLocales as $properties) {
                if ($properties['lang_code'] != $this->defaultLanguage) {
                    $translation = $product->translations->where('lang_code', $properties['lang_code'])->first();

                    $result['name_' . $properties['lang_code']] = $translation ? $translation->name : '';
                    $result['description_' . $properties['lang_code']] = $translation ? $translation->description : '';
                    $result['content_' . $properties['lang_code']] = $translation ? $translation->content : '';
                }
            }

            $results[] = $result;

            if ($this->includeVariations && $product->variations->count()) {
                foreach ($product->variations as $variation) {
                    $productAttributes = $this->getProductAttributes($variation);

                    $data = [
                        'id' => $variation->product->id,
                        'name' => $variation->product->name,
                        'description' => '',
                        'slug' => '',
                        'url' => '',
                        'sku' => $variation->product->sku,
                        'categories' => '',
                        'status' => $variation->product->status->getValue(),
                        'is_featured' => '',
                        'brand' => '',
                        'product_collections' => '',
                        'labels' => '',
                        'taxes' => '',
                        'image' => RvMedia::getImageUrl($variation->product->image),
                        'images' => collect($variation->product->images)->map(fn ($value) => RvMedia::getImageUrl($value))->implode(','),
                        'price' => $variation->product->price,
                        'product_attributes' => implode(',', $productAttributes),
                        'import_type' => 'variation',
                        'is_variation_default' => $variation->is_default,
                        'stock_status' => $variation->product->stock_status->getValue(),
                        'with_storehouse_management' => $variation->product->with_storehouse_management,
                        'quantity' => $variation->product->quantity,
                        'allow_checkout_when_out_of_stock' => $variation->product->allow_checkout_when_out_of_stock,
                        'sale_price' => $variation->product->sale_price,
                        'start_date_sale_price' => $variation->product->start_date,
                        'end_date_sale_price' => $variation->product->end_date,
                        'weight' => $variation->product->weight,
                        'length' => $variation->product->length,
                        'wide' => $variation->product->wide,
                        'height' => $variation->product->height,
                        'cost_per_item' => $variation->product->cost_per_item,
                        'barcode' => $variation->product->barcode,
                        'content' => '',
                        'tags' => '',
                        'generate_license_code' => $variation->product->generate_license_code,
                        'minimum_order_quantity' => $variation->product->minimum_order_quantity,
                        'maximum_order_quantity' => $variation->product->maximum_order_quantity,
                        'order' => (int) $variation->product->order ?: 0,
                    ];

                    if ($this->isEnabledDigital) {
                        $data['product_type'] = ProductTypeEnum::PHYSICAL;
                    }

                    if ($this->isMarketplaceActive) {
                        $data['vendor'] = '';
                    }

                    foreach ($this->supportedLocales as $properties) {
                        if ($properties['lang_code'] != $this->defaultLanguage) {
                            $translation = $variation->product->translations->where('lang_code', $properties['lang_code'])->first();

                            $data['name_' . $properties['lang_code']] = $translation ? $translation->name : '';
                            $data['description_' . $properties['lang_code']] = $translation ? $translation->description : '';
                            $data['content_' . $properties['lang_code']] = '';
                        }
                    }

                    $results[] = $data;
                }
            }
        }

        return $results;
    }

    public function getProductAttributes(Product|ProductVariation $product): array
    {
        $productAttributes = [];

        foreach ($product->productAttributes as $productAttribute) {
            if ($productAttribute->productAttributeSet) {
                $productAttributes[] = $productAttribute->productAttributeSet->title . ':' . $productAttribute->title;
            }
        }

        return $productAttributes;
    }

    protected function getProductQuery(): Builder
    {
        return Product::query();
    }

    protected function getProductsCount(): int
    {
        return Product::query()->where('is_variation', false)->count();
    }

    protected function getVariationsCount(): int
    {
        if (! $this->includeVariations) {
            return 0;
        }

        return ProductVariation::query()
            ->whereHas('product')
            ->whereHas(
                'configurableProduct',
                fn (Builder $query) => $query->where('is_variation', false)
            )
            ->count();
    }

    public function setChunkSize(int $size): self
    {
        $this->chunkSize = $size;

        return $this;
    }

    public function useChunkedExport(bool $use = true): self
    {
        $this->useChunkedExport = $use;

        return $this;
    }

    public function getChunkSize(): int
    {
        return $this->chunkSize;
    }

    protected function getView(): string
    {
        return 'plugins/ecommerce::products.export';
    }

    public function setIncludeVariations(bool $include): self
    {
        $this->includeVariations = $include;

        return $this;
    }

    /**
     * Enable streaming mode for large exports
     */
    public function enableStreamingMode(bool $enable = true): self
    {
        $this->streamingMode = $enable;

        if ($enable) {
            $this->optimizeChunkSize();
        }

        return $this;
    }

    public function isStreamingMode(): bool
    {
        return $this->streamingMode;
    }

    public function getIncludeVariations(): bool
    {
        return $this->includeVariations;
    }

    public function formatVariationRow(ProductVariation $variation): array
    {
        $productAttributes = $this->getProductAttributes($variation);

        $data = [
            'id' => $variation->product->id,
            'name' => $variation->product->name,
            'description' => '',
            'slug' => '',
            'url' => '',
            'sku' => $variation->product->sku,
            'categories' => '',
            'status' => $variation->product->status->getValue(),
            'is_featured' => '',
            'brand' => '',
            'product_collections' => '',
            'labels' => '',
            'taxes' => '',
            'image' => RvMedia::getImageUrl($variation->product->image),
            'images' => collect($variation->product->images)->map(fn ($value) => RvMedia::getImageUrl($value))->implode(','),
            'price' => $variation->product->price,
            'product_attributes' => implode(',', $productAttributes),
            'import_type' => 'variation',
            'is_variation_default' => $variation->is_default,
            'stock_status' => $variation->product->stock_status->getValue(),
            'with_storehouse_management' => $variation->product->with_storehouse_management,
            'quantity' => $variation->product->quantity,
            'allow_checkout_when_out_of_stock' => $variation->product->allow_checkout_when_out_of_stock,
            'sale_price' => $variation->product->sale_price,
            'start_date_sale_price' => $variation->product->start_date,
            'end_date_sale_price' => $variation->product->end_date,
            'weight' => $variation->product->weight,
            'length' => $variation->product->length,
            'wide' => $variation->product->wide,
            'height' => $variation->product->height,
            'cost_per_item' => $variation->product->cost_per_item,
            'barcode' => $variation->product->barcode,
            'content' => '',
            'tags' => '',
            'generate_license_code' => $variation->product->generate_license_code,
            'minimum_order_quantity' => $variation->product->minimum_order_quantity,
            'maximum_order_quantity' => $variation->product->maximum_order_quantity,
            'order' => (int) $variation->product->order ?: 0,
        ];

        if ($this->isEnabledDigital) {
            $data['product_type'] = ProductTypeEnum::PHYSICAL;
        }

        if ($this->isMarketplaceActive) {
            $data['vendor'] = '';
        }

        foreach ($this->supportedLocales as $properties) {
            if ($properties['lang_code'] != $this->defaultLanguage) {
                $data['name_' . $properties['lang_code']] = '';
                $data['description_' . $properties['lang_code']] = '';
                $data['content_' . $properties['lang_code']] = '';
            }
        }

        $mappedData = [];
        foreach ($this->getAcceptedColumns() as $column) {
            $mappedData[] = $data[$column->getName()] ?? '';
        }

        return $mappedData;
    }

    /**
     * Optimize chunk size based on dataset characteristics
     */
    protected function optimizeChunkSize(): void
    {
        $productCount = $this->getProductsCount();
        $variationCount = $this->includeVariations ? $this->getVariationsCount() : 0;

        if ($productCount > 0) {
            $avgVariationsPerProduct = $variationCount / $productCount;

            if ($avgVariationsPerProduct > 5) {
                $this->chunkSize = 100;
            } elseif ($avgVariationsPerProduct > 2) {
                $this->chunkSize = 200;
            } else {
                $this->chunkSize = 300;
            }
        }
    }

    public function map($row): array
    {
        return parent::map($row);
    }
}

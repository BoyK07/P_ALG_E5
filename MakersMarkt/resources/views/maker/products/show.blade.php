<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
            <a href="{{ route('product.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white py-2 px-4 rounded flex items-center">
                Terug naar overzicht
            </a>
        </div>

        <div class="bg-white p-4 rounded-lg shadow-md">
            <p class="text-gray-700 mb-4">{{ $product->description }}</p>
            <p class="text-gray-500 text-sm mb-2">Type: {{ $product->type }}</p>
            <p class="text-gray-500 text-sm mb-2">Materiaal: {{ $product->material }}</p>
            <p class="text-gray-500 text-sm mb-2">Productietijd: {{ $product->production_time }} dagen</p>
            <p class="text-gray-500 text-sm mb-2">Complexiteit: {{ $product->complexity }}</p>
            <p class="text-gray-500 text-sm mb-2">Duurzaamheid: {{ $product->durability }}</p>
            <p class="text-gray-500 text-sm mb-2">Unieke Kenmerken: {{ $product->unique_features }}</p>
        </div>
        
        @can('update', $product)
            <div class="mt-6 flex justify-end">
                <a href="{{ route('product.edit', $product->product_id) }}" class="bg-yellow-500 hover:bg-yellow-600 text-white py-2 px-4 rounded flex items-center">
                    Update
                </a>
            </div>
        @endcan
    </div>
</x-app-layout>

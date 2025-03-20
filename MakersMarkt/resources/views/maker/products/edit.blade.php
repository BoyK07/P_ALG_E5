<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Product Bewerken</h1>
        <form method="POST" action="{{ route('product.update', ['id' => $product->product_id]) }}">
            @method('PUT')
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Naam</label>
                <input type="text" name="name" id="name" value="{{ $product->name }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Beschrijving</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">{{ $product->description }}</textarea>
            </div>
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <input type="text" name="type" id="type" value="{{ $product->type }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="material" class="block text-sm font-medium text-gray-700">Materiaal</label>
                <input type="text" name="material" id="material" value="{{ $product->material }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="production_time" class="block text-sm font-medium text-gray-700">Productietijd (dagen)</label>
                <input type="number" name="production_time" id="production_time" value="{{ $product->production_time }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="complexity" class="block text-sm font-medium text-gray-700">Complexiteit</label>
                <input type="text" name="complexity" id="complexity" value="{{ $product->complexity }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="durability" class="block text-sm font-medium text-gray-700">Duurzaamheid</label>
                <input type="text" name="durability" id="durability" value="{{ $product->durability }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="unique_features" class="block text-sm font-medium text-gray-700">Unieke Kenmerken</label>
                <textarea name="unique_features" id="unique_features" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">{{ $product->unique_features }}</textarea>
            </div>
            <div class="mb-4">
                <label for="contains_external_links" class="block text-sm font-medium text-gray-700">Bevat Externe Links</label>
                <input type="hidden" name="contains_external_links" value="0">
                <input type="checkbox" name="contains_external_links" id="contains_external_links" value="1" {{ $product->contains_external_links ? 'checked' : '' }} class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="flex justify-end">
                {{-- @can('delete', $product)
                <form method="POST" action="{{ route('product.destroy', ['id' => $product->product_id]) }}" class="mt-4">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white py-2 px-4 rounded hover:bg-red-600">Verwijderen</button>
                </form>
                @endcan --}}

                <button type="button" @click="show = false" class="bg-gray-500 text-white py-2 px-4 rounded hover:bg-gray-600">Annuleren</button>
                <button type="submit" class="ml-3 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Opslaan</button>
            </div>
        </form>

    </div>
</x-app-layout>

<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Nieuw Product Aanmaken</h1>
        <form method="POST" action="{{ route('product.store') }}">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Naam</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Beschrijving</label>
                <textarea name="description" id="description" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"></textarea>
            </div>
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Type</label>
                <input type="text" name="type" id="type" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="material" class="block text-sm font-medium text-gray-700">Materiaal</label>
                <input type="text" name="material" id="material" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="production_time" class="block text-sm font-medium text-gray-700">Productietijd (dagen)</label>
                <input type="number" name="production_time" id="production_time" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="complexity" class="block text-sm font-medium text-gray-700">Complexiteit</label>
                <input type="text" name="complexity" id="complexity" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="durability" class="block text-sm font-medium text-gray-700">Duurzaamheid</label>
                <input type="text" name="durability" id="durability" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>
            <div class="mb-4">
                <label for="unique_features" class="block text-sm font-medium text-gray-700">Unieke Kenmerken</label>
                <textarea name="unique_features" id="unique_features" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50"></textarea>
            </div>
            <div class="mb-4">
                <label for="contains_external_links" class="block text-sm font-medium text-gray-700">Bevat Externe Links</label>
                <input type="hidden" name="contains_external_links" value="0">
                <input type="checkbox" name="contains_external_links" id="contains_external_links" value="1" class="mt-1 block rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500 focus:ring-opacity-50">
            </div>            
            <div class="flex justify-end">
                <button type="submit" class="ml-3 bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">Aanmaken</button>
            </div>
        </form>
    </div>
</x-app-layout>

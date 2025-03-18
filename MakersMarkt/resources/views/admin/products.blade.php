<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard - Products</h1>

        <div class="w-full">
            <h2 class="text-xl font-semibold mb-4">Producten Verifiëren / Verwijderen</h2>

            <!-- Zoekbalk voor Producten -->
            <div class="mb-4">
                <label for="productSearch" class="block mb-2 font-medium text-gray-700">Zoek Producten</label>
                <input type="text" id="productSearch" name="productSearch" placeholder="Typ om te zoeken..."
                    class="w-full p-2 border rounded" />
            </div>

            <!-- Tabel met Producten -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Naam</th>
                            <th class="px-4 py-2 border">Prijs</th>
                            <th class="px-4 py-2 border">Acties</th>
                        </tr>
                    </thead>
                    <tbody id="productTableBody">
                        @foreach ($products as $product)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 border">{{ $product->product_id }}</td>
                                <td class="px-4 py-2 border">
                                    <a href="{{ route('admin.product.show', $product->product_id) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ $product->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-2 border">{{ $product->price }}</td>
                                <td class="px-4 py-2 border">
                                    <button
                                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-1 px-3 rounded">
                                        Verifiëren
                                    </button>
                                    <button
                                        class="bg-red-500 hover:bg-red-600 text-white font-semibold py-1 px-3 rounded ml-2">
                                        Verwijderen
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        <tr id="productNoResults" class="hidden">
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                Geen resultaten gevonden.
                            </td>
                        </tr>

                        @if ($products->isEmpty())
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                    Geen producten gevonden.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Zoekfunctie voor producten
        document.getElementById('productSearch').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const productRows = document.querySelectorAll('#productTableBody tr:not(#productNoResults)');
            let resultsFound = false;

            productRows.forEach(row => {
                if (!row.classList.contains('empty-message')) {
                    const text = row.textContent.toLowerCase();
                    if (text.includes(searchValue)) {
                        row.style.display = '';
                        resultsFound = true;
                    } else {
                        row.style.display = 'none';
                    }
                }
            });

            // Toon 'geen resultaten' bericht indien nodig
            const noResultsRow = document.getElementById('productNoResults');
            noResultsRow.style.display = resultsFound ? 'none' : 'table-row';
        });
    </script>
</x-app-layout>

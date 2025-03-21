<x-app-layout>
    <div class="container mx-auto p-6">
        <div class="flex w-full justify-between">
            <h1 class="text-2xl font-bold mb-6">Product Overzicht</h1>

            <!-- Create Product Button -->
            @can('create', App\Models\Product::class)
                <div class="mb-6">
                    <a href="{{ route('product.create') }}" class="bg-green-500 text-white py-2 px-4 rounded hover:bg-green-600">
                        Create Product
                    </a>
                </div>
            @endcan
        </div>

        <!-- Filter UI -->
        <div class="mb-6 flex flex-col sm:flex-row gap-4">
            <div class="w-full sm:w-1/2">
                <label for="filter-category" class="block mb-2 font-medium text-gray-700">Filter categorie:</label>
                <select id="filter-category" name="filter-category" class="w-full p-2 border rounded">
                    <option value="">Alle</option>
                    <option value="type">Type</option>
                    <option value="material">Materiaal</option>
                    <option value="production_time">Productietijd</option>
                    <option value="complexity">Complexiteit</option>
                    <option value="durability">Duurzaamheid</option>
                    <option value="unique_features">Unieke Kenmerken</option>
                </select>
            </div>
            <div class="w-full sm:w-1/2" id="value-filter-container" style="display: none;">
                <label for="filter-value" class="block mb-2 font-medium text-gray-700">Filter waarde:</label>
                <select id="filter-value" name="filter-value" class="w-full p-2 border rounded">
                    <option value="">Alle</option>
                    <!-- Options will be populated with JavaScript -->
                </select>
            </div>
        </div>

        <!-- Product List -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4" id="product-list">
            @foreach ($products as $product)
                <div class="bg-white p-4 rounded-lg shadow-md flex flex-col justify-between product-card h-full"
                     data-type="{{ $product->type }}"
                     data-material="{{ $product->material }}"
                     data-production_time="{{ $product->production_time }}"
                     data-complexity="{{ $product->complexity }}"
                     data-durability="{{ $product->durability }}"
                     data-unique_features="{{ $product->unique_features }}">
                    <div>
                        <h2 class="text-xl font-semibold mb-2">{{ $product->name }}</h2>
                        <p class="text-gray-700 mb-2 overflow-hidden text-ellipsis" style="display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical;">{{ $product->description }}</p>
                        <p class="text-gray-500 text-sm mb-1">Type: {{ $product->type }}</p>
                        <p class="text-gray-500 text-sm mb-1">Materiaal: {{ $product->material }}</p>
                        <p class="text-gray-500 text-sm mb-1">Productietijd: {{ $product->production_time }} dagen</p>
                        <p class="text-gray-500 text-sm mb-1">Complexiteit: {{ $product->complexity }}</p>
                        <p class="text-gray-500 text-sm mb-1">Duurzaamheid: {{ $product->durability }}</p>
                        <p class="text-gray-500 text-sm mb-1">Unieke Kenmerken: {{ $product->unique_features }}</p>
                    </div>
                    <div class="mt-4 flex justify-end">
                        <a href="{{ route('product.show', $product->product_id) }}"
                           class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600">
                            Bekijk Product
                        </a>
                        <a href="{{ route('product.show', $product->product_id) }}"
                            class="bg-orange-500 text-white py-2 px-4 rounded hover:bg-orange-600 ml-2">
                             Koop Product
                         </a>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterCategory = document.getElementById('filter-category');
            const filterValue = document.getElementById('filter-value');
            const valueFilterContainer = document.getElementById('value-filter-container');
            const productList = document.getElementById('product-list');
            const products = productList.querySelectorAll('.product-card');

            // Function to collect unique values for a specific attribute
            function getUniqueValues(attribute) {
                const values = new Set();
                products.forEach(product => {
                    const value = product.getAttribute('data-' + attribute);
                    if (value) {
                        values.add(value);
                    }
                });

                // Convert to array for sorting
                let valuesArray = Array.from(values);

                // Sort numerically if it's production_time, otherwise sort alphabetically
                if (attribute === 'production_time') {
                    valuesArray.sort((a, b) => parseInt(a) - parseInt(b));
                } else {
                    valuesArray.sort();
                }

                return valuesArray;
            }

            // Function to update the value filter dropdown
            function updateValueFilter(category) {
                // Clear previous options
                filterValue.innerHTML = '<option value="">Alle</option>';

                if (!category) {
                    valueFilterContainer.style.display = 'none';
                    return;
                }

                // Show the values dropdown
                valueFilterContainer.style.display = 'block';

                // Get unique values for the selected category
                const uniqueValues = getUniqueValues(category);

                // Add options for each unique value
                uniqueValues.forEach(value => {
                    const option = document.createElement('option');
                    option.value = value;

                    // For production_time, append "dagen" to the display text
                    if (category === 'production_time') {
                        option.textContent = value + ' dagen';
                    } else {
                        option.textContent = value;
                    }

                    filterValue.appendChild(option);
                });
            }

            // Function to apply filters
            function applyFilters() {
                const category = filterCategory.value;
                const value = filterValue.value;

                products.forEach(product => {
                    if (!category) {
                        // No category selected, show all products
                        product.style.display = 'flex';
                    } else {
                        const attributeValue = product.getAttribute('data-' + category);

                        if (!value) {
                            // Only category selected, show all products with that attribute
                            product.style.display = attributeValue ? 'flex' : 'none';
                        } else {
                            // Both category and value selected
                            product.style.display = (attributeValue === value) ? 'flex' : 'none';
                        }
                    }
                });
            }

            // Event listener for category filter change
            filterCategory.addEventListener('change', function() {
                updateValueFilter(this.value);
                applyFilters();
            });

            // Event listener for value filter change
            filterValue.addEventListener('change', function() {
                applyFilters();
            });
        });
    </script>
</x-app-layout>

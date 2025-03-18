<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard - Users</h1>

        <div class="w-full">
            <h2 class="text-xl font-semibold mb-4">Users Verifiëren</h2>

            <!-- Zoekbalk voor Users -->
            <div class="mb-4">
                <label for="userSearch" class="block mb-2 font-medium text-gray-700">Zoek Users</label>
                <input type="text" id="userSearch" name="userSearch" placeholder="Typ om te zoeken..."
                    class="w-full p-2 border rounded" />
            </div>

            <!-- Tabel met Users -->
            <div class="overflow-x-auto">
                <table class="min-w-full bg-white border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Naam</th>
                            <th class="px-4 py-2 border">Email</th>
                            <th class="px-4 py-2 border">Acties</th>
                        </tr>
                    </thead>
                    <tbody id="userTableBody">
                        @foreach ($users as $user)
                            <tr class="border-b hover:bg-gray-50">
                                <td class="px-4 py-2 border">{{ $user->id }}</td>
                                <td class="px-4 py-2 border">
                                    <a href="{{ route('admin.user.show', $user->id) }}"
                                        class="text-blue-600 hover:underline">
                                        {{ $user->name }}
                                    </a>
                                </td>
                                <td class="px-4 py-2 border">{{ $user->email }}</td>
                                <td class="px-4 py-2 border">
                                    <button
                                        class="bg-green-500 hover:bg-green-600 text-white font-semibold py-1 px-3 rounded">
                                        Verifiëren
                                    </button>
                                </td>
                            </tr>
                        @endforeach

                        <tr id="userNoResults" class="hidden">
                            <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                Geen resultaten gevonden.
                            </td>
                        </tr>

                        @if ($users->isEmpty())
                            <tr>
                                <td colspan="4" class="px-4 py-2 text-center text-gray-500">
                                    Geen users gevonden voor verificatie.
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script>
        // Zoekfunctie voor users
        document.getElementById('userSearch').addEventListener('input', function() {
            const searchValue = this.value.toLowerCase();
            const userRows = document.querySelectorAll('#userTableBody tr:not(#userNoResults)');
            let resultsFound = false;

            userRows.forEach(row => {
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
            const noResultsRow = document.getElementById('userNoResults');
            noResultsRow.style.display = resultsFound ? 'none' : 'table-row';
        });
    </script>
</x-app-layout>

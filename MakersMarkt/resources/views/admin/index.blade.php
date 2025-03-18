<x-app-layout>
    <div class="container mx-auto p-6">
        <h1 class="text-2xl font-bold mb-6">Admin Dashboard</h1>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <!-- Gebruikers -->
            <a href="{{ route('admin.user.index') }}"
               class="block text-center py-4 bg-green-500 text-white rounded hover:bg-green-600">
                Gebruikers
            </a>

            <!-- Producten -->
            <a href="{{ route('admin.product.index') }}"
               class="block text-center py-4 bg-green-500 text-white rounded hover:bg-green-600">
                Producten
            </a>

            <!-- Externe Links -->
            {{-- <a href="{{ route('external.links') ?? '#' }}" --}}
            <a href="#"
               class="block text-center py-4 bg-green-500 text-white rounded hover:bg-green-600">
                Externe Links
            </a>

            <!-- Statistieken -->
            {{-- <a href="{{ route('admin.statistics') ?? '#' }}" --}}
            <a href="#"
            class="block text-center py-4 bg-green-500 text-white rounded hover:bg-green-600">
                Statistieken
            </a>
        </div>
    </div>
</x-app-layout>

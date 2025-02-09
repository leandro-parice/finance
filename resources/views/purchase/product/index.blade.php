<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Compras</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if (session('success'))
                        <div id="successMessage" class=" px-4 py-2 mb-8 text-green-700 bg-green-200 border border-green-600 rounded">
                            <p>{{ session('success') }}</p>
                        </div>
                    @endif

                    <p>{{ $purchase->date }}</p>

                    <table class="table-auto w-full mt-4 border-collapse border border-gray-600">
                        <thead>
                            <tr>
                                <th class="border border-gray-600">Produto</th>
                                <th class="border border-gray-600">Quantidade</th>
                                <th class="border border-gray-600">Valor unitário</th>
                                <th class="border border-gray-600">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase->products as $product)
                                <tr>
                                    <td class="border border-gray-600 text-center py-2 px-4">{{ $product->name }}</td>
                                    <td class="border border-gray-600 text-center py-2 px-4">{{ $product->pivot->quantity }}</td>
                                    <td class="border border-gray-600 text-center py-2 px-4">R$ {{ number_format($product->pivot->price, 2, ',', '.') }}</td>
                                    <td class="border border-gray-600 text-center py-2 px-4">R$ {{ number_format($product->pivot->total, 2, ',', '.') }}</td>
                                </tr>
                            @endforeach
                        </tobydy>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

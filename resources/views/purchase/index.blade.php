<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Compras</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    <a  href="{{ route('purchase.create') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Nova compra</a>

                    <table class="table-auto w-full mt-4 border-collapse border border-gray-200">
                        <thead>
                            <tr class="border border-gray-200">
                                <th class="border border-gray-600">Supermercado</th>
                                <th class="border border-gray-600">Valor</th>
                                <th class="border border-gray-600">Data</th>
                                <th class="border border-gray-600">Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchases as $key => $purchase)
                                <tr class="border border-gray-600 text-sm {{ $key % 2 == 0 ? 'bg-gray-800' : 'bg-gray-700' }}">
                                    <td class="border border-gray-600 text-center py-2 px-4">{{ $purchase->market }}</td>
                                    <td class="border border-gray-600 text-center py-2 px-4">R$ {{ number_format($purchase->value, 2) }}</td>
                                    <td class="border border-gray-600 text-center py-2 px-4">{{ \Carbon\Carbon::parse($purchase->date)->format('d/m/Y') }}</td>
                                    <td class="border border-gray-600 text-center py-2 px-4" x-data="{
                                        openModal: false,
                                        submitForm() {
                                            this.$refs.form.submit();
                                        }
                                    }">
                                        <a href="{{ route('purchase.edit', $purchase->id) }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-1 px-2 rounded inline-block text-xs">Editar</a>
                                        <button type="button" x-on:click="openModal = !openModal" class="bg-red-500 hover:bg-red-700 text-white font-bold py-1 px-2 rounded inline-block text-xs">Excluir</button>
                                        <div id="modal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 " x-show="openModal">
                                            <div class="bg-gray-800 p-6 rounded-lg shadow-lg w-96">
                                                <h2 class="text-xl font-semibold mb-4 text-white">Tem certeza que deseja remover?</h2>
                                                <p class="mb-4 text-gray-300">Esse processo é irreversível.</p>
                                                <div class="py-3 sm:flex sm:align-center sm:justify-center sm:gap-4">
                                                    <form x-ref="form" action="{{ route('purchase.destroy', $purchase->id) }}" method="POST" class="inline">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm">
                                                            Sim, excluir
                                                        </button>
                                                    </form>
                                                    <button x-on:click="openModal = !openModal" class="px-4 py-2 text-white bg-gray-500 hover:bg-gray-600 rounded">Cancelar</button>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tobydy>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

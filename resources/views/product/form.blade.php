<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($product) ? 'Editar Produto' : 'Novo Produto' }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    @if ($errors->any())
                        <div class="mb-4">
                            <div class="font-medium text-red-600 dark:text-red-400">Opa! Algo deu errado.</div>
                            <ul class="mt-3 list-disc list-inside text-sm text-red-600 dark:text-red-400">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if($product->id)
                    <form action="{{ route('product.update', $product->id) }}" method="POST">
                        @method('PUT')
                    @else
                    <form action="{{ route('product.store') }}" method="POST">
                    @endif
                        @csrf
                        <div class="flex space-x-4 gap-4">
                            <div class="mb-4 flex-1">
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nome</label>
                                <input type="text" name="name" id="name" class="form-input rounded-md shadow-sm mt-1 block w-full text-gray-800" value="{{ old('name', $product->name ?? '') }}" />
                            </div>
                            <div class="mb-4 flex-1">
                                <label for="barcode" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Código de barras</label>
                                <input type="text" name="barcode" id="barcode" class="form-input rounded-md shadow-sm mt-1 block w-full text-gray-800" value="{{ old('barcode', $product->barcode ?? '') }}" />
                            </div>
                        </div>
                        <div class="mb-4">
                            <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Descrição</label>
                            <textarea name="description" id="description" rows="4" class="form-textarea rounded-md shadow-sm mt-1 block w-full text-gray-800">{{ old('description', $product->description ?? '') }}</textarea>
                        </div>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Salvar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
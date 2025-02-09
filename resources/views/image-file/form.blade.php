<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ isset($image) ? 'Editar Imagem' : 'Nova Image' }}
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

                    @if($image->id)
                    <form action="{{ route('image-file.update', $image->id) }}" method="POST" enctype="multipart/form-data">
                        @method('PUT')
                    @else
                    <form action="{{ route('image-file.store') }}" method="POST" enctype="multipart/form-data">
                    @endif
                        @csrf
                        <div class="flex space-x-4 gap-4">
                            <div class="mb-4 flex-1">
                                <label for="file" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Selecionar imagens</label>
                                <input type="file" name="files[]" id="file" accept="image/png, image/jpeg" class="form-input rounded-md shadow-sm mt-1 block w-full text-gray-800" required multiple />
                            </div>
                            
                        </div>
                        <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">Enviar</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
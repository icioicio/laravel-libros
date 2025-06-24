<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Listado de Libros') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-medium text-gray-900">Bienvenido al Gestor de Libros</h3>
                    <p class="mt-1 text-sm text-gray-600">Aquí podrás ver y gestionar tus libros.</p>

                    <div class="mt-6 flex items-center justify-start">
                        <a href="{{ route('books.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Añadir Nuevo Libro
                        </a>
                        <a href="#" class="ml-4 inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:bg-indigo-700 active:bg-indigo-800 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                            Escanear QR (Próximamente)
                        </a>
                    </div>

                    <div class="mt-8 flow-root">
                        <div class="-mx-4 -my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                            <div class="inline-block min-w-full py-2 align-middle sm:px-6 lg:px-8">
                                <table class="min-w-full divide-y divide-gray-300">
                                    <thead>
                                        <tr>
                                            <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-semibold text-gray-900 sm:pl-0">Título</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Autor</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">Año</th>
                                            <th scope="col" class="px-3 py-3.5 text-left text-sm font-semibold text-gray-900">QR</th> <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-0">
                                                <span class="sr-only">Acciones</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-200 bg-white">
                                        @forelse ($books as $book)
                                            <tr>
                                                <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm font-medium text-gray-900 sm:pl-0">{{ $book->title }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{ $book->author->name ?? 'N/A' }}
                                                </td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">{{ $book->publication_year }}</td>
                                                <td class="whitespace-nowrap px-3 py-4 text-sm text-gray-500">
                                                    {{-- Generamos el QR para este libro --}}
                                                    {{-- El QR contendrá un enlace a la página de detalle del libro (si la tuviéramos) --}}
                                                    {{-- O simplemente el ID del libro, o un texto. Usaremos el ID y título por simplicidad. --}}
                                                    {!! QrCode::size(80)
          ->backgroundColor(255,255,255)
          ->color(0,0,0)
          ->margin(1)
          ->generate("LIBRO: {$book->title}\n AUTOR: {$book->author->name}\n AÑO: " . ($book->publication_year ?? 'N/A') . "\n VER: " . route('books.show', $book->id)) !!}

                                                    {{-- La etiqueta {!! !!} es para renderizar HTML sin escapar --}}
                                                </td> <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-0">
                                                    <a href="{{ route('books.edit', $book->id) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                                                    <form action="{{ route('books.destroy', $book->id) }}" method="POST" class="inline ml-4">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('¿Estás seguro de que quieres eliminar este libro?')">Eliminar</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="px-3 py-4 text-sm text-gray-500 text-center">No hay libros registrados.</td> </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="mt-4">
                        {{ $books->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
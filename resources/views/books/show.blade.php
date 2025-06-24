<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Detalles del Libro
            </h2>
            <a href="{{ route('books.index') }}" 
               class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                Volver al Listado
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
                <div class="p-8">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                        
                        {{-- INFORMACIÓN DEL LIBRO --}}
                        <div class="space-y-6">
                            <div>
                                <h1 class="text-3xl font-bold text-gray-900 mb-2">
                                    {{ $book->title }}
                                </h1>
                                <span class="inline-block bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
                                    ID: {{ $book->id }}
                                </span>
                            </div>

                            <div class="border-l-4 border-indigo-500 pl-4">
                                <h3 class="text-lg font-semibold text-gray-700 mb-1">Autor</h3>
                                <p class="text-xl text-gray-900">{{ $book->author->name }}</p>
                            </div>

                            @if($book->publication_year)
                                <div class="border-l-4 border-green-500 pl-4">
                                    <h3 class="text-lg font-semibold text-gray-700 mb-1">Año de Publicación</h3>
                                    <p class="text-xl text-gray-900">{{ $book->publication_year }}</p>
                                </div>
                            @endif

                            @if($book->description)
                                <div class="border-l-4 border-yellow-500 pl-4">
                                    <h3 class="text-lg font-semibold text-gray-700 mb-1">Descripción</h3>
                                    <p class="text-gray-700 leading-relaxed">
                                        {!! nl2br(e($book->description)) !!}
                                    </p>
                                </div>
                            @endif
                        </div>

                        {{-- QR CODE Y ACCIONES --}}
                        <div class="space-y-6">
                            {{-- SECCIÓN QR CODE --}}
                            <div class="bg-gray-50 rounded-lg p-6 text-center border">
                                <h3 class="text-lg font-semibold text-gray-700 mb-4">Código QR</h3>
                                
                                <div class="flex justify-center mb-4">
                                    <div class="bg-white p-4 rounded-lg shadow-md border">
                                        {!! QrCode::size(250)->backgroundColor(255,255,255)->color(0,0,0)->margin(2)->generate("LIBRO: {$book->title}\nAUTOR: {$book->author->name}\nAÑO: " . ($book->publication_year ?? 'N/A') . "\nVER MAS: " . route('books.show', $book->id)) !!}
                                    </div>
                                </div>

                                {{-- BOTONES QR MEJORADOS --}}
                                <div class="space-y-3">
                                    <a href="{{ route('books.qr', $book->id) }}" 
                                       target="_blank" 
                                       style="background-color: #2563eb !important; color: white !important; display: block !important; padding: 12px 16px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; border: 2px solid #2563eb !important;"
                                       onmouseover="this.style.backgroundColor='#1d4ed8'"
                                       onmouseout="this.style.backgroundColor='#2563eb'">
                                        Ver QR Grande
                                    </a>
                                    
                                    <a href="{{ route('books.qr.download', $book->id) }}" 
                                       style="background-color: #16a34a !important; color: white !important; display: block !important; padding: 12px 16px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; border: 2px solid #16a34a !important;"
                                       onmouseover="this.style.backgroundColor='#15803d'"
                                       onmouseout="this.style.backgroundColor='#16a34a'">
                                        Descargar QR (PNG)
                                    </a>
                                </div>
                            </div>

                            {{-- SECCIÓN ACCIONES --}}
                            <div class="bg-gray-50 rounded-lg p-6 border">
                                <h3 class="text-lg font-semibold text-gray-700 mb-4">Acciones del Libro</h3>
                                
                                <div class="space-y-3">
                                    <a href="{{ route('books.edit', $book->id) }}" 
                                       style="background-color: #ca8a04 !important; color: white !important; display: block !important; padding: 12px 16px !important; border-radius: 8px !important; text-decoration: none !important; font-weight: 500 !important; border: 2px solid #ca8a04 !important; text-align: center !important;"
                                       onmouseover="this.style.backgroundColor='#a16207'"
                                       onmouseout="this.style.backgroundColor='#ca8a04'">
                                        Editar Libro
                                    </a>
                                    
                                    <form action="{{ route('books.destroy', $book->id) }}" 
                                          method="POST" 
                                          onsubmit="return confirm('¿Estás seguro de que quieres eliminar este libro?')"
                                          style="margin: 0;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" 
                                                style="background-color: #dc2626 !important; color: white !important; display: block !important; width: 100% !important; padding: 12px 16px !important; border-radius: 8px !important; font-weight: 500 !important; border: 2px solid #dc2626 !important; cursor: pointer !important;"
                                                onmouseover="this.style.backgroundColor='#b91c1c'"
                                                onmouseout="this.style.backgroundColor='#dc2626'">
                                            Eliminar Libro
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


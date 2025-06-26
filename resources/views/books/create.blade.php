<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Añadir Nuevo Libro') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form action="{{ route('books.store') }}" method="POST">
                        @csrf
<script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>

<div id="reader" style="width: 300px"></div>
<input type="text" id="isbn" name="isbn" placeholder="Escanea ISBN">

<script>
  function onScanSuccess(decodedText, decodedResult) {
      document.getElementById("isbn").value = decodedText;
      html5QrcodeScanner.clear();
      // Opcional: hacer una llamada AJAX para autocompletar los datos del libro
  }

  const html5QrcodeScanner = new Html5QrcodeScanner(
    "reader", { fps: 10, qrbox: 250 });
  html5QrcodeScanner.render(onScanSuccess);
</script>
<script>
function onScanSuccess(decodedText, decodedResult) {
    document.getElementById("isbn").value = decodedText;

    fetch(`/api/isbn/${decodedText}`)
        .then(res => res.json())
        .then(data => {
            if (!data.error) {
                document.getElementById("titulo").value = data.titulo;
                document.getElementById("autor").value = data.autores;
                document.getElementById("editorial").value = data.editorial;
                document.getElementById("fecha").value = data.fecha;
            } else {
                alert("Libro no encontrado");
            }
        });

    html5QrcodeScanner.clear();
}
</script>

                        <div>
                            <label for="title" class="block font-medium text-sm text-gray-700">Título</label>
                            <input type="text" name="title" id="title" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <div class="mt-4">
                            <label for="author_id" class="block font-medium text-sm text-gray-700">Autor</label>
                            <select name="author_id" id="author_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="">Selecciona un autor</option>
                                @foreach ($authors as $author)
                                    <option value="{{ $author->id }}">{{ $author->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mt-4">
                            <label for="description" class="block font-medium text-sm text-gray-700">Descripción</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm"></textarea>
                        </div>

                        <div class="mt-4">
                            <label for="publication_year" class="block font-medium text-sm text-gray-700">Año de Publicación</label>
                            <input type="number" name="publication_year" id="publication_year" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <button type="submit" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                Guardar Libro
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
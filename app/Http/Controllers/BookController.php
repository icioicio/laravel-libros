<?php

namespace App\Http\Controllers;

use App\Models\Book; // Importa el modelo Book
use App\Models\Author; // Importa el modelo Author
use Illuminate\Http\Request; // Importa la clase Request

class BookController extends Controller
{
    /**
 * Muestra los detalles de un libro específico.
 * 
 * ¿Qué hace este método?
 * - Recibe un objeto Book (Laravel lo busca automáticamente)
 * - Carga la información del autor
 * - Muestra la vista con los detalles
 */
public function show(Book $book)
{
    // 🔗 CARGAR LA RELACIÓN CON EL AUTOR
    // load() = carga la relación después de obtener el modelo
    // Es como hacer una segunda consulta para traer el autor
    $book->load('author');
    
    // 📄 MOSTRAR LA VISTA
    // view() = carga una vista Blade
    // compact() = convierte $book en ['book' => $book] para la vista
    return view('books.show', compact('book'));
}
    /**
 * Descarga un QR code como imagen PNG.
 * 
 * ¿Qué hace este método?
 * - Similar al anterior, pero genera un archivo PNG para descargar
 * - El QR es más grande (400px) para mejor calidad
 * - Fuerza la descarga del archivo
 */
public function downloadQr($id)
{
    // 🔍 BUSCAR EL LIBRO (igual que antes)
    $book = Book::with('author')->findOrFail($id);
    
    // 📝 CREAR EL CONTENIDO DEL QR (igual que antes)
    $qrContent = "📚 LIBRO: {$book->title}\n";
    $qrContent .= "✍️ AUTOR: {$book->author->name}\n";
    $qrContent .= "📅 AÑO: " . ($book->publication_year ?? 'N/A') . "\n";
    $qrContent .= "🔗 VER MÁS: " . route('books.show', $book->id);
    
    // 🎨 GENERAR QR MÁS GRANDE PARA DESCARGA
    $qrCode = QrCode::format('png')                // Formato PNG (no SVG)
                   ->size(400)                     // Más grande: 400x400 píxeles
                   ->backgroundColor(255, 255, 255) // Fondo blanco
                   ->color(0, 0, 0)                // Color negro
                   ->margin(3)                     // Margen más grande
                   ->generate($qrContent);         // Generar el QR
    
    // 📁 CREAR NOMBRE DEL ARCHIVO
    // str_replace() = reemplaza espacios por guiones bajos
    $fileName = 'QR_' . str_replace(' ', '_', $book->title) . '.png';
    
    // 📤 FORZAR DESCARGA DEL ARCHIVO
    return response($qrCode)
        ->header('Content-Type', 'image/png')                           // Tipo: imagen PNG
        ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"'); // Forzar descarga
}
    /**
 * Genera un QR code mejorado para un libro específico.
 * 
 * ¿Qué hace este método?
 * - Recibe el ID de un libro
 * - Busca el libro en la base de datos
 * - Crea un texto con información del libro y autor
 * - Genera un código QR con esa información
 * - Devuelve el QR como imagen SVG
 */
public function generateQr($id)
{
    // 🔍 BUSCAR EL LIBRO EN LA BASE DE DATOS
    // Book::with('author') = Busca el libro Y también carga la información del autor
    // findOrFail($id) = Busca por ID, si no existe lanza error 404
    $book = Book::with('author')->findOrFail($id);
    
    // 📝 CREAR EL TEXTO QUE IRÁ DENTRO DEL QR
    // Usamos concatenación (.) para unir strings
    // \n = salto de línea (nueva línea)
    $qrContent = "📚 LIBRO: {$book->title}\n";           // Título del libro
    $qrContent .= "✍️ AUTOR: {$book->author->name}\n";   // Nombre del autor
    
    // Operador ternario: condición ? valor_si_true : valor_si_false
    $qrContent .= "📅 AÑO: " . ($book->publication_year ?? 'N/A') . "\n";
    
    // route() = genera la URL completa para ver el libro
    $qrContent .= "🔗 VER MÁS: " . route('books.show', $book->id);
    
    // 🎨 GENERAR EL CÓDIGO QR
    $qrCode = QrCode::size(200)                    // Tamaño 200x200 píxeles
                   ->backgroundColor(255, 255, 255) // Fondo blanco (RGB)
                   ->color(0, 0, 0)                 // Color negro para el QR (RGB)
                   ->margin(2)                      // Margen alrededor del QR
                   ->generate($qrContent);          // Genera el QR con nuestro texto
    
    // 📤 DEVOLVER LA RESPUESTA
    // response() = crea una respuesta HTTP
    // header() = establece el tipo de contenido como imagen SVG
    return response($qrCode)->header('Content-Type', 'image/svg+xml');
}
/**
 * Muestra una lista de todos los libros.
 * 
 * ¿Qué hace este método?
 * - Obtiene todos los libros de la base de datos
 * - Incluye la información del autor de cada libro
 * - Los ordena por fecha (más recientes primero)
 * - Los pagina de 10 en 10
 */
public function index()
{
    // 🔍 OBTENER LIBROS DE LA BASE DE DATOS
    $books = Book::with('author')    // Incluir información del autor (evita N+1 queries)
                 ->latest()          // Ordenar por fecha de creación (más recientes primero)
                 ->paginate(10);     // Dividir en páginas de 10 libros cada una
    
    // 📄 RETORNAR LA VISTA
    // Pasa la variable $books a la vista books.index
    return view('books.index', compact('books'));
}

    /**
     * Muestra el formulario para crear un nuevo libro.
     */
    public function create()
    {
        // Obtiene todos los autores para el menú desplegable del formulario
        $authors = Author::all();

        // Retorna la vista 'books.create' y le pasa los autores
        return view('books.create', compact('authors'));
    }

    /**
     * Almacena un libro recién creado en la base de datos.
     */
    public function store(Request $request)
    {
        // Valida los datos del formulario
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'description' => 'nullable|string',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
        ]);

        // Crea un nuevo libro en la base de datos con los datos validados
        Book::create($request->all());

        // Redirige al listado de libros con un mensaje de éxito
        return redirect()->route('books.index')->with('success', 'Libro creado exitosamente.');
    }


    /**
     * Muestra el formulario para editar un libro existente.
     */
    public function edit(Book $book)
    {
        // Obtiene todos los autores para el menú desplegable del formulario
        $authors = Author::all();

        // Retorna la vista 'books.edit' y le pasa el libro y los autores
        return view('books.edit', compact('book', 'authors'));
    }

    /**
     * Actualiza un libro existente en la base de datos.
     */
    public function update(Request $request, Book $book)
    {
        // Valida los datos del formulario
        $request->validate([
            'title' => 'required|string|max:255',
            'author_id' => 'required|exists:authors,id',
            'description' => 'nullable|string',
            'publication_year' => 'nullable|integer|min:1000|max:' . date('Y'),
        ]);

        // Actualiza el libro en la base de datos con los datos validados
        $book->update($request->all());

        // Redirige al listado de libros con un mensaje de éxito
        return redirect()->route('books.index')->with('success', 'Libro actualizado exitosamente.');
    }

    /**
     * Elimina un libro de la base de datos.
     */
    public function destroy(Book $book)
    {
        // Elimina el libro de la base de datos
        $book->delete();

        // Redirige al listado de libros con un mensaje de éxito
        return redirect()->route('books.index')->with('success', 'Libro eliminado exitosamente.');
    }
}
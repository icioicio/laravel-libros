<?php

namespace App\Http\Controllers;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
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

public function downloadQr($id)
{
    $book = Book::with('author')->findOrFail($id);
    
    // Contenido sin caracteres especiales
    $qrContent = "LIBRO: " . $book->title . "\n";
    $qrContent .= "AUTOR: " . $book->author->name . "\n";
    $qrContent .= "ANO: " . ($book->publication_year ?? 'N/A') . "\n";
    $qrContent .= "URL: " . route('books.show', $book->id);
    
    $qrCode = QrCode::size(500)
                   ->backgroundColor(255, 255, 255)
                   ->color(0, 0, 0)
                   ->margin(3)
                   ->encoding('UTF-8')
                   ->generate($qrContent);
    
    $fileName = 'QR_' . str_replace([' ', 'ñ', 'á', 'é', 'í', 'ó', 'ú'], ['_', 'n', 'a', 'e', 'i', 'o', 'u'], $book->title) . '.svg';
    
    return response($qrCode)
        ->header('Content-Type', 'image/svg+xml')
        ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
}
/**
 * Genera un QR code mejorado para un libro específico.
 */
public function generateQr($id)
{
    $book = Book::with('author')->findOrFail($id);
    
    // Contenido simplificado sin caracteres especiales
    $qrContent = "LIBRO: " . $book->title . "\n";
    $qrContent .= "AUTOR: " . $book->author->name . "\n";
    $qrContent .= "ANO: " . ($book->publication_year ?? 'N/A') . "\n";
    $qrContent .= "URL: " . route('books.show', $book->id);
    
    $qrCode = QrCode::size(400)
                   ->backgroundColor(255, 255, 255)
                   ->color(0, 0, 0)
                   ->margin(2)
                   ->encoding('UTF-8')
                   ->generate($qrContent);
    
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
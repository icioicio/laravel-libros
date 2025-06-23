<?php

namespace App\Http\Controllers;

use App\Models\Book; // Importa el modelo Book
use App\Models\Author; // Importa el modelo Author
use Illuminate\Http\Request; // Importa la clase Request

class BookController extends Controller
{
    /**
     * Muestra una lista de todos los libros.
     */
    public function index()
    {
        // Obtiene todos los libros de la base de datos, incluyendo la información de su autor
        $books = Book::with('author')->latest()->paginate(10);

        // Retorna la vista 'books.index' y le pasa los libros
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
     * Muestra los detalles de un libro específico.
     */
    public function show(Book $book)
    {
        // (Necesitarías crear una vista show.blade.php si la usas)
        return view('books.show', compact('book'));
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
<?php
namespace Database\Seeders;
use App\Models\Book;
use App\Models\Author; // Asegúrate de importar Author
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        // Crea 10 libros, cada uno con un nuevo autor generado por la BookFactory.
        Book::factory()->count(10)->create();
    }
}
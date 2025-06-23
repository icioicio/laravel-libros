<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Book;   // Importa el modelo Book
use App\Models\Author; // Importa el modelo Author (lo usaremos para la relación)

class BookFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Book::class; // Asegura que esta línea esté presente y apunte al modelo Book

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'author_id' => Author::factory(), // Esto asociará un nuevo autor creado por la fábrica de Author
            'title' => $this->faker->sentence(rand(3, 8)), // Genera una oración aleatoria de 3 a 8 palabras
            'description' => $this->faker->paragraphs(rand(1, 3), true), // Genera 1 a 3 párrafos para la descripción
            'publication_year' => $this->faker->year(), // Genera un año aleatorio
        ];
    }

    /**
     * Configure the factory to associate with an Author.
     */
    public function forAuthor(Author $author): static
    {
        return $this->state(fn (array $attributes) => [
            'author_id' => $author->id,
        ]);
    }
}

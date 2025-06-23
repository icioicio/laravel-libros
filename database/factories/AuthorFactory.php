<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Author; // Importa el modelo Author

class AuthorFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Author::class; // Asegura que esta línea esté presente y apunte al modelo Author

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->name(), // Genera un nombre de persona aleatorio
            'bio' => $this->faker->paragraph(3), // Genera un párrafo de 3 oraciones aleatorias para la biografía
        ];
    }
}
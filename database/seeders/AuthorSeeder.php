<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Author;

class AuthorSeeder extends Seeder
{
    public function run()
    {
        Author::create([
            'name' => 'Paulo Coelho',
            'biography' => 'Brazilian lyricist and novelist, best known for "The Alchemist".'
        ]);

        Author::create([
            'name' => 'J.K. Rowling',
            'biography' => 'British author, best known for the Harry Potter series.'
        ]);

        Author::create([
            'name' => 'George Orwell',
            'biography' => 'English novelist and essayist, known for "1984" and "Animal Farm".'
        ]);
    }
}

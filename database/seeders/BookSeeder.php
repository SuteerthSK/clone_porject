<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class BookSeeder extends Seeder
{
    public function run()
    {
        Book::create([
            'title' => 'The Alchemist',
            'author_id' => 1,
            'isbn' => '1234567890',
            'description' => 'A philosophical novel about following your dreams.'
        ]);

        Book::create([
            'title' => 'Harry Potter and the Philosopher\'s Stone',
            'author_id' => 2,
            'isbn' => '9780747532699',
            'description' => 'The first book in the Harry Potter fantasy series.'
        ]);

        Book::create([
            'title' => '1984',
            'author_id' => 3,
            'isbn' => '9780451524935',
            'description' => 'A dystopian social science fiction novel and cautionary tale.'
        ]);
    }
}

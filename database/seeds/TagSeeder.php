<?php

use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $tags = ['PHP', 'Javascript', 'Java', 'C#', 'C++', 'Ecmascript 6', 'Go', 'Python'];

        foreach($tags as $tag) {
            \App\Tag::create([
                'nama' => $tag
            ]);
        }
    }
}

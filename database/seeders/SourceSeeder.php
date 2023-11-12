<?php

namespace Database\Seeders;

use App\Models\Source;
use App\Models\SourceBook;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SourceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $title = ['Андарзи ман', 'Муаллифи номаълум', 'Андарзи муаллифи машҳур', 'Андарз аз китоб', 'Андарз аз филм ё силсила филмҳо', 'Андарз аз матни суруд', 'Зарбулмасал/гуфтор', 'Масал'];
        $key = ['user', 'unknown', 'author', 'book', 'movie', 'song', 'proverb', 'parable'];

        for($i=0; $i<count($title); $i++) {
            $source = new Source();
            $source->title = $title[$i];
            $source->key = $key[$i];
            $source->save();
        }

        $sb = new SourceBook();
        $sb->title = 'Алиса в стране чудес';
        $sb->author = 'Полина Гагарина';
        $sb->approved = false;
        $sb->save();
    }
}
<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $categories = ['Арзиш ва ҳадафҳо', 'Ахлоқ ва масъулият', 'Ақли эҳсосӣ', 'Бадгумонӣ', 'Бунёди ҳастӣ', 'Дастовардҳо ва сахтгирӣ', 'Зиндагии ғайримаъмулӣ ', 'Идеалӣ ва оптимизм ', 'Илм ва Фалсафа', 'Қобилият ва Захираҳо', 'Маънии ҳаёт', 'Маъруфияти илм', 'Мураббиягарӣ', 'Муҳаббат ба ҳаёт', 'Муҳити зист ва муносибат', 'Огоҳӣ', 'Олам ва космология', 'Роҳбар', 'Рушди шахсият', 'Сабки зиндагии солим', 'Салоҳиятнокӣ', 'Тамаддун', 'Таърихи ҳаёт дар рӯи замин', 'Таҳсилот', 'Хатарҳо ва хавфҳо', 'Худидоракунӣ', 'Эрудиссия', 'Эҷодиёт', 'Ҷамъият', 'Ҷаҳонбинӣ'];

        foreach($categories as $cat) {
            $category = new Category();
            $category->title = $cat;
            $category->popular = false;
            $category->approved = true;
            $category->save();
        }

        //tio categories
        $popularCats = [5,6,17,20];
        foreach($popularCats as $cat) {
            $pop = Category::find($cat);
            $pop->image = $cat . '.jpg';
            $pop->popular = true;
            $pop->save();
        }
    }
}

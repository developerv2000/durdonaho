<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\Author;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AuthorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = ['Адам Франк', 'Александр Иванович Волошин', 'Исках Пинтосевич', 'Михайло Пантич', 'Морис Друон', 'Ричард Докинз', 'Станислав Дробишевский', 'Тад Вилямс', 'Сороева Манижа'];
        $bio = ['Адам Франк (1962)', 'Александр Иванович Волошин (1962)', 'Исках Пинтосевич (1973)', 'Михайло Пантич (1957)', 'Морис Друон (1918 — 2009)', 'Ричард Докинз (1941)', 'Станислав Дробишевский (1978)', 'Тад Вилямс (1957)', 'Сороева Манижа'];

        for ($i = 0; $i < count($name); $i++) {
            $a = new Author();
            $a->name = $name[$i];
            $a->user_id = 1;
            $a->slug = Helper::generateUniqueSlug($name[$i], Author::class);
            $a->biography = $bio[$i];
            $a->image = $name[$i] . '.jpg';
            $a->popular = true;
            $a->approved = true;
            $a->save();
        }
    }
}

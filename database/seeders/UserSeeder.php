<?php

namespace Database\Seeders;

use App\Helpers\Helper;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $name = ['Администратор', 'Простой Пользователь'];
        $email = ['admin@mail.ru', 'user@mail.ru'];
        $role = ['admin', 'user'];
        $image = ['1.jpg', '2.jpg'];
        $biography = ['Администратор — должностное лицо, управляющее в учреждении, коллективе, компании. Специалисты этой группы осуществляют работы по сопровождению баз данных в компьютерных системах.', 'Пользователь — лицо или организация, которое использует действующую систему для выполнения конкретной функции'];
        $verified = [1, 0];

        for ($i = 0; $i < count($name); $i++) {
            $user = new User();
            $user->name = $name[$i];
            $user->slug = Helper::generateUniqueSlug($name[$i], User::class);
            $user->email = $email[$i];
            $user->role = $role[$i];
            $user->image = $image[$i];
            $user->biography = $biography[$i];
            $user->verified_email = $verified[$i];
            $user->password = bcrypt('12345');
            $user->gender = 'male';
            $user->save();
        }
    }
}

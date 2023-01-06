<?php

use App\Profession;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /*
        $professions = DB::select('SELECT id FROM professions WHERE title = ? LIMIT 0,1', ['Desarrollador back-end']);

        $professions = DB::select(
            'SELECT id FROM professions WHERE title=?',
            ['Desarrollador Back-End']
        );
        $profession = DB::table('professions')
            ->select('id')
            ->where('title', 'Desarrollador Back-End')
            ->first();

        $professionId = DB::table('professions')
            ->whereTitle('Desarrollador Back-End')
            ->value('id');

        */

        $professionId = Profession::where('title', 'Desarrollador back-end')->value('id');


        //dd($profession);

        factory(User::class)->create([
            'name' => 'omar santana',
            'email' => 'omar@mail.com',
            'password' => bcrypt('123456'),
            'profession_id' => $professionId,
            'is_admin' => true,
        ]);

        factory(User::class)->create([
            'profession_id' => $professionId
        ]);

        factory(User::class, 48)->create();

    }
}

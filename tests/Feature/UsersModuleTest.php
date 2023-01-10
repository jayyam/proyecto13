<?php

namespace Tests\Feature;

use App\Profession;
use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;

    private $profession;

public function getValidData(array $custom = [])
    {
        $this->profession = factory(Profession::class)->create();

        return array_filter(array_merge([
            'name' => 'Omar Santana',
            'email' => 'omar@mail.com',
            'password' => '123456',
            'profession_id' => $this->profession->id,
            'bio' => 'Programador Laravel y Vue.js',
            'twitter' => 'https://twitter.com/omardpana22',
        ], $custom));
    }

    /** @test  */
    function it_shows_the_users_list()
    {
        factory(User::class)->create([
            'name' => 'Joel',
            //'website' => 'thelastofus',
            ]);

        factory(User::class)->create([
            'name' => 'Ellie',
                ]);

        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('Listado de usuarios')
            ->assertSee('Joel')
            ->assertSee('Ellie');
    }

    /** @test */
    function it_shows_a_default_messsage_if_the_users_list_is_empty()
    {
        //DB::table('users')->truncate();

        $this->get('/usuarios')
            ->assertStatus(200)
            ->assertSee('No hay usuarios registrados.');
    }

    /** @test */
    function it_displays_the_users_details()
    {
        $user = factory(User::class)->create([
            'name' => 'Omar Santana',
        ]);

        $this->get('/usuarios/'.$user->id)
            ->assertStatus(200)
            ->assertSee('Omar Santana');
    }

    /** @test */
    function it_loads_the_new_users_page()
    {
        $this->get('/usuarios/nuevo')
            ->assertStatus(200)
            ->assertSee('Crear nuevo usuario');
    }

    /** @test */
    function it_displays_a_404_error_if_the_user_is_not_found()
    {
        $this->get('/usuarios/999')
            ->assertStatus(404)
            ->assertSee('Pagina no encontrada');
    }

    /** @test */
    function it_creates_a_new_user()
    {
        $this->withoutExceptionHandling();

        $this->post('/usuarios/', $this->getValidData())->assertRedirect('usuarios');
            //->assertRedirect(route('users.index'));//no jopa el return

        //dd(User::all());

            $this->assertCredentials([
                'name' => 'Omar Santana',
                'email' => 'omar@mail.com',
                'password' => '123456',
                'profession_id' => $this->profession->id,
            ]);

            $this->assertDatabaseHas('user_profiles', [
                'bio' =>'Programador Laravel y Vue.js',
                'twitter' =>'https://twitter.com/omardpana22',
                'user_id' => User::findByEmail('omar@mail.com')->id,
            ]);

    }
    /** @test */
    function the_twitter_field_is_optional()
    {
        $this->withoutExceptionHandling();

        $this->post('/usuarios/', $this->getValidData([
            'twitter' => null,
        ]))->assertRedirect('usuarios');
        //->assertRedirect(route('users.index'));//no jopa el return

        $this->assertCredentials([
            'name' => 'Omar Santana',
            'email' => 'omar@mail.com',
            'password' => '123456',
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' =>'Programador Laravel y Vue.js',
            'twitter' =>'',
            'user_id' => User::findByEmail('omar@mail.com')->id,
        ]);
    }
    /** @test */
    function the_profession_id_field_is_optional()
    {
        $this->withoutExceptionHandling();

        $this->post('/usuarios/', $this->getValidData([
            'profession_id' => null,
        ]))->assertRedirect('usuarios');
        //->assertRedirect(route('users.index'));//no jopa el return

        $this->assertCredentials([
            'name' => 'Omar Santana',
            'email' => 'omar@mail.com',
            'password' => '123456',
            'profession_id' =>null,
        ]);

        $this->assertDatabaseHas('user_profiles', [
            'bio' =>'Programador Laravel y Vue.js',
            'user_id' => User::findByEmail('omar@mail.com')->id,
        ]);
    }
    /** @test */
    function the_name_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/',$this->getValidData([
                'name' => '',
            ]))->assertRedirect('usuarios/nuevo')
          ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertDatabaseEmpty('users');
/*
        $this->assertDatabaseMissing('users', [
            'email' => 'omar@mail.com',
        ]);
*/
    }
    /** @test */
    function the_profession_must_be_valid()
    {
        $this->withoutExceptionHandling();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/',$this->getValidData([
                'profession_id' => '999',
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
        /*
                $this->assertDatabaseMissing('users', [
                    'email' => 'omar@mail.com',
                ]);
        */
    }
    /** @test */
    function only_not_deleted_professions_can_be_selected()
    {
        $nonSelectableProfession = factory(Profession::class)->create([
            'deleted_at' => now()->format('Y-m-d'),
        ]);

        $this->withoutExceptionHandling();

        $this->from('usuarios/nuevo')
            ->post('/usuarios/',$this->getValidData([
                'profession_id' => $nonSelectableProfession->id,
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['profession_id']);

        $this->assertDatabaseEmpty('users');
        /*
                $this->assertDatabaseMissing('users', [
                    'email' => 'omar@mail.com',
                ]);
        */
    }
    /** @test */
    function the_name_is_required_when_updating_a_user()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->from("/usuarios/$user->id/editar")
             ->put("/usuarios/$user->id",[
                'name' => '',
                'email' => 'omar@mail.com',
                'password' =>'123456',
            ])->assertRedirect("usuarios/$user->id/editar")
            ->assertSessionHasErrors(['name']);

            //$this->assertEquals(0, User::count());

                $this->assertDatabaseMissing('users', [
                    'email' => 'omar@mail.com',
                ]);

    }
    /** @test */
    function the_email_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'email' => '',
            ]))->assertRedirect('usuarios/nuevo')
              ->assertSessionHasErrors(['email']);
            //->assertSessionHasErrors(['name' => 'El campo email es obligatorio']);

        $this->assertDatabaseEmpty('users');

    }

    /** @test */
    function the_email_must_be_valid()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'email' => 'correo no valido',
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);
        //->assertSessionHasErrors(['name' => 'El campo email es obligatorio']);

        $this->assertDatabaseEmpty('users');

    }
    /** @test */
    function the_email_must_be_unique()
    {
        //$this->withoutExceptionHandling();

        factory(User::class)->create([
            'email' => 'omar@mail.com',
        ]);

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'email'=> 'omar@mail.com'
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);
        //->assertSessionHasErrors(['name' => 'El campo email es obligatorio']);

        $this->assertEquals(1, User::count());
    }
    /** @test */
    function the_password_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', $this->getValidData([
                'password' => '',
            ]))->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['password']);
        //->assertSessionHasErrors(['name' => 'El campo email es obligatorio']);

        $this->assertDatabaseEmpty('users');

    }
    /** @test */
    function it_loads_the_edit_user_page()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->get("/usuarios/$user->id/editar")
            ->assertStatus(200)
            ->assertViewIs('users.edit')
            ->assertSee('Editar usuario')
            ->assertViewHas('user', function ($viewUser) use ($user){
                return $viewUser->id === $user->id;
        });
    }
    /** @test */
    function it_updates_a_new_user()
    {
        $this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->put("/usuarios/$user->id",[
            'name' => 'Omar Santana',
            'email' => 'omar@mail.com',
            'password' =>'123456',
        ])->assertRedirect("/usuarios/$user->id");
        //->assertRedirect(route('users.index'));//no jopa el return

        $this->assertCredentials([
            'name' => 'Omar Santana',
            'email' => 'omar@mail.com',
            'password' =>'123456',
        ]);
    }
    /** @test */
    function the_password_is_optional_when_updating_a_user()
    {
        $oldPassword = 'CLAVE_ANTIGUA';

        $user = factory(User::class)->create([
            'password'=> bcrypt($oldPassword)
        ]);

        $this->from("/usuarios/$user->id/editar")
            ->put("/usuarios/$user->id",[
                'name' => 'Omar Santana',
                'email' => 'omar@mail.com',
                'password' => '',
            ])
            ->assertRedirect("usuarios/$user->id");//redireccion a users.show

        $this->assertCredentials([
            'name' => 'Omar Santana',
            'email' => 'omar@mail.com',
            'password' => $oldPassword,//Aqui comprueba que la contraseña no ha cambiado.
        ]);
          //->assertSessionHasErrors(['password']);
        //$this->assertDatabaseMissing('users', ['email' => 'omar@mail.com']);
        //$this->assertEquals(0, User::count());
    }
    /** @test */
    function the_email_is_required_when_updating_a_user()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->from("/usuarios/$user->id/editar")
            ->put("/usuarios/$user->id",[
            'name' => 'Omar Santana',
            'email' => '',
            'password' =>'123456',
        ])->assertRedirect("usuarios/$user->id/editar")
            ->assertSessionHasErrors(['email']);

        //$this->assertEquals(0, User::count());

        $this->assertDatabaseMissing('users', [
            'name' => 'Omar Santana',
        ]);
    }
    /** @test */
    function the_email_must_be_valid_when_updating_a_user()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => 'Omar Santana',
                'email' => 'Correo-no-valido',
                'password' => '123456',
            ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);
        //->assertSessionHasErrors(['name' => 'El campo email es obligatorio']);

        //$this->assertEquals(0, User::count());
    }
    /** @test */
    function the_email_must_be_unique_when_updating_a_user()
    {
        //self::markTestIncomplete();
        //return;//marcando pruebas incompletas
        //$this->withoutExceptionHandling();

        factory(User::class)->create([
            'email' => 'existing-email@example.com',
        ]);

        $user = factory(User::class)->create([
            'email' => 'omar@mail.com'
        ]);

        $this->from("/usuarios/$user->id/editar")
        ->put("/usuarios/$user->id",[
            'name' => 'Omar Santana',
            'email' => 'existing-email@example.com',
            'password' =>'123456',
        ])->assertRedirect("usuarios/$user->id/editar")
            ->assertSessionHasErrors(['email']);
/*
        $this->assertEquals(0, User::count());

        $this->assertDatabaseMissing('users', [
            'email' => 'omar@mail.com',
        ]);
*/
    }
    /** @test */
    function the_users_email_can_stay_the_same_when_updating_a_user()
    {

        $user = factory(User::class)->create([
            'email'=> 'omar@mail.com'
        ]);

        $this->from("/usuarios/$user->id/editar")
            ->put("/usuarios/$user->id",[
                'name' => 'Omar Santana',
                'email' => 'omar@mail.com',
                'password' => '123456',
            ])
            ->assertRedirect("usuarios/$user->id");//redireccion a users.show

        $this->assertDatabaseHas('users', [
            'name' => 'Omar Santana',
            'email' => 'omar@mail.com',

        ]);
        //->assertSessionHasErrors(['password']);
        //$this->assertDatabaseMissing('users', ['email' => 'omar@mail.com']);
        //$this->assertEquals(0, User::count());
    }
    /** @test */
    function it_deletes_a_user()
    {
        //$this->withoutExceptionHandling();

        $user = factory(User::class)->create();

        $this->delete("usuarios/$user->id")
            ->assertRedirect('usuarios');

        $this->assertDatabaseMissing('users', [
            'id'=> $user->id,
        ]);
        $this->assertDatabaseEmpty('users');
    }
}

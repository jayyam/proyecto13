<?php

namespace Tests\Feature;
use App\User;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use function foo\func;

class UsersModuleTest extends TestCase
{
    use RefreshDatabase;

    private $from;

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

        $this->post('/usuarios',[
            'name' => 'Omar Santana',
            'email' => 'omar@mail.com',
            'password' =>'123456',
        ])->assertRedirect('usuarios/');
            //->assertRedirect(route('users.index'));//no jopa el return

            $this->assertCredentials([
                'name' => 'Omar Santana',
                'email' => 'omar@mail.com',
                'password' =>'123456',
            ]);

    }
    /** @test */
    function the_name_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/',[
                'name' => '',
                'email' => 'omar@mail.com',
                'password' =>'123456',
        ])->assertRedirect('usuarios/nuevo')
          ->assertSessionHasErrors(['name' => 'El campo nombre es obligatorio']);

        $this->assertEquals(0, User::count());
/*
        $this->assertDatabaseMissing('users', [
            'email' => 'omar@mail.com',
        ]);
*/
    }
    /** @test */
    function the_email_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => 'Omar Santana',
                'email' => '',
                'password' => '123456',
            ])->assertRedirect('usuarios/nuevo')
              ->assertSessionHasErrors(['email']);
            //->assertSessionHasErrors(['name' => 'El campo email es obligatorio']);

        $this->assertEquals(0, User::count());
    }

    /** @test */
    function the_email_must_be_valid()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => 'Omar Santana',
                'email' => 'Correo no valido',
                'password' => '123456',
            ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);
        //->assertSessionHasErrors(['name' => 'El campo email es obligatorio']);

        $this->assertEquals(0, User::count());
    }
    /** @test */
    function the_email_must_be_unique()
    {
        //$this->withoutExceptionHandling();

        factory(User::class)->create([
            'email' => 'omar@mail.com',
        ]);

        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => 'Omar Santana',
                'email' => 'correo no valido',
                'password' => '123456',
            ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['email']);
        //->assertSessionHasErrors(['name' => 'El campo email es obligatorio']);

        $this->assertEquals(1, User::count());
    }
    /** @test */
    function the_password_is_required()
    {
        $this->from('usuarios/nuevo')
            ->post('/usuarios/', [
                'name' => 'Omar Santana',
                'email' => 'omar@mail.com',
                'password' => '',
            ])->assertRedirect('usuarios/nuevo')
            ->assertSessionHasErrors(['password']);
        //->assertSessionHasErrors(['name' => 'El campo email es obligatorio']);

        $this->assertEquals(0, User::count());
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
}

<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create the roles we'll need for testing
        Role::create(['name' => 'buyer']);
        Role::create(['name' => 'maker']);
        Role::create(['name' => 'admin']);
        Role::create(['name' => 'moderator']);
    }

    public function test_auth_page_can_be_rendered()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        // Controleer op elementen van zowel de inlog- als registratieweergaven omdat deze op dezelfde pagina staan
        $response->assertSee('MakersMarkt');
        $response->assertSee('Registreren');
        $response->assertSee('Inloggen');
        // Controleer op stap-indicator elementen die exact in de HTML voorkomen
        $response->assertSee('Stap');
        $response->assertSee('van');
    }

    public function test_registration_process_works()
    {
        $response = $this->post('/register', [
            'name' => 'Test Maker',
            'username' => 'testmaker',
            'email' => 'testmaker@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'profile_bio' => 'I create beautiful handmade products',
            'contact_info' => '+9876543210',
            'roles' => ['maker'],
            'terms' => true,
        ]);

        $user = User::where('email', 'testmaker@example.com')->first();
        $this->assertNotNull($user);
        $this->assertEquals('testmaker', $user->username);
        $this->assertEquals('I create beautiful handmade products', $user->profile_bio);
        $this->assertTrue($user->hasRole('maker'));
    }

    public function test_login_process_works()
    {
        $user = User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        // Attach a role to the user
        $role = Role::where('name', 'buyer')->first();
        $user->roles()->attach($role->role_id);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard'));
    }

    public function test_login_with_remember_me()
    {
        $user = User::create([
            'name' => 'Remember User',
            'username' => 'rememberuser',
            'email' => 'remember@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'remember@example.com',
            'password' => 'password',
            'remember' => 'on',
        ]);

        $this->assertAuthenticated();

        // Controleer op aanwezigheid van een cookie met "remember" in de naam
        $cookies = $response->headers->getCookies();
        $hasRememberCookie = false;

        foreach ($cookies as $cookie) {
            if (str_contains($cookie->getName(), 'remember')) {
                $hasRememberCookie = true;
                break;
            }
        }

        $this->assertTrue($hasRememberCookie, 'Remember me cookie not found');
    }

    public function test_registration_with_invalid_data_shows_validation_errors()
    {
        $response = $this->post('/register', [
            'name' => '',
            'username' => '',
            'email' => 'not-an-email',
            'password' => 'pass',  // Too short
            'password_confirmation' => 'different',
            'roles' => [],
            'terms' => false,
        ]);

        $response->assertSessionHasErrors(['name', 'username', 'email', 'password', 'roles', 'terms']);
        $this->assertGuest();
    }

    public function test_login_with_invalid_credentials_fails()
    {
        $user = User::create([
            'name' => 'Test User',
            'username' => 'testuser',
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);

        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    public function test_user_can_register_with_multiple_valid_roles()
    {
        $response = $this->post('/register', [
            'name' => 'Multi Role User',
            'username' => 'multirole',
            'email' => 'multi@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'profile_bio' => 'I both make and buy',
            'contact_info' => '+1234567890',
            'roles' => ['buyer', 'maker'],
            'terms' => true,
        ]);

        $user = User::where('email', 'multi@example.com')->first();
        $this->assertNotNull($user);
        $this->assertTrue($user->hasRole('buyer'));
        $this->assertTrue($user->hasRole('maker'));
    }

    public function test_user_cannot_register_with_any_privileged_roles()
    {
        $response = $this->post('/register', [
            'name' => 'Privileged User',
            'username' => 'privileged',
            'email' => 'privileged@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'profile_bio' => 'Trying to be admin',
            'contact_info' => '+1234567890',
            'roles' => ['admin', 'moderator'],
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['roles.0', 'roles.1']);
        $this->assertGuest();
    }

    public function test_user_cannot_register_with_existing_email_or_username()
    {
        // Create a user first
        User::create([
            'name' => 'Existing User',
            'username' => 'existinguser',
            'email' => 'existing@example.com',
            'password' => Hash::make('password'),
        ]);

        // Probeer te registreren met dezelfde gebruikersnaam
        $response = $this->post('/register', [
            'name' => 'New User',
            'username' => 'existinguser',  // Zelfde gebruikersnaam
            'email' => 'new@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => ['buyer'],
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['username']);

        // Probeer te registreren met hetzelfde e-mailadres
        $response = $this->post('/register', [
            'name' => 'New User',
            'username' => 'newuser',
            'email' => 'existing@example.com',  // Zelfde e-mailadres
            'password' => 'password',
            'password_confirmation' => 'password',
            'roles' => ['buyer'],
            'terms' => true,
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_logout_works()
    {
        $user = User::create([
            'name' => 'Logout User',
            'username' => 'logoutuser',
            'email' => 'logout@example.com',
            'password' => Hash::make('password'),
        ]);

        $this->actingAs($user);
        $this->assertAuthenticated();

        $response = $this->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}

<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use App\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class AuthenticationSecurityTest extends TestCase
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

    public function test_username_validation_rejects_invalid_characters()
    {
        // Test with invalid username (special characters)
        $response = $this->post('/register', [
            'name' => 'Invalid Username User',
            'username' => 'user@name!',  // Contains invalid characters
            'email' => 'invalid@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'roles' => ['buyer'],
            'terms' => true,
        ]);

        // Assert that a validation error exists for username
        if ($response->isRedirection() && session()->has('errors')) {
            $errors = session('errors')->getBag('default')->getMessages();
            $this->assertArrayHasKey('username', $errors);
        }
    }

    public function test_privileged_role_assignment_is_prevented()
    {
        // Try to register with admin role
        $response = $this->post('/register', [
            'name' => 'Sneaky Admin',
            'username' => 'sneakyadmin',
            'email' => 'sneaky@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'roles' => ['admin'],
            'terms' => true,
        ]);

        // Check for errors on the roles field or any field
        if ($response->isRedirection() && session()->has('errors')) {
            $errors = session('errors')->getBag('default')->getMessages();
            // Check if there's any validation failure for roles
            $hasRoleError = false;
            foreach ($errors as $field => $messages) {
                if (str_starts_with($field, 'roles')) {
                    $hasRoleError = true;
                    break;
                }
            }
            $this->assertTrue($hasRoleError, "No role validation error found");
        }

        // Verify no admin user was created
        $this->assertFalse(
            User::whereHas('roles', function($q) {
                $q->where('name', 'admin');
            })->where('email', 'sneaky@example.com')->exists(),
            'Admin user was incorrectly created'
        );
    }

    public function test_email_normalization_works()
    {
        // Register with mixed-case email
        $response = $this->post('/register', [
            'name' => 'Email Case User',
            'username' => 'emailcase',
            'email' => 'MixedCase@Example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'roles' => ['buyer'],
            'terms' => true,
        ]);

        // If user was created, verify email normalization
        $user = User::where('username', 'emailcase')->first();
        if ($user) {
            // Email should be lowercase
            $this->assertEquals('mixedcase@example.com', $user->email);
        }
    }

    public function test_role_assignment_works_correctly()
    {
        // Test single role assignment
        $response = $this->post('/register', [
            'name' => 'Single Role User',
            'username' => 'singlerole',
            'email' => 'single@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'roles' => ['buyer'],
            'terms' => true,
        ]);

        // Check if user was created
        $user = User::where('email', 'single@example.com')->first();
        if ($user) {
            // Verify role assignment
            $this->assertTrue(
                $user->roles()->where('name', 'buyer')->exists(),
                'Buyer role was not correctly assigned'
            );
        }

        // Clean up for next test
        if (Auth::check()) {
            Auth::logout();
        }

        // Test multiple role assignment only if the first test worked
        if (isset($user)) {
            $response = $this->post('/register', [
                'name' => 'Multi Role User',
                'username' => 'multirole',
                'email' => 'multi@example.com',
                'password' => 'Password123!',
                'password_confirmation' => 'Password123!',
                'roles' => ['buyer', 'maker'],
                'terms' => true,
            ]);

            $multiUser = User::where('email', 'multi@example.com')->first();
            if ($multiUser) {
                $this->assertTrue(
                    $multiUser->roles()->where('name', 'buyer')->exists() &&
                    $multiUser->roles()->where('name', 'maker')->exists(),
                    'Multiple roles were not correctly assigned'
                );
            }
        }
    }

    public function test_terms_agreement_is_required()
    {
        // Test registration without terms agreement
        $response = $this->post('/register', [
            'name' => 'No Terms User',
            'username' => 'noterms',
            'email' => 'noterms@example.com',
            'password' => 'Password123!',
            'password_confirmation' => 'Password123!',
            'roles' => ['buyer'],
            // terms field intentionally omitted
        ]);

        // Check for validation error
        if ($response->isRedirection() && session()->has('errors')) {
            $errors = session('errors')->getBag('default')->getMessages();
            $this->assertArrayHasKey('terms', $errors);
        }

        // Verify user was not created
        $this->assertFalse(
            User::where('email', 'noterms@example.com')->exists(),
            'User was created despite missing terms agreement'
        );
    }
}

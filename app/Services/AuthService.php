<?php

namespace App\Services;

use App\Models\User;
use App\Models\Status;
use App\Helpers\Logger;
use App\Helpers\RateLimiter;
use App\Helpers\AccessKeyGen;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

/**
 * Service class for handling user authentication and registration.
 */
class AuthService
{
    private ?User $user = null;
    private string $name = '';
    private string $email = '';
    private string $password = '';
    private bool $remember = false;
    private string $accountType = 'student';
    private string $accessKey = '';

    /**
     * AuthService constructor.
     *
     * @param array $userDetails User details including name, email, password, and account type.
     */
    public function __construct(array $userDetails)
    {
        $this->name = $userDetails['name'] ?? $this->name;
        $this->email = $userDetails['email'] ?? $this->email;
        $this->password = $userDetails['password'] ?? $this->password;
        $this->remember = $userDetails['remember'] ?? $this->remember;
        $this->accountType = $userDetails['accountType'] ?? $this->accountType;
        $this->accessKey = $userDetails['accessKey'] ?? $this->accessKey;
    }

    /**
     * Register a new user and log them in.
     *
     * @return void
     */
    public function register(): void
    {
        try {
            RateLimiter::ensureNotRateLimited('register:', 5, 'register');

            $this->user = $this->createNewUser();
            Auth::login($this->user);
        } catch (\Throwable $th) {
            Logger::handle('Failed to register a new user.', $th);
            Session::flash('message.error', __('auth.register_failed'));
        }
    }

    /**
     * Attempts to log in the user.
     *
     * @return void
     */
    public function login(): void
    {
        try {
            RateLimiter::ensureNotRateLimited('login:', 5, 'login');

            if ($this->isUserValid()) {
                $this->authenticateUser();
            }
        } catch (\Throwable $th) {
            Logger::handle('Failed to log in the user.', $th);
            Session::flash('message.error', __('auth.login_error'));
        }
    }

    /**
     * Attempt to log the user in with an access key.
     *
     * @param string $accessKey
     * @return \Illuminate\Http\RedirectResponse|null
     */
    public function loginWithKey()
    {
        try {
            RateLimiter::ensureNotRateLimited('clogin:', 5, 'login');
            $accessKeyRecord = AccessKeyGen::verifyKey($this->accessKey);

            if (!$accessKeyRecord) {
                Session::flash('message.error', 'Kunci akses tidak valid atau sudah kadaluarsa.');
                return false;
            }

            $user = $accessKeyRecord->user;

            if (!$user || !$user->hasRole('supervisor')) {
                Session::flash('message.error', 'Pengguna ini bukan supervisor.');
                return false;
            }

            Auth::login($user, $this->remember);
            return true;
        } catch (\Throwable $th) {
            Logger::handle('Failed to login with access key.', $th);
            Session::flash('message.error', __('auth.login_error'));
        }
    }

    /**
     * Create a new user and assign a role.
     *
     * @return User
     */
    private function createNewUser(): User
    {
        $role = Role::firstOrCreate(['name' => $this->accountType]);
        $status = Status::where(['slug' => 'user-status-pending'])->first();

        $user = User::create([
            'email' => $this->email,
            'name' => $this->name,
            'password' => Hash::make($this->password),
            'status_id' => $status->id,
        ]);

        $user->assignRole($role);

        return $user;
    }

    /**
     * Validates if the user exists and the password matches.
     *
     * @return bool
     */
    private function isUserValid(): bool
    {
        $user = User::where('email', $this->email)->first();

        if (!$user || !Hash::check($this->password, $user->password)) {
            Session::flash('message.error', __('auth.user_invalid'));
            return false;
        }

        return true;
    }

    /**
     * Authenticates the user.
     *
     * @return bool
     */
    private function authenticateUser(): bool
    {
        if (!Auth::attempt([
            'email' => $this->email,
            'password' => $this->password
        ], $this->remember)) {
            Session::flash('message.error', __('auth.failed'));
            return false;
        }

        return true;
    }
}

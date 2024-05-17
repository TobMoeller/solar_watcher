<?php

namespace App\Console\Commands;

use App\Actions\Fortify\CreateNewUser;
use Illuminate\Console\Command;

use function Laravel\Prompts\info;
use function Laravel\Prompts\password;
use function Laravel\Prompts\text;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Creates a new User';

    /**
     * Execute the console command.
     */
    public function handle(CreateNewUser $createNewUserAction): void
    {
        $data['name'] = text(__('Name'));
        $data['email'] = text(__('Email'));
        $data['password'] = password(__('Password'));
        $data['password_confirmation'] = password(
            label: __('Confirm Password'),
            validate: fn (string $value) =>
                $value !== $data['password'] ? __('The confirmed Password does not match') : null
        );

        $user = $createNewUserAction->create($data);

        info(__('User with ID: :id created successfully', ['id' => $user->id]));
    }
}

<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class Setup extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:setup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //migrate
        $this->info('Migrating database...');
        $this->call('migrate');
        $this->info('Creating user...');
        //check if user exists
        $user = User::where('username', 'aws')->first();
        if ($user) {
            $this->info('User already exists');
            return;
        }
        $user = User::create([
            'name' => 'John Doe',
            'username' => 'aws',
            'email' => 'john.doe@example.com',
            'password' => bcrypt('12345678'),
        ]);
        //return to command line
        $this->info('User created successfully credentials are: username: aws, password: 12345678');
        //run the app
        $this->info('Application is ready to use');
        
    }
}

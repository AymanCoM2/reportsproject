<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Console\Command;

class firstuser extends Command
{
    protected $signature = 'app:firstuser';

    protected $description = 'Command description';


    public function handle()
    {
        $adminUser = new User();
        $adminUser->role_id = 1;
        $adminUser->name = 'Ahmed Rabie';
        $adminUser->email = 'ahmed.rabie@2coom.com';
        $adminUser->password = Hash::make('123@2coom');
        $adminUser->approved = true;
        $adminUser->email_verified_at = now();
        $adminUser->save();
    }
}

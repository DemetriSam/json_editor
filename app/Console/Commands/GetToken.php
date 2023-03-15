<?php

namespace App\Console\Commands;

use App\Models\Token;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class GetToken extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gettoken {email} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get token to access API methods';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $user = User::firstWhere('email', $this->argument('email'));
        $passed = Hash::check($this->argument('password'), $user->password);

        if ($passed) {
            $token = Str::random(50);
            Token::create([
                'token' => Hash::make($token),
                'user_id' => $user->id,
                'expires_at' => now()->addMinutes(5)->format('Y-m-d H:i:s'),
            ]);
            echo $token . PHP_EOL;
            return Command::SUCCESS;
        }
        return Command::FAILURE;
    }
}

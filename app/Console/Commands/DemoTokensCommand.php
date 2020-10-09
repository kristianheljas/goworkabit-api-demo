<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;

class DemoTokensCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'demo:tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate personal access tokens for demo';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tokensFileName = 'DEMO_TOKENS.md';
        $tokensFilePath = base_path($tokensFileName);

        if (file_exists($tokensFilePath) && !$this->confirm('Demo tokens already generated! Overwrite?')) {
            return 1;
        }

        $tokensMarkdown = '';

        foreach (['user@example.com', 'company@example.com'] as $email) {
            /* @var $user User */
            $user = User::where('email', $email)->first();

            $this->info('Generating token for ' . $user->email);

            $accessToken = $user->createToken('Demo token')->accessToken;

            $tokensMarkdown .= "## " . $user->email . "\n" .
                "```\n" .
                $accessToken . "\n" .
                "```\n" .
                "\n";
        }

        file_put_contents($tokensFilePath, $tokensMarkdown);

        $this->line('');
        $this->info('Access tokens written to ' . $tokensFileName);

        return 0;
    }
}

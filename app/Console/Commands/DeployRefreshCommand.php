<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeployRefreshCommand extends Command
{
/**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'deploy:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs the deploy refresh artisan commands';

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

        $this->call('storage:link');

        $this->call('optimize');

        $this->call('route:clear');

        $this->call('config:clear');

    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class PositionalUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'positionalupdate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'A command that calls our VATSIM parser and stores positional data in the database';

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
     * @return mixed
     */
    public function handle()
    {
        app('App\Http\Controllers\MapRenderController')->getServers(true);
        $this->info("[SUCCES] PositionalUpdate");
    }
}

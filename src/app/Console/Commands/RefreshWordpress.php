<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use App\Models\Player;
use App\Models\Team;

use A440\Wordpress\Wordpress;

use File;

class RefreshWordpress extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'wordpress:refresh';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pulls all WP posts';

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
        // all blog posts
        with(new Wordpress)->refresh();
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Mirrorlist;

class UpdateMirrorlist extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'update_mirrorlist';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the mirrorlist cache';

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        Mirrorlist::getMirrorlist(true);
    }

}

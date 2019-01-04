<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class UpdateProject extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = "
        loan:update
        {--force : For confirm}
        {--pro : Run as production}
        {--force-git : Force reset git}
    ";

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update current project';

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
        $force = !!$this->input->getOption('force');
        $pro = !!$this->input->getOption('pro');
        $forceGit = !!$this->input->getOption('force-git');
        if ($force || $this->confirm('"' . base_path() . '" is current project working dir?')) {
            // Update project code
            if ($forceGit) {
                $this->info(\shell_exec("git reset --hard HEAD"));
                $this->info(\shell_exec("git pull origin master -f"));
            }
            // Update composer
            $this->info(\shell_exec("composer install" . ($pro ? " --no-dev" : "")));
            $this->info(\shell_exec("composer dump-autoload"));

            // Do migration
            $this->call('migrate');
            // Clear all set
            $this->call('cache:clear');
            $this->call('route:clear');
            $this->call('view:clear');
            $this->call('config:clear');
        } else {
            $this->info('Command are not confirm');
        }
    }
}

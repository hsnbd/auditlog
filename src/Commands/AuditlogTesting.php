<?php

namespace Hsnbd\AuditLogger\Commands;

use Hsnbd\AuditLogger\Facades\AuditLog;
use Illuminate\Console\Command;

class AuditlogTesting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auditlog:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test eloquent model auditlog if it is working or not.';

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
        $this->info('Testing started.');
        AuditLog::debug('Hello world');
        $this->alert('Testing success.');
    }
}

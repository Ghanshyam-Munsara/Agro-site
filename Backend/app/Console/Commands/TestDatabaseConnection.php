<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class TestDatabaseConnection extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test database connection and display connection details';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info('Testing database connection...');
        
        try {
            $connection = DB::connection();
            $driver = $connection->getDriverName();
            $config = $connection->getConfig();
            
            $this->info("✓ Database driver: {$driver}");
            $this->info("✓ Database host: {$config['host']}");
            $this->info("✓ Database port: {$config['port']}");
            $this->info("✓ Database name: {$config['database']}");
            $this->info("✓ Database user: {$config['username']}");
            
            // Test connection by running a simple query
            $result = DB::select('SELECT version() as version');
            $version = $result[0]->version ?? 'Unknown';
            
            $this->info("✓ Database version: {$version}");
            $this->newLine();
            $this->info('✓ Database connection successful!');
            
            return 0;
        } catch (\Exception $e) {
            $this->error('✗ Database connection failed!');
            $this->error('Error: ' . $e->getMessage());
            $this->newLine();
            $this->warn('Please check your .env file and ensure:');
            $this->warn('- DB_CONNECTION is set correctly (pgsql for PostgreSQL)');
            $this->warn('- DB_HOST, DB_PORT, DB_DATABASE are correct');
            $this->warn('- DB_USERNAME and DB_PASSWORD are correct');
            $this->warn('- PostgreSQL server is running and accessible');
            
            return 1;
        }
    }
}

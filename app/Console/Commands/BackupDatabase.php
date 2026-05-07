<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class BackupDatabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Backup database MySQL ke file .sql';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $connection = config('database.default');
        $config = config("database.connections.{$connection}");

        if (! is_array($config) || ($config['driver'] ?? null) !== 'mysql') {
            $this->error('Backup hanya didukung untuk driver mysql.');
            return self::FAILURE;
        }

        $backupDir = $this->option('path') ?: storage_path('app/backups');
        File::ensureDirectoryExists($backupDir);

        $file = $backupDir.DIRECTORY_SEPARATOR.'backup-'.now()->format('Ymd_His').'.sql';

        $process = new Process([
            'mysqldump',
            '--host='.$config['host'],
            '--port='.(string) ($config['port'] ?? 3306),
            '--user='.$config['username'],
            $config['database'],
        ]);

        $process->setEnv([
            'MYSQL_PWD' => (string) ($config['password'] ?? ''),
        ]);

        $process->setTimeout(300);
        $process->run();

        if (! $process->isSuccessful()) {
            $this->error('Gagal backup. Pastikan mysqldump tersedia di PATH.');
            $this->line($process->getErrorOutput() ?: $process->getOutput());
            return self::FAILURE;
        }

        File::put($file, $process->getOutput());

        $this->info("Backup berhasil: {$file}");
        return self::SUCCESS;
    }
}

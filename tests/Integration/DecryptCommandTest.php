<?php

namespace Spatie\Backup\Test\Integration;

use Carbon\Carbon;
use Illuminate\Support\Facades\Artisan;
use ZipArchive;

class DecryptCommandTest extends TestCase
{
    /** @var \Carbon\Carbon */
    protected $date;

    /** @var string */
    protected $expectedZipPath;

    public function setUp()
    {
        parent::setUp();

        $this->date = Carbon::create('2016', 1, 1, 21, 1, 1);

        Carbon::setTestNow($this->date);

        $this->expectedZipPath = 'mysite/2016-01-01-21-01-01.zip';

        $this->app['config']->set('backup.backup.destination.disks', [
            'local',
        ]);

        $this->app['config']->set('backup.backup.source.files.include', [base_path()]);
    }

    /** @test */
    public function it_can_decrypt_backup_files()
    {
        $backupDisk = $this->app['config']->get('filesystems.disks.local.root');

        $this->app['config']->set('backup.backup.source.files.include', [$backupDisk]);
        $this->app['config']->set('backup.backup.encrypt', true);

        $zipFullPath = $backupDisk . DIRECTORY_SEPARATOR . $this->expectedZipPath;

        touch($backupDisk . DIRECTORY_SEPARATOR . 'testing-file.txt');

        // Create a backup first
        Artisan::call('backup:run', ['--only-files' => true]);

        $this->assertFileExistsOnDisk($this->expectedZipPath, 'local');

        // An encrypted ZIP file cannot be opened by ZipArchive and should give an error
        $this->assertEquals(ZipArchive::ER_NOZIP, (new ZipArchive)->open($zipFullPath));

        // Then decrypt it
        Artisan::call('backup:decrypt', ['file' => $zipFullPath]);

        $this->assertFileExistsOnDisk($this->expectedZipPath, 'local');
        $this->assertTrue((new ZipArchive)->open($zipFullPath));
        $this->assertFileExistsInZip($zipFullPath, 'testing-file.txt');
    }

    /** @test */
    public function it_will_fail_when_the_file_cannot_be_found()
    {
        Artisan::call('backup:decrypt', ['file' => 'non-existing-file.zip']);

        $this->seeInConsoleOutput('File `non-existing-file.zip` not found');
    }
}

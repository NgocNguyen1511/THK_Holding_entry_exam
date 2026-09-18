<?php

namespace Tests\Unit;

use App\Services\FileService;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class FileServiceTest extends TestCase
{
    private FileService $fileService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fileService = new FileService;
    }

    public function testHandleUploadedImageReturnsNullWhenNoFile(): void
    {
        $result = $this->fileService->handleUploadedImage(null);

        $this->assertNull($result);
    }

    public function testHandleUploadedImageSavesFileAndReturnsRelativePath(): void
    {
        $fakeFile = UploadedFile::fake()->image('test_hotel.png', 100, 100);

        $result = $this->fileService->handleUploadedImage($fakeFile, 'hotel');

        $this->assertNotNull($result);
        $this->assertStringStartsWith('hotel/', $result);

        $fullPath = public_path('assets/img/'.$result);
        $this->assertTrue(File::exists($fullPath));

        $this->fileService->deleteImage($result);
        $this->assertFalse(File::exists($fullPath));
    }

    public function testDeleteImageDoesNothingWhenNullOrEmpty(): void
    {
        $this->fileService->deleteImage(null);
        $this->fileService->deleteImage('');

        $this->assertTrue(true);
    }
}

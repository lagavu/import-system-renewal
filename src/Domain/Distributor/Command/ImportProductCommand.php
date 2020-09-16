<?php

namespace App\Domain\Distributor\Command;

use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;

class ImportProductCommand
{
    /**
     * @var string
     * @Assert\NotBlank(message="Выберите дистрибьютера")
     */
    public $distributorName;

    /**
     * @var UploadedFile
     * @Assert\NotBlank(message="Необходимо добавить текстовый файл для импорта")
     * @Assert\File(
     *     maxSize = "1024k",
     *     mimeTypes = {"text/plain"},
     *     mimeTypesMessage = "Загружаемые файлы должны быть в txt формате"
     * )
     */
    public $file;

    public function __construct(string $distributorName, File $file)
    {
        $this->distributorName = $distributorName;
        $this->file = $file;
    }
}

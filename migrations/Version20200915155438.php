<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200915155438 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Adding distributors';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('INSERT INTO `distributors` (`id`, `name`) VALUES ("0706bbc6-20f2-4a98-a3be-5e779d58bd3a", "Дистрибьютер 1")');
        $this->addSql('INSERT INTO `distributors` (`id`, `name`) VALUES ("f03de0b3-1e72-4850-b52a-37ccc155f0d8", "Дистрибьютер 2")');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DELETE FROM distributors');
    }
}

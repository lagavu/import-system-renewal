<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20200915155432 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'Creating all tables';
    }

    public function up(Schema $schema) : void
    {
        $this->addSql('CREATE TABLE preparations_undefined (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pharmacies (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', address VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE distributors (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preparations (id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', pharmacy_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', distributor_id CHAR(36) NOT NULL COMMENT \'(DC2Type:uuid)\', name VARCHAR(255) NOT NULL, quantity INT DEFAULT 0 NOT NULL, INDEX IDX_A12709A48A94ABE2 (pharmacy_id), INDEX IDX_A12709A42D863A58 (distributor_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE preparations ADD CONSTRAINT FK_A12709A48A94ABE2 FOREIGN KEY (pharmacy_id) REFERENCES pharmacies (id)');
        $this->addSql('ALTER TABLE preparations ADD CONSTRAINT FK_A12709A42D863A58 FOREIGN KEY (distributor_id) REFERENCES distributors (id)');
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('ALTER TABLE preparations DROP FOREIGN KEY FK_A12709A42D863A58');
        $this->addSql('ALTER TABLE preparations DROP FOREIGN KEY FK_A12709A48A94ABE2');
        $this->addSql('DROP TABLE distributors');
        $this->addSql('DROP TABLE pharmacies');
        $this->addSql('DROP TABLE preparations');
        $this->addSql('DROP TABLE preparations_undefined');
    }
}

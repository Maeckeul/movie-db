<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200819112747 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE award (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, year INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE movie ADD award_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26F3D5282CF FOREIGN KEY (award_id) REFERENCES award (id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26F3D5282CF ON movie (award_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26F3D5282CF');
        $this->addSql('DROP TABLE award');
        $this->addSql('DROP INDEX IDX_1D5EF26F3D5282CF ON movie');
        $this->addSql('ALTER TABLE movie DROP award_id');
    }
}

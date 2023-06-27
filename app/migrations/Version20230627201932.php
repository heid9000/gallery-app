<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230627201932 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cached (id INT AUTO_INCREMENT NOT NULL, image_id INT NOT NULL, dimension_id INT DEFAULT NULL, ratio NUMERIC(9, 5) DEFAULT NULL, src VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, INDEX IDX_A6F27A7C3DA5256D (image_id), INDEX IDX_A6F27A7C277428AD (dimension_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dimension (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, ratio NUMERIC(9, 5) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, width INT NOT NULL, height INT NOT NULL, ratio NUMERIC(9, 5) DEFAULT NULL, src VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, image_hash VARCHAR(255) NOT NULL, error VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cached ADD CONSTRAINT FK_A6F27A7C3DA5256D FOREIGN KEY (image_id) REFERENCES image (id)');
        $this->addSql('ALTER TABLE cached ADD CONSTRAINT FK_A6F27A7C277428AD FOREIGN KEY (dimension_id) REFERENCES dimension (id)');
        $this->addSql("insert into dimension values (null, 'big', 800, 600, '1.33333')");
        $this->addSql("insert into dimension values (null, 'med', 640, 480, '1.33333')");
        $this->addSql("insert into dimension values (null, 'min', 320, 240, '1.33333')");
        $this->addSql("insert into dimension values (null, 'mic', 150, 150, '1.00000')");
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cached DROP FOREIGN KEY FK_A6F27A7C3DA5256D');
        $this->addSql('ALTER TABLE cached DROP FOREIGN KEY FK_A6F27A7C277428AD');
        $this->addSql('DROP TABLE cached');
        $this->addSql('DROP TABLE dimension');
        $this->addSql('DROP TABLE image');
    }
}

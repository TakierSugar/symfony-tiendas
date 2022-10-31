<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221026072102 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE empresa (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE tipo');
        $this->addSql('ALTER TABLE tienda ADD empresa_id INT DEFAULT NULL, ADD tipo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE tienda ADD CONSTRAINT FK_C0C6E53E521E1991 FOREIGN KEY (empresa_id) REFERENCES empresa (id)');
        $this->addSql('CREATE INDEX IDX_C0C6E53E521E1991 ON tienda (empresa_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE tienda DROP FOREIGN KEY FK_C0C6E53E521E1991');
        $this->addSql('CREATE TABLE tipo (id INT AUTO_INCREMENT NOT NULL, nombre VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE empresa');
        $this->addSql('DROP INDEX IDX_C0C6E53E521E1991 ON tienda');
        $this->addSql('ALTER TABLE tienda DROP empresa_id, DROP tipo');
    }
}

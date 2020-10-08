<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200723081235 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE insight (id INT AUTO_INCREMENT NOT NULL, journal_id INT NOT NULL, type_id INT NOT NULL, value VARCHAR(255) NOT NULL, date DATETIME NOT NULL, INDEX IDX_FE3413DB478E8802 (journal_id), INDEX IDX_FE3413DBC54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE insight_type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE journal (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source (id INT AUTO_INCREMENT NOT NULL, insight_type_id INT NOT NULL, journal_id INT NOT NULL, url VARCHAR(255) NOT NULL, selector VARCHAR(255) DEFAULT NULL, script LONGTEXT DEFAULT NULL, regex VARCHAR(255) DEFAULT NULL, regex_capture_group INT DEFAULT NULL, INDEX IDX_5F8A7F73D4AA2A70 (insight_type_id), INDEX IDX_5F8A7F73478E8802 (journal_id), UNIQUE INDEX UNIQ_5F8A7F73D4AA2A70478E8802 (insight_type_id, journal_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE insight ADD CONSTRAINT FK_FE3413DB478E8802 FOREIGN KEY (journal_id) REFERENCES journal (id)');
        $this->addSql('ALTER TABLE insight ADD CONSTRAINT FK_FE3413DBC54C8C93 FOREIGN KEY (type_id) REFERENCES insight_type (id)');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT FK_5F8A7F73D4AA2A70 FOREIGN KEY (insight_type_id) REFERENCES insight_type (id)');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT FK_5F8A7F73478E8802 FOREIGN KEY (journal_id) REFERENCES journal (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE insight DROP FOREIGN KEY FK_FE3413DBC54C8C93');
        $this->addSql('ALTER TABLE source DROP FOREIGN KEY FK_5F8A7F73D4AA2A70');
        $this->addSql('ALTER TABLE insight DROP FOREIGN KEY FK_FE3413DB478E8802');
        $this->addSql('ALTER TABLE source DROP FOREIGN KEY FK_5F8A7F73478E8802');
        $this->addSql('DROP TABLE insight');
        $this->addSql('DROP TABLE insight_type');
        $this->addSql('DROP TABLE journal');
        $this->addSql('DROP TABLE source');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241221160212 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enseignant CHANGE departement departement VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE promotion CHANGE choix choix VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD promotion_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649139DF194 ON user (promotion_id)');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE enseignant CHANGE departement departement VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE messenger_messages CHANGE delivered_at delivered_at DATETIME DEFAULT \'NULL\' COMMENT \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE promotion CHANGE choix choix VARCHAR(255) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649139DF194');
        $this->addSql('DROP INDEX IDX_8D93D649139DF194 ON user');
        $this->addSql('ALTER TABLE user DROP promotion_id');
    }
}

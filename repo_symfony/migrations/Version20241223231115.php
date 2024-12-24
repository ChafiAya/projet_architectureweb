<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241223231115 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserve DROP etat_reservation');
        $this->addSql('ALTER TABLE sale DROP disponibilite');
        $this->addSql('ALTER TABLE user ADD nom VARCHAR(255) NOT NULL, ADD prenom VARCHAR(255) NOT NULL, ADD type_utilisateur VARCHAR(50) NOT NULL, CHANGE roles roles VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserve ADD etat_reservation VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE sale ADD disponibilite TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE user DROP nom, DROP prenom, DROP type_utilisateur, CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}

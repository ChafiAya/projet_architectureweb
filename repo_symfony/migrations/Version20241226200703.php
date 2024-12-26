<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241226200703 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere_enseignant DROP FOREIGN KEY FK_536FA40FE455FCC0');
        $this->addSql('ALTER TABLE matiere_enseignant DROP FOREIGN KEY FK_536FA40FF46CD258');
        $this->addSql('DROP TABLE matiere_enseignant');
        $this->addSql('ALTER TABLE enseignant ADD id_matiere_id INT DEFAULT NULL, ADD nom VARCHAR(55) NOT NULL, ADD email VARCHAR(255) NOT NULL, DROP nom_enseignant, DROP email_enseignant, DROP departement, CHANGE prenom prenom VARCHAR(55) NOT NULL');
        $this->addSql('ALTER TABLE enseignant ADD CONSTRAINT FK_81A72FA151E6528F FOREIGN KEY (id_matiere_id) REFERENCES matiere (id)');
        $this->addSql('CREATE INDEX IDX_81A72FA151E6528F ON enseignant (id_matiere_id)');
        $this->addSql('ALTER TABLE matiere ADD nom VARCHAR(55) NOT NULL, ADD code_matiere VARCHAR(55) NOT NULL, ADD description LONGTEXT DEFAULT NULL, DROP code_mat, DROP nom_matiere');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_9014574AA8B313D6 ON matiere (code_matiere)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE matiere_enseignant (matiere_id INT NOT NULL, enseignant_id INT NOT NULL, INDEX IDX_536FA40FE455FCC0 (enseignant_id), INDEX IDX_536FA40FF46CD258 (matiere_id), PRIMARY KEY(matiere_id, enseignant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE matiere_enseignant ADD CONSTRAINT FK_536FA40FE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere_enseignant ADD CONSTRAINT FK_536FA40FF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE enseignant DROP FOREIGN KEY FK_81A72FA151E6528F');
        $this->addSql('DROP INDEX IDX_81A72FA151E6528F ON enseignant');
        $this->addSql('ALTER TABLE enseignant ADD email_enseignant VARCHAR(255) NOT NULL, ADD departement VARCHAR(255) DEFAULT NULL, DROP id_matiere_id, DROP nom, CHANGE prenom prenom VARCHAR(255) NOT NULL, CHANGE email nom_enseignant VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX UNIQ_9014574AA8B313D6 ON matiere');
        $this->addSql('ALTER TABLE matiere ADD code_mat VARCHAR(255) NOT NULL, ADD nom_matiere VARCHAR(255) NOT NULL, DROP nom, DROP code_matiere, DROP description');
    }
}

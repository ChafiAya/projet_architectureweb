<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219111338 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE enseignant (id INT AUTO_INCREMENT NOT NULL, nom_enseignant VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email_enseignant VARCHAR(255) NOT NULL, departement VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, code_mat VARCHAR(255) NOT NULL, nom_matiere VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere_enseignant (matiere_id INT NOT NULL, enseignant_id INT NOT NULL, INDEX IDX_536FA40FF46CD258 (matiere_id), INDEX IDX_536FA40FE455FCC0 (enseignant_id), PRIMARY KEY(matiere_id, enseignant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion (id INT AUTO_INCREMENT NOT NULL, niveau_promotion VARCHAR(255) NOT NULL, enseignement VARCHAR(255) NOT NULL, choix VARCHAR(255) DEFAULT NULL, nbr_etudiants INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE promotion_matiere (promotion_id INT NOT NULL, matiere_id INT NOT NULL, INDEX IDX_C869A170139DF194 (promotion_id), INDEX IDX_C869A170F46CD258 (matiere_id), PRIMARY KEY(promotion_id, matiere_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserve (id INT AUTO_INCREMENT NOT NULL, date_reservation DATE NOT NULL, heure_depart TIME NOT NULL, heure_fin TIME NOT NULL, etat_reservation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserve_sale (reserve_id INT NOT NULL, sale_id INT NOT NULL, INDEX IDX_DE15B3185913AEBF (reserve_id), INDEX IDX_DE15B3184A7E4868 (sale_id), PRIMARY KEY(reserve_id, sale_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserve_enseignant (reserve_id INT NOT NULL, enseignant_id INT NOT NULL, INDEX IDX_8371D61B5913AEBF (reserve_id), INDEX IDX_8371D61BE455FCC0 (enseignant_id), PRIMARY KEY(reserve_id, enseignant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reserve_promotion (reserve_id INT NOT NULL, promotion_id INT NOT NULL, INDEX IDX_7A77CD125913AEBF (reserve_id), INDEX IDX_7A77CD12139DF194 (promotion_id), PRIMARY KEY(reserve_id, promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sale (id INT AUTO_INCREMENT NOT NULL, batiment VARCHAR(255) NOT NULL, etage INT NOT NULL, nom_de_salle VARCHAR(255) NOT NULL, disponibilite TINYINT(1) NOT NULL, capacite INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, enseignant_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E455FCC0 (enseignant_id), UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE matiere_enseignant ADD CONSTRAINT FK_536FA40FF46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere_enseignant ADD CONSTRAINT FK_536FA40FE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_matiere ADD CONSTRAINT FK_C869A170139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion_matiere ADD CONSTRAINT FK_C869A170F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reserve_sale ADD CONSTRAINT FK_DE15B3185913AEBF FOREIGN KEY (reserve_id) REFERENCES reserve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reserve_sale ADD CONSTRAINT FK_DE15B3184A7E4868 FOREIGN KEY (sale_id) REFERENCES sale (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reserve_enseignant ADD CONSTRAINT FK_8371D61B5913AEBF FOREIGN KEY (reserve_id) REFERENCES reserve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reserve_enseignant ADD CONSTRAINT FK_8371D61BE455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reserve_promotion ADD CONSTRAINT FK_7A77CD125913AEBF FOREIGN KEY (reserve_id) REFERENCES reserve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reserve_promotion ADD CONSTRAINT FK_7A77CD12139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649E455FCC0 FOREIGN KEY (enseignant_id) REFERENCES enseignant (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matiere_enseignant DROP FOREIGN KEY FK_536FA40FF46CD258');
        $this->addSql('ALTER TABLE matiere_enseignant DROP FOREIGN KEY FK_536FA40FE455FCC0');
        $this->addSql('ALTER TABLE promotion_matiere DROP FOREIGN KEY FK_C869A170139DF194');
        $this->addSql('ALTER TABLE promotion_matiere DROP FOREIGN KEY FK_C869A170F46CD258');
        $this->addSql('ALTER TABLE reserve_sale DROP FOREIGN KEY FK_DE15B3185913AEBF');
        $this->addSql('ALTER TABLE reserve_sale DROP FOREIGN KEY FK_DE15B3184A7E4868');
        $this->addSql('ALTER TABLE reserve_enseignant DROP FOREIGN KEY FK_8371D61B5913AEBF');
        $this->addSql('ALTER TABLE reserve_enseignant DROP FOREIGN KEY FK_8371D61BE455FCC0');
        $this->addSql('ALTER TABLE reserve_promotion DROP FOREIGN KEY FK_7A77CD125913AEBF');
        $this->addSql('ALTER TABLE reserve_promotion DROP FOREIGN KEY FK_7A77CD12139DF194');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649E455FCC0');
        $this->addSql('DROP TABLE enseignant');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE matiere_enseignant');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE promotion_matiere');
        $this->addSql('DROP TABLE reserve');
        $this->addSql('DROP TABLE reserve_sale');
        $this->addSql('DROP TABLE reserve_enseignant');
        $this->addSql('DROP TABLE reserve_promotion');
        $this->addSql('DROP TABLE sale');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

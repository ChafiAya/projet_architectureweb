<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241210172118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reserve_promotion (reserve_id INT NOT NULL, promotion_id INT NOT NULL, INDEX IDX_7A77CD125913AEBF (reserve_id), INDEX IDX_7A77CD12139DF194 (promotion_id), PRIMARY KEY(reserve_id, promotion_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reserve_promotion ADD CONSTRAINT FK_7A77CD125913AEBF FOREIGN KEY (reserve_id) REFERENCES reserve (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reserve_promotion ADD CONSTRAINT FK_7A77CD12139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE promotion CHANGE nbr_etudiant nbr_etudiants INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reserve_promotion DROP FOREIGN KEY FK_7A77CD125913AEBF');
        $this->addSql('ALTER TABLE reserve_promotion DROP FOREIGN KEY FK_7A77CD12139DF194');
        $this->addSql('DROP TABLE reserve_promotion');
        $this->addSql('ALTER TABLE promotion CHANGE nbr_etudiants nbr_etudiant INT NOT NULL');
    }
}

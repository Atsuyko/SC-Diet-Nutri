<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231107144506 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE allergen (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diet (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(50) NOT NULL, description LONGTEXT NOT NULL, preparation_time INT NOT NULL, timeout INT NOT NULL, cooking_time INT NOT NULL, ingredient LONGTEXT NOT NULL, steps LONGTEXT NOT NULL, is_premium TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_diet (recipe_id INT NOT NULL, diet_id INT NOT NULL, INDEX IDX_E2FF3FFF59D8A214 (recipe_id), INDEX IDX_E2FF3FFFE1E13ACE (diet_id), PRIMARY KEY(recipe_id, diet_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recipe_allergen (recipe_id INT NOT NULL, allergen_id INT NOT NULL, INDEX IDX_532FAD5059D8A214 (recipe_id), INDEX IDX_532FAD506E775A4A (allergen_id), PRIMARY KEY(recipe_id, allergen_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE recipe_diet ADD CONSTRAINT FK_E2FF3FFF59D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_diet ADD CONSTRAINT FK_E2FF3FFFE1E13ACE FOREIGN KEY (diet_id) REFERENCES diet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_allergen ADD CONSTRAINT FK_532FAD5059D8A214 FOREIGN KEY (recipe_id) REFERENCES recipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE recipe_allergen ADD CONSTRAINT FK_532FAD506E775A4A FOREIGN KEY (allergen_id) REFERENCES allergen (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recipe_diet DROP FOREIGN KEY FK_E2FF3FFF59D8A214');
        $this->addSql('ALTER TABLE recipe_diet DROP FOREIGN KEY FK_E2FF3FFFE1E13ACE');
        $this->addSql('ALTER TABLE recipe_allergen DROP FOREIGN KEY FK_532FAD5059D8A214');
        $this->addSql('ALTER TABLE recipe_allergen DROP FOREIGN KEY FK_532FAD506E775A4A');
        $this->addSql('DROP TABLE allergen');
        $this->addSql('DROP TABLE diet');
        $this->addSql('DROP TABLE recipe');
        $this->addSql('DROP TABLE recipe_diet');
        $this->addSql('DROP TABLE recipe_allergen');
        $this->addSql('DROP TABLE messenger_messages');
    }
}

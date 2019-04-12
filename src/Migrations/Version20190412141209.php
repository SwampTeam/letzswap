<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190412141209 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, condition_status VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_status (id INT AUTO_INCREMENT NOT NULL, time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_status_item (item_status_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_C775D0BC672D164D (item_status_id), INDEX IDX_C775D0BC126F525E (item_id), PRIMARY KEY(item_status_id, item_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_status_status (item_status_id INT NOT NULL, status_id INT NOT NULL, INDEX IDX_2AE6658A672D164D (item_status_id), INDEX IDX_2AE6658A6BF700BD (status_id), PRIMARY KEY(item_status_id, status_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, item_id_id INT NOT NULL, mime_type VARCHAR(50) NOT NULL, INDEX IDX_16DB4F8955E38587 (item_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, roles JSON NOT NULL, tos_accepted TINYINT(1) DEFAULT NULL, email_confirmed TINYINT(1) DEFAULT NULL, activation_token CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649B1B4826B (activation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_status (id INT AUTO_INCREMENT NOT NULL, time DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_status_user (user_status_id INT NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', INDEX IDX_1C8EB2486B178D59 (user_status_id), INDEX IDX_1C8EB248A76ED395 (user_id), PRIMARY KEY(user_status_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_status_status (user_status_id INT NOT NULL, status_id INT NOT NULL, INDEX IDX_28C460016B178D59 (user_status_id), INDEX IDX_28C460016BF700BD (status_id), PRIMARY KEY(user_status_id, status_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_status_item ADD CONSTRAINT FK_C775D0BC672D164D FOREIGN KEY (item_status_id) REFERENCES item_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_status_item ADD CONSTRAINT FK_C775D0BC126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_status_status ADD CONSTRAINT FK_2AE6658A672D164D FOREIGN KEY (item_status_id) REFERENCES item_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_status_status ADD CONSTRAINT FK_2AE6658A6BF700BD FOREIGN KEY (status_id) REFERENCES status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F8955E38587 FOREIGN KEY (item_id_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE user_status_user ADD CONSTRAINT FK_1C8EB2486B178D59 FOREIGN KEY (user_status_id) REFERENCES user_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_status_user ADD CONSTRAINT FK_1C8EB248A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_status_status ADD CONSTRAINT FK_28C460016B178D59 FOREIGN KEY (user_status_id) REFERENCES user_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_status_status ADD CONSTRAINT FK_28C460016BF700BD FOREIGN KEY (status_id) REFERENCES status (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_status_item DROP FOREIGN KEY FK_C775D0BC126F525E');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F8955E38587');
        $this->addSql('ALTER TABLE item_status_item DROP FOREIGN KEY FK_C775D0BC672D164D');
        $this->addSql('ALTER TABLE item_status_status DROP FOREIGN KEY FK_2AE6658A672D164D');
        $this->addSql('ALTER TABLE item_status_status DROP FOREIGN KEY FK_2AE6658A6BF700BD');
        $this->addSql('ALTER TABLE user_status_status DROP FOREIGN KEY FK_28C460016BF700BD');
        $this->addSql('ALTER TABLE user_status_user DROP FOREIGN KEY FK_1C8EB248A76ED395');
        $this->addSql('ALTER TABLE user_status_user DROP FOREIGN KEY FK_1C8EB2486B178D59');
        $this->addSql('ALTER TABLE user_status_status DROP FOREIGN KEY FK_28C460016B178D59');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_status');
        $this->addSql('DROP TABLE item_status_item');
        $this->addSql('DROP TABLE item_status_status');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_status');
        $this->addSql('DROP TABLE user_status_user');
        $this->addSql('DROP TABLE user_status_status');
    }
}

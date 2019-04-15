<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190415140913 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, condition_status VARCHAR(255) NOT NULL, INDEX IDX_1F1B251EA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_status (id INT AUTO_INCREMENT NOT NULL, items_id INT NOT NULL, statuses_id INT NOT NULL, time DATETIME NOT NULL, INDEX IDX_FDF910D36BB0AE84 (items_id), INDEX IDX_FDF910D31259C1FF (statuses_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture (id INT AUTO_INCREMENT NOT NULL, item_id INT NOT NULL, mime_type VARCHAR(50) NOT NULL, INDEX IDX_16DB4F89126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, email_confirmed TINYINT(1) DEFAULT NULL, roles JSON NOT NULL, tos_accepted TINYINT(1) DEFAULT NULL, activation_token CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649B1B4826B (activation_token), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_status (id INT AUTO_INCREMENT NOT NULL, users_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', statuses_id INT NOT NULL, time DATETIME NOT NULL, INDEX IDX_1E527E2167B3B43D (users_id), INDEX IDX_1E527E211259C1FF (statuses_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item ADD CONSTRAINT FK_1F1B251EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE item_status ADD CONSTRAINT FK_FDF910D36BB0AE84 FOREIGN KEY (items_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE item_status ADD CONSTRAINT FK_FDF910D31259C1FF FOREIGN KEY (statuses_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE picture ADD CONSTRAINT FK_16DB4F89126F525E FOREIGN KEY (item_id) REFERENCES item (id)');
        $this->addSql('ALTER TABLE user_status ADD CONSTRAINT FK_1E527E2167B3B43D FOREIGN KEY (users_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user_status ADD CONSTRAINT FK_1E527E211259C1FF FOREIGN KEY (statuses_id) REFERENCES status (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_status DROP FOREIGN KEY FK_FDF910D36BB0AE84');
        $this->addSql('ALTER TABLE picture DROP FOREIGN KEY FK_16DB4F89126F525E');
        $this->addSql('ALTER TABLE item_status DROP FOREIGN KEY FK_FDF910D31259C1FF');
        $this->addSql('ALTER TABLE user_status DROP FOREIGN KEY FK_1E527E211259C1FF');
        $this->addSql('ALTER TABLE item DROP FOREIGN KEY FK_1F1B251EA76ED395');
        $this->addSql('ALTER TABLE user_status DROP FOREIGN KEY FK_1E527E2167B3B43D');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_status');
        $this->addSql('DROP TABLE picture');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_status');
    }
}

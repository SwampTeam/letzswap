<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190415111000 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE item_status_item');
        $this->addSql('DROP TABLE item_status_status');
        $this->addSql('DROP TABLE user_status_status');
        $this->addSql('DROP TABLE user_status_user');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item_status_item (item_status_id INT NOT NULL, item_id INT NOT NULL, INDEX IDX_C775D0BC672D164D (item_status_id), INDEX IDX_C775D0BC126F525E (item_id), PRIMARY KEY(item_status_id, item_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE item_status_status (item_status_id INT NOT NULL, status_id INT NOT NULL, INDEX IDX_2AE6658A672D164D (item_status_id), INDEX IDX_2AE6658A6BF700BD (status_id), PRIMARY KEY(item_status_id, status_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_status_status (user_status_id INT NOT NULL, status_id INT NOT NULL, INDEX IDX_28C460016B178D59 (user_status_id), INDEX IDX_28C460016BF700BD (status_id), PRIMARY KEY(user_status_id, status_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_status_user (user_status_id INT NOT NULL, user_id CHAR(36) NOT NULL COLLATE utf8mb4_unicode_ci COMMENT \'(DC2Type:guid)\', INDEX IDX_1C8EB2486B178D59 (user_status_id), INDEX IDX_1C8EB248A76ED395 (user_id), PRIMARY KEY(user_status_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE item_status_item ADD CONSTRAINT FK_C775D0BC126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_status_item ADD CONSTRAINT FK_C775D0BC672D164D FOREIGN KEY (item_status_id) REFERENCES item_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_status_status ADD CONSTRAINT FK_2AE6658A672D164D FOREIGN KEY (item_status_id) REFERENCES item_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_status_status ADD CONSTRAINT FK_2AE6658A6BF700BD FOREIGN KEY (status_id) REFERENCES status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_status_status ADD CONSTRAINT FK_28C460016B178D59 FOREIGN KEY (user_status_id) REFERENCES user_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_status_status ADD CONSTRAINT FK_28C460016BF700BD FOREIGN KEY (status_id) REFERENCES status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_status_user ADD CONSTRAINT FK_1C8EB2486B178D59 FOREIGN KEY (user_status_id) REFERENCES user_status (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_status_user ADD CONSTRAINT FK_1C8EB248A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }
}

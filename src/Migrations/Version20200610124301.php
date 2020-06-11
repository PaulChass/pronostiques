<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200610124301 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, stats LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', stats5 LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_98197A65296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, logourl VARCHAR(255) NOT NULL, twitter VARCHAR(255) DEFAULT NULL, injuries LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', last_update DATETIME NOT NULL, stats LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', stats5 LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', stats_location LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', last5games LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_C4E0A61FE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C4D77E7D8');
        $this->addSql('DROP INDEX IDX_9474526C4D77E7D8 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE game_id_id game_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('CREATE INDEX IDX_9474526CE48FD905 ON comment (game_id)');
        $this->addSql('ALTER TABLE game ADD game_id INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65296CD8AE');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE team');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE48FD905');
        $this->addSql('DROP INDEX IDX_9474526CE48FD905 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE game_id game_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4D77E7D8 FOREIGN KEY (game_id_id) REFERENCES game (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9474526C4D77E7D8 ON comment (game_id_id)');
        $this->addSql('ALTER TABLE game DROP game_id');
    }
}

<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200618103028 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, game_id INT NOT NULL, username VARCHAR(255) NOT NULL, bet VARCHAR(255) DEFAULT NULL, message VARCHAR(255) NOT NULL, publish_date DATETIME NOT NULL, INDEX IDX_9474526CE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game (id INT AUTO_INCREMENT NOT NULL, datetime DATETIME NOT NULL, awayteamid INT NOT NULL, odds VARCHAR(255) DEFAULT NULL, hometeamid INT NOT NULL, game_id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, team_id INT NOT NULL, stats LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', stats5 LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', last5_games LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_98197A65296CD8AE (team_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, game_id INT DEFAULT NULL, logourl VARCHAR(255) NOT NULL, twitter VARCHAR(255) DEFAULT NULL, injuries LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', last_update DATETIME NOT NULL, stats LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', stats5 LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', stats_location LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', last5games LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_C4E0A61FE48FD905 (game_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('ALTER TABLE player ADD CONSTRAINT FK_98197A65296CD8AE FOREIGN KEY (team_id) REFERENCES team (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FE48FD905 FOREIGN KEY (game_id) REFERENCES game (id)');
        $this->addSql('DROP TABLE likedcomment');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE signaledcomment');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_choices');
        $this->addSql('DROP TABLE wor749_commentmeta');
        $this->addSql('DROP TABLE wor749_comments');
        $this->addSql('DROP TABLE wor749_links');
        $this->addSql('DROP TABLE wor749_options');
        $this->addSql('DROP TABLE wor749_postmeta');
        $this->addSql('DROP TABLE wor749_posts');
        $this->addSql('DROP TABLE wor749_term_relationships');
        $this->addSql('DROP TABLE wor749_term_taxonomy');
        $this->addSql('DROP TABLE wor749_termmeta');
        $this->addSql('DROP TABLE wor749_terms');
        $this->addSql('DROP TABLE wor749_usermeta');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CE48FD905');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FE48FD905');
        $this->addSql('ALTER TABLE player DROP FOREIGN KEY FK_98197A65296CD8AE');
        $this->addSql('CREATE TABLE likedcomment (userId INT NOT NULL, commentId INT NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, title VARCHAR(255) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, content TEXT CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, createDate DATE NOT NULL, deleteDate DATE DEFAULT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE reservation (id INT NOT NULL, reservation_date DATETIME DEFAULT NULL, is_paid TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, email_address VARCHAR(200) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE signaledcomment (userId INT NOT NULL, commentId INT NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ticket (id INT NOT NULL, type INT NOT NULL, first_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, country VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, price DOUBLE PRECISION NOT NULL, birth_day DATE NOT NULL, reservation_id INT NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user (id INT NOT NULL, emailAddress VARCHAR(30) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, username VARCHAR(30) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, password VARCHAR(30) CHARACTER SET latin1 NOT NULL COLLATE `latin1_swedish_ci`, registrationDate DATE NOT NULL, userType VARCHAR(30) CHARACTER SET latin1 DEFAULT \'user\' NOT NULL COLLATE `latin1_swedish_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_choices (id INT NOT NULL, date DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', day_tickets INT DEFAULT NULL, half_day_tickets INT DEFAULT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wor749_commentmeta (meta_id BIGINT UNSIGNED NOT NULL, comment_id BIGINT UNSIGNED DEFAULT 0 NOT NULL, meta_key VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_520_ci`, meta_value LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_520_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wor749_comments (comment_ID BIGINT UNSIGNED NOT NULL, comment_post_ID BIGINT UNSIGNED DEFAULT 0 NOT NULL, comment_author TINYTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_520_ci`, comment_author_email VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, comment_author_url VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, comment_author_IP VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, comment_date DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, comment_date_gmt DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, comment_content TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_520_ci`, comment_karma INT DEFAULT 0 NOT NULL, comment_approved VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'1\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, comment_agent VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, comment_type VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, comment_parent BIGINT UNSIGNED DEFAULT 0 NOT NULL, user_id BIGINT UNSIGNED DEFAULT 0 NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wor749_links (link_id BIGINT UNSIGNED NOT NULL, link_url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, link_name VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, link_image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, link_target VARCHAR(25) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, link_description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, link_visible VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'Y\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, link_owner BIGINT UNSIGNED DEFAULT 1 NOT NULL, link_rating INT DEFAULT 0 NOT NULL, link_updated DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, link_rel VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, link_notes MEDIUMTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_520_ci`, link_rss VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wor749_options (option_id BIGINT UNSIGNED NOT NULL, option_name VARCHAR(191) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, option_value LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_520_ci`, autoload VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'yes\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wor749_postmeta (meta_id BIGINT UNSIGNED NOT NULL, post_id BIGINT UNSIGNED DEFAULT 0 NOT NULL, meta_key VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_520_ci`, meta_value LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_520_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wor749_posts (ID BIGINT UNSIGNED NOT NULL, post_author BIGINT UNSIGNED DEFAULT 0 NOT NULL, post_date DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, post_date_gmt DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, post_content LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_520_ci`, post_title TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_520_ci`, post_excerpt TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_520_ci`, post_status VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'publish\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, comment_status VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'open\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, ping_status VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'open\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, post_password VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, post_name VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, to_ping TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_520_ci`, pinged TEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_520_ci`, post_modified DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, post_modified_gmt DATETIME DEFAULT \'0000-00-00 00:00:00\' NOT NULL, post_content_filtered LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_520_ci`, post_parent BIGINT UNSIGNED DEFAULT 0 NOT NULL, guid VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, menu_order INT DEFAULT 0 NOT NULL, post_type VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT \'post\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, post_mime_type VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, comment_count BIGINT DEFAULT 0 NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wor749_term_relationships (object_id BIGINT UNSIGNED DEFAULT 0 NOT NULL, term_taxonomy_id BIGINT UNSIGNED DEFAULT 0 NOT NULL, term_order INT DEFAULT 0 NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wor749_term_taxonomy (term_taxonomy_id BIGINT UNSIGNED NOT NULL, term_id BIGINT UNSIGNED DEFAULT 0 NOT NULL, taxonomy VARCHAR(32) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, description LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_520_ci`, parent BIGINT UNSIGNED DEFAULT 0 NOT NULL, count BIGINT DEFAULT 0 NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wor749_termmeta (meta_id BIGINT UNSIGNED NOT NULL, term_id BIGINT UNSIGNED DEFAULT 0 NOT NULL, meta_key VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_520_ci`, meta_value LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_520_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wor749_terms (term_id BIGINT UNSIGNED NOT NULL, name VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, slug VARCHAR(200) CHARACTER SET utf8mb4 DEFAULT \'\' NOT NULL COLLATE `utf8mb4_unicode_520_ci`, term_group BIGINT DEFAULT 0 NOT NULL) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE wor749_usermeta (umeta_id BIGINT UNSIGNED NOT NULL, user_id BIGINT UNSIGNED DEFAULT 0 NOT NULL, meta_key VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_520_ci`, meta_value LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_520_ci`) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE game');
        $this->addSql('DROP TABLE player');
        $this->addSql('DROP TABLE team');
    }
}

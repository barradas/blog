<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221216153922 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_BA5AE01DF675F31B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__blog_post AS SELECT id, author_id, title, published, content, slug FROM blog_post');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('CREATE TABLE blog_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, published DATETIME NOT NULL, content CLOB DEFAULT NULL COLLATE BINARY, slug VARCHAR(255) DEFAULT NULL COLLATE BINARY, CONSTRAINT FK_BA5AE01DF675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO blog_post (id, author_id, title, published, content, slug) SELECT id, author_id, title, published, content, slug FROM __temp__blog_post');
        $this->addSql('DROP TABLE __temp__blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01DF675F31B ON blog_post (author_id)');
        $this->addSql('DROP INDEX IDX_B4E0AA59A77FBEAF');
        $this->addSql('DROP INDEX IDX_B4E0AA593DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__blog_post_image AS SELECT blog_post_id, image_id FROM blog_post_image');
        $this->addSql('DROP TABLE blog_post_image');
        $this->addSql('CREATE TABLE blog_post_image (blog_post_id INTEGER NOT NULL, image_id INTEGER NOT NULL, PRIMARY KEY(blog_post_id, image_id), CONSTRAINT FK_B4E0AA59A77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_B4E0AA593DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO blog_post_image (blog_post_id, image_id) SELECT blog_post_id, image_id FROM __temp__blog_post_image');
        $this->addSql('DROP TABLE __temp__blog_post_image');
        $this->addSql('CREATE INDEX IDX_B4E0AA59A77FBEAF ON blog_post_image (blog_post_id)');
        $this->addSql('CREATE INDEX IDX_B4E0AA593DA5256D ON blog_post_image (image_id)');
        $this->addSql('DROP INDEX IDX_9474526CF675F31B');
        $this->addSql('DROP INDEX IDX_9474526CA77FBEAF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, author_id, blog_post_id, content, published FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, blog_post_id INTEGER NOT NULL, content CLOB NOT NULL COLLATE BINARY, published DATETIME NOT NULL, CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9474526CA77FBEAF FOREIGN KEY (blog_post_id) REFERENCES blog_post (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO comment (id, author_id, blog_post_id, content, published) SELECT id, author_id, blog_post_id, content, published FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE INDEX IDX_9474526CA77FBEAF ON comment (blog_post_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_BA5AE01DF675F31B');
        $this->addSql('CREATE TEMPORARY TABLE __temp__blog_post AS SELECT id, author_id, title, published, content, slug FROM blog_post');
        $this->addSql('DROP TABLE blog_post');
        $this->addSql('CREATE TABLE blog_post (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER DEFAULT NULL, title VARCHAR(255) NOT NULL, published DATETIME NOT NULL, content CLOB DEFAULT NULL, slug VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO blog_post (id, author_id, title, published, content, slug) SELECT id, author_id, title, published, content, slug FROM __temp__blog_post');
        $this->addSql('DROP TABLE __temp__blog_post');
        $this->addSql('CREATE INDEX IDX_BA5AE01DF675F31B ON blog_post (author_id)');
        $this->addSql('DROP INDEX IDX_B4E0AA59A77FBEAF');
        $this->addSql('DROP INDEX IDX_B4E0AA593DA5256D');
        $this->addSql('CREATE TEMPORARY TABLE __temp__blog_post_image AS SELECT blog_post_id, image_id FROM blog_post_image');
        $this->addSql('DROP TABLE blog_post_image');
        $this->addSql('CREATE TABLE blog_post_image (blog_post_id INTEGER NOT NULL, image_id INTEGER NOT NULL, PRIMARY KEY(blog_post_id, image_id))');
        $this->addSql('INSERT INTO blog_post_image (blog_post_id, image_id) SELECT blog_post_id, image_id FROM __temp__blog_post_image');
        $this->addSql('DROP TABLE __temp__blog_post_image');
        $this->addSql('CREATE INDEX IDX_B4E0AA59A77FBEAF ON blog_post_image (blog_post_id)');
        $this->addSql('CREATE INDEX IDX_B4E0AA593DA5256D ON blog_post_image (image_id)');
        $this->addSql('DROP INDEX IDX_9474526CF675F31B');
        $this->addSql('DROP INDEX IDX_9474526CA77FBEAF');
        $this->addSql('CREATE TEMPORARY TABLE __temp__comment AS SELECT id, author_id, blog_post_id, content, published FROM comment');
        $this->addSql('DROP TABLE comment');
        $this->addSql('CREATE TABLE comment (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, blog_post_id INTEGER NOT NULL, content CLOB NOT NULL, published DATETIME NOT NULL)');
        $this->addSql('INSERT INTO comment (id, author_id, blog_post_id, content, published) SELECT id, author_id, blog_post_id, content, published FROM __temp__comment');
        $this->addSql('DROP TABLE __temp__comment');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE INDEX IDX_9474526CA77FBEAF ON comment (blog_post_id)');
    }
}

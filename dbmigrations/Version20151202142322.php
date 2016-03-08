<?php

namespace Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20151202142322 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS `notification_type` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `type` VARCHAR(255) NOT NULL,
                `data` TEXT DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS `build` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `started_date` DATETIME NOT NULL,
                `completed_date` DATETIME DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
        );
        $this->addSql(
            'CREATE TABLE IF NOT EXISTS `job` (
                `id` BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
                `build_id` BIGINT(20) UNSIGNED NOT NULL,
                `name` VARCHAR(255) NOT NULL,
                `output` TEXT DEFAULT NULL,
                `exit_code` SMALLINT(255) DEFAULT NULL,
                `started_date` DATETIME NOT NULL,
                `completed_date` DATETIME DEFAULT NULL,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8;'
        );
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $this->addSql('DROP TABLE notification_type');
        $this->addSql('DROP TABLE build');
        $this->addSql('DROP TABLE job');
    }
}

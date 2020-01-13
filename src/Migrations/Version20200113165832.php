<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200113165832 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        $table = $schema->createTable('subscription_data');
        $table->addColumn('user_id', 'binary')->setLength(16);
        $table->addColumn('competitions', 'text')->setNotnull(false);
        $table->addColumn('created_at', 'integer');
        $table->addColumn('updated_at', 'integer');
        $table->setPrimaryKey(['user_id']);

        $table = $schema->createTable('subscription_betting');
        $table->addColumn('user_id', 'binary')->setLength(16);
        $table->addColumn('type', 'text')->setNotnull(false);
        $table->addColumn('exclude_teams', 'text')->setNotnull(false);
        $table->addColumn('created_at', 'integer');
        $table->addColumn('updated_at', 'integer');
        $table->setPrimaryKey(['user_id']);
    }

    public function down(Schema $schema) : void
    {
        $schema->dropTable('subscription_data');
        $schema->dropTable('subscription_betting');
    }
}

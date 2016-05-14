<?php

use yii\db\Migration;

/**
 * Handles the dropping of unused tables
 */
class m160506_124505_drop_unused_tables extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        //Subcategory Relation Drop `subcategory_ibfk_1`
        $this->dropForeignKey(
            'subcategory_ibfk_1',
            'subcategory'
        );

        //Project Relation Drop `project_ibfk_1` and `project_ibfk_2`
        $this->dropForeignKey(
            'project_ibfk_1',
            'project'
        );
        $this->dropForeignKey(
            'project_ibfk_2',
            'project'
        );

        $this->dropTable('category');
        $this->dropTable('subcategory');
        $this->dropTable('project');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        echo "m160506_124505_drop_unused_tables cannot be reverted.\n";

        return false;
    }
}

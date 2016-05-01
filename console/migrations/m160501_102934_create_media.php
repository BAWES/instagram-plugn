<?php

use yii\db\Migration;

/**
 * Handles the creation for table `media`.
 * Has foreign keys to the tables:
 *
 * - `user`
 */
class m160501_102934_create_media extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('media', [
            'media_id' => $this->bigPrimaryKey()->unsigned(),
            'user_id' => $this->integer()->notNull()->unsigned(),
            'media_instagram_id' => $this->string()->notNull()->unique(), //1229816053673214945_35734335
            'media_type' => $this->string()->notNull(), //video vs image
            'media_num_comments' => $this->integer()->notNull(), //Number of comments
            'media_num_likes' => $this->integer()->notNull(), //Number of likes
            'media_caption' => $this->text(), //Firemen trying to get us out of the elevator
            'media_image_lowres' => $this->string(),
            'media_image_thumb' => $this->string(),
            'media_image_standard' => $this->string(),
            'media_video_lowres' => $this->string(),
            'media_video_thumb' => $this->string(),
            'media_video_standard' => $this->string(),
            'media_location_name' => $this->string(), //Kuwait City (sharq)
            'media_location_longitude' => $this->string(), //47.984060789421
            'media_location_latitude' => $this->string(), //29.378924950715
            'media_created_datetime' => $this->dateTime()->notNull(), //must be converted from unix

        ]);

        // creates index for column `media_instagram_id`
        $this->createIndex(
            'idx-media-media_instagram_id',
            'media',
            'media_instagram_id'
        );

        // creates index for column `user_id`
        $this->createIndex(
            'idx-media-user_id',
            'media',
            'user_id'
        );

        // add foreign key for table `user`
        $this->addForeignKey(
            'fk-media-user_id',
            'media',
            'user_id',
            'user',
            'user_id',
            'CASCADE'
        );
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        // drops foreign key for table `user`
        $this->dropForeignKey(
            'fk-media-user_id',
            'media'
        );

        // drops index for column `user_id`
        $this->dropIndex(
            'idx-media-user_id',
            'media'
        );

        // drops index for column `media_instagram_id`
        $this->dropIndex(
            'idx-media-media_instagram_id',
            'media'
        );

        $this->dropTable('media');
    }
}

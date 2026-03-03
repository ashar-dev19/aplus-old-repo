<?php 
namespace frontend\models;

use Yii;
use yii\web\UploadedFile;

/**
 * This is the model class for table "subject".
 *
 * @property int $id
 * @property string $title
 * @property string|null $image
 * @property string|null $details
 * @property int $status
 * @property int|null $created_by
 * @property int|null $updated_by
 * @property int|null $created_at
 * @property int|null $updated_at
 */
class Subject extends \yii\db\ActiveRecord
{
    /**
     * @var UploadedFile
     */
    public $imageFile; // New attribute for image upload

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subject';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'required'],
            [['status', 'created_by', 'updated_by', 'created_at', 'updated_at'], 'integer'],
            [['details'], 'string'],
            [['title'], 'string', 'max' => 50],
            [['imageFile'], 'file', 'extensions' => 'png, jpg, jpeg'], // Validate image file types
            [['image'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'image' => 'Image',
            'imageFile' => 'Upload Image', // Add label for the file input
            'details' => 'Details',
            'status' => 'Status',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $filePath = 'uploads/' . $this->imageFile->baseName . '.' . $this->imageFile->extension;
            $this->imageFile->saveAs($filePath);
            $this->image = $filePath; // Save the file path to the model
            return true;
        } else {
            return false;
        }
    }
}

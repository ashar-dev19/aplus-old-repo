<?
namespace backend\modules\elearning\models\search;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use backend\modules\elearning\models\Grade;

/**
 * GradeSearch represents the model behind the search form about `backend\modules\elearning\models\Grade`.
 */
class GradeSearch extends Grade
{
    public $subject_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'subject_id'], 'integer'],
            [['title', 'description'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Grade::find()
            ->joinWith('chapters.subject') // Subject ke saath join karein
            ->groupBy('grade.id');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        // Subject ID filter agar diya gaya ho
        if (!empty($this->subject_id)) {
            $query->andWhere(['subject.id' => $this->subject_id]);
        }

        return $dataProvider;
    }
}

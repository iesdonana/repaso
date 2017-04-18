<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Dispositivo;

/**
 * DispositivoSearch represents the model behind the search form about `app\models\Dispositivo`.
 */
class DispositivoSearch extends Dispositivo
{
    public $ubicacion;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'ordenador_id', 'aula_id'], 'integer'],
            [['marca_disp', 'modelo_disp', 'ubicacion'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
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
        $query = Dispositivo::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $dataProvider->sort->attributes['ubicacion'] = [
            'asc' => ['ubicacion' => SORT_ASC],
            'desc' => ['ubicacion' => SORT_DESC],
        ];

        $query->from('v_dispositivos d')
            ->andFilterWhere(['like', 'marca_disp', $this->marca_disp])
            ->andFilterWhere(['like', 'modelo_disp', $this->modelo_disp])
            ->andFilterWhere(['like', 'ubicacion', $this->ubicacion]);
        return $dataProvider;
    }
}

<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Ordenador;

/**
 * OrdenadorSearch represents the model behind the search form about `app\models\Ordenador`.
 */
class OrdenadorSearch extends Ordenador
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'aula_id'], 'integer'],
            [['marca_ord', 'modelo_ord'], 'safe'],
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
        $query = Ordenador::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'aula_id' => $this->aula_id,
        ]);

        $query->andFilterWhere(['like', 'marca_ord', $this->marca_ord])
            ->andFilterWhere(['like', 'modelo_ord', $this->modelo_ord]);

        return $dataProvider;
    }
}

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
    public $numero;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id'], 'integer'],
            [['aula_id', 'numero'], 'safe'],
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
        $query = Ordenador::find()->joinWith(['aula', 'dispositivos']);

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

        $query->select('ordenadores.*, count(ordenadores.id) as numero');

        $dataProvider->sort->attributes['aula_id'] = [
            'asc' => ['den_aula' => SORT_ASC],
            'desc' => ['den_aula' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['numero'] = [
            'asc' => ['numero' => SORT_ASC],
            'desc' => ['numero' => SORT_DESC],
        ];

        $query->groupBy('ordenadores.id');

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
        ]);

        $query->andFilterWhere(['like', 'marca_ord', $this->marca_ord])
            ->andFilterWhere(['like', 'modelo_ord', $this->modelo_ord])
            ->andFilterWhere(['like', 'den_aula', $this->aula_id]);

        $query->andFilterHaving([
            'count(dispositivos.id)' => $this->numero,
        ]);

        return $dataProvider;
    }
}

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
            [['id', 'numero'], 'integer'],
            [['marca_ord', 'modelo_ord', 'aula_id'], 'safe'],
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

        $dataProvider->sort->attributes['aula_id'] = [
            'asc' => ['den_aula' => SORT_ASC],
            'desc' => ['den_aula' => SORT_DESC],
        ];

        $dataProvider->sort->attributes['numero'] = [
            'asc' => ['numero' => SORT_ASC],
            'desc' => ['numero' => SORT_DESC],
        ];

        $query->select('o.*, count(d.id) as numero')
            ->from('ordenadores o')
            ->joinWith(['aula', 'dispositivos d'])
            ->andFilterWhere(['ilike', 'marca_ord', $this->marca_ord])
            ->andFilterWhere(['ilike', 'modelo_ord', $this->modelo_ord])
            ->andFilterWhere(['ilike', 'den_aula', $this->aula_id])
            ->groupBy('o.id')
            ->andFilterHaving(['count(d.id)' => $this->numero]);

        return $dataProvider;
    }
}

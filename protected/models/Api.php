<?php

/**
 * This is the model class for table "np_api".
 *
 * The followings are the available columns in table 'np_api':
 * @property integer $id_api
 * @property integer $id_user
 * @property string $key_public
 * @property string $key_secret
 * @property string $key_description
 *
 * The followings are the available model relations:
 * @property Users $idUser
 */
class Api extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'np_api';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('id_user, key_public, key_secret, key_description', 'required'),
            array('id_user', 'numerical', 'integerOnly'=>true),
            array('key_public', 'length', 'max'=>50),
            array('key_secret, key_description', 'length', 'max'=>200),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('id_api, id_user, key_public, key_secret, key_description', 'safe', 'on'=>'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
            'id_user' => array(self::BELONGS_TO, 'Users', 'id_user'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id_api' => 'Id Api',
            'id_user' => 'Id User',
            'key_public' => 'API Key Public',
            'key_secret' => 'API Key Secret',
            'key_description' => 'Key Description',
        );
    }

    /**
     * Retrieves a list of models based on the current search/filter conditions.
     *
     * Typical usecase:
     * - Initialize the model fields with values from filter form.
     * - Execute this method to get CActiveDataProvider instance which will filter
     * models according to data in model fields.
     * - Pass data provider to CGridView, CListView or any similar widget.
     *
     * @return CActiveDataProvider the data provider that can return the models
     * based on the search/filter conditions.
     */
    public function search()
    {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria=new CDbCriteria;

        $criteria->compare('id_api',$this->id_api);
        $criteria->compare('id_user',$this->id_user);
        $criteria->compare('key_public',$this->key_public,true);
        $criteria->compare('key_secret',$this->key_secret,true);
        $criteria->compare('key_description',$this->key_description,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Api the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}

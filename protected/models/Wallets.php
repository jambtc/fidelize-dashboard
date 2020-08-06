
<?php
/**
 * This is the model class for table "np_wallets".
 *
 * The followings are the available columns in table 'np_wallets':
 *
 */
class Wallets extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'np_wallets';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_user, wallet_address', 'required'),
			array('id_wallet, id_user', 'numerical', 'integerOnly'=>true),
			array('wallet_address, poa_url, blocknumber', 'length', 'max'=>50),
			array('wallet_key', 'length', 'max'=>1000),
			array('poa_port', 'length', 'max'=>10),

			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('id_wallet,id_user,wallet_address,wallet_key,poa_url,poa_port,blocknumber', 'safe', 'on'=>'search'),
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
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'wallet_address'=>'Indirizzo Token',
			'id_user'=>'Utente/Socio',
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

		$criteria->compare('id_wallet',$this->id_wallet);
		$criteria->compare('id_user',$this->id_user,true);
		$criteria->compare('wallet_address',$this->wallet_address,true);
		$criteria->compare('wallet_key',$this->wallet_key,true);
		$criteria->compare('poa_url',$this->poa_url,true);
		$criteria->compare('poa_port',$this->poa_port,true);
		$criteria->compare('blocknumber',$this->blocknumber,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Settings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}

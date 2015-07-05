<?php
/**
 * Auto generated from model.proto at 2015-07-04 02:41:45
 */

/**
 * OrderInfo message
 */
class OrderInfo extends \ProtobufMessage
{
    /* Field index constants */
    const ID = 1;
    const USER_ID = 2;
    const TIMESTAMP = 3;
    const DESCRIPTION = 11;
    const PERFORM_COST = 51;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::ID => array(
            'name' => 'id',
            'required' => true,
            'type' => 7,
        ),
        self::USER_ID => array(
            'name' => 'user_id',
            'required' => true,
            'type' => 7,
        ),
        self::TIMESTAMP => array(
            'name' => 'timestamp',
            'required' => true,
            'type' => 5,
        ),
        self::DESCRIPTION => array(
            'name' => 'description',
            'required' => false,
            'type' => 7,
        ),
        self::PERFORM_COST => array(
            'name' => 'perform_cost',
            'required' => false,
            'type' => 1,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     *
     * @return null
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::ID] = null;
        $this->values[self::USER_ID] = null;
        $this->values[self::TIMESTAMP] = null;
        $this->values[self::DESCRIPTION] = null;
        $this->values[self::PERFORM_COST] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setId($value)
    {
        return $this->set(self::ID, $value);
    }

    /**
     * Returns value of 'id' property
     *
     * @return string
     */
    public function getId()
    {
        return $this->get(self::ID);
    }

    /**
     * Sets value of 'user_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUserId($value)
    {
        return $this->set(self::USER_ID, $value);
    }

    /**
     * Returns value of 'user_id' property
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->get(self::USER_ID);
    }

    /**
     * Sets value of 'timestamp' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setTimestamp($value)
    {
        return $this->set(self::TIMESTAMP, $value);
    }

    /**
     * Returns value of 'timestamp' property
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->get(self::TIMESTAMP);
    }

    /**
     * Sets value of 'description' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setDescription($value)
    {
        return $this->set(self::DESCRIPTION, $value);
    }

    /**
     * Returns value of 'description' property
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->get(self::DESCRIPTION);
    }

    /**
     * Sets value of 'perform_cost' property
     *
     * @param float $value Property value
     *
     * @return null
     */
    public function setPerformCost($value)
    {
        return $this->set(self::PERFORM_COST, $value);
    }

    /**
     * Returns value of 'perform_cost' property
     *
     * @return float
     */
    public function getPerformCost()
    {
        return $this->get(self::PERFORM_COST);
    }
}

/**
 * OrderPerformInfo message
 */
class OrderPerformInfo extends \ProtobufMessage
{
    /* Field index constants */
    const USER_ID = 2;
    const TIMESTAMP = 3;
    const PERFORM_PROFIT = 51;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::USER_ID => array(
            'name' => 'user_id',
            'required' => true,
            'type' => 7,
        ),
        self::TIMESTAMP => array(
            'name' => 'timestamp',
            'required' => true,
            'type' => 5,
        ),
        self::PERFORM_PROFIT => array(
            'name' => 'perform_profit',
            'required' => false,
            'type' => 1,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     *
     * @return null
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::USER_ID] = null;
        $this->values[self::TIMESTAMP] = null;
        $this->values[self::PERFORM_PROFIT] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'user_id' property
     *
     * @param string $value Property value
     *
     * @return null
     */
    public function setUserId($value)
    {
        return $this->set(self::USER_ID, $value);
    }

    /**
     * Returns value of 'user_id' property
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->get(self::USER_ID);
    }

    /**
     * Sets value of 'timestamp' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setTimestamp($value)
    {
        return $this->set(self::TIMESTAMP, $value);
    }

    /**
     * Returns value of 'timestamp' property
     *
     * @return int
     */
    public function getTimestamp()
    {
        return $this->get(self::TIMESTAMP);
    }

    /**
     * Sets value of 'perform_profit' property
     *
     * @param float $value Property value
     *
     * @return null
     */
    public function setPerformProfit($value)
    {
        return $this->set(self::PERFORM_PROFIT, $value);
    }

    /**
     * Returns value of 'perform_profit' property
     *
     * @return float
     */
    public function getPerformProfit()
    {
        return $this->get(self::PERFORM_PROFIT);
    }
}

/**
 * UserWalletInfo message
 */
class UserWalletInfo extends \ProtobufMessage
{
    /* Field index constants */
    const PERFORM_ORDER = 1;
    const QUANTITY_OF_MONEY = 11;

    /* @var array Field descriptors */
    protected static $fields = array(
        self::PERFORM_ORDER => array(
            'name' => 'perform_order',
            'required' => false,
            'type' => 5,
        ),
        self::QUANTITY_OF_MONEY => array(
            'name' => 'quantity_of_money',
            'required' => false,
            'type' => 1,
        ),
    );

    /**
     * Constructs new message container and clears its internal state
     *
     * @return null
     */
    public function __construct()
    {
        $this->reset();
    }

    /**
     * Clears message values and sets default ones
     *
     * @return null
     */
    public function reset()
    {
        $this->values[self::PERFORM_ORDER] = null;
        $this->values[self::QUANTITY_OF_MONEY] = null;
    }

    /**
     * Returns field descriptors
     *
     * @return array
     */
    public function fields()
    {
        return self::$fields;
    }

    /**
     * Sets value of 'perform_order' property
     *
     * @param int $value Property value
     *
     * @return null
     */
    public function setPerformOrder($value)
    {
        return $this->set(self::PERFORM_ORDER, $value);
    }

    /**
     * Returns value of 'perform_order' property
     *
     * @return int
     */
    public function getPerformOrder()
    {
        return $this->get(self::PERFORM_ORDER);
    }

    /**
     * Sets value of 'quantity_of_money' property
     *
     * @param float $value Property value
     *
     * @return null
     */
    public function setQuantityOfMoney($value)
    {
        return $this->set(self::QUANTITY_OF_MONEY, $value);
    }

    /**
     * Returns value of 'quantity_of_money' property
     *
     * @return float
     */
    public function getQuantityOfMoney()
    {
        return $this->get(self::QUANTITY_OF_MONEY);
    }
}

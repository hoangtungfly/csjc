<?php
/**
 * Project: OMS
 * User: André Marques <amarques@rocket-internet.pt>
 * Date: 07-11-2014
 * Time: 17:44
 * File: Picklist.php
 */
class Outbound_Model_Picklist_Collection {
    /**
     * @var int
     */
    private $_idUser;
    /**
     * @var int|null
     */
    private $_idPicklist;
    /**
     * @var int
     */
    private $_idOrder;
    /**
     * @var array
     */
    private $_pickedItems;
    /**
     * @var bool
     */
    private $_isToSplit;
    /**
     * @var int
     */
    private $_defaultLocation;
    /**
     * @var Ims_Model_Picklist
     */
    private $_model;
    public function __construct($idOrder, $idUser = null, $pickedItems = null, $idPicklist = null){
        $this->_model = new Ims_Model_Picklist();
        $this->setIdOrder($idOrder);
        $this->setIdUser($idUser);
        $this->setPickedItems($pickedItems);
        $this->setDefaultLocation();
        $this->setIsToSplit();
        $this->setIdPicklist($idPicklist);
        /**
         * Perform the validation if the object is created correctly
         */
        $this->__validate();
    }
    /**
     * @return Ims_Model_Picklist
     */
    public function getModel()
    {
        return $this->_model;
    }
    /**
     * @return int
     */
    public function getDefaultLocation()
    {
        return $this->_defaultLocation;
    }
    /**
     * @param int $defaultLocation
     */
    public function setDefaultLocation()
    {
        $this->_defaultLocation = $this->_model->getPicklistPreferredLocation($this->getPickedItems());
    }
    /**
     * @return int
     */
    public function getIdOrder()
    {
        return $this->_idOrder;
    }
    /**
     * @param int $idOrder
     */
    public function setIdOrder($idOrder)
    {
        $this->_idOrder = $idOrder;
    }
    /**
     * @return int|null
     */
    public function getIdPicklist()
    {
        return $this->_idPicklist;
    }
    /**
     * Set the id picklist
     */
    public function setIdPicklist($idPicklist = null)
    {
        if(is_null($idPicklist)){
            //Is WW?
            $this->_idPicklist = $this->getModel()->getOpenPickListForSalesOrderItem(current($this->_pickedItems));
        }else{
            $this->_idPicklist = $idPicklist;
        }
    }
    /**
     * @return int
     */
    public function getIdUser()
    {
        return $this->_idUser;
    }
    /**
     * @param int $idUser
     */
    public function setIdUser($idUser)
    {
        $this->_idUser = $idUser;
    }
    /**
     * @return array|boolean
     */
    public function isIsToSplit()
    {
        return $this->_isToSplit;
    }
    /**
     *
     */
    public function setIsToSplit()
    {
        $this->_isToSplit = $this->getModel()->isToSplitPickList($this->getPickedItems());
    }
    /**
     * @return array
     */
    public function getPickedItems()
    {
        return $this->_pickedItems;
    }
    /**
     * Set the picked items
     */
    public function setPickedItems($pickedItems)
    {
        if(is_null($pickedItems)){
            $this->_pickedItems = Outbound_Model_Dao_SalesOrderItem::getInstance()->getOrderItemsToPickByIdSalesOrder($this->getIdOrder());
        }else{
            $this->_pickedItems = $pickedItems;
        }
    }
    /**
     * Validate if the object is correctly populated
     * @throws Exception
     */
    private function __validate(){
        //If no order id is given retrieve the id sales order by the association of the picked items
        if(empty($this->_idOrder)){
            $soi = current($this->_pickedItems);
            $sales_order_item = Ims_Service_Order_Item::getInstance()->getById($soi);
            if(isset($sales_order_item[DbTable_Ims_Sales_Order_ItemRow::FK_SALES_ORDER])){
                $this->setIdOrder($sales_order_item[DbTable_Ims_Sales_Order_ItemRow::FK_SALES_ORDER]);
            }
        }
        if(empty($this->_idOrder) && empty($this->_pickedItems)){
            throw new Exception("Verify the input parameters. They are not filled correctly");
        }
    }
} 


#test case 1 : check class Ims_Model_Picklist exists
if(!class_exists(Ims_Model_Picklist)) {
    
}
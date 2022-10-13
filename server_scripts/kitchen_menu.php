<?php
//This class will be dealing with the signup operation only.
class kitchen_dealer {
	//Database login credentials
	private
	const HOST_NAME = "localhost",
		DATABASE_NAME = "products_contents",
		SQL_USERNAME = "Collector",
		SQL_PASSWORD = "Bm7iHqPAHQF7yfIx";

	//Kitchen name variable
	private $kitchen_name;

	//PHP Data Object (PDO) variable to insert the PDO Object in.
	public $pdo_for_user_registration_sql;
    
    public $query = "SELECT nid, title, body_value AS description, field_product_price_per_unit_value AS ppu FROM (SELECT entity_id, field_kitchen_restruant_value FROM `products_contents`.`node__field_kitchen_restruant`) a
               INNER JOIN
               (SELECT nid, title FROM `node_field_data`) b 
               ON a.entity_id=b.nid
               INNER JOIN
               (SELECT entity_id, body_value FROM `node__body`) c
               ON a.entity_id=c.entity_id
               INNER JOIN
               (SELECT entity_id, field_product_price_per_unit_value FROM `node__field_product_price_per_unit`) d 
               ON a.entity_id=d.entity_id
               WHERE a.field_kitchen_restruant_value=:kitchen_name";
    
    //where the result/formated result will be store when  data is queried
    private $results_fetched;

	//In order for this class to be created a kitchen name is needed
	public

	function __construct( $kitchen_name ) {

		//creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
		$this->pdo_for_user_registration_sql = new PDO( "mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD );
        
        $this->kitchen_name = $kitchen_name;
    }
    
    //Fetch and return
    public
    function fetch_kitchen() {
        $this->pdo_for_user_registration_sql = $this->pdo_for_user_registration_sql->prepare($this->query);
        $this->pdo_for_user_registration_sql->bindValue(":kitchen_name", $this->kitchen_name);
        $this->pdo_for_user_registration_sql->execute();
        return json_encode($this->pdo_for_user_registration_sql->fetchall(PDO::FETCH_ASSOC));
    }
}
//Look here to optimize code speed in the future
if(!empty($_GET["kitchen"])) {
    $kitchen = $_GET["kitchen"];
 
    if($kitchen == "kitchen-0") {
    $kitchen = "Chicken Republic";
    }
    else if ($kitchen == "kitchen-1") {
        $kitchen = "Kum Chop";
    }
    else if ($kitchen == "kitchen-2") {
        $kitchen = "Simple";
    }
    $kitchen_dealer = new kitchen_dealer($kitchen);
    echo $kitchen_dealer->fetch_kitchen();
}
?>
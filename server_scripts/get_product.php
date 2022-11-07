<?php
class product_dealer {
	//Database login credentials
	private
	const HOST_NAME = "localhost",
		DATABASE_NAME = "dpl_db_fx",
		SQL_USERNAME = "collector",
		SQL_PASSWORD = "v2JE3!NMo6@i";

	//product nid variable
	private $product_nid;

	//PHP Data Object (PDO) variable to insert the PDO Object in.
	public $pdo_for_user_registration_sql;
    
    public $query = "SELECT nid, title, field_product_price_per_unit_value AS ppu FROM
               (SELECT nid, title FROM `node_field_data`) a
               INNER JOIN
               (SELECT entity_id, field_product_price_per_unit_value FROM `node__field_product_price_per_unit`) b
               ON a.nid=b.entity_id
               WHERE a.nid=:product_nid LIMIT 1";
    
    //where the result/formated result will be store when  data is queried
    private $result_fetched;

	//In order for this class to be created a product name is needed
	public

	function __construct( $product_nid ) {

		//creating a new PHP Data Object(PDO) for MySql and assigning it the previously created variable for it above
		$this->pdo_for_user_registration_sql = new PDO( "mysql:host=" . self::HOST_NAME . ";dbname=" . self::DATABASE_NAME, self::SQL_USERNAME, self::SQL_PASSWORD );
        
        $this->product_nid = $product_nid;
    }
    
    //Fetch and return
    public
    function fetch_product() {
        $this->pdo_for_user_registration_sql = $this->pdo_for_user_registration_sql->prepare($this->query);
        $this->pdo_for_user_registration_sql->bindValue(":product_nid", $this->product_nid);
        $this->pdo_for_user_registration_sql->execute();
        return json_encode($this->pdo_for_user_registration_sql->fetchall(PDO::FETCH_ASSOC));
    }
}
//Look here to optimize code speed in the future
if(!empty($_GET["product_nid"])) {
    $product_nid = $_GET["product_nid"];
 
    $product_dealer = new product_dealer($product_nid);
    echo $product_dealer->fetch_product();
}
?>
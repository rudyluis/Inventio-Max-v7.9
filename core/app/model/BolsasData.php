<?php
class BolsasData {
	public static $tablename = "bolsas";

	public function __construct(){
        $this->nombre_bolsas="";
        $this->numero_sachets="";
        $this->cantidad_minima="";
        $this->precio_compra="";
        $this->id_usuario="";
        $this->fecha_creado="NOW()";
        $this->estado="";

		/*$this->name = "";
		$this->price_in = "";
		$this->price_out = "";
		$this->unit = "";
		$this->user_id = "";
		$this->image = "";
		$this->presentation = "0";
		$this->created_at = "NOW()";*/
	}

	//public function getCategory(){ return CategoryData::getById($this->category_id);}

	public function add(){
		$sql = "insert into ".self::$tablename." (nombre_bolsas, cantidad_bolsas_adquiridas, numero_sachets, cantidad_minima, precio_compra_unidad, id_usuario, estado) ";
		$sql .= "values (\"$this->nombre_bolsas\",\"$this->cantidad_bolsas_adquiridas\", \"$this->numero_sachets\",\"$this->cantidad_minima\",$this->precio_compra_unidad,\"$this->id_usuario\", 1)";
		return Executor::doit($sql);
	}


	public static function delById($id){
		$sql = "delete from ".self::$tablename." where id=$id";
		Executor::doit($sql);
	}
	public function del(){
		$sql = "update ".self::$tablename." set estado=0  where id_bolsas=$this->id_bolsas";
		//$sql = "delete from ".self::$tablename." where id_bolsas=$this->id_bolsas";
		return Executor::doit($sql);
	}

// partiendo de que ya tenemos creado un objecto BolsasData previamente utilizamos el contexto
	public function update(){
		$sql = "update ".self::$tablename." set nombre_bolsas=\"$this->nombre_bolsas\",numero_sachets=\"$this->numero_sachets\",cantidad_minima=\"$this->cantidad_minima\",precio_compra=\"$this->precio_compra\",id_usuario_modificado=\"$this->id_usuario_modificado\", fecha_modificado=current_timestamp  where id_bolsas=$this->id_bolsas";
		//echo $sql; exit;
		return Executor::doit($sql);
	}

	/*public function del_category(){
		$sql = "update ".self::$tablename." set category_id=NULL where id=$this->id";
		Executor::doit($sql);
	}*/

	/*public function del_brand(){
		$sql = "update ".self::$tablename." set brand_id=NULL where id=$this->id";
		Executor::doit($sql);
	}*/


	/*public function update_image(){
		$sql = "update ".self::$tablename." set image=\"$this->image\" where id=$this->id";
		Executor::doit($sql);
	}*/

	public function update_prices(){
		$sql = "update ".self::$tablename." set precio_compra=\"$this->precio_compra\"  where id_bolsas=$this->id";
		Executor::doit($sql);
	}

	public static function getById($id){
		$sql = "select * from ".self::$tablename." where id_bolsas=$id";
		$query = Executor::doit($sql);
		return Model::one($query[0],new BolsasData());

	}

	public static function getAll(){
		$sql = "select * from ".self::$tablename." where estado=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BolsasData());
	}

	/*public static function getAllByCategoryId($id){
		$sql = "select * from ".self::$tablename." where category_id=$id";
		$query = Executor::doit($sql);
		return Model::many($query[0],new ProductData());
	}*/

	public static function getAllByPage($start_from,$limit){
		$sql = "select * from ".self::$tablename." where id_bolsas>=$start_from limit $limit";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BolsasData());
	}


	public static function getLike($p){
		$sql = "select * from ".self::$tablename." where (nombre_bolsas like '%$p%' or numero_sachets like '%$p%' or cantidad_minima like '%$p%' or precio_compra like '%$p%') and is_active=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BolsasData());
	}


	public static function getLike2($p){
		$sql = "select * from ".self::$tablename." where (nombre_bolsas like '%$p%' or numero_sachets like '%$p%' or cantidad_minima like '%$p%' or precio_compra like '%$p%') and is_active=1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BolsasData());
	}


	public static function getBolsaProducto($id_producto, $id_bolsa){
		$sql = "select count(id_bolsa) as id_bolsa from producto_bolsas where id_bolsa = $id_bolsa and id_producto=$id_producto and estado=1 order by id_producto_bolsas desc limit 1";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BolsasData());
	}
	
	public static function getAllByUserId($user_id){
		$sql = "select * from ".self::$tablename." where id_usuario=$user_id order by fecha_creado desc";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BolsasData());
	}
	
	public function producto_bolsa_add(){
		$sql = "insert into producto_bolsas(id_producto, id_bolsa, numero_sachets_utilizado, id_usuario_registro, estado) ";
		$sql .= "values (\"$this->id_producto\",\"$this->id_bolsa\",\"$this->numero_sachets_utilizado\", \"$this->id_usuario_registro\", 1)";
		return Executor::doit($sql);
	}
	
	public function getStockBolsas($id_bolsa){
		$sql = "select sum(pb.numero_sachets_utilizado*ope.q)  as sachets_vendidos
				from product pro
				inner join operation ope on pro.id = ope.product_id
				inner join producto_bolsas pb on ope.product_id = pb.id_producto 
				where ope.operation_type_id = 2
				and pb.id_bolsa = $id_bolsa";
		$query = Executor::doit($sql);
		return Model::many($query[0],new BolsasData());
	}

}

?>
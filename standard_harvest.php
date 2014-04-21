<?PHP

	class standard_ingest{
	
		function __construct(){
		
			include "../../../config.php";
			include "../../../site/database/database_layer.inc";
			include "../../../site/database/" . DB_TYPE . "_database_layer.inc";
			$db_class = DB_TYPE . "_database_layer";
			$this->database = new $db_class();
			
			$this->link = $this->database->database_connect();
		
		}
		
		function add_entry($term, $value){
			
			if(!in_array($value, $this->current_data)){
				
				if(!isset($this->current_url[$term])){
				
					$this->current_url[$term] = array();
				
				}
				
				array_push($this->current_data, $value);			
				array_push($this->current_url[$term], $value);
			
			}
		
		}
		
		function translate_word($word){
		
			if(file_exists(dirname(__FILE__) . "/translate/" . $word . ".txt")){
			
				return explode(",",file_get_contents(dirname(__FILE__) . "/translate/" . $word . ".txt"));
				
			}else{
			
				return array($word);
			
			}
			
		}

		function translate_item($text){
			
			if(strpos($text," ")===FALSE){
			
				return $this->translate_word($text);
			
			}else{
			
				$words = explode(" ", $text);
				
				foreach($words as $word){
				
					$new_word = $this->translate_word($word); 
				
					if($word!=$new_word[0]){
					
						$text = str_replace($word, $word . " (" . implode(",", $new_word) . ") ", $text);  
					
					}
					
				}
				
				return array($text);
			
			}
		
		}
		
		function translate_list($list){
		
			$new_list = array();
			
			$list = array_unique(array_filter($list));
			
			foreach($list as $item){
			
				if(strpos(strtolower($item),"http")===FALSE){
					
					$data = $this->translate_item($item);
					array_push($data, $item);
					$new_list = array_merge($new_list, $data);
				
				}
			
			}
			
			return $new_list;
		
		}
		
		function node_insert(){
		
			foreach($this->current_url as $node => $list){
			
				$list = $this->translate_list($list);
				
				$this->current_url[$node] = $list;
			
				foreach($list as $item){
				
					$item = trim($item);
					
					$statement = $this->database->select_query("SELECT node_id FROM node_data WHERE node_value=:value", array(":value" => utf8_encode($item)), $this->link);
					$data = $this->database->get_all_rows($statement);					
				
					if(count($data)==0){
						
						$this->database->insert_query("insert into node_data(node_value)VALUES(:item)", array(":item" => utf8_encode($item)), $this->link);
						$node_id = $this->database->last_insert_id($this->link);
						
					}else{
											
						$node_id = $data[0]['node_id'];
						
					}
				
					$this->term_insert($node,$node_id);
				
				}
			
			}
		
		}
		
		function term_insert($node, $node_id){
			
			if(!in_array($node, $this->ignore_nodes)&&strpos($node,"XMLNS")===FALSE){
			
				$statement = $this->database->select_query("SELECT term_id FROM node_term WHERE term=:term and node_id =:node_id", array(":term" => $node, ":node_id" => $node_id), $this->link);
				$data = $this->database->get_all_rows($statement);	
				
				if(count($data)==0){
							
					$this->database->insert_query("insert into node_term(term,node_id)VALUES(:node, :node_id)", array(":node" => $node, ":node_id" => $node_id), $this->link);
					$term_id = $this->database->last_insert_id($this->link);
						
				}else{
											
					$term_id = $data[0]['term_id'];
						
				}
				
				array_push($this->terms_used, $term_id);
				
			}
		
		}

	}
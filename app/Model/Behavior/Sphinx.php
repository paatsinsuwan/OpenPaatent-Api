<?php  
/** 
 * Behavior for simple usage of Sphinx search engine 
 * http://www.sphinxsearch.com 
 * 
 * @copyright 2008, Vilen Tambovtsev 
 * @author  Vilen Tambovtsev 
 * @license      http://www.opensource.org/licenses/mit-license.php The MIT License 
 * 
 * @modifiedby Eugenio Fage (2010) 
 */  

class SphinxBehavior extends ModelBehavior{ 
    /** 
     * Used for runtime configuration of model 
     */ 
    var $runtime = array(); 
    
    /** 
     * Spinx client object 
     * 
     * @var SphinxClient 
     */ 
    var $sphinx = null; 

    function setup(&$model, $config = array()) { 

        App::import('Component', 'Sphinx'); 
         
        $this->runtime[$model->alias]['sphinx'] = new SphinxComponent(); 
    } 
     
    /** 
     * beforeFind Callback 
     * 
     * @param array $query 
     * @return array Modified query 
     * @access public 
     */ 
    function beforeFind(&$model, $query) 
    { 
        if (empty($query['sphinx']) ) 
            return true; 

             
        if ($model->findQueryType == 'count'){ 
            $model->recursive = -1; 
            $query['limit'] = 1; 
            $query['page'] = 1; 
        } else if (empty($query['limit'])) { 
            $query['limit'] = 9999999; 
            $query['page'] = 1; 
        } 

        if(!isset($query['search']))$query['search']=''; 
         
        $s=array_merge($query['sphinx'],array('search'=>$query['search'],'limit'=>$query['limit'],'page'=>$query['page']));
        $result=$this->runtime[$model->alias]['sphinx']->search($s); 
                 
        unset($query['conditions']); 
        unset($query['order']); 
        unset($query['offset']); 
        $query['page'] = 1; 
        if ($model->findQueryType == 'count')    { 
            $result['total'] = !empty($result['total']) ? $result['total'] : 0; 
            $query['fields'] = 'ABS(' . $result['total'] . ') AS count'; 
        } else  { 
            if (isset($result['matches'])){ 
                $ids = array_keys($result['matches']); 
            }elseif (is_array($result)){ 
                   $ids=array(); 
                   while($r=array_shift($result)){ 
                       $ids=array_unique(array_merge($ids,array_keys($r['matches']))); 
                   } 
            }else{ 
                $ids = array(0); 
            } 
            $query['conditions'] = array($model->alias . '.'.$model->primaryKey => $ids); 
            $query['order'] = 'FIND_IN_SET('.$model->alias.'.'.$model->primaryKey.', \'' . implode(',', $ids) . '\')'; 
        } 

        return $query; 
    } 
} 

?>
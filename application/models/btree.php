<?php
//用于比较节点数据,先按level排序，再递归按parentId排序,再按id排序(这里的id大小比较的结果(same parent),以显示先创建的左节点，再创建的右节点)
function cmpNode($nodeA,$nodeB) {
	global $root;
	if($nodeA->level != $nodeB->level) {
		return strcmp(sprintf('%08d',$nodeA->level),sprintf('%08d',$nodeB->level)); 
	}
	if($nodeA->parentId == $nodeB->parentId) {
		$nodeAid = sprintf('%08d',$nodeA->data->id);
		$nodeBid = sprintf('%08d',$nodeB->data->id);
		return strcmp($nodeAid,$nodeBid);
	}
	else {
		$parentA = btree::locateNodeById($nodeA->parentId,$root);
		$parentB = btree::locateNodeById($nodeB->parentId,$root);
		return cmpNode($parentA, $parentB, $root);
	}
}

//用于比较节点数据,先按level排序，再按parentId排序,再按id排序
/*function mysort($a,$b) {
   //先按level排序，再按parentId排序，再按id排序
   $aobj = sprintf('%08d',$a->level) . sprintf('%08d',$a->parentId) . sprintf('%08d',$a->data->id);
   $bojb = sprintf('%08d',$b->level) . sprintf('%08d',$b->parentId) . sprintf('%08d',$b->data->id);
   return strcmp($aobj,$bojb); 
} */

if(!class_exists("btree")) {
	class btree
{
	public $data = null ;
	public $left = null;
	public $right = null;
	public $level = 0;
	public $parentId = null; //父节点Id,方便向上回溯

	public function __construct($data, $left, $right) {
		$this->data = $data; //数据,这里是一个object
		$this->left = $left; //左节点,这里是一个object
		$this->right = $right; //右节点，这里是一个object
		$this->level = 0;
	}

	
	//获取当前节点的子节点数目
	public function getChildCount() {
		$stack = array();
		$counter = 0 ;
		array_push($stack, $this);
		while (!empty($stack)) {
			$cnode = array_pop($stack);
			$counter += 1;
			if ($cnode->right != null) array_push($stack, $cnode->right);
			if ($cnode->left != null) array_push($stack, $cnode->left);
		}
		return $counter -1;
	}

	//向上回溯找到当前节点所处于的绝对层数
	public static function getAbsLevel($node,$rootNode) {
		$rootId = $rootNode->data->id;
		$currLevel = 0;
		while(true) {
			$parentNode= self::locateNodeById($node->parentId,$rootNode);
			if(empty($parentNode)) {
				break;
			}
			$currLevel += 1;
			$currId = $parentNode->data->id;
			if($currId == $rootId) {
				break;
			}
			$node = $parentNode;
		}
		return $currLevel;
	}

	//获取指定节点的下级总层数
	public static function getLastChildLevel($node,$result=0) {
		if($node->left != null || $node->right != null ) {
			$result += 1;
			if($node->left != null && $node->right == null) {
				return self::getLastChildLevel($node->left,$result);
			}
			else if ($node->right != null && $node->left == null) {
				return self::getLastChildLevel($node->right,$result);
			}
			else {
				$leftMaxLevel = self::getLastChildLevel($node->left,$result);
				$rightMaxLevel = self::getLastChildLevel($node->right,$result);
				return $leftMaxLevel >= $rightMaxLevel ? $leftMaxLevel : $rightMaxLevel;
				//左右节点都有，则要看那边层级多了，选层级多的。
			}
		}
		else if ($node->left == null && $node->right  == null ) {
			return $result;
		}
	}



	//根据用户id来查找定位节点
	public static function locateNodeById($id,$node){
		$stack = array();
		array_push($stack, $node);
		while (!empty($stack)) {
			$cnode = array_pop($stack);
			if ($cnode->data->id == $id ) {
				return $cnode;
			}
			if ($cnode->right != null) array_push($stack, $cnode->right);
			if ($cnode->left != null) array_push($stack, $cnode->left);
		}
		return null;
	}


    //判断是否满二叉树,暂时没有用到此函数
    public static function isFullTree($node) {
	   if($node->left == null && $node->right == null) {
		  return false;
	   }

	    $allNode=array();
		$stack = array();
		array_push($stack, $node);
		while (!empty($stack)) {
			$cnode = array_pop($stack);
			array_push($allNode,$cnode);
			if ($cnode->left != null) array_push($stack, $cnode->left);
			if ($cnode->right != null) array_push($stack, $cnode->right);

		}
		foreach($allNode as $tmpNode) {
		  if($tmpNode->left != null && $tmpNode->right == null) {
			return false;
		  }
		  else if ($tmpNode->right != null && $tmpNode->left == null) {
			return false;

		  }
		}
		return true;
	}


	
	//把当前节点($childNode),挂靠到源店铺所在子树($recomNode)上去
	public static function setChildNode($childNode,$recomNode,$rootNode) {
		$ctrf=(isset($_SERVER["REQUEST_URI"]) ? "<br>" : "\r\n");
		
		if ($childNode == null || $recomNode == null) {
			return;
		}
		if ($recomNode->left == null) {
			$recomNode->left = $childNode;
			$childNode->parentId = $recomNode->data->id;
			$childNode->level = self::getAbsLevel($childNode,$rootNode); //更新其level,这里的level是从root算起的。
			return;
		}

		if ($recomNode->right == null) {
			$recomNode->right = $childNode;
			$childNode->parentId = $recomNode->data->id;
			$childNode->level = self::getAbsLevel($childNode,$rootNode); //更新其level,这里的level是从root算起的。
			return;
		}

	    $leftNode = $recomNode->left;
		$rightNode = $recomNode->right;

		$leftResult =  self::getSmartFullTreeLevel($leftNode);
		$rightResult = self::getSmartFullTreeLevel($rightNode);

		$leftFullTreeLevel  = $leftResult->level;
		$rightFullTreeLevel = $rightResult->level;

		$isLeftNodeEnough  = $leftResult->full;
		$isRightNodeEnough = $rightResult->full;

		if($leftFullTreeLevel < $rightFullTreeLevel) {
			//左边层数少，添加到左枝树
			self::setChildNode($childNode, $leftNode,$rootNode);
		}
		else if ($leftFullTreeLevel == $rightFullTreeLevel) {
			//再逐层看左边是否满了，左边没满，则添加到左边; 
			//左边满了，右边没满,则添加到右边;
			//左右都满了，进入下层;
			//循环完都没找到，则选左边

			if (!$isLeftNodeEnough) {
				self::setChildNode($childNode, $leftNode,$rootNode);
			}
			else if (!$isRightNodeEnough) {
				self::setChildNode($childNode, $rightNode,$rootNode);
			}
			else {
				self::setChildNode($childNode, $leftNode,$rootNode);
			}
		}
		else {
			//右边层数少，添加到右枝树
			self::setChildNode($childNode, $rightNode,$rootNode);
		}
		
	}

	//打印排序树中每个节点的直推人
	public static function printChildCount($node){
		$stack = array();
		array_push($stack, $node);
		while (!empty($stack)) {
				$cnode = array_pop($stack);
			echo $cnode->data->name . "(" .  $cnode->getChildCount() . ")";
			if ($cnode->right != null)array_push($stack, $cnode->right);
			if ($cnode->left != null) array_push($stack, $cnode->left);
		}
	} 




	//找到子树下满二叉树,并返回叶子节点所在层数(如果它的下级不是满二叉树，就直接返回上级)
	public static function getSmartFullTreeLevel($node) {
		$alldata = array();
		$stack = array();
		array_push($stack, $node);
		while(!empty($stack)) {
			$cnode = array_pop($stack);
			array_push($alldata, (object)array('data'=> $cnode->data, 'level'=> $cnode->level,'parentId'=>$cnode->parentId,'left'=>$cnode->left, 'right'=>$cnode->right));
			if ($cnode->right != null) array_push($stack, $cnode->right);
			if ($cnode->left != null) array_push($stack, $cnode->left);
		}
		//已经按层级排序了。
		//usort($alldata, 'mysort');
		usort($alldata, 'cmpNode');
		
		if($node->left == null && $node->right == null) {
			return (object)array('level'=>end($alldata)->level,'full'=>false);
		}
		foreach ($alldata as $obj) {
			if($obj->left == null || $obj->right == null) {
				return (object)array('level'=>$obj->level,'full'=> ($obj->left == null && $obj->right == null) ? true: false);
			}
			
		}
	}

	//打印指定节点5级内的所有层数(采用前序遍历节点，再按层次排序)
	public static function printChildByLevel($node,$maxLevel){
		$ctrf=(isset($_SERVER["REQUEST_URI"]) ? "<br>" : "\r\n");
		$alldata = array();
		$stack = array();
		array_push($stack, $node);
		$level_counter = 0;
		while(!empty($stack)) {
			$cnode = array_pop($stack);
			array_push($alldata, (object)array('data'=> $cnode->data, 'level'=> $cnode->level,'parentId'=>$cnode->parentId));
			if ($cnode->right != null) array_push($stack, $cnode->right);
			if ($cnode->left != null) array_push($stack, $cnode->left);
		}
		//已经按层级排序了。
		//usort($alldata, 'mysort');
		usort($alldata, 'cmpNode');

		$level_limit = $maxLevel + $alldata[0]->level;
		
		//如果超过限制的层数，则不打印。
        $childs=array();
		foreach ($alldata as $obj) {
		  	if($obj->data->id == $node->data->id) continue; //不打印当前节点
	    	if ($obj->level > $level_limit) break;  //不打印超过指定层次的节点
			array_push($childs,$obj->data->id);
		}
        return $childs;

    }
 }
}




?>

<?php

class m_btree extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function addNode($data,$left,$right){
        
    }

    //用于比较节点数据,先按level排序，再递归按parentId排序,再按id排序(这里的id大小比较的结果(same parent),以显示先创建的左节点，再创建的右节点)
    function cmpNode($nodeA,$nodeB) {
        global $root;
        if($nodeA!=null && $nodeB!=null){
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
            if($cnode!=null){
                if ($cnode->data->id == $id) {
                    return $cnode;
                }
                if ($cnode->right != null) array_push($stack, $cnode->right);
                if ($cnode->left != null) array_push($stack, $cnode->left);
            }
        }
        return null;
    }


    /***
     * 把当前节点($childNode),挂靠到源店铺所在子树($recomNode)上去
     * @childNode   当前升级用户的节点
     * @recomNode   当前升级用户的父ID节点
     * @rootNode    根节点(整棵树)
     */
    public static function setChildNode($childNode,$recomNode,$rootNode) {
        /***如果值为空，直接返回***/
        if ($childNode == null || $recomNode == null) {
            return;
        }
        //如果父树的左边为空，则添加到左边
        if ($recomNode->left == null) {
            $recomNode->left = $childNode;
            $childNode->parentId = $recomNode->data->id;
            $childNode->level = self::getAbsLevel($childNode,$rootNode); //更新其level,这里的level是从root算起的。
            return;
        }
        //如果父树的右边为空，则添加到右边
        if ($recomNode->right == null) {
            $recomNode->right = $childNode;
            $childNode->parentId = $recomNode->data->id;
            $childNode->level = self::getAbsLevel($childNode,$rootNode); //更新其level,这里的level是从root算起的。
            return;
        }

        //寻找父树的左树和右树
        $leftNode = $recomNode->left;
        $rightNode = $recomNode->right;


        $leftResult =  self::getSmartFullTreeLevel($leftNode);
        $rightResult = self::getSmartFullTreeLevel($rightNode);

        $leftFullTreeLevel  = $leftResult->level;
        $rightFullTreeLevel = $rightResult->level;

        $isLeftNodeEnough  = $leftResult->full;
        $isRightNodeEnough = $rightResult->full;

        //左边层数少，添加到左枝树
        if($leftFullTreeLevel < $rightFullTreeLevel) {
            self::setChildNode($childNode, $leftNode,$rootNode);
        }
        //如果两边层数一样
        else if ($leftFullTreeLevel == $rightFullTreeLevel) {
            //再逐层看左边是否满了，左边没满，则添加到左边;
            if (!$isLeftNodeEnough) {
                self::setChildNode($childNode, $leftNode,$rootNode);
            }
            //左边满了，右边没满,则添加到右边;
            else if (!$isRightNodeEnough) {
                self::setChildNode($childNode, $rightNode,$rootNode);
            }

            //左右都满了，进入下层;
            //循环完都没找到，则选左边
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

    //打印指定节点5级内的所有节点(采用前序遍历节点，再按层次排序)
    public static function printChildByLevel($node,$maxLevel){
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

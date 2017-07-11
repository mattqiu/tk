<?php
/**
 * Pcntl 功能类
 * @author Terry
 */
class o_pcntl extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function tps_pcntl_wait($childProcessCode){

        $pid = pcntl_fork();
        if($pid>0){

            pcntl_wait($status);
            @$this->db->reconnect();
        }elseif($pid==0){

            eval($childProcessCode);
            exit;
        }else{
            die('Cannot fork.');
        }
    }
    
    /**
     * 创建子进程
     * @author: derrick
     * @date: 2017年3月27日
     * @param: @param Closure $callback
     * @reurn: return_type
     */
    public function fork_child(Closure $callback) {
    	while ( true ) {
    		$pid = pcntl_fork ();
    		if ($pid == - 1) {
    			die ( 'could not fork' );
    		}
    		break;
    	}
    	if ($pid) {
    		// 父进程
    		pcntl_wait ($status, WNOHANG);
//     		exit(1);
    	} else {
    		$callback();
    		exit (0);
    	}
    }
}

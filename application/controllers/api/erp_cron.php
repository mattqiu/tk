<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Erp_cron extends CI_Controller {

    public function __construct() {

        parent::__construct();

        // 命令行接口不设时间限制
        set_time_limit(0);

        // inaccessible from being loaded in the URL
        if (!$this->input->is_cli_request()) {
            echo 'Please run this script in CLI.';
            exit;
        }
    }

    /**
     * 检查系统是否已有同名进程正在执行
     * 返回：
     *  没有同名进程，可以执行： true
     *  有同名进程，不可以执行： false
     */
    private function _check_process($apiFunc) {

        /**
         * 系统调用会在当前进程 fork 子进程，意味着复制当前进程所有资源（包括内存），然后子进程从 fork 位置继续执行
         * 系统调用必须谨慎使用，仅允许在程序初始化未占用大量资源前使用，禁止在程序业务逻辑中使用（已耗大量资源）
         */

        // 获取当前进程 pid
        $curPid = getmypid();

        // 查询进程
        $output = `ps -eo pid,ppid,cmd | grep index.php\ api\ wms_cron\ $apiFunc`;
        $opArr = explode("\n", $output);
        $procList = array();
        foreach ($opArr as $proc) {
            // 过滤空信息
            if (empty($proc)) {
                continue;
            }

            // 合并连续空格为一个空格，然后分割为 3 个参数
            list($pid, $ppid, $cmd) = explode(" ", trim(preg_replace('/ {2,}/', ' ', $proc)), 3);

            // 记录当前进程 ppid
            if ($pid == $curPid) {
                $curPpid = $ppid;

                // 过滤当前进程
                continue;
            }

            // 过滤掉 grep
            if (false !== strpos($cmd, "grep index.php api wms_cron")) {
                continue;
            }

            $procList[] = array(
                'pid' => $pid,
                'ppid' => $ppid,
                'cmd' => $cmd,
            );
        }

        foreach ($procList as $proc) {
            if (false !== strpos($proc['cmd'], "index.php api wms_cron {$apiFunc}") && $proc['pid'] !== $curPpid) {
                return false;
            }
        }

        return true;
    }

    /****************************** 数据推送定时器 ******************************/

    /**
     * 推送商品数据
     * CLI: php index.php api wms_cron push_commodities
     * 频率： 每分钟拉起一次
     */
    public function push_commodities() {

        // 检测是否有未执行完的进程
        if (false == $this->_check_process("push_commodities")) {
            echo "check process failed, similar process is still running\n";
            exit;
        }

    }

    /**
     * 推送订单数据
     * CLI: php index.php api wms_cron push_orders
     * 频率： 每分钟拉起一次
     */
    public function push_orders() {

        // 检测是否有未执行完的进程
        if (false == $this->_check_process("push_orders")) {
            echo "check process failed, similar process is still running\n";
            exit;
        }

    }

    /****************************** 数据同步定时器 ******************************/


}
